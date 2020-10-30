<?php


class AB_files
{
    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scirpts_styles']);
    }
    public function enqueue_scirpts_styles()
    {
        wp_enqueue_script('jquery');
        wp_enqueue_script('oe_front_scirpt', BASE_URL . 'Includes/Scripts/front-end-form.js', 'jquery', '1.0.0', true);
        wp_localize_script('oe_front_scirpt', 'file_url', [
            'admin_ajax' => admin_url( 'admin-ajax.php' ),
        ]);
    }
}

new AB_files;