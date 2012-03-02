<?php
/*
Plugin Name: VPM Easy OpenGraph
Plugin URI: http://www.vanpattenmedia.com/
Description: "Set it and forget it" Facebook OpenGraph
Author: Van Patten Media
Version: 0.9
Author URI: http://www.vanpattenmedia.com/
*/


/**
 *
 * Set up the OpenGraph tags
 *
 */

// og:locale
function easy_og_locale() {
	echo '<meta property="og:locale" content="en_US">';
}

// og:admins 
function easy_og_admins() {
	echo '<meta property="fb:admins" content="">';
}

// og:title
function easy_og_title() {
	echo '<meta property="og:title" content="' . wp_title('', false) . '">';
}

// og:url
function easy_og_url() {
	echo '<meta property="og:url" content="' . get_permalink() .'">';
}


// og:site_name
function easy_og_site_name() {
	echo '<meta property="og:site_name" content="' get_bloginfo('name') '">';
}

// og:type
function easy_og_type() {
	if ( is_single() ) {
		echo '<meta property="og:type" content="article">';
	} else {
		echo '<meta property="og:type" content="website">';
	}
}

// og:description
function easy_og_description() {

	if ( !is_front_page() ) {
		echo '<meta property="og:description" content="' . rach5_get_the_excerpt() . '">';
	} else { 
		echo '<meta property="og:description" content="Long description of site.">';
	}
}

// og:image
function easy_og_image() {
	'<meta property="og:image" content="' . home_url() . get_bloginfo('template_directory') . '/img/opengraph.png">';
}


/**
 *
 * Spit it out
 *
 */

add_action('wp_head', 'easy_og_locale');
add_action('wp_head', 'easy_og_admins');
add_action('wp_head', 'easy_og_title');
add_action('wp_head', 'easy_og_url');
add_action('wp_head', 'easy_og_site_name');
add_action('wp_head', 'easy_og_type');
add_action('wp_head', 'easy_og_description');
add_action('wp_head', 'easy_og_image');


/**
 *
 * Plugin Updater
 *
 */
 
require_once('updater.php');