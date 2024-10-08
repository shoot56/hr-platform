<?php
function hr_offers_remove_columns($columns) {
    unset($columns['date']);
    return $columns;
}

function hr_offers_add_custom_column($columns) {
    $columns['tools'] = 'Tools';
    return $columns;
}

function hr_offers_custom_column_content($column, $post_id) {
    if ($column === 'tools') {
         echo '<a href="' . plugin_dir_url(__FILE__) . 'generate-pdf.php?post_id=' . $post_id . '" target="_blank" class="hr-offer-button button button-primary">Show PDF <span class="dashicons dashicons-media-document"></span></a> ';
        echo '<button class="hr-offer-button button send-email-button" data-id="' . $post_id . '">Send email <span class="dashicons dashicons-email"></span></button>';
    }
}

add_filter('manage_hr-offers_posts_columns', 'hr_offers_remove_columns');
add_filter('manage_hr-offers_posts_columns', 'hr_offers_add_custom_column');
add_action('manage_hr-offers_posts_custom_column', 'hr_offers_custom_column_content', 10, 2);
