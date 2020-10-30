<?php

 if(!defined('ABSPATH')){
     die('you cant access this plugin directly');
 }

 class CPT_Creator {
    public function __construct() {
        add_action('init', [$this, 'front_end_form']);
    }
    public function front_end_form()
    {
       add_shortcode( 'assignment_create', [get_called_class(), 'cpt_create'] );
    }
    public static function cpt_create($attr)
    {
        echo '
            <form action="" >
                 <div>
                    <strong>
                        <label for="p_name">Patient Name :</label>
                    </strong>
                    <br/>
                    <input type="text" value="" name="p_name" id="p_name" placeholder="Enter your name" >
                </div>
                <br/>

                <div>
                    <strong>
                        <label for="p_phone">Patient Phone :</label>
                    </strong>
                    <br/>
                    <input type="text" value="" name="p_phone" id="p_phone" placeholder="Enter your phone" >
                </div>
                <br/>
                
                <div>
                    <strong>
                        <label for="p_email">Patient Email :</label>
                    </strong>
                    <br/>
                    <input type="email" value="" name="p_email" id="p_email">
                </div>
                <br/>

                <div>
                    <strong>
                        <label for="p_address">Patient Address :</label>
                    </strong>
                    <br/>
                    <textarea rows="3" cols="30" name="p_address" id="p_address"></textarea>
                </div>
                <br/>

            </form>
        ';
    }
 }
 new CPT_Creator;