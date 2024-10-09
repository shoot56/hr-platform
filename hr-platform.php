<?php
/*
Plugin Name: HR Platform
Description: Platform for recruiters and HR
Version: 1.1.4
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
        $option_value = get_option('dashboard_' . $document_id . '_' . $field) ?? 'not defined';

        if (preg_match('/^\d{8}$/', $option_value)) {
            $year = substr($option_value, 0, 4);
            $month = substr($option_value, 4, 2);
            $day = substr($option_value, 6, 2);

            if (checkdate($month, $day, $year)) {
                $formatted_date = date_i18n(get_option('date_format'), strtotime($option_value));
               // $formatted_date = date('d F Y', strtotime($option_value));
                $option_value = $formatted_date;
            }
        }

        $document_content = str_replace(
            '{' . $field . '}',
            $option_value,
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

    $shortcodes_list = '';

    foreach (get_field_groups() as $group_key => $group_data) {
        $group_title = $group_data['title'];
        $group_fields = $group_data['fields'];
        $shortcodes_list .= '<div class="' . $group_key . '" ><strong>' . $group_title . '</strong><ul style="padding:10px 0 15px">';
        foreach ($group_fields as $field_key => $field_label) {
            $shortcodes_list .= '<li><input readonly onclick="this.select()" type="text" value="{' . $field_key . '}" /> - ' . $field_label . '</li>';
        }
        $shortcodes_list .= '</ul></div>';
    }

    $html = '<div id="shortcodediv" class="postbox">';
    $html .= '<h2 class="hndle esig-section-title"><span>' . __('Shortcode List', 'hr-platform') . '</span></h2>';
    $html .= '<div class="inside">';
    $html .= '<div>' . $shortcodes_list . '</div>';
    $html .= '</div></div>';

    echo $html;

});

/**
 * Retrieves and organizes field groups and their corresponding fields.
 *
 * @return array An associative array of field groups, each containing a title and fields with their labels.
 */
function get_field_groups(): array
{
    if (function_exists('acf_get_field_groups')) {
        $field_groups = acf_get_field_groups();
        $fields = [];

        if (!empty($field_groups)) {
            foreach ($field_groups as $field_group) {
                $field_objects = acf_get_fields($field_group['key']);
                if (!empty($field_objects)) {
                    $fields[$field_group['key']]['title'] = $field_group['title'];
                    foreach ($field_objects as $field) {
                        $fields[$field_group['key']]['fields'][$field['name']] = $field['label'];
                    }
                }
            }
        }
    }

    return $fields ?? [];
}

/**
 * Retrieves the document type for a given document ID.
 *
 * @param int $id The ID of the document. Default value is 0.
 * @return string|null The document type if found, null otherwise.
 */
function get_document_type(int $id = 0): ?string
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'esign_documents';
    return $wpdb->get_var($wpdb->prepare("
        SELECT document_type FROM $table_name WHERE document_id = %d
    ", $id));
}

/**
 * Retrieves field groups by matching the document title with the given document ID.
 *
 * @param int $document_id The ID of the document.
 * @return array An array of field group keys that match the document title, or all field groups if no match is found.
 */
function get_field_group_by_doc_id(int $document_id): array
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'esign_documents';
    $field_groups = get_field_groups();
    $document_title = $wpdb->get_var($wpdb->prepare("
        SELECT document_title 
        FROM $table_name 
        WHERE document_id = %d
    ", $document_id));
    $groups = array();
    foreach ($field_groups as $key => $get_field_group) {
        if ($get_field_group['title'] === $document_title) {
            $groups[] = $key;
        }
    }
    // Return all groups if we don't have doc title equivalent
    if (empty($groups)) {
        foreach ($field_groups as $key => $get_field_group) {
            $groups[] = $key;
        }
    }
    return $groups;
}

/**
 * Renders a form for a given document ID.
 *
 * This method checks the document type and, if it is not of type 'esig_template',
 * it renders an ACF form specific to the document. The form includes additional
 * options and configurations based on the document ID and associated field groups.
 *
 * @param mixed $doc_id The ID of the document for which the form is to be rendered.
 * @return mixed Returns the document ID.
 */
function render_form(mixed $doc_id): mixed
{

    $doc_type = get_document_type($doc_id);
    if ($doc_type !== 'esig_template') {
        acf_form_head();
        if (function_exists('acf_add_options_sub_page')) {
            acf_add_options_sub_page(array(
                'page_title' => 'Dashboard unique options',
                'menu_title' => 'Dashboard Options',
                'parent_slug' => $doc_id,
            ));
        }
        echo '<div id="shortcodefielddiv" class="postbox">';
        echo '<div class="inside">';
        acf_form(
            array(
                'id' => 'acf-esign-form',
                'post_id' => 'dashboard_' . $doc_id,
                'new_post' => false,
                'form' => false,
                'instruction_placement' => 'field',
                'field_groups' => get_field_group_by_doc_id($doc_id)
            ,
                'return' => admin_url('admin.php?post_type=esign'), // Redirect to same page after form is submitted
                'html_before_fields' => '',
                'html_after_fields' => '',
            ));
        echo '<style>#shortcodediv{display: none}.acf-fields > .acf-field{border: none!important}</style>';
        echo '</div></div>';
    }
    return $doc_id;
}

//add_action('hr_add_acf_form', 'render_form', 10, 1);
add_action('esig_display_advanced_settings', 'render_form', 10, 1);

include_once plugin_dir_path(__FILE__) . 'includes/post-type.php';
include_once plugin_dir_path(__FILE__) . 'includes/acf-fields.php';
require_once plugin_dir_path(__FILE__) . 'includes/admin-columns.php';
require_once plugin_dir_path(__FILE__) . 'includes/offers-email-modal.php';
require_once plugin_dir_path(__FILE__) . 'includes/generate-pdf.php';
require_once plugin_dir_path(__FILE__) . 'includes/admin-post-buttons.php';