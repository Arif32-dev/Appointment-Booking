<?php

/* All ajax request handled here */

class Ajax_req {
    public function __construct() {
        add_action('wp_ajax_get_doctor', [$this, 'get_doctor']);
        add_action('wp_ajax_nopriv_get_doctor', [$this, 'get_doctor']);

        add_action('wp_ajax_ab_form_submit', [$this, 'ab_form_submit']);
        add_action('wp_ajax_nopriv_ab_form_submit', [$this, 'ab_form_submit']);

        add_action('wp_ajax_ab_form_search', [$this, 'ab_form_search']);
        add_action('wp_ajax_nopriv_ab_form_search', [$this, 'ab_form_search']);
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
        if($_POST['action'] != 'ab_form_submit'){
            wp_die();
        }
        parse_str($_POST['form_data'], $parsed_data);
        /* Field validation */
        if(
            (!isset($parsed_data['p_name']) && $parsed_data['p_name'] == "" ) ||
            (!isset($parsed_data['p_phone']) && $parsed_data['p_phone'] == "" ) ||
            (!isset($parsed_data['p_email']) && $parsed_data['p_email'] == "" ) ||
            (!isset($parsed_data['p_address']) && $parsed_data['p_address'] == "" ) ||
            (!isset($parsed_data['p_disease']) && $parsed_data['p_disease'] == "" ) ||
            (!isset($parsed_data['p_doctor']) && $parsed_data['p_doctor'] == "" ) ||
            (!isset($parsed_data['p_date']) && $parsed_data['p_date'] == "" )
        ){
            echo 'empty_field';
            wp_die();
        }
            $sanitzed_parsed_data = [
                'p_name' => sanitize_text_field($parsed_data['p_name']),
                'p_phone' => sanitize_text_field($parsed_data['p_phone']),
                'p_email' => sanitize_email($parsed_data['p_email']),
                'p_address' => sanitize_text_field($parsed_data['p_address']),
                'p_disease' => sanitize_text_field($parsed_data['p_disease']),
                'p_doctor' => sanitize_text_field($parsed_data['p_doctor']),
                'p_date' => sanitize_text_field($parsed_data['p_date']),
            ];
            self::create_post($sanitzed_parsed_data);
    }
    public static function create_post(array $sanitzed_parsed_data)
    {
       $arg = [
            'post_title' => $sanitzed_parsed_data['p_name'],
            'post_status' => 'publish',
            'post_type'=> 'appointmentbooking',
            'tax_input' => [
                'typeofdisease' => [
                    $sanitzed_parsed_data['p_disease']
                ],
                'doctor' => [
                    $sanitzed_parsed_data['p_doctor']
                ]
            ],
            'meta_input' => [
                '_phone_value_key' => $sanitzed_parsed_data['p_phone'],
                '_email_value_key' => $sanitzed_parsed_data['p_email'],
                '_address_value_key' => $sanitzed_parsed_data['p_address'],
                '_date_value_key' => $sanitzed_parsed_data['p_date'],
            ]
       ];

       if(is_int(wp_insert_post($arg))){
           echo 'post_created';
       }else{
           echo 'post_failed';
       }
        wp_die();
    }

     public function ab_form_search()
     {
        if($_POST['action'] != 'ab_form_search'){
            wp_die();
        }
        print_r($_POST);
        wp_die();
    }
}
new Ajax_req;