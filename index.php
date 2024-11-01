<?php
/*
Plugin Name: Webiots Testimonial Showcase
Plugin URI: https://www.webiots.com
Description: Webiots Testimonial Showcase WordPress Plugin 
Version: 1.0
Author: Webiots
Author URI: https://www.webiots.com
License: GPLv2 or later
Text Domain: webiots-tm
*/


/**
 * Plugin Base File
 **/
define("WEBIOTS_TESTIMONAILS_PATH",dirname(__FILE__));
/**
 * Plugin Base Directory
 **/
define("WEBIOTS_TESTIMONAILS_DIR",basename(WEBIOTS_TESTIMONAILS_PATH));
include_once(ABSPATH . 'wp-includes/pluggable.php');

include('includes/functions.php');

if (is_admin()) { //if admin include the admin specific functions
    require_once(dirname( __FILE__ ).'/includes/options.php');
}


//Setup

add_action( 'wp_enqueue_scripts', 'webiotstestmonialsscriptsstyles' );



/**
 * Register all shortcodes
 *
 * @return null
 */



function webiotsregistershortcodesform() {
    add_shortcode( 'webiots-tm-form', 'shortcode_webiots_testimonials_form' );
}
add_action( 'init', 'webiotsregistershortcodesform' );



function webiotstestimonialregistershortcodes() {
    add_shortcode( 'webiots-tm', 'shortcodewebiotstestimonials' );
}
add_action( 'init', 'webiotstestimonialregistershortcodes' );
add_action( 'vc_before_init', 'addonvcwebiotstestimonials' );



//TODO: FrontEND Form Submission

