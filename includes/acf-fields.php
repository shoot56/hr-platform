<?php
function hr_platform_add_acf_fields() {
    if( function_exists('acf_add_local_field_group') ):

    acf_add_local_field_group(array(
        'key' => 'group_hr_offers',
        'title' => 'HR Offers Fields',
        'fields' => array(
            array(
                'key' => 'field_hr_description',
                'label' => 'Description',
                'name' => 'hr_description',
                'type' => 'textarea',
                'required' => 1,
                "rows" => 4,
                "new_lines" => "br",
                'default_value' => 'I hope this message finds you well. Congratulations on receiving the job offer! We are thrilled to have you join our team and look forward to the valuable contributions you will bring.

Please take the time to review the offer letter and let us know if you have any questions or need any further information. We are here to assist you in any way possible to ensure a smooth transition into your new role.',
            ),
            array(
                'key' => 'field_hr_name',
                'label' => "Recipient's Name",
                'name' => 'hr_name',
                'type' => 'text',
                'required' => 1,
                'wrapper' => array (
                    'width' => '34',
                    'class' => '',
                    'id' => '',
                ),
            ),
            array(
                'key' => 'field_hr_offer_date',
                'label' => "Offer Date",
                'name' => 'hr_offer_date',
                'type' => 'date_picker',
                'display_format' => 'j.m.Y',
                'return_format' => 'j.m.Y',
                'required' => 1,
                'wrapper' => array (
                    'width' => '33',
                    'class' => '',
                    'id' => '',
                ),
            ),
            array(
                'key' => 'field_hr_salary',
                'label' => "Salary",
                'name' => 'hr_salary',
                'type' => 'number',
                'required' => 1,
                'placeholder' => '1200',
                "prepend" => "$",
                "append" => "/month",
                'wrapper' => array (
                    'width' => '33',
                    'class' => '',
                    'id' => '',
                ),
            ),
            array(
                'key' => 'field_hr_position',
                'label' => "Position",
                'name' => 'hr_position',
                'type' => 'text',
                'placeholder' => 'UI/UX Designer',
                'required' => 1,
                'wrapper' => array (
                    'width' => '50',
                    'class' => '',
                    'id' => '',
                ),
            ),
            array(
                'key' => 'field_hr_vacation',
                'label' => "Vacation",
                'name' => 'hr_vacation',
                'type' => 'text',
                'default_value' => '14 days',
                'required' => 1,
                'wrapper' => array (
                    'width' => '50',
                    'class' => '',
                    'id' => '',
                ),
            ),
            array(
                'key' => 'field_hr_starting_date',
                'label' => "Starting date",
                'name' => 'hr_starting_date',
                'type' => 'date_picker',
                'display_format' => 'j.m.Y',
                'return_format' => 'j.m.Y',
                'required' => 0,
                'wrapper' => array (
                    'width' => '33',
                    'class' => '',
                    'id' => '',
                ),
            ),
            array(
                'key' => 'field_hr_sick_days',
                'label' => "Sick days",
                'name' => 'hr_sick_days',
                'type' => 'text',
                'default_value' => '7 days',
                'required' => 1,
                'wrapper' => array (
                    'width' => '33',
                    'class' => '',
                    'id' => '',
                ),
            ),
            array(
                'key' => 'field_hr_project',
                'label' => "Project",
                'name' => 'hr_project',
                'type' => 'text',
                'required' => 0,
                'wrapper' => array (
                    'width' => '50',
                    'class' => '',
                    'id' => '',
                ),
            ),
            array(
                'key' => 'field_hr_period',
                'label' => "Probationary period",
                'name' => 'hr_period',
                'type' => 'text',
                'required' => 0,
                'wrapper' => array (
                    'width' => '50',
                    'class' => '',
                    'id' => '',
                ),
            ),
            array(
                'key' => 'field_hr_when',
                'label' => "When",
                'name' => 'hr_when',
                'type' => 'text',
                'required' => 0,
                'default_value' => 'Flexible hours',
                'wrapper' => array (
                    'width' => '50',
                    'class' => '',
                    'id' => '',
                ),
            ),
            array(
                'key' => 'field_hr_where',
                'label' => "Where",
                'name' => 'hr_where',
                'type' => 'text',
                'required' => 0,
                'default_value' => 'Remote',
                'wrapper' => array (
                    'width' => '50',
                    'class' => '',
                    'id' => '',
                ),
            ),
            array(
                'key' => 'field_hr_information',
                'label' => "Information",
                'name' => 'hr_information',
                'type' => 'textarea',
                'required' => 0,
                "rows" => 4,
                "new_lines" => "br",
                'wrapper' => array (
                    'width' => '100',
                    'class' => '',
                    'id' => '',
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'hr-offers',
                ),
            ),
        ),
    ));

    endif;
}

add_action('acf/init', 'hr_platform_add_acf_fields');