<?php

if(!defined('ABSPATH')){
     die('you cant access this plugin directly');
 }

 class Search_field {
     public function __construct() {
        add_action('init', [$this, 'search_form']);
     }
      
     public function search_form(){
       add_shortcode( 'search_form_sortcode', [get_called_class(), 'search_form_create'] );
    }
    public static function search_form_create()
    {
        ?>
            <form action="" method="POST" id="ab_search_form">
                <?php wp_nonce_field('ab_search_nonce_action', 'ab_search_nonce'); ?>
                <strong>
                    <label for="s_appointment">Search Appointment: </label>
                </strong>
                <br>
                <input type="text" required name="s_appointment" id="s_appointment" placeholder="Name or Email or Phone or Doctor or Date or Disease">
                <br>
                <input type="submit" value="Search Appointment" name="submit_appointment" id="submit_appointment">
            </form>
            <div id="ab_search_result">
            </div>
        <?php
    }
 }
 new Search_field;