<?php
 if(!defined('ABSPATH')){
     die('you cant access this plugin directly');
 }
 
class Appointment_cpt_column
{
    public function __construct()
    {
        add_filter('manage_appointmentbooking_posts_columns',  [$this, 'cpt_columns']);
        add_action('manage_appointmentbooking_posts_custom_column', [$this, 'add_data_to_columns'], 10, 2);
        add_filter('manage_edit-appointmentbooking_sortable_columns',  [$this, 'sort_columns']); 
    }
    public function cpt_columns($columns)
    {
        $date = $columns['date'];
        unset( $columns['date']  );
        $columns['title'] = 'Patient Name';
        $columns['p_phone'] = 'Patient Phone';
        $columns['p_email'] = 'Patient Email';
        $columns['p_address'] = 'Patient Address';
        $columns['p_date'] = 'Patient Date';
        $columns['date']  = $date;
        return $columns;
    }
    public function add_data_to_columns($column, $post_id)
    {
        $phone_value = get_post_meta($post_id, '_phone_value_key', true);
        $email_value = get_post_meta($post_id, '_email_value_key', true);
        $address_value = get_post_meta($post_id, '_address_value_key', true);
        $date_value = get_post_meta($post_id, '_date_value_key', true);
        
         switch ($column) {
            case 'p_phone':
                echo $phone_value;
                break;
            case 'p_email':
                echo $email_value;
                break;
            case 'p_address':
                echo $address_value;
                break;
            case 'p_date':
                echo $date_value;
                break;
        }
    }
    public function sort_columns($columns)
    {
        $columns['p_phone'] = 'Patient Phone';
        $columns['p_email'] = 'Patient Email';
        $columns['p_date'] = 'Patient Date';
        return $columns;
    }
}
new Appointment_cpt_column;