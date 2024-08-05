<?php
require_once(dirname(__DIR__) . '/dompdf/autoload.inc.php');

require_once(__DIR__ . '/../../../../wp-load.php'); 

if ( ! function_exists('get_field') ) {
    require_once( plugin_dir_path( __FILE__ ) . 'advanced-custom-fields/acf.php' );
}

if ( ! class_exists('ACF') ) {
    exit('Advanced Custom Fields plugin is not active.');
}

function pixelsToCm($pixels) {
    return ($pixels / 1440) * 21.0;
}
function pixelsToPoints($pixels, $dpi = 174.5) {
    return ($pixels / $dpi) * 72;
}

// $pixels = 283;
// $cm = pixelsToCm($pixels, $paperWidthPx, $paperWidthCm);

// echo "Размер в см: " . $cm;

use Dompdf\Dompdf;

if (isset($_GET['post_id'])) {
    $post_id = intval($_GET['post_id']);
    $description = get_field('field_hr_description', $post_id);
    $name = get_field('field_hr_name', $post_id);

    $data = [];
    $fields = [
        'field_hr_offer_date' => 'Job offer',
        'field_hr_position' => 'Position',
        'field_hr_starting_date' => 'Starting date',
        'field_hr_project' => 'Project',
        'field_hr_salary' => 'Salary',
        'field_hr_period' => 'Probationary period',
        'field_hr_when' => 'When',
        'field_hr_where' => 'Where',
        'field_hr_vacation' => 'Vacation',
        'field_hr_sick_days' => 'Sick days'
    ];

    foreach ($fields as $field_key => $label) {
        $value = get_field($field_key, $post_id);
        if (!empty($value)) {
            if ($field_key == 'field_hr_salary') {
                $value = '$' . number_format($value) . '/month'; 
            }
            if ($field_key == 'field_hr_offer_date') {
                $value = $value . '<br>' . htmlspecialchars($name); 
            }

            $data[] = [
                'label' => $label,
                'value' => $value
            ];
        }
    }

    // $data = [
    //     [
    //         'label' => 'Job offer',
    //         'value' => get_field('field_hr_offer_date', $post_id) . '<br>' . htmlspecialchars($name),
    //     ],
    //     [
    //         'label' => 'Position',
    //         'value' => get_field('field_hr_position', $post_id),
    //     ],
    //     [
    //         'label' => 'Starting date:',
    //         'value' => get_field('field_hr_starting_date', $post_id),
    //     ],
    //     [
    //         'label' => 'Project',
    //         'value' => get_field('field_hr_project', $post_id),
    //     ],
    //     [
    //         'label' => 'Salary:',
    //         'value' => '$' . get_field('field_hr_salary', $post_id) . '/month',
    //     ],
    //     [
    //         'label' => 'Probationary period',
    //         'value' => get_field('field_hr_period', $post_id),
    //     ],
    //     [
    //         'label' => 'When',
    //         'value' => get_field('field_hr_when', $post_id),
    //     ],
    //     [
    //         'label' => 'Where',
    //         'value' => get_field('field_hr_where', $post_id),
    //     ],
    //     [
    //         'label' => 'Vacation',
    //         'value' => get_field('field_hr_vacation', $post_id) . ' days',
    //     ],
    //     [
    //         'label' => 'Sick days',
    //         'value' => get_field('field_hr_sick_days', $post_id) . ' days',
    //     ]
    // ];

    $cop_image_url = plugins_url('assets/images/cop-dw.png', __DIR__);
    $logo_image_url = plugins_url('assets/images/logo-white.png', __DIR__);

    // Создание экземпляра Dompdf
    $dompdf = new Dompdf(array('enable_remote' => true));

    // Генерация HTML для PDF
    $html_head = '<html>
    <head>
        <style>
            @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&family=Roboto+Mono:ital,wght@0,100..700;1,100..700&display=swap");
            @page { margin: 0in; }
            body {
                background-color: #0B0B22;
                color: #FFF;
                font-family: "Poppins", sans-serif;
                font-family: "Roboto Mono", monospace;
                padding: 28pt 45pt;
                font-size: 10pt;
            }
            h1 {
                color: #0066cc;
            }
            p {
                margin: 1em 0;
            }
            .logo {
                margin: 0 0 51pt;
            }
            .logo-img {
                width: 116pt;
                height: 39pt;
            }
            .wellcome-block {
                position: relative;
                width: 100%;
                background-color: #4F52FF;
                margin: 0 0 15pt;
            }
            .wellcome-block__wrap {
                padding: 23pt 212pt 23pt 33pt;
            }
            .wellcome-block__label {
                font-size: 7pt;
                color: #66FFA3;
                font-family: "Poppins", sans-serif;
                margin: 0 0 6pt;
                line-height: 1;
                font-weight: 600;
            }
            .wellcome-block__title {
                text-transform: uppercase;
                margin: 0 0 16pt;
                font-size: 26pt;
                line-height: 1;
                font-weight: 600;
                font-family: "Poppins", sans-serif;
            }
            .wellcome-block__text {
                font-size: 6.5pt;
            }

            .wellcome-block__image {
                width: 217pt;
                height: 267pt;
                float: right;
                margin: -42pt -20pt 0 0;
            }
            .wellcome-block__image img{
                width: 100%;
                height: auto;
            }
            .tiles__label {
                font-family: "Poppins", sans-serif;
                font-size: 11pt;
                line-height: 1;
                font-weight: bold;
            }
            .tiles__text {
                font-family: "Roboto Mono", monospace;
                line-height: 1.2;
            }
            table {
                width: calc(100% + 32px);
                table-layout: fixed;
                border-collapse: separate;
                margin: 0 -16px;
                padding: 0;
                border-spacing: 16px;
                border: none;
            }
            table table {
                border-spacing: 0;
                margin: 0;
                width: 100%;
            }
            table td.td {
                background-color:#232338;
                padding: 8pt 12pt;
                vertical-align: top;
            }
            .bottom-info {
                color: #A6BFD0;
                padding: 8pt 0;
            }
            .bottom-info a{
                color: #0096FF;
            }
        </style>
    </head>
    <body>';
    $html_footer = '</body>
    </html>';
    $html_body = '
    <div class="logo">
        <img src="' . $logo_image_url . '" class="logo-img" width="160">
    </div>
    <div class="wellcome-block"  style="position: relative; top: auto; width: 100%;">
        <div class="wellcome-block__image">
            <img src="' . $cop_image_url . '" class="cop-img">
        </div>
        <div class="wellcome-block__wrap">
            <div class="wellcome-block__label">With ProCoder Orientation</div>
            <div class="wellcome-block__title">Join the dark side</div>
            <div class="wellcome-block__text">
                Dear <span style="color: #66FFA3;">' . htmlspecialchars($name) . '</span>, <br>
                ' . $description . '
            </div>
        </div>
    </div>
    
    <table class="table">';
        $row_count = 0;
        $total_items = count($data);

        foreach ($data as $index => $item) {
            if ($total_items == 10) {
                $colspan = $index < 6 ? 2 : 3;
                $max_cols = $index < 6 ? 3 : 2; 
            } else {
                $colspan = 2;
                $max_cols = 3; 
            }

            if ($row_count % $max_cols == 0) {
                if ($row_count > 0) {
                    $html_body .= '</tr>';
                }
                $html_body .= '<tr>';
            }

            $html_body .= '<td colspan="' . $colspan . '" class="td">
            <div class="tiles__item">
            <div class="tiles__label">' . $item['label'] . '</div>
            <div class="tiles__text">' . $item['value'] . '</div>
            </div>
            </td>';

            $row_count++;
        }

        if ($row_count % $max_cols != 0) {
            $html_body .= '</tr>';
        }
    $html_body .= '
    </table>
    <table>
        <tr>
            <td class="td">
                <table>
                    <tr>
                        <td class="bottom-info">
                            <div>
                                <div class="bottom-info__item" >
                                    When submitting your registration, you are required to give 4 weeks notice.
                                </div>
                            </div>
                        </td>
                        <td class="bottom-info">
                            <div style="text-align: right;">
                                <div class="bottom-info__item" style="display: inline-block;text-align: left;">
                                    CEO, Kopachovets Oleg <br> <a href="https://procoders.tech">procoders.tech</a>
                                </div>
                            </div>

                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>
    
    ';
    $html = $html_head;
    $html .= $html_body;
    $html .= $html_footer;

    // $dompdf->set_option( 'dpi' , '96' );

    // Загрузка HTML в Dompdf
    $dompdf->loadHtml($html);


    // Настройка бумаги
    $dompdf->setPaper('A4', 'portrait');

    // Рендеринг PDF
    $dompdf->render();

    // Вывод PDF на экран
    $dompdf->stream('description.pdf', array('Attachment' => 0));

    // Путь для сохранения файла
    // $upload_dir = wp_upload_dir();
    // $save_path = $upload_dir['basedir'] . '/offers/' . $post_id;
    // $save_file = $save_path . '/offer.pdf';

    // // Создание директории, если не существует
    // if (!file_exists($save_path)) {
    //     wp_mkdir_p($save_path);
    // }

    // // Сохранение PDF файла на сервере
    // file_put_contents($save_file, $dompdf->output());

    // echo "PDF успешно сохранен в " . $save_file;
    exit;
}
?>


