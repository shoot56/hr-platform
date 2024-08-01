<?php 
/*
Plugin Name: HR Platform
Description: Platform for recruiters and HR
Version: 1.0
Author: Shutko Dmytro
Author URI: http://procoders.tech

GitHub Plugin URI: shoot56/hr-platform
Primary Branch: main
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}


function hr_platform_check_acf_plugin() {
    if (!is_plugin_active('advanced-custom-fields/acf.php')) {
        add_action('admin_notices', 'hr_platform_acf_notice');
    }
}

function hr_platform_acf_notice() {
    ?>
    <div class="notice notice-warning is-dismissible">
        <p><?php _e('HR Platform requires the Advanced Custom Fields plugin. Please install and activate ACF.', 'hr-platform'); ?></p>
        <p><a href="<?php echo esc_url(admin_url('plugin-install.php?s=advanced+custom+fields&tab=search&type=term')); ?>" class="button button-primary"><?php _e('Install ACF', 'hr-platform'); ?></a></p>
    </div>
    <?php
}
add_action('admin_init', 'hr_platform_check_acf_plugin');

include_once plugin_dir_path(__FILE__) . 'includes/post-type.php';
include_once plugin_dir_path(__FILE__) . 'includes/acf-fields.php';
require_once plugin_dir_path(__FILE__) . 'includes/admin-columns.php';