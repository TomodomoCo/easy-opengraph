<?php

/**
 *
 * Plugin Updater
 *
 */

// Plugin prefix = easy_og (find and replace me)
$easy_og_api_url = 'https://updates.vanpattenmedia.com/';
$easy_og_plugin_slug = basename(dirname(__FILE__));


/**
 *
 * This is very hacky, but it's the best way to force-update plugins.
 * This should be improved in WordPress 3.4, see Trac #18876
 *
 */
 
/* if ( !function_exists('remove_plugin_transient') ) {
	function remove_plugin_transient() {
		$file = basename( $_SERVER['PHP_SELF'] );
		if ( $file == 'update-core.php' ) {
			set_site_transient('update_plugins', null);
		}
	}
	add_action('admin_head', 'remove_plugin_transient');
} */


/**
 *
 * Set up the request.
 *
 */

function easy_og_request($action, $args) {
	global $wp_version;
	
	return array(
		'body' => array(
			'action' => $action, 
			'request' => serialize($args),
			'api-key' => md5(get_bloginfo('url'))
		),
		'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url')
	);	
}


/**
 *
 * Now that we've reset the plugin updater, we can hijack it.
 *
 */
 
function easy_og_update_check($checked_data) {
	global $easy_og_api_url, $easy_og_plugin_slug;
	
	if (empty($checked_data->checked))
		return $checked_data;
	
	$request_args = array(
		'slug' => $easy_og_plugin_slug,
		'version' => $checked_data->checked[$easy_og_plugin_slug .'/'. $easy_og_plugin_slug .'.php'],
	);
	
	$request_string = easy_og_request('basic_check', $request_args);
	
	// Start checking for an update
	$raw_response = wp_remote_post($easy_og_api_url, $request_string);
	
	if (!is_wp_error($raw_response) && ($raw_response['response']['code'] == 200))
		$response = unserialize($raw_response['body']);
	
	if (is_object($response) && !empty($response)) // Feed the update data into WP updater
		$checked_data->response[$easy_og_plugin_slug .'/'. $easy_og_plugin_slug .'.php'] = $response;
	
	return $checked_data;
}
add_filter('pre_set_site_transient_update_plugins', 'easy_og_update_check');


/**
 *
 * Display the plugin information through the API.
 *
 */

function easy_og_api_call($def, $action, $args) {
	global $easy_og_plugin_slug, $easy_og_api_url;
	
	if ($args->slug != $easy_og_plugin_slug)
		return false;
	
	// Get the current version
	$plugin_info = get_site_transient('update_plugins');
	$current_version = $plugin_info->checked[$easy_og_plugin_slug .'/'. $easy_og_plugin_slug .'.php'];
	$args->version = $current_version;
	
	$request_string = easy_og_request($action, $args);
	
	$request = wp_remote_post($easy_og_api_url, $request_string);
	
	if (is_wp_error($request)) {
		$res = new WP_Error('plugins_api_failed', __('An Unexpected HTTP Error occurred during the API request.</p> <p><a href="?" onclick="document.location.reload(); return false;">Try again</a>'), $request->get_error_message());
	} else {
		$res = unserialize($request['body']);
		
		if ($res === false)
			$res = new WP_Error('plugins_api_failed', __('An unknown error occurred'), $request['body']);
	}
	
	return $res;
}
add_filter('plugins_api', 'easy_og_api_call', 10, 3);