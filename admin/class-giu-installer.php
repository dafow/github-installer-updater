<?php

/**
 * Provides functionality to download a repository and install it as a WP plugin
 *
 * @link       https://github.com/BBackerry/github-installer-updater
 * @since      1.0.0
 *
 * @package    GithubInstallerUpdater
 * @subpackage GithubInstallerUpdater/admin
 * @author     Falah Salim <falah.salim@gmail.com>
 */
class GIU_Installer {
  /**
   * The cache directory of this plugin
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $cache_dir    The cache directory of this plugin
   */
  private $cache_dir;

  /**
   * The WP plugins directory
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $wp_plugins_dir    The WP plugins directory
   */
  private $wp_plugins_dir;

  /**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct( ) {

    $this->cache_dir = dirname( __DIR__ ) . '/cache/';
      //dirname(..., 2) goes up 2 levels
    $this->wp_plugins_dir = dirname( __DIR__, 2 ) . '/';
	}

  /**
	 * Downloads an archive (zipball) via cURL and installs it in WP's plugins directory
	 *
	 * @since    1.0.0
	 */
	public function install_repo_archive( $archive_url ) {
		//Create temporary file to write archive to
		$temp_archive_path = $this->cache_dir . uniqid();
		$temp_archive = fopen( $temp_archive_path, 'w+' );

		if ($temp_archive !== false) {
			//Download the archive
			$ch = curl_init();
			curl_setopt( $ch, CURLOPT_URL, $archive_url );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			//Has no effect if CURLOPT_RETURNTRANSFER is set since PHP 5.1.3
			curl_setopt( $ch, CURLOPT_BINARYTRANSFER, true );
			//Don't return headers as part of the response
			curl_setopt( $ch, CURLOPT_HEADER, false );
			//Needed or else Github API rejects request
			curl_setopt( $ch, CURLOPT_USERAGENT, 'Github-Installer-Updater' );
			curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 30 );
			curl_setopt( $ch, CURLOPT_TIMEOUT, 30 );
			//Github redirects from the link given by the API to the real archive download link
			curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
			$result = curl_exec( $ch );
			curl_close( $ch );

			if ( $result !== false ) {
				//Write download result to temp file
				$write_result = fwrite( $temp_archive, $result );
        $close_result = fclose( $temp_archive );

        if ( $write_result !== false && $close_result !== false ) {
  				//Unzip archive to wp-plugins
  				$zip = new ZipArchive;
  				if ( $zip->open( $temp_archive_path ) ) {
            //Check if plugin folder already exists. If it does, delete it and extract
            //Usually, the archive contains a folder containing the repository
            $archive_dir_name = $zip->getNameIndex( 0 );
            if ( file_exists( $this->wp_plugins_dir . $archive_dir_name ) ) {
              $this->delete_directory( $this->wp_plugins_dir . $archive_dir_name );
            }
  				  $extract_result = $zip->extractTo( $this->wp_plugins_dir );
  				  $zip->close();

            //Clean up

  					if ( $result ) {
  						return true;
  					}
  					else {
  						return "An error occurred while extracting the repository's archive.";
  					}
  				} else {
  				  return "An error occurred while creating the repository's directory. Please check your permissions.";
  				}
        }
        else {
          return "An error occurred while writing the repository's data";
        }
			}
			else {
				return "A network error occurred while downloading the archive file.";
			}
		}
		else {
			return "An error occurred while creating the repository's archive. Please check your permissions.";
		}
	}

  /**
	 * Empties and deletes a directory
	 *
	 * @since    1.0.0
	 */
   public function delete_directory( $dir ) {
     $di = new RecursiveDirectoryIterator( $dir, FilesystemIterator::SKIP_DOTS );
     $ri = new RecursiveIteratorIterator( $di, RecursiveIteratorIterator::CHILD_FIRST );
     foreach ( $ri as $file ) {
       $file->isDir() ? rmdir( $file ) : unlink( $file );
     }
   }
}
