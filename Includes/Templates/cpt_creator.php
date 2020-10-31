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
        ?>
            <form action="" id="ab_plugin">
                <?php wp_nonce_field('ab_cpt_create_action', 'ab_cpt_nonce'); ?>
                 <div>
                    <strong>
                        <label for="p_name">Patient Name :</label>
                    </strong>
                    <br/>
                    <input required type="text" value="" name="p_name" id="p_name" placeholder="Enter your name" >
                </div>
                <br/>

                <div>
                    <strong>
                        <label for="p_phone">Patient Phone :</label>
                    </strong>
                    <br/>
                    <input required type="text" value="" name="p_phone" id="p_phone" placeholder="Enter your phone" >
                </div>
                <br/>
                
                <div>
                    <strong>
                        <label for="p_email">Patient Email :</label>
                    </strong>
                    <br/>
                    <input required type="email" value="" name="p_email" id="p_email" placeholder="Enter your email">
                </div>
                <br/>

                <div>
                    <strong>
                        <label for="p_address">Patient Address :</label>
                    </strong>
                    <br/>
                    <textarea required rows="3" cols="30" name="p_address" id="p_address" placeholder="Enter your address"></textarea>
                </div>
                <br/>

                <div>
                    <strong>
                        <label for="p_disease">Type Of Disease :</label>
                    </strong>
                    <br/>
                    <select required name="p_disease" id="p_disease">
					<option selected disabled hidden>Choose Disease</option>
                        <?php self::disease_types() ?>
                    </select>
                </div>
                <br/>

                  <div id="doctor_field">
                  </div>
                <br/>

                <div>
                    <strong>
                        <label for="p_date">Appointment Date :</label>
                    </strong>
                    <br/>
                    <input required type="date" value="" name="p_date" id="p_date">
                </div>
                <br/>

                <input type="submit" value="Get Appointment" name="p_submit" id="p_submit">
            </form>
        <?php
    }
    public static function disease_types()
    {
        $disease_tax = get_terms(
			[
				'taxonomy' => 'typeofdisease',
				'hide_empty' => false,
			]
        );
        if($disease_tax && !is_wp_error( $disease_tax )){
            foreach ($disease_tax as $disease) {
                
                ?>
                    <option value="<?php echo esc_attr($disease->term_id) ?>"><?php echo esc_attr($disease->name) ?></option>
                <?php
			}
        }
    }
 }
 new CPT_Creator;