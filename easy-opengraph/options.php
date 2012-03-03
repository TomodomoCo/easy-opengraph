<?php

function easy_og_menu() {
	add_options_page('Easy OpenGraph Settings', 'Easy OpenGraph', 'manage_options', 'easy_og', 'easy_og_settings');
}
add_action('admin_menu', 'easy_og_menu');

function easy_og_settings() {
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	echo '<div class="wrap">';
	echo '<p>Here is where the form would go if I actually had options.</p>';
	echo '</div>';
}