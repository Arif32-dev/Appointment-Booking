<?php
// Register Taxonomy doctor

class Doctor_Tax{
	public function __construct() {
		add_action('init', [$this, 'create_doctor_tax']);
		add_action('doctor_add_form_fields', [$this, 'add_occupation']);
		add_action('doctor_edit_form_fields', [$this, 'edit_occupation']);
		add_action('created_doctor', [$this, 'update_term'], 10, 2);
		add_action('edited_doctor', [$this, 'update_term'], 10, 2);
	}
	public function create_doctor_tax()
	{
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
	public function add_occupation($taxonomy_slug)
	{
		echo '
			<div class="occupation">
				<strong>
					<label for="occupation_tax_meta">Occupation</label>
				</strong>
				<input type="text" name="occupation_tax_meta" id="occupation_tax_meta" value="">
				<p class="description">This is a occupation field . It will be used to professional occupation for appointment</p>
				<br>
				<br>
			</div>
		';
	}
	public function edit_occupation($term_obj)
	{
		$term_id = $term_obj->term_id;
		$term_value = get_term_meta( $term_id, '_occupation', true );
		?>
			<tr class="form-field">
			<th scope="row" valign="top"><label for="occupation_tax_meta"><?php _e( 'Occupation', 'ab' ); ?></label></th>
				<td>
					<input type="text" name="occupation_tax_meta" id="occupation_tax_meta" value="<?php echo esc_attr($term_value) ?>">
					<p class="description"><?php _e( 'Enter a occupation','ab' ); ?></p>
				</td>
			</tr>
		<?php
	}

	public function update_term($term_id, $tt_id)
	{
		$new_meta_value = ( isset( $_POST['occupation_tax_meta'] ) ? sanitize_text_field( $_POST['occupation_tax_meta'] ) : "" );
        $meta_key = '_occupation';
        update_term_meta( $term_id, $meta_key, $new_meta_value );
	}
}
new Doctor_Tax;