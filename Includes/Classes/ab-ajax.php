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
        
        if ( !isset( $parsed_data['ab_cpt_nonce'] ) || !wp_verify_nonce( $parsed_data['ab_cpt_nonce'],  'ab_cpt_create_action') ){
            wp_die();
        }
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
        parse_str($_POST['form_data'], $parsed_data);

        if ( !isset( $parsed_data['ab_search_nonce'] ) || !wp_verify_nonce( $parsed_data['ab_search_nonce'],  'ab_search_nonce_action') ){
            wp_die();
        }
        $sanitzed_parsed_data = [
                's_appointment' => sanitize_text_field($parsed_data['s_appointment']),
            ];
        self::search_result($sanitzed_parsed_data);
    }

    public static function search_result(array $sanitzed_parsed_data)
    {
        $query = null;
         $terms = get_terms([
            'taxonomy' => ['doctor', 'typeofdisease'],
            'search' =>  $sanitzed_parsed_data['s_appointment']
        ]);

        $tax_array = [];

        if($terms){
            foreach ($terms as $term) {
                $tax_array[] = $term->term_id;
            }
        }
        if(self::result_by_tax_query($tax_array)->posts){
            $query = self::result_by_tax_query($tax_array)->posts;
        }
        elseif (self::result_by_meta_query($sanitzed_parsed_data)->posts) {
           $query = self::result_by_meta_query($sanitzed_parsed_data)->posts;
        }
        elseif (self::result_by_post_name($sanitzed_parsed_data)->posts) {
            $query = self::result_by_post_name($sanitzed_parsed_data)->posts; 
        }
        if($query){
            self::outputHtml($query);
        }else{
            echo 'wrong';
        }
        wp_die();
    }
    public static function result_by_tax_query(array $tax_array)
    {
        if($tax_array){
            $result_by_tax_query = new WP_Query(
                 [
                     'post_type' => 'appointmentbooking',
                     'posts_per_page' => -1,
                     'tax_query' => [
                         'relation' => 'OR',
                         [
                             'taxonomy' => 'doctor',
                             'field' => 'term_id', 
                             'terms' => $tax_array
                         ],
                         [
                             'taxonomy' => 'typeofdisease',
                             'field' => 'term_id', 
                             'terms' => $tax_array
                         ]
                     ]
                 ]
             );
            return $result_by_tax_query;
        }
    }
    public static function result_by_meta_query(array $sanitzed_parsed_data)
    {
          $result_by_meta_query = new WP_Query(
                [
                    'post_type' => 'appointmentbooking',
                    'posts_per_page' => -1,
                    'meta_value' => $sanitzed_parsed_data['s_appointment'],
                    'meta_compare' => 'LIKE'
                ]
            );
            return $result_by_meta_query;
    }
    public static function result_by_post_name(array $sanitzed_parsed_data)
    {
        $result_by_post_name = new WP_Query(
            [
                'post_type' => 'appointmentbooking',
                'posts_per_page' => -1,
                's' => $sanitzed_parsed_data['s_appointment'],
            ]
        );
        return $result_by_post_name;
    }
    public static function outputHtml(array $query)
    {
       if($query){
           foreach ($query as $res) {
               ?>
                    <div class="p_details">
                            <div>
                                <span>Patient Name : <?php echo sanitize_text_field($res->post_title) ?></span>
                            </div>
                            <div>
                                <span>Patient Phone : <?php echo sanitize_text_field(get_post_meta( $res->ID, '_phone_value_key', true )) ?></span>
                            </div>
                            <div>
                                <span>Patient Email : <?php echo sanitize_text_field(get_post_meta( $res->ID, '_email_value_key', true )) ?></span>
                            </div>
                            <div>
                                <span>Patient Address : <?php echo sanitize_text_field(get_post_meta( $res->ID, '_address_value_key', true )) ?></span>
                            </div>
                            <div>
                                <span>Patient Disease : <?php echo sanitize_text_field(self::patient_taxonomies($res->ID)[1]) ?></span>
                            </div>
                            <div>
                                <span>Required Doctor : <?php echo sanitize_text_field(self::patient_taxonomies($res->ID)[0]) ?></span>
                            </div>
                            <div>
                                <span>Appointment Date : <input type="date" value="<?php echo sanitize_text_field(get_post_meta( $res->ID, '_date_value_key', true )) ?>"></span>
                            </div>
                    </div>
                    <br>
                    <br>
               <?php
           }
       }
    }
    public static function patient_taxonomies(int $post_id)
    {
        $terms = wp_get_post_terms($post_id, 
            [
                'doctor',
                'typeofdisease'
            ],
            [
                'fields' => 'names'
            ]
        );
        return $terms;
    }
    
}
new Ajax_req;