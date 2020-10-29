<?php
// Register Custom Post Type appointment-booking
function create_appointmentbooking_cpt() {

	$labels = array(
		'name' => _x( 'Dr. Appointment Booking', 'Post Type General Name', 'ab' ),
		'singular_name' => _x( 'appointment-booking', 'Post Type Singular Name', 'ab' ),
		'menu_name' => _x( 'Dr. Appointment Booking', 'Admin Menu text', 'ab' ),
		'name_admin_bar' => _x( 'appointment-booking', 'Add New on Toolbar', 'ab' ),
		'archives' => __( 'appointment-booking Archives', 'ab' ),
		'attributes' => __( 'appointment-booking Attributes', 'ab' ),
		'parent_item_colon' => __( 'Parent appointment-booking:', 'ab' ),
		'all_items' => __( 'All Dr. Appointment Booking', 'ab' ),
		'add_new_item' => __( 'Add New appointment-booking', 'ab' ),
		'add_new' => __( 'Add New', 'ab' ),
		'new_item' => __( 'New appointment-booking', 'ab' ),
		'edit_item' => __( 'Edit appointment-booking', 'ab' ),
		'update_item' => __( 'Update appointment-booking', 'ab' ),
		'view_item' => __( 'View appointment-booking', 'ab' ),
		'view_items' => __( 'View Dr. Appointment Booking', 'ab' ),
		'search_items' => __( 'Search appointment-booking', 'ab' ),
		'not_found' => __( 'Not found', 'ab' ),
		'not_found_in_trash' => __( 'Not found in Trash', 'ab' ),
		'featured_image' => __( 'Featured Image', 'ab' ),
		'set_featured_image' => __( 'Set featured image', 'ab' ),
		'remove_featured_image' => __( 'Remove featured image', 'ab' ),
		'use_featured_image' => __( 'Use as featured image', 'ab' ),
		'insert_into_item' => __( 'Insert into appointment-booking', 'ab' ),
		'uploaded_to_this_item' => __( 'Uploaded to this appointment-booking', 'ab' ),
		'items_list' => __( 'Dr. Appointment Booking list', 'ab' ),
		'items_list_navigation' => __( 'Dr. Appointment Booking list navigation', 'ab' ),
		'filter_items_list' => __( 'Filter Dr. Appointment Booking list', 'ab' ),
	);
	$args = array(
		'label' => __( 'appointment-booking', 'ab' ),
		'description' => __( 'Appointment Booking CPT', 'ab' ),
		'labels' => $labels,
		'menu_icon' => 'dashicons-calendar',
		'supports' => array('title', 'editor', 'thumbnail'),
		'taxonomies' => array('doctor', 'type_of_disease'),
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'menu_position' => 5,
		'show_in_admin_bar' => true,
		'show_in_nav_menus' => true,
		'can_export' => true,
		'has_archive' => true,
		'hierarchical' => true,
		'exclude_from_search' => false,
		'show_in_rest' => true,
		'publicly_queryable' => true,
		'capability_type' => 'post',
	);
	register_post_type( 'appointmentbooking', $args );

}
add_action( 'init', 'create_appointmentbooking_cpt', 0 );