<?php
function hr_platform_add_acf_fields()
{
    if (function_exists('acf_add_local_field_group')):

        acf_add_local_field_group(array(
            'key' => 'group_hr_offers',
            'title' => 'HR Offers Fields',
            'fields' => array(
                array(
                    'key' => 'field_hr_name',
                    'label' => "Recipient's Name",
                    'name' => 'hr_name',
                    'type' => 'text',
                    'required' => 1,
                    'wrapper' => array(
                        'width' => '25',
                        'class' => '',
                        'id' => '',
                    ),
                ),
                array(
                    'key' => 'field_hr_second_name',
                    'label' => "Recipient's Second Name",
                    'name' => 'hr_second_name',
                    'type' => 'text',
                    'required' => 1,
                    'wrapper' => array(
                        'width' => '25',
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
                    'wrapper' => array(
                        'width' => '25',
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
                    'wrapper' => array(
                        'width' => '25',
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
                    'wrapper' => array(
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
                    'wrapper' => array(
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
                    'wrapper' => array(
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
                    'wrapper' => array(
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
                    'wrapper' => array(
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
                    'wrapper' => array(
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
                    'wrapper' => array(
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
                    'wrapper' => array(
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
                    'wrapper' => array(
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


        acf_add_local_field_group(array(
            'key' => 'group_hr_contract',
            'title' => 'HR Contract Fields',
            'fields' => array(

                array(
                    'key' => 'field_sign_name',
                    'label' => "Recipient's Name",
                    'name' => 'hr_name',
                    'instructions' => 'Responsible for {sign_name}',
                    'type' => 'text',
                    'required' => 1,
                    'wrapper' => array(
                        'width' => '34',
                        'class' => '',
                        'id' => '',
                    ),
                ),
                array(
                    'key' => 'field_sign_lastname',
                    'label' => "Recipient's Lastname",
                    'name' => 'hr_name',
                    'instructions' => 'Responsible for {sign_lastname}',
                    'type' => 'text',
                    'required' => 1,
                    'wrapper' => array(
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
                    'wrapper' => array(
                        'width' => '33',
                        'class' => '',
                        'id' => '',
                    ),
                )
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'esign',
                    ),
                ),
            ),
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'field',
        ));

    endif;
}

add_action('acf/init', 'hr_platform_add_acf_fields');