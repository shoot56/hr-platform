<?php
require_once(dirname(__DIR__) . '/dompdf/autoload.inc.php');

require_once(__DIR__ . '/../../../../wp-load.php'); 

if ( ! function_exists('get_field') ) {
    require_once( plugin_dir_path( __FILE__ ) . 'advanced-custom-fields/acf.php' );
}

if ( ! class_exists('ACF') ) {
    exit('Advanced Custom Fields plugin is not active.');
}

use Dompdf\Dompdf;

if (isset($_GET['post_id'])) {
    $post_id = intval($_GET['post_id']);
    $description = get_field('field_hr_description', $post_id);

    // Создание экземпляра Dompdf
    $dompdf = new Dompdf();

    // Генерация HTML для PDF
    $html = "<h1>Post Description:</h1><p>{$description}</p>";

    // Загрузка HTML в Dompdf
    $dompdf->loadHtml($html);

    // Настройка бумаги
    $dompdf->setPaper('A4', 'portrait');

    // Рендеринг PDF
    $dompdf->render();

    // Вывод PDF на экран
    $dompdf->stream('description.pdf', array('Attachment' => 0));
    exit;
}
?>
