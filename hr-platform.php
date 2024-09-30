<?php
/*
Plugin Name: HR Platform
Description: Platform for recruiters and HR
Version: 1.0.4
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
    $acf_free_active = is_plugin_active('advanced-custom-fields/acf.php');
    $acf_pro_active = is_plugin_active('advanced-custom-fields-pro/acf.php');

    if (!$acf_free_active && !$acf_pro_active) {
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


add_filter('esignature_content', function ($document_content, $document_id) {

    preg_match_all('/\{([^}]*)\}/', $document_content, $matches);
    $fields = $matches[1];
    foreach ($fields as $field) {
        $document_content = str_replace(
            '{' . $field . '}',
            get_option('dashboard_' . $document_id . '_' . $field) ?? 'not defined',
            $document_content
        );
    }

    return $document_content;
}, 10, 2);


function hr_filter_document_content($document_id, $content, $fields)
{
    foreach ($fields as $key => $field) {
        update_field($key, esc_html($field), 'dashboard_' . $document_id);
    }
    return $content;
}

add_action('esig_document_advanced_settings', function ($document_id, $data) {
    if (is_plugin_active('hr-platform/hr-platform.php') && isset($data['acf'])) {
        $data['document_content'] = hr_filter_document_content($document_id, $data['document_content'], $data['acf']);
    }
}, 10, 2);

add_filter('esig-document-notification-content', function () {

    if (function_exists('acf_get_field_groups')) {
        $field_groups = acf_get_field_groups();
        $fields = [];
        if (!empty($field_groups)) {
            foreach ($field_groups as $field_group) {
                if ($field_group['key'] === 'group_hr_contract') {
                    $field_objects = acf_get_fields($field_group['key']);
                    if (!empty($field_objects)) {
                        foreach ($field_objects as $field) {
                            $fields[$field['name']] = $field['label'];
                        }
                    }
                    break;
                }
            }
        }
    }

    $shortcodes_list = '';
    if (!empty($fields)) {
        foreach ($fields as $field_key => $field_label) {
            $shortcodes_list .= '<li><input onclick="this.select()" type="text" value="{' . $field_key . '}" /> - ' . $field_label . '</li>';
        }
    }

    $html = '<div id="shortcodediv" class="postbox">';
    $html .= '<h3 class="hndle esig-section-title"><span>' . __('Shortcode List', 'hr-platform') . '</span></h3>';
    $html .= '<div class="inside">';
    $html .= '<div><ul>' . $shortcodes_list . '</ul></div>';
    $html .= '</div></div>';

    echo $html;

});


add_filter('esig_owner_change_option', function ($doc_id) {

    acf_form_head();
    if (function_exists('acf_add_options_sub_page')) {
        acf_add_options_sub_page(array(
            'page_title' => 'Dashboard unique options',
            'menu_title' => 'Dashboard Options',
            'parent_slug' => $doc_id,
        ));
    }
    echo '<div id="shortcodefielddiv" class="postbox">';
    echo  '<div class="inside">';
    acf_form(
        array(
            'id' => 'acf-esign-form',
            'post_id' => 'dashboard_' . $doc_id,
            'new_post' => false,
            'form' => false,
            'instruction_placement' => 'field',
            'field_groups' => array('group_hr_contract'),
            'return' => admin_url('admin.php?post_type=esign'), // Redirect to same page after form is submitted
            'html_before_fields' => '',
            'html_after_fields' => '',
        ));
    echo '<style>#shortcodediv{display: none}</style>';
    echo '</div></div>';
});


include_once plugin_dir_path(__FILE__) . 'includes/post-type.php';
include_once plugin_dir_path(__FILE__) . 'includes/acf-fields.php';
require_once plugin_dir_path(__FILE__) . 'includes/admin-columns.php';
require_once plugin_dir_path(__FILE__) . 'includes/offers-email-modal.php';
require_once plugin_dir_path(__FILE__) . 'includes/generate-pdf.php';
require_once plugin_dir_path(__FILE__) . 'includes/admin-post-buttons.php';