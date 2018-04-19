<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/BBackerry/github-installer-updater
 * @since      1.0.0
 *
 * @package    GithubInstallerUpdater
 * @subpackage GithubInstallerUpdater/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    GithubInstallerUpdater
 * @subpackage GithubInstallerUpdater/admin
 * @author     Falah Salim <falah.salim@gmail.com>
 */
class GIU_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $giu    The ID of this plugin.
	 */
	private $giu;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The number of repositories fetched at each request to the Github API
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      int    $per_page    The number of repositories fetched from the Github API
	 */
	private $per_page;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $giu       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $giu, $version ) {

		$this->giu = $giu;
		$this->version = $version;
		$this->per_page = 6;

	}

	/**
	* Register Nav Menu and its descendants.
	*
	* @since	1.0.0
	*/
	public function add_nav_menu() {
		add_menu_page( 'Github Installer & Updater', 'Github Installer/Updater', 'install_plugins', 'giu',
			array($this, 'output_admin_page') );
		add_submenu_page( 'giu', 'Browse Plugins', 'Browse Plugins', 'install_plugins', 'giu-browse',
			array($this, 'output_browse_page') );
	}

	/**
	* Output Plugin Dashboard page HTML
	*
	* @since	1.0.0
	*/
	public function output_admin_page() {
		require plugin_dir_path( __FILE__ ) . 'partials/giu-admin-dashboard.php';
	}

	/**
	* Output Plugin Browsing page HTML
	*
	* @since	1.0.0
	*/
	public function output_browse_page() {
		require plugin_dir_path( __FILE__ ) . 'partials/giu-admin-browse.php';
	}

	/**
	* Handle form action for browsing plugins
	*
	* @since	1.0.0
	*/
	public function browse_plugins() {
		//Clear repos transient from possible previous requests
		delete_transient( 'giu-browse-repos' );

		if ( current_user_can( 'install_plugins' ) && isset( $_POST['_giunonce'] ) && wp_verify_nonce( $_POST['_giunonce'], 'giu-browse-plugins' ) ) {

			if ( isset( $_POST['q'] ) && !empty( $_POST['q'] ) && !isset( $_POST['p'] ) ) {
				//Getting a request from Browse button
				//Determine keywords structure (query by repo name, URL...)
				$query = $_POST['q'];
				$query = sanitize_text_field( $query );
				$query_original = $query;
				if ( strpos( $query, 'http://' ) !== false || strpos( $query, 'https://' ) !== false ) {
					//Parse URL and get Owner/Repo Name
					$query = trim( $query, '\\' ); //Remove possible trailing slash
					$query = preg_replace( '/^https?:\/\//', '', $query ); //Remove http or https part
					$query = explode( '/', $query );

					if ( count( $query ) === 3 ) {
						$owner_name = $query[1];
						$repo_name = $query[2];
					}
				}
				elseif ( strpos( $query, '/' ) !== false ) {
					$query = explode( '/', $query );

					if ( count( $query ) === 2 ) {
						$owner_name = $query[0];
						$repo_name = $query[1];
					}
				}

				//Load Github API Wrapper
				//https://github.com/KnpLabs/php-github-api/
				require_once plugin_dir_path( __FILE__ ) . '../vendor/autoload.php';
				$github_client = new \Github\Client();

				try {
					//Get repositories by owner/name or keywords and store them in transients to persist after redirection
					if ( isset( $owner_name ) && isset ( $repo_name ) ) {
						$repos = $github_client->api( 'repo' )->show( $owner_name, $repo_name );
						set_transient( 'giu-browse-repos', $repos, 60 );
					}
					else {
						//Querying Github Search API (not using php-github-api search api because of broken parameters)
						$api_res = $github_client->getHttpClient()->get( 'search/repositories?q=' . urlencode( $query )
							. '&page=1&per_page=' . $this->per_page );
						$repos = Github\HttpClient\Message\ResponseMediator::getContent($api_res);

						set_transient( 'giu-browse-repos', $repos, 60 );
					}
				}
				catch (Exception $e) {
					set_transient( 'giu-errors', $e->getMessage(), 60 );
				}
				finally {
					if ( isset( $owner_name ) && isset ( $repo_name ) ) {
						wp_safe_redirect( 'admin.php?page=giu-browse' );
					}
					else {
						wp_safe_redirect( 'admin.php?page=giu-browse&q=' . urlencode( $query_original ) );
					}
				}
			}

			elseif ( isset( $_POST['q'] ) && !empty( $_POST['q'] ) && isset( $_POST['p'] ) && !empty( $_POST['p'] ) ) {
				//Getting a request from the pagination button
				$query = urldecode( $_POST['q'] );
				$page = intval( $_POST['p'], 10 ) + 1;

				//Querying Github Search API
				require_once plugin_dir_path( __FILE__ ) . '../vendor/autoload.php';
				$github_client = new \Github\Client();
				$api_res = $github_client->getHttpClient()->get( 'search/repositories?q=' . $_POST['q']
					. '&page=' . $page . '&per_page=' . $this->per_page );
				$repos = Github\HttpClient\Message\ResponseMediator::getContent( $api_res );
				set_transient( 'giu-browse-repos', $repos, 60 );

				wp_safe_redirect( 'admin.php?page=giu-browse&q=' . $_POST['q'] . '&p=' . $page );
			}

			else {
				//No params set for correct usage of browsing
				set_transient( 'giu-errors', 'Invalid Request', 60 );
				wp_safe_redirect( 'admin.php?page=giu-browse' );
			}
		}
		else {
			wp_die( 'Invalid nonce', 'Error',
				array(
					'response'	=>	403
				)
			);
		}
	}

	/**
	* Get repository information to populate download/installation data
	*
	* @since	1.0.0
	*/
	public function get_repo_install_info() {
		if ( current_user_can( 'install_plugins' ) && isset( $_POST['_guiAjaxNonce'] ) && wp_verify_nonce( $_POST['_guiAjaxNonce'], 'giu-ajax-actions' ) ) {

			if ( isset( $_POST['repo'] ) && !empty( $_POST['repo'] ) &&
					isset( $_POST['installChoice'] ) && !empty( $_POST['installChoice'] ) ) {
				$repo_name = $_POST['repo'];
				$repo = explode( '/', $repo_name );
				if ( count ( $repo ) === 2 ) {
					$repo_owner = sanitize_text_field( $repo[0] );
					$repo_name = sanitize_text_field( $repo[1] );
					$install_choice = sanitize_text_field( $_POST['installChoice'] );

					require_once plugin_dir_path( __FILE__ ) . '../vendor/autoload.php';
					$github_client = new \Github\Client();

					if ( $install_choice === 'master-last-commit' ) {

					}
					elseif ( $install_choice === 'release' ) {
						//Get releases. Note: only published releases and releases not associated with tags are returned
						//https://developer.github.com/v3/repos/releases
						$releases = $github_client->api( 'repo' )->releases()->all( $repo_owner, $repo_name );
						error_log(print_r($releases, true));
						if ( is_array( $releases ) && count( $releases ) > 0 ) {
							//Load HTML partial and populate with results
							ob_start();
							include plugin_dir_path( __FILE__ ) . 'partials/giu-install-plugin-modal-releases.php';
							echo ob_get_clean();
						}
						else {
							$error_msg = "No releases found from this repository.<br />";
							$error_msg .= "Note that only published releases and releases not associated with tags are returned";
							$error_msg .= " (due to how the Gihub API works).";
							echo $error_msg;
						}
					}
					elseif ( $install_choice === 'tag' ) {
						$tags = $github_client->api( 'repo' )->tags( $repo_owner, $repo_name );
						error_log(print_r($releases, true));
					}

					wp_die();
				}
			}

		}

		wp_send_json_error();
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->giu, plugin_dir_url( __FILE__ ) . 'css/giu-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'fancybox', plugin_dir_url( __FILE__ ) . 'css/jquery.fancybox.min.css', array(), '3.3.5', 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->giu, plugin_dir_url( __FILE__ ) . 'js/giu-admin.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'fancybox', plugin_dir_url( __FILE__ ) . 'js/jquery.fancybox.min.js', array( 'jquery' ), '3.3.5', false );

		wp_localize_script( $this->giu, 'giu_ajaxnonce', wp_create_nonce( 'giu-ajax-actions' ) );

	}

}
