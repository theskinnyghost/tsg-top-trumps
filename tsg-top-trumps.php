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