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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $giu       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $giu, $version ) {

		$this->giu = $giu;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in GIU_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The GIU_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->giu, plugin_dir_url( __FILE__ ) . 'css/giu-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in GIU_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The GIU_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->giu, plugin_dir_url( __FILE__ ) . 'js/giu-admin.js', array( 'jquery' ), $this->version, false );

	}

}
