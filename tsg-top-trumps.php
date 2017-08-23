<?php
/**
 * Plugin Name:       TSG Top Trumps
 * Plugin URI:        https://github.com/theskinnyghost/tsg-top-trumps
 * Description:       This plugin allows you to play Top Trumps on your WordPress site.
 * Version:           1.0.0
 * Author:            Luca Ricci
 * Author URI:        https://www.michiamoluca.it
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'FTLLTP_NONCE_ACTION', '_tsgtt_save_fields_to_db' );
define( 'FTLLTP_NONCE_NAME',  basename(__FILE__) );

/**
 * The core plugin class that is used to define
 * custom post types and custom meta fields
 */
require plugin_dir_path( __FILE__ ) . 'includes/plugin-class.php';

$TSG_Top_Trumps = new TSG\WordPress\Plugin\Top_Trumps();

/**
 * Define Admin hooks
 */
add_action( 'init', array( $TSG_Top_Trumps, 'register_post_type' ) );
add_action( 'add_meta_boxes', array( $TSG_Top_Trumps, 'add_metabox' ) );
add_action( 'save_post', array( $TSG_Top_Trumps, 'save_post' ) );
add_action( 'rest_api_init', array( $TSG_Top_Trumps, 'register_custom_endpoint' ) );