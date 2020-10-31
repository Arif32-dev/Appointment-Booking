<?php
 if(!defined('ABSPATH')){
     die('you cant access this plugin directly');
 }

class Meta_Fields {
    public function __construct() {
        add_action('add_meta_boxes_appointmentbooking', [$this, 'register_meta_boxes']);
        add_action('save_post_appointmentbooking', [$this, 'save_meta_boxes'], 10, 2);
    }
    public function register_meta_boxes($post)
    {
        self::meta_func($post);
    }
    public static function meta_func($post)
    {
        add_meta_box(
            'appointment_meta',
            'Appointment Details', 
            [get_called_class(), 'appoint_meta_content'],
            ['appointmentbooking'],
            'normal',
            'high'
        );
    }
    public static function appoint_meta_content($post)
    {
        wp_nonce_field('ab_meta_nonce_action', 'ab_meta_nonce');
        self::patient_phone_field($post);
        self::patient_email_field($post);
        self::patient_address_field($post);
        self::patient_date_field($post);
        
    }
    public static function patient_phone_field($post)
    {
        $phone_value = get_post_meta($post->ID, '_phone_value_key', true);
        echo '
            <div>
                <strong>
                    <label for="p_phone">Patient Phone :</label>
                </strong>
                <br/>
                <input type="text" value="' . esc_attr(sanitize_text_field($phone_value)) .'" name="p_phone" id="p_phone">
            </div>
            <br/>
        ';
    }
    public static function patient_email_field($post)
    {
        $email_value = get_post_meta($post->ID, '_email_value_key', true);

        echo '
            <div>
                <strong>
                    <label for="p_email">Patient Email :</label>
                </strong>
                <br/>
                <input type="email" value="' . esc_attr(sanitize_text_field($email_value)) .'" name="p_email" id="p_email">
            </div>
            <br/>
        ';
    }
    public static function patient_address_field($post)
    {

        $address_value = get_post_meta($post->ID, '_address_value_key', true);

        echo '
            <div>
                <strong>
                    <label for="p_address">Patient Address :</label>
                </strong>
                <br/>
                <textarea rows="3" cols="30" name="p_address" id="p_address">' . esc_attr(sanitize_text_field($address_value)) .'</textarea>
            </div>
            <br/>
        ';
    }
    public static function patient_date_field($post)
    {
        $date_value = get_post_meta($post->ID, '_date_value_key', true);

        echo '
            <div>
                <strong>
                    <label for="p_date">Patient Date :</label>
                </strong>
                <br/>
                    <input type="date" value="' . esc_attr(sanitize_text_field($date_value)) .'" name="p_date" id="p_date">
            </div>
            <br/>
        ';
    }
    public function save_meta_boxes(int $post_id, object $post_object)
    {
        /* Check nonce for security */
         if ( !isset( $_POST['ab_meta_nonce'] ) || !wp_verify_nonce( $_POST['ab_meta_nonce'],  'ab_meta_nonce_action') )
            return $post_id;

        /* Get the post type object. */
            $post_type = get_post_type_object( $post_object->post_type );
          if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
                return $post_id;

        self::update_meta_loop($post_id);
    }
    public static function update_meta_loop(int $post_id)
    {
        $meta_data = [
            [
                'meta_key' => '_phone_value_key', 
                'meta_value' =>  'p_phone'
            ],
            [
                'meta_key' => '_email_value_key', 
                'meta_value' =>  'p_email'
            ],
            [
                'meta_key' => '_address_value_key', 
                'meta_value' =>  'p_address'
            ],
            [
                'meta_key' => '_date_value_key', 
                'meta_value' =>  'p_date'
            ],
        ];
        foreach ($meta_data as $meta) {
            self::update_meta_fields($post_id, $meta);
        }
    }
    public static function update_meta_fields(int $post_id, array $meta)
    {
         /* Get the posted data and sanitize it for use as an HTML class. */
        $new_meta_value = ( isset( $_POST[$meta['meta_value']] ) ? sanitize_text_field( $_POST[$meta['meta_value']] ) : "" );

        /* Get the meta key. */
        $meta_key = $meta['meta_key'];

         /* Get the meta value of the custom field key. */
        $meta_value = get_post_meta( $post_id, $meta_key, true );

        /* If a new meta value was added and there was no previous value, add it. */
        if ( $new_meta_value && "" == $meta_value )
            add_post_meta( $post_id, $meta_key, $new_meta_value, true );

        /* If the new meta value does not match the old value, update it. */
        elseif ( $new_meta_value && $new_meta_value != $meta_value )
            update_post_meta( $post_id, $meta_key, $new_meta_value );

        /* If there is no new meta value but an old value exists, delete it. */
        elseif ( "" == $new_meta_value && $meta_value )
            delete_post_meta( $post_id, $meta_key, $meta_value );
    }
}
new Meta_Fields;
