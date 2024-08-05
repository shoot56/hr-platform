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
        // echo '<button class="button button-small" data-id="' . $post_id . '">Print PDF</button> ';
         echo '<a href="' . plugin_dir_url(__FILE__) . 'generate-pdf.php?post_id=' . $post_id . '" target="_blank" class="button button-small">Show PDF</a> ';
        echo '<button class="button button-small" data-id="' . $post_id . '">Click me</button>';
    }
}

add_filter('manage_hr-offers_posts_columns', 'hr_offers_remove_columns');
add_filter('manage_hr-offers_posts_columns', 'hr_offers_add_custom_column');
add_action('manage_hr-offers_posts_custom_column', 'hr_offers_custom_column_content', 10, 2);
