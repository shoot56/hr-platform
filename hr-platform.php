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

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}


function hr_platform_check_acf_plugin()
{
    if (!is_plugin_active('advanced-custom-fields/acf.php')) {
        add_action('admin_notices', 'hr_platform_acf_notice');
    }
}

function hr_platform_acf_notice()
{
    ?>
    <div class="notice notice-warning is-dismissible">
        <p><?php _e('HR Platform requires the Advanced Custom Fields plugin. Please install and activate ACF.', 'hr-platform'); ?></p>
        <p>
            <a href="<?php echo esc_url(admin_url('plugin-install.php?s=advanced+custom+fields&tab=search&type=term')); ?>"
               class="button button-primary"><?php _e('Install ACF', 'hr-platform'); ?></a></p>
    </div>
    <?php
}

add_action('admin_init', 'hr_platform_check_acf_plugin');

function hr_offers_enqueue_admin_scripts()
{
    wp_enqueue_script('jquery');
    wp_enqueue_script('hr-offers-admin-js', plugin_dir_url(__FILE__) . 'assets/js/hr-offers-admin.js', array('jquery'), null, true);
    wp_enqueue_style('hr-offers-admin-css', plugin_dir_url(__FILE__) . 'assets/css/hr-offers-admin.css');
}

add_action('admin_enqueue_scripts', 'hr_offers_enqueue_admin_scripts');

add_action('save_post', 'save_pdf_on_post_save', 10, 3);

function save_pdf_on_post_save($post_id, $post, $update)
{
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) {
        return;
    }
    if ($post->post_type != 'hr-offers' || $post_id == 0) {
        return;
    }
    generate_pdf_for_offer($post_id, true);
}
/**
 * Rewrite rule for HR contract link.
 *
 * @return void
 */
function hr_contract_link_rewrite_rule(): void
{
    add_rewrite_rule('^contract/([^/]*)/?', 'index.php?cid=$matches[1]', 'top');
    flush_rewrite_rules();
}

add_action('init', 'hr_contract_link_rewrite_rule', 10, 0);

/**
 * Filters the query variables for the HR contracts.
 *
 * @param array $vars An array of query variables.
 * @return array The filtered array of query variables.
 */
function hr_contract_query_vars_filter(array $vars): array
{
    $vars[] .= 'cid';
    return $vars;
}

add_filter('query_vars', 'hr_contract_query_vars_filter');

/**
 * Redirects the HR contract template page.
 *
 * @return void
 * @global WP_Query $wp_query The main query object.
 */
function hr_contract_template_redirect(): void{
    global $wp_query;

    $contractId = $wp_query->get('cid');

    if ($contractId){
        require plugin_dir_path(__FILE__) . 'pages/contract-template.php';
        die;
    }
}

add_action('template_redirect', 'hr_contract_template_redirect');

include_once plugin_dir_path(__FILE__) . 'includes/post-type.php';
include_once plugin_dir_path(__FILE__) . 'includes/acf-fields.php';
require_once plugin_dir_path(__FILE__) . 'includes/admin-columns.php';
require_once plugin_dir_path(__FILE__) . 'includes/offers-email-modal.php';
require_once plugin_dir_path(__FILE__) . 'includes/generate-pdf.php';
require_once plugin_dir_path(__FILE__) . 'includes/admin-post-buttons.php';
