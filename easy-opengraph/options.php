<?php

function easy_og_load_style($hook) {
	if ( $hook == 'settings_page_easy_og' ) {
		wp_register_style('easy_og_style', plugins_url('css/style.css', __FILE__));
		wp_enqueue_style('easy_og_style');
	}
}
add_action('admin_enqueue_scripts', 'easy_og_load_style');

function easy_og_menu() {
	add_options_page('Easy OpenGraph Settings', 'Easy OpenGraph', 'manage_options', 'easy_og', 'easy_og_settings');
}
add_action('admin_menu', 'easy_og_menu');

function easy_og_settings() {	
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	?>
	<div class="wrap">
	<div class="icon32" id="icon-opengraph"><br></div>
	<h2>Easy OpenGraph</h2>
	<p><strong>Easy OpenGraph</strong> is ready to work out of the box, but we've provided some settings below so you can personalize the output.</p>
	<form method="post" action="options.php">
		<h3>Title, Type, Image, and URL</h3>
		<p>Sorry, these four properties are required. <a href="http://ogp.me/#metadata">See the OpenGraph Protocol website</a> for more.</p>
		
		<h3>Site Name</h3>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="easy_og-sitename_check">Enable?</label></th>
					<td>
						<input type="checkbox" name="easy_og-sitename_check" id="easy_og-sitename_check" checked>
					</td>
				</tr>
			</tbody>
		</table>
		
		<h3>Description</h3>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="easy_og-description_check">Enable?</label></th>
					<td>
						<input type="checkbox" name="easy_og-description_check" id="easy_og-description_check" checked>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="easy_og-description">Set a long description</label></th>
					<td>
						<textarea name="easy_og-description" id="easy_og-description" class="large-text"><?php bloginfo('description'); ?></textarea>
						<span class="description">The long description is used when we can't get an excerpt (on your home page, for instance). By default, we'll use your site's slogan, but you can also set something longer.</span>
					</td>
				</tr>
			</tbody>
		</table>
		
		<h3>Locale</h3>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="easy_og-locale_check">Enable?</label></th>
					<td>
						<input type="checkbox" name="easy_og-locale_check" id="easy_og-locale_check" checked>
					</td>
				</tr>
			</tbody>
		</table>
		
		<h3>Facebook-specific properties</h3>
		<p>Lorem ipsum dolor sit amet.</p>
		
		<p class="submit">
			<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
		</p>
	</form>
	</div>
	<?php
}