<?php
/**
 * Plugin Name: Book Management System
 * Plugin URI: https://profiles.wordpress.org/reedwanul/
 * Description: This plugin adds awesome features to your WordPress site, you can manage book management system.
 * Version: 1.0.0
 * Author: Reedwanul
 * Author URI: https://profiles.wordpress.org/reedwanul/
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Requires at least: 6.8
 * Tested up to: 6.8
 * Requires PHP: 7.4
 * Text Domain: bms-system
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Define plugin constants
define( 'BMS_SYSTEM_VERSION', '1.0.0' );
define( 'BMS_PLUGIN_FILE', __FILE__ );
define( 'BMS_PLUGIN_PATH', plugin_dir_path( BMS_PLUGIN_FILE ) );
define( 'BMS_PLUGIN_URL', plugin_dir_url( BMS_PLUGIN_FILE ) );
define( 'BMS_PLUGIN_BASNAME', plugin_basename( BMS_PLUGIN_FILE ) );

include_once BMS_PLUGIN_PATH . 'class/BookManagement.php';

$bookManagementObject = new BookManagement();
     

