<?php

/**
 *
 * Plugin Updater
 *
 */

$vpm_api_url = 'https://updates.vanpattenmedia.com/';
$og_plugin_slug = basename(dirname(__FILE__));


/**
 *
 * This is very hacky, but it's the best way to force-update plugins.
 * This should be improved in WordPress 3.4, see Trac #18876
 *
 */
 
function remove_plugin_transient() {
	$file = basename( $_SERVER['PHP_SELF'] );
	if ( $file == 'update-core.php' ) {
		set_site_transient('update_plugins', null);
	}
}
add_action('admin_head', 'remove_plugin_transient');


/**
 *
 * Now that we've reset the plugin updater, we can hijack it.
 *
 */
 
function check_for_plugin_update($checked_data) {
	global $vpm_api_url, $og_plugin_slug;
	
	if (empty($checked_data->checked))
		return $checked_data;
	
	$request_args = array(
		'slug' => $og_plugin_slug,
		'version' => $checked_data->checked[$og_plugin_slug .'/'. $og_plugin_slug .'.php'],
	);
	
	$request_string = prepare_request('basic_check', $request_args);
	
	// Start checking for an update
	$raw_response = wp_remote_post($vpm_api_url, $request_string);
	
	if (!is_wp_error($raw_response) && ($raw_response['response']['code'] == 200))
		$response = unserialize($raw_response['body']);
	
	if (is_object($response) && !empty($response)) // Feed the update data into WP updater
		$checked_data->response[$og_plugin_slug .'/'. $og_plugin_slug .'.php'] = $response;
	
	return $checked_data;
}
add_filter('pre_set_site_transient_update_plugins', 'check_for_plugin_update');


/**
 *
 * Display the plugin information through the API.
 *
 */

function my_plugin_api_call($def, $action, $args) {
	global $og_plugin_slug, $vpm_api_url;
	
	if ($args->slug != $og_plugin_slug)
		return false;
	
	// Get the current version
	$plugin_info = get_site_transient('update_plugins');
	$current_version = $plugin_info->checked[$og_plugin_slug .'/'. $og_plugin_slug .'.php'];
	$args->version = $current_version;
	
	$request_string = prepare_request($action, $args);
	
	$request = wp_remote_post($vpm_api_url, $request_string);
	
	if (is_wp_error($request)) {
		$res = new WP_Error('plugins_api_failed', __('An Unexpected HTTP Error occurred during the API request.</p> <p><a href="?" onclick="document.location.reload(); return false;">Try again</a>'), $request->get_error_message());
	} else {
		$res = unserialize($request['body']);
		
		if ($res === false)
			$res = new WP_Error('plugins_api_failed', __('An unknown error occurred'), $request['body']);
	}
	
	return $res;
}
add_filter('plugins_api', 'my_plugin_api_call', 10, 3);

function prepare_request($action, $args) {
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