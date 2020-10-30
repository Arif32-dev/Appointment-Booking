<?php
class Disease_type {
	public function __construct() {
		add_action( 'init', [$this, 'create_typeofdisease_tax'] );
		add_action('typeofdisease_add_form_fields', [$this, 'add_typeofdisease']);
		add_action('typeofdisease_edit_form_fields', [$this, 'edit_typeofdisease']);

		add_action('created_typeofdisease', [$this, 'update_term'], 10, 2);
		add_action('edited_typeofdisease', [$this, 'update_term'], 10, 2);
	}
	public function create_typeofdisease_tax()
	{
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
	
	public function add_typeofdisease($taxonomy_slug)
	{
		?>
			<div class="occupation">
				<strong>
					<label for="occupation_tax_meta">Doctor Required</label>
				</strong>
				<select name="profession_tax_meta" id="profession_tax_meta">
					<option selected disabled hidden>Choose Professionals</option>
					<?php self::professinals_occupation() ?>
				</select>
				<br>
				<p class="description">Choose professional for this disease</p>
				<br>
				<br>
			</div>
		<?php
	}

	public static function professinals_occupation($term_value = false)
	{
		$doctor_tax = get_terms(
			[
				'taxonomy' => 'doctor',
				'hide_empty' => false,
			]
		);
		$ocuupation_array = [];
		if($doctor_tax && !is_wp_error( $doctor_tax )){

			foreach ($doctor_tax as $tax) {
				$occupation = get_term_meta($tax->term_id, '_occupation', true);
				$ocuupation_array[] = $occupation;
			}
			if(!$ocuupation_array)
				return;

			foreach (array_unique($ocuupation_array, SORT_STRING) as $occu_name) {
				if($term_value){
					?>
						<option <?php echo strtolower($occu_name) == strtolower($term_value) ? "selected" : "" ?>
							value="<?php echo esc_attr($occu_name) ?>"><?php echo esc_attr($occu_name) ?></option>
					<?php
				}else{
					echo '<option value="' . esc_attr($occu_name) .'">' . esc_html($occu_name) .'</option>';
				}
			}
		}
	}

	public function edit_typeofdisease($term_obj)
	{
		$term_id = $term_obj->term_id;
		$term_value = get_term_meta( $term_id, '_profession', true );

		?>
			<tr class="form-field">
			<th scope="row" valign="top"><label for="profession_tax_meta"><?php _e( 'Doctor Required', 'ab' ); ?></label></th>
				<td>
					<select name="profession_tax_meta" id="profession_tax_meta">
						<option selected disabled hidden>Choose Professionals</option>
						<?php $term_value == "" ? self::professinals_occupation() : self::professinals_occupation($term_value) ?>
					</select>
					<p class="description"><?php _e( 'Choose a occupation','ab' ); ?></p>
				</td>
			</tr>
		<?php
	}

	public function update_term($term_id, $tt_id)
	{
		$new_meta_value = ( isset( $_POST['profession_tax_meta'] ) ? sanitize_text_field( $_POST['profession_tax_meta'] ) : "" );
        $meta_key = '_profession';
        update_term_meta( $term_id, $meta_key, $new_meta_value );
	}
}
new Disease_type;
