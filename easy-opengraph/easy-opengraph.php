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

// Direct path to current theme, in case something is messing around with it
define( 'EASY_OG_THEME_PATH', trailingslashit(get_theme_root()) . trailingslashit(get_template()) );


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
		    "profile-usernames"   => "",
		
		// image
		"image-status"            => "on",
		    "image-uploaded"      => "",
		    "image-dimensions"    => "",
		    "image-featured"      => "on",
		    "image-scan"          => "",
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
		    "fbprops-admins"      => "",
		    "fbprops-app_id"      => ""
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
 
function easy_og_title($options, $posts, $demo_mode = false) {
	
	if ( is_front_page() || is_home() || $demo_mode == 'website' ) {
		echo '<meta property="og:title" content="' . get_bloginfo('name') . '">' . "\n";
	} else {
		echo '<meta property="og:title" content="' . wp_title('', false) . '">' . "\n";
	}
}

function easy_og_type($options, $posts, $demo_mode = false) {

	if ( $demo_mode )
	{
		$post = $posts[0];
	}
	
	if ( is_single() || $demo_mode == 'article' ) {
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
					echo '<meta property="article:tag" content="' . esc_attr( $tag->name ) . '">' . "\n";
				}
			}
		}
			
	} elseif ( (is_author()  && ($options['profile-status'] == 'on') ) || $demo_mode == 'profile' ) {
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
		if ($options['profile-usernames'] == 'on') {
			echo '<meta property="profile:username" content="' . get_the_author_meta('user_login', $posts[0]->post_author) . '">' . "\n";
		}
		
	} else {
		echo '<meta property="og:type" content="website">' . "\n";
	}
}

function easy_og_image($options, $posts, $demo_mode = false, $do_not_iterate_again = false) {

	if ( $demo_mode )
	{
		$post = $posts[0];
		
	}
	
	if ( ( is_author() || $demo_mode == 'profile' ) && ($options['profile-status'] == 'on') && ($options['image-gravatar'] == 'on') ) {
	// If we're on an author page, og:profile support is on, and we want to use Gravatars...
	
		// Get src, width, and height
		preg_match_all('/(src|width|height)="([^"]*)"/i', str_replace("'", "\"", get_avatar($posts[0]->author, '150')), $matches);
		
		echo '<meta property="og:image" content="' . $matches[2][0] . '">' . "\n";
		
		// Show dimensions
		if ( $options['image-dimensions'] == 'on' ) {
			echo '<meta property="og:image:width" content="' . intval( $matches[2][1] ) . '">' . "\n";
			echo '<meta property="og:image:height" content="' . intval( $matches[2][2] ) . '">' . "\n";
		}
		
	} else {
	// Otherwise...
		
		// Let's set up absolute URLs
		$uploads = wp_upload_dir();
		$parsed_base = parse_url($uploads['baseurl']);
		
		if ( function_exists('get_post_thumbnail_id') && get_post_thumbnail_id() && ($options['image-featured'] == 'on') && !is_front_page() && !is_archive() ) {
		// Use featured image, if it's available and set
			
			// Get featured image ID and image info
			$image_id = get_post_thumbnail_id();
			$image_info = wp_get_attachment_image_src($image_id, 'medium');
			
			// Echo it out
			echo '<meta property="og:image" content="' . $uploads['baseurl'] . str_replace($parsed_base[path], '', $image_info[0]) . '">' . "\n";
			
			// Show dimensions
			if ( $options['image-dimensions'] == 'on' ) {
				echo '<meta property="og:image:width" content="' . intval( $image_info[1] ) . '">' . "\n";
				echo '<meta property="og:image:height" content="' . intval( $image_info[2] ) . '">' . "\n";
			}
			
		} else {
		// If not, cycle through some defaults.
		
			if ( isset($options['image-uploaded']) && !empty($options['image-uploaded']) ) {
			// If it's available, use the user's uploaded default image
				
				// Get the image info
				$image_info = wp_get_attachment_image_src($options['image-uploaded'], 'medium');
				
				// Echo it out
				echo '<meta property="og:image" content="' . $uploads['baseurl'] . str_replace($parsed_base[path], '', $image_info[0]) . '">' . "\n";
				
				// Show dimensions
				if ( $options['image-dimensions'] == 'on' ) {
					echo '<meta property="og:image:width" content="' . intval( $image_info[1] ) . '">' . "\n";
					echo '<meta property="og:image:height" content="' . intval( $image_info[2] ) . '">' . "\n";
				}
				
			} else {
			// Otherwise, auto-detect a theme screenshot
			
				// Get the theme directory as an array
				$theme_dir = @scandir(EASY_OG_THEME_PATH);
				
				// Mush the $theme_dir array into a string and search it for a screenshot
				preg_match_all('/(screenshot.(?:jpg|gif|png|jpeg)),/', implode(',', $theme_dir), $screenshot);
				
				// If we find any, grab the first one and echo it out
				if ( isset($screenshot[1][0]) && !empty($screenshot[1][0]) ) {
					echo '<meta property="og:image" content="' . esc_url( EASY_OG_THEME_URL . $screenshot[1][0] ) . '">' . "\n";
				}
				
			}
		}
		
		// Scan for images in a post
		if ( ( is_single() || $demo_mode == 'article' ) && ($options['image-scan'] == 'on') ) {
		
			// Run preg_match_all to grab all the images and save the results in $images
			preg_match_all('~<img [^>]* />~', $posts[0]->post_content, $images);
			
			// Cycle through the images
			foreach ( $images as $image_arr ) {
				foreach ( $image_arr as $image ) {
				
					// Get the image ID
					preg_match_all('/wp-image-([0-9]*)/', $image, $image_id);
					
					// If we can get the ID...
					if ( isset($image_id[1][0]) && !empty($image_id[1][0]) ) {
					// ...we'll use it, and do this the right way!
						
						// Get the image info
						$image_info = wp_get_attachment_image_src($image_id[1][0], 'medium');
						
						// Echo it out
						echo '<meta property="og:image" content="' . esc_url( $uploads['baseurl'] . str_replace($parsed_base[path], '', $image_info[0]) ) . '">' . "\n";
						
						// Show dimensions
						if ( $options['image-dimensions'] == 'on' ) {
							echo '<meta property="og:image:width" content="' . intval( $image_info[1] ) . '">' . "\n";
							echo '<meta property="og:image:height" content="' . intval( $image_info[2] ) . '">' . "\n";
						}
						
					} else {
					// We couldn't get the ID. Let's do it the old fashioned way.
						
						// Get fallback src
						preg_match_all('/(src)="([^"]*)"/i', $image, $src_match);
						
						// Echo it out
						echo '<meta property="og:image" content="' . esc_url( $src_match[2][0] ) . '">' . "\n";
						
					}
				}
			}
			
		}
	}
}

function easy_og_url($options, $posts, $demo_mode = false) {

	if ( $demo_mode )
	{
		$post = $posts[0];
	}
	
	if ( is_single() || is_page() || $demo_mode == 'article' ) {
		echo '<meta property="og:url" content="' . get_permalink() .'">' . "\n";
	} elseif ( is_author() || $demo_mode == 'profile' ) {
		echo '<meta property="og:url" content="' . get_author_posts_url($posts[0]->post_author) .'">' . "\n";
	} elseif ( is_front_page() || is_home() || $demo_mode == 'website' ) {
		echo '<meta property="og:url" content="' . site_url() .'">' . "\n";
	} else {
		echo '<meta property="og:url" content="' . esc_url( $_SERVER['REQUEST_URI'] ) .'">' . "\n";
	}
}

function easy_og_site_name($options, $posts, $demo_mode = false) {
	if ( $options['site_name-status'] == 'on' ) {
		echo '<meta property="og:site_name" content="' . get_bloginfo('name') . '">' . "\n";
	}
}

function easy_og_description($options, $posts, $demo_mode = false) {
	
	if ( $options['description-status'] == 'on' ) {
		$author_bio = get_the_author_meta('description', $posts[0]->post_author);
		
		// Strip the content down to its bare essentials to make sure we can use it
		$clean_content = trim(str_replace(' ', '', str_replace('&nbsp;', '', wp_trim_words(strip_shortcodes($posts[0]->post_content), 20))));
		
		if ( ( is_single() || $demo_mode == 'article' ) && ($options['description-article'] == 'on') && empty($posts[0]->post_excerpt) && !empty($clean_content) ) {
			echo '<meta property="og:description" content="' . esc_attr( wp_trim_words(strip_shortcodes($posts[0]->post_content), 20) ) . '">' . "\n";
		} elseif ( ( is_single() || $demo_mode == 'article' ) && ($options['description-article'] == 'on') && !empty($posts[0]->post_excerpt) ) {
			echo '<meta property="og:description" content="' . esc_attr( $posts[0]->post_excerpt ) . '">' . "\n";
		} elseif ( (is_author() || $demo_mode == 'profile') && !empty($author_bio) && ($options['description-profile'] == 'on') ) {
			echo '<meta property="og:description" content="' . esc_attr( wp_trim_words(get_the_author_meta('description', $posts[0]->post_author), 20) ) . '">' . "\n";
		} elseif ( (is_archive() || $demo_mode == 'article' ) && !is_author() && $demo_mode != 'profile' ) {
			echo '<meta property="og:description" content="' . esc_attr( $options['description-long'] ) . '">' . "\n";
		} else {
			echo '<meta property="og:description" content="' . esc_attr( $options['description-long'] ) . '">' . "\n";
		}
	}
}

function easy_og_locale($options, $posts, $demo_mode = false) {
	if ( $options['locale-status'] == 'on' ) {
		echo '<meta property="og:locale" content="' . esc_attr( $options['locale-setting'] ) . '">' . "\n";
	}
}

function easy_og_fbprops($options, $posts, $demo_mode = false) {
	if ( $options['fbprops-status'] == 'on' ) {
		
		if ( isset($options['fbprops-admins']) && !empty($options['fbprops-admins']) ) {
			echo '<meta property="fb:admins" content="' . esc_attr( $options['fbprops-admins'] ) . '">' . "\n";
		}
		
		if ( isset($options['fbprops-app_id']) && !empty($options['fbprops-app_id']) ) {
			echo '<meta property="fb:app_id" content="' . esc_attr( $options['fbprops-app_id'] ) . '">' . "\n";
		}
		
	}
}

function easy_og($should_demo = false, $posts = null) {
	// Get options
	if ( !$should_demo )
	{
		$options = get_option('easy_og_options');
	}
	else {
		// in demo mode, all the checkable options should be 'on'
		// inherit other options from live options
		
		$original_options = get_option('easy_og_options');
		
		$options = array(
		
			'article-pubdate' 		=> 'on',
			'article-moddate'		=> 'on',
			'article-tag'			=> 'on',
			'article-cattag'		=> 'on',
			'profile-status'		=> 'on',
			'profile-realnames'		=> 'on',
			'profile-usernames'		=> 'on',
			'image-uploaded' 		=> $original_options['image-uploaded'],
			'image-dimensions'		=> 'on',
			'image-featured'		=> 'on',
			'image-scan'			=> 'on',
			'image-gravatar'		=> 'on',
			'description-long' 		=> $original_options['description-long'],
			'description-article'	=> 'on',
			'description-profile'	=> 'on',
			'locale-setting'		=> $original_options['locale-setting'],
			'fbprops-admins' 		=> $original_options['fbprops-admins'],
			'fbprops-app_id' 		=> $original_options['fbprops-app_id'],
			'site_name-status'		=> 'on',
			'description-status'	=> 'on'
		);
	}
	
	if ( !$posts )
	{
		global $posts;
	}
	
	if ( $should_demo )
	{
		ob_start();
	}
	
	// og:title
	easy_og_title($options, $posts, $should_demo);
	
	// og:type
	easy_og_type($options, $posts, $should_demo);
	
	// og:image
	easy_og_image($options, $posts, $should_demo);
	
	// og:url
	easy_og_url($options, $posts, $should_demo);
	
	// og:site_name
	easy_og_site_name($options, $posts, $should_demo);
	
	// og:description
	easy_og_description($options, $posts, $should_demo);
	
	// og:locale
	easy_og_locale($options, $posts, $should_demo);
	
	// fb:properties
	easy_og_fbprops($options, $posts, $should_demo);
	
	// newline for nicer output
	echo "\n";
	
	if ( $should_demo )
	{
		$demo_output = ob_get_contents();
		ob_end_clean();
		return $demo_output;
	}

}


/**
 *
 * Spit it out
 *
 */

add_action('wp_head', 'easy_og');