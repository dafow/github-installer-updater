<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://github.com/BBackerry/github-installer-updater
 * @since      1.0.0
 *
 * @package    GithubInstallerUpdater
 * @subpackage GithubInstallerUpdater/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    GithubInstallerUpdater
 * @subpackage GithubInstallerUpdater/includes
 * @author     Falah Salim <falah.salim@gmail.com>
 */
class GIU_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		delete_option( 'giu-settings-auth' );
	}

}
