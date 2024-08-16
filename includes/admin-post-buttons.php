<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

function hr_offers_add_meta_box() {
    add_meta_box(
        'hr_offers_buttons_meta_box', // ID мета блока
        __('HR Offers Actions', 'hr-platform'), // Название мета блока
        'hr_offers_render_meta_box', // Функция для рендеринга содержимого
        'hr-offers', // Тип записи
        'side', // Положение на экране ('normal', 'side', 'advanced')
        'default' // Приоритет
    );
}
add_action('add_meta_boxes', 'hr_offers_add_meta_box');

function hr_offers_render_meta_box($post) {
    ?>
    <div>
        <a href="<?php echo plugin_dir_url(__FILE__) . 'generate-pdf.php?post_id=' . $post->ID; ?>" target="_blank" class="hr-offer-button button button-primary">Show PDF <span class="dashicons dashicons-media-document"></span></a>
        <button type="button" class="hr-offer-button button button-secondary send-email-button" data-id="<?php echo $post->ID; ?>">Send Email <span class="dashicons dashicons-email"></span></button>
    </div>

    <?php
}
