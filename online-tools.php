<?php
/**
 * Plugin Name: UMW Online Tools
 * Description: Implements the toolbar for Online Tools at the UMW website
 * Version: 0.2
 * Author: cgrymala
 * License: GPL2
 */

if ( ! defined( 'ABSPATH' ) ) {
  die( 'You should not access this file directly' );
}

if ( ! class_exists( 'UMW_Online_Tools' ) ) {
  require_once( plugin_dir_path( __FILE__ ) . '/classes/class-umw-online-tools.php' );
  global $umw_online_tools_obj;
  $umw_online_tools_obj = new UMW_Online_Tools;
}
