<?php


/**
 *
 * Register and load
 *
 */

function easy_og_load_ext($hook) {
	if ( $hook == 'settings_page_easy_og' ) {
		// Register scripts
		wp_register_script('easy_og_waypoints', 'http://cdnjs.cloudflare.com/ajax/libs/waypoints/1.1/waypoints.min.js');
		wp_register_script('easy_og_waypoints_ssl', 'https://cdnjs.cloudflare.com/ajax/libs/waypoints/1.1/waypoints.min.js');
		wp_register_script('easy_og_js', plugins_url('js/script.js', __FILE__));
		
		// Register style
		wp_register_style('easy_og_style', plugins_url('css/style.css', __FILE__));
		
		// Enqueue scripts
		if (is_ssl()) {
			wp_enqueue_script('easy_og_waypoints_ssl');
		} else {
			wp_enqueue_script('easy_og_waypoints');
		}
		wp_enqueue_script('common');
		wp_enqueue_script('wp-lists');
		wp_enqueue_script('postbox');
		wp_enqueue_script('easy_og_js');
		
		// Enqueue style
		wp_enqueue_style('easy_og_style');
	}
}
add_action('admin_enqueue_scripts', 'easy_og_load_ext');


/**
 *
 * Don't save metabox re-ordering
 *
 */

function easy_og_disable_metabox_ordering($action) {
    if ( 'meta-box-order' == $action ) {
    	die;
    }
}
add_action('check_ajax_referer', 'easy_og_disable_metabox_ordering');


/**
 *
 * Set up the menu item
 *
 */

function easy_og_menu() {
	add_options_page('Easy OpenGraph Settings', 'OpenGraph', 'manage_options', 'easy_og', 'easy_og_settings');
}
add_action('admin_menu', 'easy_og_menu');


/**
 *
 * Set up the settings page
 *
 */

function easy_og_settings() {	
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	
	
	/**
	 *
	 * Set up the metaboxes
	 *
	 */
	
	// Preview
	add_meta_box('preview', 'Preview', 'content_two', 'easy_og', 'side', 'core');
	
	// Title
	add_meta_box('easy_og-title', '<input type="checkbox" checked disabled> Title', 'content', 'easy_og', 'normal', 'core');
	
	// Type
	add_meta_box('easy_og-type', '<input type="checkbox" checked disabled> Type', 'content', 'easy_og', 'normal', 'core');
	
	// Image
	add_meta_box('easy_og-image', '<input type="checkbox" checked disabled> Image', 'content', 'easy_og', 'normal', 'core');
	
	// URL
	add_meta_box('easy_og-url', '<input type="checkbox" checked disabled> URL', 'content', 'easy_og', 'normal', 'core');
	
	// Site Name
	add_meta_box('easy_og-site_name', '<input type="checkbox" checked> Site Name', 'content', 'easy_og', 'normal', 'core');
	
	// Description
	add_meta_box('easy_og-description', '<input type="checkbox" checked> Description', 'content', 'easy_og', 'normal', 'core');
	
	// Locale
	add_meta_box('easy_og-locale', '<input type="checkbox" checked> Locale', 'content', 'easy_og', 'normal', 'core');
	
	// Facebook-specific Properties
	add_meta_box('easy_og-fbprops', '<input type="checkbox" checked> Facebook-specific Properties', 'content', 'easy_og', 'normal', 'core');
	
	?>
	<div class="wrap">
		<div class="icon32" id="icon-opengraph"><br></div>
		<h2>Easy OpenGraph Settings</h2>
		<p><strong>Easy OpenGraph</strong> is ready to work out of the box, but we've provided some settings below so you can personalize the output.</p>
		<form method="post" action="options.php">
			<?php wp_nonce_field('easy_og-metaboxes'); ?>
			<?php wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false ); ?>
			<div class="metabox-holder has-right-sidebar">
				<div class="inner-sidebar">
					<?php do_meta_boxes('easy_og', 'side', $data); ?>
				</div>
				<div id="post-body">
					<div id="post-body-content">
						<?php do_meta_boxes('easy_og', 'normal', $data); ?>
						
						<p class="submit">
							<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
						</p>
					</div>
				</div>
			</div>
		</form>
	</div>
	<?php
}


/**
 *
 * Set up metabox content
 *
 */

function content() {
	echo '<ul class="wp-tab-bar">
			<li class="wp-tab-active"><a href="#tabs-1">Tab 1</a></li>
			<li><a href="#tabs-2">Tab 2</a></li>
			<li><a href="#tabs-3">Tab 3</a></li>
		</ul>
		<div class="wp-tab-panel" id="tabs-1">
			<p>Tab 1 content</p>
		</div>
		<div class="wp-tab-panel" id="tabs-2" style="display: none;">
			<p>Tab 2 content</p>
		</div>
		<div class="wp-tab-panel" id="tabs-3" style="display: none;">
			<p>Tab 3 content</p>
		</div>';
}

function content_two() {
	echo '<ul class="wp-tab-bar">
			<li class="wp-tab-active"><a href="#tabs-1">Tab 1</a></li>
			<li><a href="#tabs-2">Tab 2</a></li>
			<li><a href="#tabs-3">Tab 3</a></li>
		</ul>
		<div class="wp-tab-panel" id="tabs-1">
			<p>Tab 1 content</p>
		</div>
		<div class="wp-tab-panel" id="tabs-2" style="display: none;">
			<p>Tab 2 content</p>
		</div>
		<div class="wp-tab-panel" id="tabs-3" style="display: none;">
			<p>Tab 3 content</p>
		</div>';
}