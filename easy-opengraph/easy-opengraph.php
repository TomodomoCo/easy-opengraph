<?php
/*
Plugin Name: VPM Easy OpenGraph
Plugin URI: http://www.vanpattenmedia.com/
Description: "Set it and forget it" Facebook OpenGraph
Author: Van Patten Media
Version: 0.5
Author URI: http://www.vanpattenmedia.com/
*/


/**
 *
 * Constants
 *
 */

define( 'EASY_OG_PATH', plugin_dir_path( __FILE__ ) );


/**
 *
 * Options
 *
 */
 
require_once( EASY_OG_PATH . 'options.php');


/**
 *
 * Set up the OpenGraph tags
 *
 */

// og:title
function easy_og_title() {
	if ( is_front_page() || is_home() ) {
		echo '<meta property="og:title" content="' . get_bloginfo('name') . '">' . "\n";
	} else {
		echo '<meta property="og:title" content="' . wp_title('', false) . '">' . "\n";
	}
}

// og:type
function easy_og_type() {
	if ( is_single() ) {
		echo '<meta property="og:type" content="article">' . "\n";
	} else {
		echo '<meta property="og:type" content="website">' . "\n";
	}
}

// og:image
function easy_og_image() {
	if ( function_exists('get_post_thumbnail_id') ) {
		$image_id = get_post_thumbnail_id();
		$image_url = wp_get_attachment_image_src($image_id,'large', true);
		echo '<meta property="og:image" content="' . home_url() . $image_url[0] . '">' . "\n";
	} else {
		if ( isset( $easy_og_image_default ) ) {
			
		} else {
			echo '<meta property="og:image" content="' . home_url() . get_bloginfo('template_directory') . '/screenshot.jpg">' . "\n";
		}
	}
}

// og:url
function easy_og_url() {
	echo '<meta property="og:url" content="' . get_permalink() .'">' . "\n";
}

// og:site_name
function easy_og_site_name() {
	echo '<meta property="og:site_name" content="' . get_bloginfo('name') . '">' . "\n";
}

// og:description
function easy_og_description() {
	if ( !is_front_page() ) {
		$excerpt = get_the_excerpt();
		echo '<meta property="og:description" content="' . wp_trim_words($excerpt, 20) . '">' . "\n";
	} else { 
		echo '<meta property="og:description" content="Long description of site.">' . "\n";
	}
}

// og:locale
function easy_og_locale() {
	echo '<meta property="og:locale" content="en_US">' . "\n";
}

// fb:admins 
function easy_og_fb_admins() {
	echo '<meta property="fb:admins" content="">' . "\n";
}


/**
 *
 * Spit it out
 *
 */

add_action('wp_head', 'easy_og_title');
add_action('wp_head', 'easy_og_type');
add_action('wp_head', 'easy_og_image');
add_action('wp_head', 'easy_og_url');
add_action('wp_head', 'easy_og_site_name');
add_action('wp_head', 'easy_og_description');
add_action('wp_head', 'easy_og_locale');
add_action('wp_head', 'easy_og_fb_admins');

/**
 *
 * Plugin Updater
 *
 */
 
require_once( EASY_OG_PATH . 'updater.php');