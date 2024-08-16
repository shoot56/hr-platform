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

use Dompdf\Dompdf;

function generate_pdf_for_offer($post_id, $save_to_disk = false) {

    // $post_id = intval($_GET['post_id']);
    $description = get_field('field_hr_description', $post_id);
    $name = get_field('field_hr_name', $post_id);
    $additionals = get_field('field_hr_information', $post_id);

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

    $cop_image_url = plugins_url('assets/images/cop-dw.png', __DIR__);
    $logo_image_url = plugins_url('assets/images/logo-white.png', __DIR__);
    $join_image_url = plugins_url('assets/images/join-text.png', __DIR__);

    $dompdf = new Dompdf(array('enable_remote' => true));

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
                padding: 28pt 45pt 0;
                font-size: 10pt;
            }
            h1 {
                color: #0066cc;
            }
            p {
                margin: 1em 0;
            }
            .logo {
                margin: 0 0 31pt;
            }
            .logo-img {
                width: 116pt;
                height: 39pt;
            }
            .wellcome-block {
                position: relative;
                width: 100%;
                border-top: 1px solid #3B4C62;
                border-bottom: 1px solid #3B4C62;
                margin: 0 0 15pt;
            }
            .wellcome-block__wrap {
                padding: 23pt 212pt 23pt 0;
            }
            .wellcome-block__label {
                font-size: 11pt;
                font-family: "Poppins", sans-serif;
                margin: 0 0 10pt;
                line-height: 1;
                font-weight: 600;
                color: #0096FF;
            }
            .wellcome-block__title {
                text-transform: uppercase;
                margin: 0 0 10pt;
                font-size: 20pt;
                line-height: 1;
                font-weight: 600;
                font-family: "Poppins", sans-serif;
            }
            .wellcome-block__title img{
                width: 360pt;
                heigth: auto;
                vertical-align: top;
            }

            .wellcome-block__image {
                width: 147pt;
                height: 97pt;
                float: right;
                margin: -64pt -20pt 0 0;
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
            <div class="wellcome-block__label">Dear, <span>' . htmlspecialchars($name) . '</span>!</div>
            <div class="wellcome-block__title">
                <img src="' . $join_image_url . '" class="join-img">
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
    $html_body .= '</table>';

    if ($additionals) {
        $html_body .= '<table><tr><td class="td">
            <div class="tiles__item">
            <div class="tiles__label">Special terms and conditions</div>
            <div class="tiles__text">' . $additionals . '</div>
            </div>
        </td></tr></table>';
        
    }
    $html_body .= '
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

    $dompdf->loadHtml($html);

    $dompdf->setPaper('A4', 'portrait');

    $dompdf->render();

    if ($save_to_disk) {
        $upload_dir = wp_upload_dir();
        $save_path = $upload_dir['basedir'] . '/offers/' . $post_id;
        $save_file = $save_path . '/offer.pdf';
        if (!file_exists($save_path)) {
            wp_mkdir_p($save_path);
        }
        file_put_contents($save_file, $dompdf->output());

        // echo "PDF created " . $save_file;
        return $save_file;

    } else {
        $dompdf->stream('offer.pdf', array('Attachment' => 0));
    }


    exit;
}

if (isset($_GET['post_id'])) {
    $post_id = intval($_GET['post_id']);
    generate_pdf_for_offer($post_id, false);
}

?>


