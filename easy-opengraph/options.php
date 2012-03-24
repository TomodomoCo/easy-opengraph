<?php


/**
 *
 * Register and load scripts and styles
 *
 */

function easy_og_load_ext($hook) {
	if ( $hook == 'settings_page_easy_og' ) {
		// Register Waypoints
		wp_register_script('easy_og_waypoints', 'http://cdnjs.cloudflare.com/ajax/libs/waypoints/1.1/waypoints.min.js');
		wp_register_script('easy_og_waypoints_ssl', 'https://cdnjs.cloudflare.com/ajax/libs/waypoints/1.1/waypoints.min.js');
		
		// Register Prettify
		wp_register_script('easy_og_prettify', 'http://cdnjs.cloudflare.com/ajax/libs/prettify/188.0.0/prettify.js');
		wp_register_script('easy_og_prettify_ssl', 'https://cdnjs.cloudflare.com/ajax/libs/prettify/188.0.0/prettify.js');
		wp_register_style('easy_og_prettify_css', plugins_url('css/prettify.css', __FILE__));
		
		// Register Plugin JS	
		wp_register_script('easy_og_js', plugins_url('js/script.js', __FILE__));
		
		// Register Plugin Style
		wp_register_style('easy_og_style', plugins_url('css/style.css', __FILE__));
		
		// Enqueue scripts
		if (is_ssl()) {
			wp_enqueue_script('easy_og_waypoints_ssl');
			wp_enqueue_script('easy_og_prettify_ssl');
		} else {
			wp_enqueue_script('easy_og_waypoints');
			wp_enqueue_script('easy_og_prettify');
		}
		wp_enqueue_script('common');
		wp_enqueue_script('wp-lists');
		wp_enqueue_script('postbox');
		wp_enqueue_script('media-upload');
		wp_enqueue_script('thickbox');
		wp_enqueue_script('easy_og_js');
		
		// Enqueue style
		wp_enqueue_style('easy_og_style');
		wp_enqueue_style('easy_og_prettify_css');
		wp_enqueue_style('thickbox');
	}
}
add_action('admin_enqueue_scripts', 'easy_og_load_ext');


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
 * Validate/sanitize and register the settings
 *
 */

// Validate
function easy_og_validate($input) {
	$input['locale-setting'] = strip_tags($input['locale-setting']);
	$input['description-long'] = strip_tags($input['description-long']);
	return $input;
}

// Register
function easy_og_register() {
	register_setting('easy_og_plugin_options', 'easy_og_options', 'easy_og_validate');
}
add_action('admin_init', 'easy_og_register' );


/**
 *
 * Set up the settings page
 *
 */

function easy_og_settings() {	
	
	// Test permissions
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	
	// Get options
	$options = get_option('easy_og_options');
	
	
	/**
	 *
	 * Set up the metaboxes
	 *
	 */
	
	// Preview
	add_meta_box('preview', 'Code Preview', 'easy_og_preview_box', 'easy_og', 'side', 'core');
	
	// Title
	add_meta_box('easy_og-title', '<input type="checkbox" checked disabled> Title', 'easy_og_title_box', 'easy_og', 'normal', 'core');
	
	// Type
	add_meta_box('easy_og-type', '<input type="checkbox" checked disabled> Type', 'easy_og_type_box', 'easy_og', 'normal', 'core');
	
	// Image
	add_meta_box('easy_og-image', '<input type="checkbox" checked disabled> Image', 'easy_og_image_box', 'easy_og', 'normal', 'core');
	
	// URL
	add_meta_box('easy_og-url', '<input type="checkbox" checked disabled> URL', 'easy_og_url_box', 'easy_og', 'normal', 'core');
	
	// Site Name
	add_meta_box('easy_og-site_name', '<input name="easy_og_options[site_name-status]" type="checkbox" ' . checked( $options['site_name-status'], 'on', false ) . '> Site Name', 'easy_og_site_name_box', 'easy_og', 'normal', 'core');
	
	// Description
	add_meta_box('easy_og-description', '<input name="easy_og_options[description-status]" type="checkbox" ' . checked( $options['description-status'], 'on', false ) . '> Description', 'easy_og_description_box', 'easy_og', 'normal', 'core');
	
	// Locale
	add_meta_box('easy_og-locale', '<input name="easy_og_options[locale-status]" type="checkbox" ' . checked( $options['locale-status'], 'on', false ) . '> Locale', 'easy_og_locale', 'easy_og_box', 'normal', 'core');
	
	// Facebook-specific Properties
	add_meta_box('easy_og-fbprops', '<input name="easy_og_options[fbprops-status]" type="checkbox" ' . checked( $options['fbprops-status'], 'on', false ) . '> Facebook-specific Properties_box', 'easy_og_fbprops', 'easy_og', 'normal', 'core');
	
	?>
	<div class="wrap">
		<div class="icon32" id="icon-opengraph"><br></div>
		<h2>Easy OpenGraph Settings</h2>
		<p><strong>Easy OpenGraph</strong> is ready to work out of the box, but we've provided some settings below so you can personalize the output.</p>
		<form method="post" action="options.php">
			<?php
			
			// Add nonces and hidden fields
			settings_fields('easy_og_plugin_options');
			wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false );
			
			?>
			<div class="metabox-holder has-right-sidebar">
				<div class="inner-sidebar">
					<?php do_meta_boxes('easy_og', 'side', $data); ?>
				</div>
				<div id="post-body">
					<div id="post-body-content">
						<?php do_meta_boxes('easy_og', 'normal', $data); ?>
						
						<p class="submit">
							<input type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
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

// Preview
function easy_og_preview_box() {

	global $post;
	$rand_posts = get_posts('numberposts=1&orderby=rand');
	
	foreach( $rand_posts as $post ) {

	?>
		<ul class="wp-tab-bar">
			<li class="wp-tab-active"><a href="#tabs-1">type:website</a></li>
			<li><a href="#tabs-2">type:article</a></li>
			<li><a href="#tabs-3">type:profile</a></li>
		</ul>
		<div class="wp-tab-panel code-preview" id="tabs-1">
			<pre class="prettyprint"><code class="lang-html"><?php
				/**
				 *
				 * Website
				 *
				 */
				
				esc_html(easy_og('website_demo'));
			?></code></pre>
		</div>
		<div class="wp-tab-panel code-preview" id="tabs-2" style="display: none;">
			<pre class="prettyprint"><code class="lang-html"><?php
				/**
				 *
				 * Article
				 *
				 */
				
				easy_og('article_demo');
			?></code></pre>
		</div>
		<div class="wp-tab-panel code-preview" id="tabs-3" style="display: none;">
			<pre class="prettyprint"><code class="lang-html"><?php
				/**
				 *
				 * Profile
				 *
				 */
				
				easy_og('profile_demo');
			?></code></pre>
		</div>
	<?php
	
	}
	
}

// og:title
function easy_og_title_box() {
	echo '<p>There are no adjustable settings for the <strong>title</strong> property.</p>';
}

// og:type
function easy_og_type_box() {
	// Get options
	$options = get_option('easy_og_options');
	
	echo '<ul class="wp-tab-bar">
			<li class="wp-tab-active"><a href="#tabs-1">type:website</a></li>
			<li><a href="#tabs-2">type:article</a></li>
			<li><a href="#tabs-3">type:profile</a></li>
		</ul>
		<div class="wp-tab-panel" id="tabs-1">
			<p>The <strong>website</strong> type is used on the front page and most archive pages.</p>
		</div>
		<div class="wp-tab-panel" id="tabs-2" style="display: none;">
			<p><input type="checkbox" name="easy_og_options[article-pubdate]" ' . checked( $options['article-pubdate'], 'on', false ) . '> Include article published datetime</p>
			<p><input type="checkbox" name="easy_og_options[article-moddate]" ' . checked( $options['article-moddate'], 'on', false ) . '> Include article modified datetime</p>
			<p><input type="checkbox" name="easy_og_options[article-tag]" ' . checked( $options['article-tag'], 'on', false ) . '> Include article tags</p>
			<p>Where should we get the data for OpenGraph article tags? <strong>' . $options['article-cattag'] . '</strong></p>
		</div>
		<div class="wp-tab-panel" id="tabs-3" style="display: none;">
			<p>The <strong>profile</strong> type is used on author archive pages.</p>
			<p><input type="checkbox" name="easy_og_options[profile-status]" ' . checked( $options['profile-status'], 'on', false ) . '> Enable the <strong>profile</strong> type</p>
			<p><input type="checkbox" name="easy_og_options[profile-realnames]" ' . checked( $options['profile-realnames'], 'on', false ) . '> Include first and last names (if available)</p>
			<p><input type="checkbox" name="easy_og_options[profile-usernames]" ' . checked( $options['profile-usernames'], 'on', false ) . '> Include usernames</p>
		</div>';
}

// og:image
function easy_og_image_box() {
	// Get options
	$options = get_option('easy_og_options');
	
	echo '<ul class="wp-tab-bar">
			<li class="wp-tab-active"><a href="#tabs-1">Default</a></li>
			<li><a href="#tabs-2">type:article</a></li>
			<li><a href="#tabs-3">type:profile</a></li>
		</ul>
		<div class="wp-tab-panel" id="tabs-1">
			<p>Upload a default image below. <strong><i>(Recommended)</i></strong></p>
			<p>
				<a href="javascript:;" class="button-secondary" id="upload-default-image">Upload image</a>
				<input type="hidden" name="easy_og_options[image-uploaded]" value="' . $options['image-uploaded'] . '">
			</p>
			
			<div id="image-uploaded">';
			if ( isset($options['image-uploaded']) && !empty($options['image-uploaded']) ) {
				$image_info = wp_get_attachment_image_src($options['image-uploaded'], 'medium');
				echo '<img src="' . $image_info[0] . '">';
			}
			echo '</div>
			
			<p><strong>Note:</strong> If no default is set, we will use your active theme&rsquo;s sample screenshot.</p>
			<p><input type="checkbox" name="easy_og_options[image-dimensions]" ' . checked( $options['image-dimensions'], 'on', false ) . '> Include image dimensions, if available</i></strong></p>
		</div>
		<div class="wp-tab-panel" id="tabs-2" style="display: none;">
			<p><input type="checkbox" name="easy_og_options[image-featured]" ' . checked( $options['image-featured'], 'on', false ) . '> Use a post or page&rsquo;s featured image, if available <strong><i>(Recommended)</i></strong></p>
			<p><input type="checkbox" name="easy_og_options[image-scan]" ' . checked( $options['image-scan'], 'on', false ) . '> Provide additional image options by scanning a post or page for embedded images</p>
		</div>
		<div class="wp-tab-panel" id="tabs-3" style="display: none;">
			<p><input type="checkbox" name="easy_og_options[image-gravatar]" ' . checked( $options['image-gravatar'], 'on', false ) . '> Use a user&rsquo;s <a href="http://www.gravatar.com">Gravatar</a> on profile pages <strong><i>(Recommended)</i></strong></p>
		</div>';
}

// og:url
function easy_og_url_box() {
	echo '<p>There are no adjustable settings for the <strong>URL</strong> property.</p>';
}

// og:site_name
function easy_og_site_name_box() {
	echo '<p>There are no adjustable settings for the <strong>site name</strong> property.</p><p>The <strong>site name</strong> is equivalent to your site title, which is set in your <a href="' . admin_url('options-general.php') . '">general settings</a>.</p>';
}

// og:description
function easy_og_description_box() {
	// Get options
	$options = get_option('easy_og_options');
	
	echo '<ul class="wp-tab-bar">
			<li class="wp-tab-active"><a href="#tabs-1">Default</a></li>
			<li><a href="#tabs-2">type:article</a></li>
			<li><a href="#tabs-3">type:profile</a></li>
		</ul>
		<div class="wp-tab-panel" id="tabs-1">
			<p>Set a long description for your site. We use your slogan by default, but it can be longer.</p>
			<p><textarea class="widefat" name="easy_og_options[description-long]">' . $options['description-long'] . '</textarea></p>
		</div>
		<div class="wp-tab-panel" id="tabs-2" style="display: none;">
			<p><input type="checkbox" name="easy_og_options[description-article]" ' . checked( $options['description-article'], 'on', false ) . '> Set the article description from the post excerpt, if it exists (we&rsquo;ll generate one from your content if you don&rsquo;t set one manually)</p>
		</div>
		<div class="wp-tab-panel" id="tabs-3" style="display: none;">
			<p><input type="checkbox" name="easy_og_options[description-profile]" ' . checked( $options['description-article'], 'on', false ) . '> Set the profile description from the author&rsquo;s biography, if it exists</p>
		</div>';
}

// og:locale
function easy_og_locale_box() {
	// Get options
	$options = get_option('easy_og_options');
	
	// See if we can get the locale automatically, and display a message as a result.
	$auto_locale = get_locale();
	if ( isset($auto_locale) ) {
		echo '<p>We&rsquo;ve auto-detected your site&rsquo;s locale, but you can override it below.</p>';
	} else {
		echo '<p>We couldn&rsquo;t detect a locale. You can set it manually below.</p>';
	}
	
	// Display the form and populate it with the set locale
	echo '<input type="text" name="easy_og_options[locale-setting]" value="' . $options['locale-setting'] . '">';
}

// FB properties
function easy_og_fbprops_box() {
	// Get options
	$options = get_option('easy_og_options');
	
	echo '<ul class="wp-tab-bar">
			<li class="wp-tab-active"><a href="#tabs-1">fb:admins</a></li>
			<li><a href="#tabs-2">fb:app_id</a></li>
		</ul>
		<div class="wp-tab-panel" id="tabs-1">
			<input type="text" name="easy_og_options[fbprops-admins]" value="' . $options['fbprops-admins'] . '">
		</div>
		<div class="wp-tab-panel" id="tabs-2" style="display: none;">
			<input type="text" name="easy_og_options[fbprops-app_id]" value="' . $options['fbprops-app_id'] . '">
		</div>';
}


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