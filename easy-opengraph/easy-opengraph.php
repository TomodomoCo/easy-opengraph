<?php
/*
Plugin Name: VPM Easy OpenGraph
Plugin URI: http://www.vanpattenmedia.com/
Description: "Set it and forget it" Facebook OpenGraph
Author: Van Patten Media
Version: 0.6
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
define( 'EASY_OG_THEME_URL', trailingslashit(content_url()) . trailingslashit('themes') . trailingslashit(get_template()) );


/**
 *
 * Plugin activation/deactivation
 *
 */

// Set default options on activation
function easy_og_defaults() {
	$locale_default = get_locale();
	if ( !isset($locale_default) ) {
		$locale_default = 'en_US';
	}
	
	$description_default = get_bloginfo('description');
	if ( empty($description_default) ) {
		$description_default = 'Visit ' . get_bloginfo('name');
	}
	
	$arr = array(
		// type:article
		"article-status"          => "on",
		    "article-pubdate"     => "on",
		    "article-moddate"     => "on",
		    "article-tag"         => "on",
		    "article-cattag"      => "Tags",
		
		// type:profile
		"profile-status"          => "on",
		    "profile-realnames"   => "on",
		    "profile-usernames"   => "on",
		
		// image
		"image-status"            => "on",
		    "image-featured"      => "on",
		    "image-gravatar"      => "on",
		
		// og:site_name
		"site_name-status"        => "on",
		
		// og:description
		"description-status"      => "on",
		    "description-long"    => $description_default,
		    "description-article" => "on",
		    "description-profile" => "on",
		
		// og:locale
		"locale-status"           => "on",
		    "locale-setting"      => $locale_default,
		
		// FB Properties
		"fbprops-status"          => "",
		    "fbprops-admins"      => ""
	);
	update_option('easy_og_options', $arr);
}
register_activation_hook(__FILE__, 'easy_og_defaults');

// Delete options on deactivation
function easy_og_delete_options() {
	delete_option('easy_og_options');
}
register_uninstall_hook(__FILE__, 'easy_og_delete_options');


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
	// Get options
	$options = get_option('easy_og_options');
	
	global $posts;

	// og:title
	if ( is_front_page() || is_home() ) {
		echo '<meta property="og:title" content="' . get_bloginfo('name') . '">' . "\n";
	} else {
		echo '<meta property="og:title" content="' . wp_title('', false) . '">' . "\n";
	}
	
	// og:type
	if ( is_single() ) {
		echo '<meta property="og:type" content="article">' . "\n";
		
		// article:published_time
		if ($options['article-pubdate'] == 'on') {
			echo '<meta property="article:published_time" content="' . get_the_time('c') . '">' . "\n";
		}
		
		// article:modified_time
		if ($options['article-moddate'] == 'on') {
			echo '<meta property="article:modified_time" content="' . get_the_modified_time('c') . '">' . "\n";
		}
		
		// article:author
		if ($options['profile-status'] == 'on') {
			echo '<meta property="article:author" content="' . get_author_posts_url($posts[0]->post_author) . '">' . "\n";
		}
		
		// article:tag
		if ($options['article-tag'] == 'on') {
			$posttags = get_the_tags($posts->ID);
			if ($posttags) {
				foreach($posttags as $tag) {
					echo '<meta property="article:tag" content="' . $tag->name . '">' . "\n";
				}
			}
		}
			
	} elseif ( is_author() && ($options['profile-status'] == 'on') ) {
		echo '<meta property="og:type" content="profile">' . "\n";
		
		// profile:first_name
		if ( get_the_author_meta('user_firstname', $posts[0]->post_author) ) {
			echo '<meta property="profile:first_name" content="' . get_the_author_meta('user_firstname', $posts[0]->post_author) . '">' . "\n";
		}
		
		// profile:last_name
		if ( get_the_author_meta('user_lastname', $posts[0]->post_author) ) {
			echo '<meta property="profile:last_name" content="' . get_the_author_meta('user_lastname', $posts[0]->post_author) . '">' . "\n";
		}
		
		// profile:username
		echo '<meta property="profile:username" content="' . get_the_author_meta('user_login', $posts[0]->post_author) . '">' . "\n";
		
	} else {
		echo '<meta property="og:type" content="website">' . "\n";
	}
	
	// og:image
	if ( is_author() && ($options['profile-status'] == 'on') && ($options['image-gravatar'] == 'on') ) {
		preg_match('/(src)=("[^"]*")/i', str_replace("'", "\"", get_avatar($posts[0]->author)), $matches);
		echo '<meta property="og:image" content=' . $matches[2] . '>' . "\n";
	} else {
		if ( function_exists('get_post_thumbnail_id') && get_post_thumbnail_id() && ($options['image-featured'] == 'on') ) {
			$image_id = get_post_thumbnail_id();
			$image_url = wp_get_attachment_image_src($image_id,'large', true);
			echo '<meta property="og:image" content="' . home_url() . $image_url[0] . '">' . "\n";
		} else {
			if ( isset( $easy_og_image_default ) ) {
				$image_url = wp_get_attachment_image_src($easy_og_image_id,'large', true);
				echo '<meta property="og:image" content="' . $image_url[0] . '">' . "\n";
			} else {
				echo '<meta property="og:image" content="' . EASY_OG_THEME_URL . trailingslashit('img') . 'screenshot.jpg">' . "\n";
			}
		}
	}
	
	// og:url
	if ( is_single() || is_page() ) {
		echo '<meta property="og:url" content="' . get_permalink() .'">' . "\n";
	} elseif ( is_author() ) {
		echo '<meta property="og:url" content="' . get_author_posts_url($posts[0]->post_author) .'">' . "\n";
	} elseif ( is_front_page() || is_home() ) {
		echo '<meta property="og:url" content="' . site_url() .'">' . "\n";
	} else {
		echo '<meta property="og:url" content="' . esc_url( $_SERVER['REQUEST_URI'] ) .'">' . "\n";
	}
	
	// og:site_name
	if ( $options['site_name-status'] == 'on' ) {
		echo '<meta property="og:site_name" content="' . get_bloginfo('name') . '">' . "\n";
	}
	
	// og:description
	if ( $options['description-status'] == 'on' ) {
		$author_bio = get_the_author_meta('description', $posts[0]->post_author);
		
		if ( is_single() && ($options['description-article'] == 'on') && empty($posts[0]->post_excerpt) ) {
			echo '<meta property="og:description" content="' . wp_trim_words(strip_shortcodes($posts[0]->post_content), 20) . '">' . "\n";
		} elseif ( is_single() && ($options['description-article'] == 'on') && !empty($posts[0]->post_excerpt) ) {
			echo '<meta property="og:description" content="' . $posts[0]->post_excerpt . '">' . "\n";
		} elseif ( is_author() && !empty($author_bio) && ($options['description-profile'] == 'on') ) {
			echo '<meta property="og:description" content="' . wp_trim_words(get_the_author_meta('description', $posts[0]->post_author), 20) . '">' . "\n";
		} elseif ( is_archive() && !is_author() ) {
			echo '<meta property="og:description" content="' . $options['description-long'] . '">' . "\n";
		} else {
			echo '<meta property="og:description" content="' . $options['description-long'] . '">' . "\n";
		}
	}
	
	// og:locale
	if ( $options['locale-status'] == 'on' ) {
		echo '<meta property="og:locale" content="' . $options['locale-setting'] . '">' . "\n";
	}
	
	// fb:admins
	if ( $options['fbprops-status'] == 'on' ) {
		echo '<meta property="fb:admins" content="' . $options['fbprops-admins'] . '">' . "\n";
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