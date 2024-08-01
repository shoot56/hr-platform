<?php 

function hr_platform_register_post_type() {
    $labels = array(
        'name'               => 'HR Offers',
        'singular_name'      => 'HR Offer',
        'menu_name'          => 'HR Offers',
        'name_admin_bar'     => 'HR Offer',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New HR Offer',
        'new_item'           => 'New HR Offer',
        'edit_item'          => 'Edit HR Offer',
        'view_item'          => 'View HR Offer',
        'all_items'          => 'All HR Offers',
        'search_items'       => 'Search HR Offers',
        'parent_item_colon'  => 'Parent HR Offers:',
        'not_found'          => 'No HR Offers found.',
        'not_found_in_trash' => 'No HR Offers found in Trash.',
    );

    $args = array(
        'labels' 				=> $labels,
        'public' 				=> true,
        'publicly_queryable' 	=> false,
        'has_archive' 			=> false,
        'exclude_from_search' 	=> true,
        'show_in_nav_menus'   	=> false,
        'show_in_menu' 			=> true,
        'supports' 				=> array('title'),
        'menu_position' 		=> 20,
        'menu_icon' 			=> 'dashicons-clipboard',
        'capability_type'		=> 'post',
        'rewrite'            	=> false,
    );

    register_post_type('hr-offers', $args);
}
add_action('init', 'hr_platform_register_post_type');