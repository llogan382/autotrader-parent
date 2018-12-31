<?php
/**
 * Initializing deafault sidebars
 *
 * @since AutoTrader 1.0
 */

add_filter('tf_get_taxonomies', 'custom_get_taxonomies_args');
function custom_get_taxonomies_args ($args){
    $args = array(
        'public' => TRUE,
        'show_ui' => TRUE,
        '_builtin' => FALSE
    );
    return $args;
}
function sidebar_exclude_group ($taxonomies){
    unset($taxonomies['vehicle_statuses']);
    unset($taxonomies['vehicle_interior_features']);
    unset($taxonomies['vehicle_exterior_features']);
    unset($taxonomies['vehicle_safely_features']);
    unset($taxonomies['vehicle_extras']);
    return $taxonomies;
}
add_filter('tfuse_sidebar_taxonomies', 'sidebar_exclude_group');

function tf_sidebar_cfg() {
    static $sidebar_cfg = array();
    #Sidebar options
    $beforeWidget = '<div id="%1$s" class="box %2$s">';
    $afterWidget = '</div>';
    $beforeTitle = '<h3>';
    $afterTitle = '</h3>';
    #End sidebar options
    if (count($sidebar_cfg) == 0) {
        #Sidebar filters
        $beforeWidget = apply_filters('tfuse_filter_before_widget', $beforeWidget);
        $afterWidget = apply_filters('tfuse_filter_after_widget', $afterWidget);
        $beforeTitle = apply_filters('tfuse_filter_before_title', $beforeTitle);
        $afterTitle = apply_filters('tfuse_filter_after_title', $afterTitle);
        #End sidebar filters
        $sidebar_cfg = compact('beforeWidget', 'afterWidget', 'beforeTitle', 'afterTitle');
    }
    return $sidebar_cfg;
}

function tf_sidebars_init() {
    extract(tf_sidebar_cfg());
    register_sidebar(array('name' => __('General Sidebar','tfuse'), 'id' => 'sidebar-1', 'before_widget' => $beforeWidget, 'after_widget' => $afterWidget, 'before_title' => $beforeTitle, 'after_title' => $afterTitle, 'description' => ''));
    if ( !tfuse_options('enable_footer_shortcodes') )
    {
        register_sidebar(array('name' => __('Footer 1','tfuse'), 'id' => 'footer-1', 'before_widget' => $beforeWidget, 'after_widget' => $afterWidget, 'before_title' => $beforeTitle, 'after_title' => $afterTitle));
        register_sidebar(array('name' => __('Footer 2','tfuse'), 'id' => 'footer-2', 'before_widget' => $beforeWidget, 'after_widget' => $afterWidget, 'before_title' => $beforeTitle, 'after_title' => $afterTitle));
        register_sidebar(array('name' => __('Footer 3','tfuse'), 'id' => 'footer-3', 'before_widget' => $beforeWidget, 'after_widget' => $afterWidget, 'before_title' => $beforeTitle, 'after_title' => $afterTitle));
        register_sidebar(array('name' => __('Footer 4','tfuse'), 'id' => 'footer-4', 'before_widget' => $beforeWidget, 'after_widget' => $afterWidget, 'before_title' => $beforeTitle, 'after_title' => $afterTitle));
    }
}

tf_sidebars_init();
