<?php

/**
 *  Dr. Appointment Booking
 *
 * @package           PluginPackage
 * @author            Arifur Rahman Arif
 * @copyright         2019 Your Name or Company Name
 * @license           GPL-2.0-or-later
 *
 * @Dr. Appointment Plugin
 * Plugin Name:       Dr. Appointment Booking
 * Plugin URI:        https://example.com/plugin-name
 * Description:       Dr. Assignment booking plugin 
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Arifur Rahman Arif
 * Author URI:        https://www.facebook.com/profile.php?id=100023045749987
 * Text Domain:      ab
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

/**
 * @plugin start here
 */

 /**
 * defining all the constant to use across the plugin
 */

 /* if accessed directly exit from plugin */
 if(!defined('ABSPATH')){
     die('you cant access this plugin directly');
 }

if(!defined('BASE_PATH')){
    define('BASE_PATH', plugin_dir_path(__FILE__));
}
if(!defined('BASE_URL')){
    define('BASE_URL', plugin_dir_url(__FILE__));
}

class AB_Initializer
{
    public function __construct()
    {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
        if ($this->version_check() == 'version_low') {
            return;
        }
        $this->register_active_deactive_hooks();
        $this->plugins_check();
    }

    public function version_check()
    {
        if (version_compare(PHP_VERSION, '7.0.0') < 0) {
            if (is_plugin_active(plugin_basename(__FILE__))) {
                deactivate_plugins(plugin_basename(__FILE__));
                add_action('admin_notices', [$this, 'show_notice']);
            }
            return 'version_low';
        }
    }

    public function show_notice()
    {
        echo '<div class="notice notice-error is-dismissible"><h3><strong>Plugin </strong></h3><p> cannot be activated - requires at least  PHP 7.0.0 Plugin automatically deactivated.</p></div>';
        return;
    }

    public function plugins_check()
    {
        if (is_plugin_active(plugin_basename(__FILE__))) {
            $this->including_class();
        }
    }

    /**
     * registering activation and deactivation Hooks
     * @return void
     */
    public function register_active_deactive_hooks()
    {
        register_activation_hook(__FILE__, function () {
            flush_rewrite_rules();
        });
        register_activation_hook(__FILE__, function () {
            flush_rewrite_rules();
        });
    }

    /**
     * @requiring all the classes once
     * @return void
     */
    public function including_class()
    {
        require_once BASE_PATH . 'Includes/Classes/cpt.php';
        require_once BASE_PATH . 'Includes/Classes/Taxonomies/doctor.php';
        require_once BASE_PATH . 'Includes/Classes/Taxonomies/type_of_disease.php';
        require_once BASE_PATH . 'Includes/Classes/Meta/meta-field.php';
        require_once BASE_PATH . 'Includes/Admin/cpt_column_field.php';
    }
}
new AB_Initializer;