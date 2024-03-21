<?php

//create custom post type 'jobs'

function create_posttype_jobs()
{
	register_post_type(
		'jobs',
		array(
			'labels' => array(
				'name' => __('Jobs'),
				'singular_name' => __('Job')
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'jobs'),
			'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
		)
	);
}
add_action('init', 'create_posttype_jobs');

// adding the meta field
function add_custom_url_metabox()
{
	add_meta_box(
		'job_custom_url', // ID meta field
		__('Job Custom URL', 'textdomain'), // The title of the meta box
		'custom_url_metabox_callback', // Callback function to display the field
		'jobs', // The ID of the post to which the meta box will be added
		'normal', // Display context
		'high' // Display priority
	);
}
add_action('add_meta_boxes', 'add_custom_url_metabox');

// Callback to display the field
function custom_url_metabox_callback($post)
{
	//use nonce for verification
	wp_nonce_field('custom_url_nonce_action', 'custom_url_nonce');

	// Gets the value of the meta field, if it exists
	$value = get_post_meta($post->ID, 'custom_url', true);

	// display meta field
	echo '<label for="custom_url_field">Custom URL: </label>';
	echo '<input type="url" id="custom_url_field" name="custom_url_field" value="' . esc_attr($value) . '" size="25" />';
}

// Meta field saving
function save_custom_url_meta_box_data($post_id)
{
	// Check nonce
	if (!isset($_POST['custom_url_nonce']) || !wp_verify_nonce($_POST['custom_url_nonce'], 'custom_url_nonce_action')) {
		return;
	}

	// autosave check
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}

	// Checking user rights
	if (isset($_POST['post_type']) && 'jobs' == $_POST['post_type']) {
		if (!current_user_can('edit_page', $post_id)) {
			return;
		}
	} else {
		if (!current_user_can('edit_post', $post_id)) {
			return;
		}
	}

	// Updating the meta field
	if (isset($_POST['custom_url_field']) && !empty($_POST['custom_url_field'])) {
		update_post_meta($post_id, 'custom_url', sanitize_text_field($_POST['custom_url_field']));
	}
}
add_action('save_post', 'save_custom_url_meta_box_data');