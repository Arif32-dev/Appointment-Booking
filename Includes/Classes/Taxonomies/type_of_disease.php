<?php
// Register Taxonomy Type of Disease
function create_typeofdisease_tax() {

	$labels = array(
		'name'              => _x( 'Type of Diseases', 'taxonomy general name', 'ab' ),
		'singular_name'     => _x( 'Type of Disease', 'taxonomy singular name', 'ab' ),
		'search_items'      => __( 'Search Type of Diseases', 'ab' ),
		'all_items'         => __( 'All Type of Diseases', 'ab' ),
		'parent_item'       => __( 'Parent Type of Disease', 'ab' ),
		'parent_item_colon' => __( 'Parent Type of Disease:', 'ab' ),
		'edit_item'         => __( 'Edit Type of Disease', 'ab' ),
		'update_item'       => __( 'Update Type of Disease', 'ab' ),
		'add_new_item'      => __( 'Add New Type of Disease', 'ab' ),
		'new_item_name'     => __( 'New Type of Disease Name', 'ab' ),
		'menu_name'         => __( 'Type of Disease', 'ab' ),
	);
	$args = array(
		'labels' => $labels,
		'description' => __( 'Type of Disease ', 'ab' ),
		'hierarchical' => true,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'show_in_nav_menus' => true,
		'show_tagcloud' => true,
		'show_in_quick_edit' => true,
		'show_admin_column' => false,
		'show_in_rest' => true,
	);
	register_taxonomy( 'typeofdisease', array('appointmentbooking'), $args );

}
add_action( 'init', 'create_typeofdisease_tax' );