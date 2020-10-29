<?php
// Register Taxonomy doctor
function create_doctor_tax() {

	$labels = array(
		'name'              => _x( 'Doctors', 'taxonomy general name', 'ab' ),
		'singular_name'     => _x( 'Doctor', 'taxonomy singular name', 'ab' ),
		'search_items'      => __( 'Search Doctors', 'ab' ),
		'all_items'         => __( 'All Doctors', 'ab' ),
		'parent_item'       => __( 'Parent doctor', 'ab' ),
		'parent_item_colon' => __( 'Parent doctor:', 'ab' ),
		'edit_item'         => __( 'Edit doctor', 'ab' ),
		'update_item'       => __( 'Update doctor', 'ab' ),
		'add_new_item'      => __( 'Add New doctor', 'ab' ),
		'new_item_name'     => __( 'New doctor Name', 'ab' ),
		'menu_name'         => __( 'Doctor', 'ab' ),
	);
	$args = array(
		'labels' => $labels,
		'description' => __( 'Doctor taxonomy', 'ab' ),
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
	register_taxonomy( 'doctor', array('appointmentbooking'), $args );

}
add_action( 'init', 'create_doctor_tax' );