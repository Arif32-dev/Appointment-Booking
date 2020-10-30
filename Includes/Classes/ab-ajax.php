<?php

/* All ajax request handled here */

class Ajax_req {
    public function __construct() {
        add_action('wp_ajax_get_doctor', [$this, 'get_doctor']);
        add_action('wp_ajax_nopriv_get_doctor', [$this, 'get_doctor']);

        add_action('wp_ajax_ab_form_submit', [$this, 'ab_form_submit']);
        add_action('wp_ajax_nopriv_ab_form_submit', [$this, 'ab_form_submit']);
    }
    public function get_doctor()
    {
        if($_POST['action'] != 'get_doctor')
            return;
        $term_id = sanitize_text_field($_POST['value']);
        $term_meta_value = get_term_meta( $term_id, '_profession', true );
        $doctor_tax = get_terms(
			[
				'taxonomy' => 'doctor',
                'hide_empty' => false,
                'meta_value' => $term_meta_value,
			]
        );
        if(!is_wp_error( $doctor_tax )){
            echo json_encode($doctor_tax);
        }else{
            echo 'failed';
        }
        wp_die();
    }
    public function ab_form_submit()
    {
        if($_POST['action'] != 'ab_form_submit')
            return;

        wp_die();
    }
}
new Ajax_req;