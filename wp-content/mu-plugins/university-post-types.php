<?php

/**
 * Note That for the supports atrgument (title, editor) are the defaults for this option 
 * there is 2 types for custom fields (ACF, CMB2)
 */
//register Post types
add_action('init', 'university_post_types');
function university_post_types()
{
	//Event Post type
	register_post_type('event', array(
		'capability_type' => 'event',
		'map_meta_cap' => true,
		'show_in_rest' => true, //to make it show in the rest API
		'supports' => array('title', 'editor', 'excerpt'),
		'has_archive' => true,
		'rewrite' => array('slug' => 'events'),
		'public' => true, //to be available to everyone to edit
		'labels' => array(
			'name' => 'Events',
			'add_new_item' => 'Add New Event',
			'edit_item' => 'Edit Event',
			'all_items' => 'All Events',
			'singular_name' => 'Events'
		),
		'menu_icon' => 'dashicons-calendar'
	));

	//Program Post type
	register_post_type('program', array(
		'supports' => array('title'),
		'has_archive' => true,
		'rewrite' => array('slug' => 'programs'),
		'public' => true, //to be available to everyone to edit
		'labels' => array(
			'name' => 'Programs',
			'add_new_item' => 'Add New Program',
			'edit_item' => 'Edit Program',
			'all_items' => 'All Programs',
			'singular_name' => 'Programs'
		),
		'menu_icon' => 'dashicons-awards'
	));

	//Professor Post type
	register_post_type('professor', array(
		'supports' => array('title', 'editor', 'thumbnail'),
		'public' => true, //to be available to everyone to edit
		'labels' => array(
			'name' => 'professors',
			'add_new_item' => 'Add New Professor',
			'edit_item' => 'Edit Professor',
			'all_items' => 'All Professors',
			'singular_name' => 'Professor'
		),
		'menu_icon' => 'dashicons-welcome-learn-more'
	));
	//Campuses Post type
	register_post_type('campus', array(
		'supports' => array('title', 'editor', 'thumbnail'),
		'public' => true, //to be available to everyone to edit
		'labels' => array(
			'name' => 'Campuses',
			'add_new_item' => 'Add New Campus',
			'edit_item' => 'Edit campus',
			'all_items' => 'All Campuses',
			'singular_name' => 'Campus'
		),
		'menu_icon' => 'dashicons-location-alt'
	));
	//Notes Post type
	register_post_type('note', array(
		'capability_type' => 'note', //this should be something unique
		'map_meta_cap' => true,
		'supports' => array('title', 'editor'),
		'public' => false, //to be available to everyone to edit
		'show_ui' => true, //show in the admin dashboard
		'show_in_rest' => true, //to make it show in the rest API
		'labels' => array(
			'name' => 'Notes',
			'add_new_item' => 'Add New Note',
			'edit_item' => 'Edit Note',
			'all_items' => 'All Notes',
			'singular_name' => 'Note'
		),
		'menu_icon' => 'dashicons-welcome-write-blog'
	));
	//Likes Post type
	register_post_type('like', array(
		'supports' => array('title'),
		'public' => false, //to be available to everyone to edit
		'show_ui' => true, //show in the admin dashboard
		'labels' => array(
			'name' => 'Likes',
			'add_new_item' => 'Add New Like',
			'edit_item' => 'Edit Like',
			'all_items' => 'All Likes',
			'singular_name' => 'Like'
		),
		'menu_icon' => 'dashicons-heart'
	));
}
