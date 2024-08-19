<?php 

function hr_platform_register_post_type() {
    $labels_offers = array(
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

    $args_offers = array(
        'labels' 				=> $labels_offers,
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

    // $labels_contracts = array(
    //     'name'               => 'HR Contracts',
    //     'singular_name'      => 'HR Contract',
    //     'menu_name'          => 'HR Contracts',
    //     'name_admin_bar'     => 'HR Contract',
    //     'add_new'            => 'Add New',
    //     'add_new_item'       => 'Add New HR Contract',
    //     'new_item'           => 'New HR Contract',
    //     'edit_item'          => 'Edit HR Contract',
    //     'view_item'          => 'View HR Contract',
    //     'all_items'          => 'All HR Contracts',
    //     'search_items'       => 'Search HR Contracts',
    //     'parent_item_colon'  => 'Parent HR Contracts:',
    //     'not_found'          => 'No HR Contracts found.',
    //     'not_found_in_trash' => 'No HR Contracts found in Trash.',
    // );

    // $args_contracts = array(
    //     'labels' 				=> $labels_contracts,
    //     'public' 				=> true,
    //     'publicly_queryable' 	=> false,
    //     'has_archive' 			=> false,
    //     'exclude_from_search' 	=> true,
    //     'show_in_nav_menus'   	=> false,
    //     'show_in_menu' 			=> true,
    //     'supports' 				=> array('title'),
    //     'menu_position' 		=> 20,
    //     'menu_icon' 			=> 'dashicons-media-document',
    //     'capability_type'		=> 'post',
    //     'rewrite'            	=> false,
    // );

    register_post_type('hr-offers', $args_offers);
    // register_post_type('hr-contracts', $args_contracts);
}
add_action('init', 'hr_platform_register_post_type');