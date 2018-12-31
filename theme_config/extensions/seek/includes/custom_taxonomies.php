<?php

if (!defined('TFUSE'))
    exit('Direct access forbidden.');


/**
 * User defined custom taxonomies
 */
global $seek_post_type;
$post_type = TF_SEEK_HELPER::get_post_type();
$seek_post_type = $post_type;

if (!function_exists('tfuse_vehicle_comments')) :
    /**
     *
     *
     * To override tfuse_vehicle_comments() in a child theme, add your own tfuse_holidays_comments()
     * to your child theme's file.
     */
    add_action('init', 'tfuse_vehicle_comments', 99);

    function tfuse_vehicle_comments ()
    {
            $supports = array( 'title', 'excerpt', 'editor', 'comments' );
            add_post_type_support( TF_SEEK_HELPER::get_post_type(), $supports );
    }
endif;
//Vehicle Types
register_taxonomy($post_type . '_type', array($post_type), array(
        'hierarchical' => false,
        'labels' => array
        (
            'name' => TF_SEEK_HELPER::get_option('seek_property_name_singular'). ' ' .__( 'Types','tfuse' ),
            'singular_name' => TF_SEEK_HELPER::get_option('seek_property_name_singular') .' '.__( 'Type','tfuse' ),
            'search_items' =>  __( 'Search', 'tfuse') . ' ' . TF_SEEK_HELPER::get_option('seek_property_name_singular') . ' ' . __('Types','tfuse' ),
            'all_items' => __( 'All','tfuse').' ' . TF_SEEK_HELPER::get_option('seek_property_name_singular') .' '. __('Types','tfuse' ),
            'edit_item' => __( 'Edit','tfuse') .' '. TF_SEEK_HELPER::get_option('seek_property_name_singular') .' '.__('Type','tfuse' ),
            'update_item' => __( 'Update','tfuse') .' '. TF_SEEK_HELPER::get_option('seek_property_name_singular') .' '.__('Type','tfuse' ),
            'add_new_item' => __( 'Add New Type','tfuse' ),
            'new_item_name' => __( 'New','tfuse') .' '. TF_SEEK_HELPER::get_option('seek_property_name_singular') .' '. __('Type Name','tfuse' ),
            'menu_name' => __('Types','tfuse' ),
        ),
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => array(
            'slug' => apply_filters( 'tfuse_vehicle_type_slug', $post_type . '_type' ),
            'with_front' => true
        ),

    )
);

//Fuel Types
register_taxonomy($post_type . '_fuel_type', array($post_type), array(
        'hierarchical' => false,
        'labels' => array
        (
            'name' => TF_SEEK_HELPER::get_option('seek_property_name_singular') .' '.__( 'Fuel Types','tfuse' ),
            'singular_name' => TF_SEEK_HELPER::get_option('seek_property_name_singular') .' '. __( 'Fuel Type','tfuse' ),
            'search_items' =>  __( 'Search','tfuse'). ' ' . TF_SEEK_HELPER::get_option('seek_property_name_singular') .' '.__('Fuel Types','tfuse' ),
            'all_items' => __( 'All','tfuse') .' '. TF_SEEK_HELPER::get_option('seek_property_name_singular') .' '. __('Fuel Types','tfuse' ),
            'edit_item' => __( 'Edit','') .' '. TF_SEEK_HELPER::get_option('seek_property_name_singular') .' '. __('Fuel Type','tfuse' ),
            'update_item' => __( 'Update','tfuse') .' '. TF_SEEK_HELPER::get_option('seek_property_name_singular') .' '. __('Fuel Type','tfuse' ),
            'add_new_item' => __( 'Add New Type','tfuse' ),
            'new_item_name' => __( 'New','tfuse') .' '. TF_SEEK_HELPER::get_option('seek_property_name_singular') .' '. __('Fuel Type Name','tfuse' ),
            'menu_name' => __('Fuel Types','tfuse' ),
        ),
        'show_ui'   => true,
        'public'    => true,
        'query_var' => true,
        'rewrite' => array(
            'slug' => apply_filters( 'tfuse_vehicle_fuel_slug', $post_type . '_fuel' ),
            'with_front' => true
        ),
        'capabilities'  => array(
        )
    )
);

//Colors
register_taxonomy($post_type . '_colors', array($post_type), array(
        'hierarchical' => false,
        'labels' => array
        (
            'name' => TF_SEEK_HELPER::get_option('seek_property_name_singular'). ' '.__( 'Colors','tfuse' ),
            'singular_name' => TF_SEEK_HELPER::get_option('seek_property_name_singular') .' '. __( 'Color','tfuse' ),
            'search_items' =>  __( 'Search','tfuse') .' '. TF_SEEK_HELPER::get_option('seek_property_name_singular') .' '. __('Colors','tfuse' ),
            'all_items' => __( 'All','tfuse') .' '. TF_SEEK_HELPER::get_option('seek_property_name_singular') .' '. __('Colors','tfuse' ),
            'edit_item' => __( 'Edit','tfuse') .' '. TF_SEEK_HELPER::get_option('seek_property_name_singular') .' '. __('Color','tfuse' ),
            'update_item' => __( 'Update','tfuse') .' '. TF_SEEK_HELPER::get_option('seek_property_name_singular') .' '. __('Color','tfuse' ),
            'add_new_item' => __( 'Add New Color','tfuse' ),
            'new_item_name' => __( 'New','tfuse') .' '. TF_SEEK_HELPER::get_option('seek_property_name_singular') .' '. __('Color','tfuse' ),
            'menu_name' => __('Colors','tfuse' ),
        ),
        'show_ui'   => true,
        'public'    => true,
        'query_var' => true,
        'rewrite' => array(
            'slug' => apply_filters( 'tfuse_vehicle_colors_slug', $post_type . '_colors' ),
            'with_front' => true
        ),
        'capabilities'  => array(
        )
    )
);
//Status
register_taxonomy($post_type . '_statuses', array($post_type), array(
        'hierarchical' => false,
        'labels' => array
        (
            'name' => TF_SEEK_HELPER::get_option('seek_property_name_singular') . ' '.__( 'Statuses','tfuse' ),
            'singular_name' => TF_SEEK_HELPER::get_option('seek_property_name_singular') . ' '.__(  'Status','tfuse' ),
            'search_items' =>  __( 'Search','tfuse') .' '. TF_SEEK_HELPER::get_option('seek_property_name_singular') .' '. __('Statuses','tfuse' ),
            'all_items' => __( 'All','tfuse') .' '. TF_SEEK_HELPER::get_option('seek_property_name_singular') .' '. __('Statuses','tfuse' ),
            'edit_item' => __( 'Edit','tfuse') .' '. TF_SEEK_HELPER::get_option('seek_property_name_singular') .' '. __('Status','tfuse' ),
            'update_item' => __( 'Update','tfuse') .' '. TF_SEEK_HELPER::get_option('seek_property_name_singular') .' '. __('Status','tfuse' ),
            'add_new_item' => __( 'Add New Type','tfuse' ),
            'new_item_name' => __( 'New','tfuse') .' '. TF_SEEK_HELPER::get_option('seek_property_name_singular') .' '. __('Status Name','tfuse' ),
            'menu_name' => __('Statuses','tfuse' ),
        ),
        'show_ui'   => true,
        'public'    => true,
        'query_var' => true,
        'rewrite' => array(
            'slug' => apply_filters( 'tfuse_vehicle_statuses_slug', $post_type . '_statuses' ),
            'with_front' => true
        ),
    )
);
//Gearboxes
register_taxonomy($post_type . '_gearboxes', array($post_type), array(
        'hierarchical' => false,
        'labels' => array
        (
            'name' => TF_SEEK_HELPER::get_option('seek_property_name_singular') .' '. __( 'Gearboxes','tfuse' ),
            'singular_name' => TF_SEEK_HELPER::get_option('seek_property_name_singular') .' '. __( 'Gearbox','tfuse' ),
            'search_items' =>  __( 'Search','tfuse') .' '. TF_SEEK_HELPER::get_option('seek_property_name_singular') .' '. __('Gearboxes','tfuse' ),
            'all_items' => __( 'All','tfuse') .' '. TF_SEEK_HELPER::get_option('seek_property_name_singular') .' '. __('Gearboxes','tfuse' ),
            'edit_item' => __( 'Edit','tfuse') .' '. TF_SEEK_HELPER::get_option('seek_property_name_singular') .' '. __('Gearbox','tfuse' ),
            'update_item' => __( 'Update','tfuse') .' '. TF_SEEK_HELPER::get_option('seek_property_name_singular') .' '. __('Gearbox','tfuse' ),
            'add_new_item' => __( 'Add New Gearbox','tfuse' ),
            'new_item_name' => __( 'New','tfuse') .' '. TF_SEEK_HELPER::get_option('seek_property_name_singular') .' '. __('Gearbox Name','tfuse' ),
            'menu_name' => __('Gearboxes','tfuse' ),
        ),
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => array(
            'slug' => apply_filters( 'tfuse_vehicle_gearboxes_slug', $post_type . '_gearboxes' ),
            'with_front' => true
        ),
    )
);
//Interior Features
register_taxonomy($post_type . '_interior_features', array($post_type), array(
        'hierarchical' => false,
        'labels' => array
        (
            'name' => TF_SEEK_HELPER::get_option('seek_property_name_singular') .' '. __( 'Interior Features','tfuse' ),
            'singular_name' => TF_SEEK_HELPER::get_option('seek_property_name_singular') .' '. __( 'Interior Feature','tfuse' ),
            'search_items' =>  __( 'Search','tfuse') .' '. TF_SEEK_HELPER::get_option('seek_property_name_singular') .' '. __('Interior Features','tfuse' ),
            'all_items' => __( 'All','tfuse') .' '. TF_SEEK_HELPER::get_option('seek_property_name_singular') .' '. __('Interior Features','tfuse' ),
            'edit_item' => __( 'Edit','tfuse') .' '. TF_SEEK_HELPER::get_option('seek_property_name_singular') .' '. __('Interior Feature','tfuse' ),
            'update_item' => __( 'Update','tfuse') .' '. TF_SEEK_HELPER::get_option('seek_property_name_singular') .' '. __('Interior Feature','tfuse' ),
            'add_new_item' => __( 'Add New Interior Feature','tfuse' ),
            'new_item_name' => __( 'New','tfuse') .' '. TF_SEEK_HELPER::get_option('seek_property_name_singular') .' '. __('Interior Feature Name','tfuse' ),
            'choose_from_most_used'     => __('Choose from the most used interior features','tfuse'),
            'separate_items_with_commas'=> __('Separate interior features with commas','tfuse'),
            'menu_name' => __('Interior Features','tfuse' ),
        ),
        'capabilities'  => array(
            'manage_terms'              => 'manage_categories',
            'edit_terms'                => 'manage_categories',
            'delete_terms'              => 'manage_categories',
            'assign_terms'              => 'assign_seek_terms'
        ),
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => array(
            'slug' => apply_filters( 'tfuse_vehicle_interior_features', $post_type . '_interior_features' ),
            'with_front' => true
        ),
    )
);
//Exterior Features
register_taxonomy($post_type . '_exterior_features', array($post_type), array(
        'hierarchical' => false,
        'labels' => array
        (
            'name' => TF_SEEK_HELPER::get_option('seek_property_name_singular') .' '. __( 'Exterior Features','tfuse' ),
            'singular_name' => TF_SEEK_HELPER::get_option('seek_property_name_singular') .' '. __( 'Exterior Feature','tfuse' ),
            'search_items' =>  __( 'Search','tfuse') .' '. TF_SEEK_HELPER::get_option('seek_property_name_singular') .' '. __('Exterior Features','tfuse' ),
            'all_items' => __( 'All','tfuse') .' '. TF_SEEK_HELPER::get_option('seek_property_name_singular') .' '. __('Exterior Features','tfuse' ),
            'edit_item' => __( 'Edit','tfuse') .' '. TF_SEEK_HELPER::get_option('seek_property_name_singular') .' '. __('Exterior Feature','tfuse' ),
            'update_item' => __( 'Update','tfuse') .' '. TF_SEEK_HELPER::get_option('seek_property_name_singular') .' '. __('Exterior Feature','tfuse' ),
            'add_new_item' => __( 'Add New Exterior Feature','tfuse' ),
            'new_item_name' => __( 'New','tfuse') .' '. TF_SEEK_HELPER::get_option('seek_property_name_singular') .' '. __('Exterior Feature Name','tfuse' ),
            'menu_name' => __('Exterior Features','tfuse' ),
        ),
        'capabilities'  => array(
            'manage_terms'              => 'manage_categories',
            'edit_terms'                => 'manage_categories',
            'delete_terms'              => 'manage_categories',
            'assign_terms'              => 'assign_seek_terms'
        ),
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => array(
            'slug' => apply_filters( 'tfuse_vehicle_exterior_features_slug', $post_type . '_exterior_features' ),
            'with_front' => true
        ),
    )
);
//Safely Features
register_taxonomy($post_type . '_safely_features', array($post_type), array(
        'hierarchical' => false,
        'labels' => array
        (
            'name' => TF_SEEK_HELPER::get_option('seek_property_name_singular') .' '. __( 'Safety Features','tfuse' ),
            'singular_name' => TF_SEEK_HELPER::get_option('seek_property_name_singular') .' '. __( 'Safety Feature','tfuse' ),
            'search_items' =>  __( 'Search','tfuse') .' '. TF_SEEK_HELPER::get_option('seek_property_name_singular') .' '. __('Safety Features','tfuse' ),
            'all_items' => __( 'All','tfuse') .' '. TF_SEEK_HELPER::get_option('seek_property_name_singular') .' '. __('Safety Features','tfuse' ),
            'edit_item' => __( 'Edit','tfuse') .' '. TF_SEEK_HELPER::get_option('seek_property_name_singular') .' '. __('Safety Feature','tfuse' ),
            'update_item' => __( 'Update','tfuse') .' '. TF_SEEK_HELPER::get_option('seek_property_name_singular') .' '. __('Safety Feature','tfuse' ),
            'add_new_item' => __( 'Add New Safely Feature','tfuse' ),
            'new_item_name' => __( 'New','tfuse') .' '. TF_SEEK_HELPER::get_option('seek_property_name_singular') .' '. __('Safety Feature Name','tfuse' ),
            'menu_name' => __('Safety Features','tfuse' ),
        ),
        'capabilities'  => array(
            'manage_terms'              => 'manage_categories',
            'edit_terms'                => 'manage_categories',
            'delete_terms'              => 'manage_categories',
            'assign_terms'              => 'assign_seek_terms'
        ),
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => array(
            'slug' => apply_filters( 'tfuse_vehicle_safely_features_slug', $post_type . '_safely_features' ),
            'with_front' => true
        ),
    )
);
//Extras
register_taxonomy($post_type . '_extras', array($post_type), array(
        'hierarchical' => false,
        'labels' => array
        (
            'name' => TF_SEEK_HELPER::get_option('seek_property_name_singular') .' '. __( 'Extra','tfuse' ),
            'singular_name' => TF_SEEK_HELPER::get_option('seek_property_name_singular') .' '. __( 'Extra','tfuse' ),
            'search_items' =>  __( 'Search','tfuse') .' '. TF_SEEK_HELPER::get_option('seek_property_name_singular') .' '. __('Extra','tfuse' ),
            'all_items' => __( 'All','tfuse') .' '. TF_SEEK_HELPER::get_option('seek_property_name_singular') .' '. __('Extras','tfuse' ),
            'edit_item' => __( 'Edit','tfuse') .' '. TF_SEEK_HELPER::get_option('seek_property_name_singular') .' '. __('Extra','tfuse' ),
            'update_item' => __( 'Update','tfuse') .' '. TF_SEEK_HELPER::get_option('seek_property_name_singular') .' '. __('Extra','tfuse' ),
            'add_new_item' => __( 'Add New Extra','tfuse' ),
            'new_item_name' => __( 'New','tfuse') .' '. TF_SEEK_HELPER::get_option('seek_property_name_singular') .' '. __('Extra','tfuse' ),
            'menu_name' => __('Extras','tfuse' ),
        ),
        'capabilities'  => array(
            'manage_terms'              => 'manage_categories',
            'edit_terms'                => 'manage_categories',
            'delete_terms'              => 'manage_categories',
            'assign_terms'              => 'assign_seek_terms'
        ),
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => array(
            'slug' => apply_filters( 'tfuse_vehicle_extras_slug', $post_type . '_extras' ),
            'with_front' => true
        ),
    )
);
//Locations
register_taxonomy($post_type . '_locations', array($post_type), array(
    'hierarchical'  => true,
    'labels'        => array(
        'name'                      => __('Locations','tfuse'),
        'singular_name'             => __('Location','tfuse'),
        'search_items'              => __('Search Locations','tfuse'),
        'all_items'                 => __('All Locations','tfuse'),
        'parent_item'               => __('Parent Location','tfuse'),
        'parent_item_colon'         => __('Parent Location:','tfuse'),
        'edit_item'                 => __('Edit Location','tfuse'),
        'update_item'               => __('Update Location','tfuse'),
        'add_new_item'              => __('Add New Location','tfuse'),
        'new_item_name'             => __('New Location Name','tfuse'),
        'choose_from_most_used'     => __('Choose from the most used locations','tfuse'),
        'separate_items_with_commas'=> __('Separate locations with commas','tfuse')
    ),
    'capabilities'  => array(
        'manage_terms'              => 'manage_categories',
        'edit_terms'                => 'assign_seek_terms',
        'delete_terms'              => 'manage_categories',
        'assign_terms'              => 'assign_seek_terms'
    ),
    'rewrite' => array(
        'slug' => apply_filters( 'tfuse_vehicle_locations_slug', $post_type . '_locations' ),
        'with_front' => true
    ),
    'show_ui'       => true,
    'query_var'     => true
));
//Maker/Models
register_taxonomy($post_type . '_models', array($post_type), array(
    'hierarchical'  => true,
    'labels'        => array(
        'name'                      => __('Makers/Models','tfuse'),
        'singular_name'             => __('Maker/Model','tfuse'),
        'search_items'              => __('Search Maker/Model','tfuse'),
        'all_items'                 => __('All Makers/Models','tfuse'),
        'parent_item'               => __('Parent Maker/Model','tfuse'),
        'parent_item_colon'         => __('Parent Maker/Model:','tfuse'),
        'edit_item'                 => __('Edit Maker/Model','tfuse'),
        'update_item'               => __('Update Maker/Location','tfuse'),
        'add_new_item'              => __('Add New Maker/Model','tfuse'),
        'new_item_name'             => __('New Maker/Model Name','tfuse'),
        'choose_from_most_used'     => __('Choose from the most used makers/models','tfuse'),
        'separate_items_with_commas'=> __('Separate maker/model with commas','tfuse')
    ),
    'capabilities'  => array(
        'manage_terms'              => 'manage_categories',
        'edit_terms'                => 'assign_seek_terms',
        'delete_terms'              => 'manage_categories',
        'assign_terms'              => 'assign_seek_terms'
    ),
    'rewrite' => array(
        'slug' => apply_filters( 'tfuse_vehicle_models_slug', $post_type . '_models' ),
        'with_front' => true
    ),
    'show_ui'       => true,
    'query_var'     => true
));
function tfuse_taxonomy_redirect_note_form_1($taxonomy){
    global $seek_post_type;
    $taxonomy = str_replace($seek_post_type . '_','',$taxonomy);
    $taxonomy = substr_replace($taxonomy ,"",-1);
    $taxonomy = mb_ucfirst($taxonomy);
    echo '<p><strong>Note:</strong> More options are available after you add the '.$taxonomy.'. <br />
        Click on the Edit button under the '.$taxonomy.' name.</p>';
}
function tfuse_taxonomy_redirect_note_form_2($taxonomy){
    global $seek_post_type;
    $taxonomy = str_replace($seek_post_type . '_','',$taxonomy);
    $taxonomy = substr_replace($taxonomy ,"",-3);
    $taxonomy .= 'y';
    $taxonomy = mb_ucfirst($taxonomy);
    echo '<p><strong>Note:</strong> More options are available after you add the '.$taxonomy.'. <br />
        Click on the Edit button under the '.$taxonomy.' name.</p>';
}
function tfuse_taxonomy_redirect_note_form_3($taxonomy){
    global $seek_post_type;
    $taxonomy = str_replace($seek_post_type . '_','',$taxonomy);
    $taxonomy = mb_ucfirst($taxonomy);
    echo '<p><strong>Note:</strong> More options are available after you add the '.$taxonomy.'. <br />
        Click on the Edit button under the '.$taxonomy.' name.</p>';
}