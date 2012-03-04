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

// Path to this plugin
define( 'EASY_OG_PATH', plugin_dir_path( __FILE__ ) );

// Direct URL to this theme, in case something is messing around with it
define( 'EASY_OG_THEME_URL', content_url() . '/themes/' . next(explode('/themes/', get_template_directory())) );


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

function easy_og() {

	// og:title
	if ( is_front_page() || is_home() ) {
		echo '<meta property="og:title" content="' . get_bloginfo('name') . '">' . "\n";
	} else {
		echo '<meta property="og:title" content="' . wp_title('', false) . '">' . "\n";
	}
	
	// og:type
	if ( is_single() ) {
		echo '<meta property="og:type" content="article">' . "\n";
		
		global $posts;
		
		// article:published_time
		echo '<meta property="article:published_time" content="' . get_the_time('c') . '">' . "\n";
		
		// article:modified_time
		echo '<meta property="article:modified_time" content="' . get_the_modified_time('c') . '">' . "\n";
		
		// article:author
		echo '<meta property="article:author" content="' . get_author_posts_url($posts[0]->post_author) . '">' . "\n";
		
		$posttags = get_the_tags($posts->ID);
		if ($posttags) {
			foreach($posttags as $tag) {
				echo '<meta property="article:tag" content="' . $tag->name . '">' . "\n";
			}
		}
			
	} elseif ( is_author() ) {
		echo '<meta property="og:type" content="profile">' . "\n";
	} else {
		echo '<meta property="og:type" content="website">' . "\n";
	}
	
	// og:image
	if ( !is_author() ) {
		if ( function_exists('get_post_thumbnail_id') && get_post_thumbnail_id() ) {
			$image_id = get_post_thumbnail_id();
			$image_url = wp_get_attachment_image_src($image_id,'large', true);
			echo '<meta property="og:image" content="' . home_url() . $image_url[0] . '">' . "\n";
		} else {
			if ( isset( $easy_og_image_default ) ) {
				$image_url = wp_get_attachment_image_src($easy_og_image_id,'large', true);
				echo '<meta property="og:image" content="' . home_url() . $image_url[0] . '">' . "\n";
			} else {
				echo '<meta property="og:image" content="' . EASY_OG_THEME_URL . '/img/screenshot.jpg">' . "\n";
			}
		}
	} else {
		preg_match('/(src)=("[^"]*")/i', str_replace("'", "\"", get_avatar($posts[0]->author)), $matches);
		echo '<meta property="og:image" content=' . $matches[2] . '>' . "\n";
	}
	
	// og:url
	echo '<meta property="og:url" content="' . get_permalink() .'">' . "\n";
	
	// og:site_name
	echo '<meta property="og:site_name" content="' . get_bloginfo('name') . '">' . "\n";
	
	// og:description
	if ( !is_front_page() ) {
		global $posts;
		
		echo '<meta property="og:description" content="' . wp_trim_words(strip_shortcodes($posts[0]->post_content), 20) . '">' . "\n";
	} else { 
		echo '<meta property="og:description" content="Long description of site.">' . "\n";
	}
	
	// og:locale
	echo '<meta property="og:locale" content="en_US">' . "\n";
	
	// fb:admins
	if ( isset($fbadmins) ) {
		echo '<meta property="fb:admins" content="">' . "\n";
	}
	
	// newline for nicer output
	echo "\n";

}


/**
 *
 * Spit it out
 *
 */

add_action('wp_head', 'easy_og');


/**
 *
 * Plugin Updater
 *
 */
 
require_once( EASY_OG_PATH . 'updater.php');