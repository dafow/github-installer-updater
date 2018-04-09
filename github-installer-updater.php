<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/BBackerry/github-installer-updater
 * @since             1.0.0
 * @package           GithubInstallerUpdater
 *
 * @wordpress-plugin
 * Plugin Name:       GithubInstallerUpdater
 * Plugin URI:        https://github.com/BBackerry/github-installer-updater
 * Description:       Browse, Install, and Update Wordpress plugins hosted on Github
 * Version:           1.0.0
 * Author:            Falah Salim
 * Author URI:        http://falahsalim.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       github-installer-updater
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'GIU_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-giu-activator.php
 */
function activate_giu() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-giu-activator.php';
	Plugin_Name_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-giu-deactivator.php
 */
function deactivate_giu() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-giu-deactivator.php';
	Plugin_Name_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_giu' );
register_deactivation_hook( __FILE__, 'deactivate_giu' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-giu.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_giu() {

	$plugin = new GIU();
	$plugin->run();

}
run_GIU();
