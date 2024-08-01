<?php
function hr_platform_add_acf_fields() {
    if( function_exists('acf_add_local_field_group') ):

    acf_add_local_field_group(array(
        'key' => 'group_hr_offers',
        'title' => 'HR Offers Fields',
        'fields' => array(
            array(
                'key' => 'field_hr_name',
                'label' => 'Name',
                'name' => 'hr_name',
                'type' => 'text',
                'required' => 1,
            ),
            array(
                'key' => 'field_hr_description',
                'label' => 'Description',
                'name' => 'hr_description',
                'type' => 'textarea',
                'required' => 1,
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