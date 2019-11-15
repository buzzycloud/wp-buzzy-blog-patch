<?php
/**
 * @package Buzzy Blog Patch
 */
/**
 * Plugin Name: Buzzy Blog Patch
 * Plugin URI: https://github.com/buzzycloud/wp-buzzy-blog-patch
 * Description: A plugin to patch some settings of buzzy blog backend
 * Version: 1.0.0
 * Author: Yumin Gui <yumindev@gmail.com>
 * Author URI: https://guiyumin.com
 * License: GPLv2 or later
 */
defined( 'ABSPATH' ) or die( 'ERROR!' );
require('env.php');
require('helpers.php');
/** allow access via rest api while maintaing force-login  */
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

 /**Allow Comments via REST API */
add_filter( 'rest_allow_anonymous_comments', '__return_true' );
/** disable xml-rpc */
add_filter( 'xmlrpc_enabled', '__return_false' );

// check for plugin using plugin name
if ( is_plugin_active( 'wp-force-login/wp-force-login.php' ) ) {
  //plugin is activated
  add_filter( 'rest_authentication_errors', '__return_true' );
} 

// notify me when there are new comments
add_action('wp_insert_comment', 'notify_new_comment', 10, 2);

// Add the hook action
add_action('transition_post_status', 'notify_new_post', 10, 3);