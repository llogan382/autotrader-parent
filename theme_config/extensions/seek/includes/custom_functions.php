<?php

if (!defined('TFUSE'))
    exit('Direct access forbidden.');

// Include extra features
require_once 'post_attachmets/POST_ATTACHMENTS.php';

// Other user's custom functions and classes
//

/**
 * Ajax action for input-text-location autocompleter
 */
function tf_action_ajax_seek_location_autocomplete() {
    global $wpdb, $TFUSE;

    $items_options  = TF_SEEK_HELPER::get_items_options();

    if( !( $TFUSE->request->isset_POST('action') || $TFUSE->request->isset_POST('tf_action') )){ // Just to be sure
        die();
    }

    if(!$TFUSE->request->isset_POST('item_id')){
        die();
    }
    if(!$item_id = trim($TFUSE->request->POST('item_id'))){
        die();
    }
    if(!isset($items_options[$item_id])){
        die();
    }
    if(!isset($items_options[$item_id]['sql_generator_options']['search_on']) || $items_options[$item_id]['sql_generator_options']['search_on']!='taxonomy'){
        die();
    }
    if(!isset($items_options[$item_id]['sql_generator_options']['search_on_id'])){
        die();
    }

    $item_options = $items_options[$item_id];

    // search term (term%)
    if(!$TFUSE->request->isset_POST('term')){
        die();
    }
    if(!($term = trim($TFUSE->request->POST('term')))){
        die();
    }
    // Replace multiple spaces (no regexp because of utf8)
    while (strpos('  ', $term)) $term = str_replace('  ', ' ', $term);
    $term = TF_SEEK_HELPER::safe_sql_like($term);
    // Replace spaces with %
    $term = str_replace(' ', '%', $term);
    // Add % to the end to match like prefix
    $term .= '%';

    // prepare WHERE to excule allready autocompleted words (ex: Africa, Moldova, Los Angeles,)
    $without_existing_terms = "";
    if($TFUSE->request->isset_POST('value') && $values = (string)$TFUSE->request->POST('value')){

        $exploded   = explode(',', $values);
        $counter    = 0;
        if( sizeof($exploded) ){ // exclude currently typing word (last) if there is more than one words
            array_pop($exploded);
        }
        if( sizeof($exploded) ){
            $sqla = array();

            foreach( $exploded as $search_item ){

                $search_item = trim($search_item);

                if(!$search_item) continue;

                $counter++;

                $sqla[]  = $wpdb->prepare("tte.name != %s", $search_item);
            }

            if($counter){
                $without_existing_terms = " AND (".implode(' AND ', $sqla).")";
            }
        }
    }

    $sql = "SELECT
        DISTINCT(tte.name) AS name
            FROM " . (TF_SEEK_HELPER::get_db_table_name()) . " as lep
        INNER JOIN " . $wpdb->prefix . "posts AS p               ON p.ID                 = lep.post_id
        LEFT  JOIN " . $wpdb->prefix . "term_relationships AS tr ON tr.object_id         = lep.post_id
        LEFT  JOIN " . $wpdb->prefix . "term_taxonomy AS tt      ON tt.term_taxonomy_id  = tr.term_taxonomy_id
        LEFT  JOIN " . $wpdb->prefix . "terms AS tte             ON tte.term_id          = tr.term_taxonomy_id
            WHERE p.post_status = 'publish'
                AND tte.name    != ''
                AND tt.taxonomy = ".$wpdb->prepare('%s', $item_options['sql_generator_options']['search_on_id'])."
                AND tte.name LIKE N'".$term."'
                ".$without_existing_terms."
        GROUP BY tte.term_id
        ORDER BY tte.name ASC
        LIMIT 10";
    $rows       = $wpdb->get_results($sql, ARRAY_A);

    $result     = array();

    if( sizeof($rows) ){
        foreach($rows as $row){
            $result[] = htmlentities($row['name'], ENT_QUOTES, 'UTF-8');
        }
    }

    echo json_encode( $result );
    die();
}

class TF_SEEK_CUSTOM_FUNCTIONS {
    public static function html_paging($params = array()){
        ?>

        <div class="pages">
            <span class="manage_title"><?php _e('Page:','tfuse'); ?> &nbsp;<strong><?php print($params['curr_page'] ? $params['curr_page'] : 1); ?> <?php _e('of','tfuse'); ?> <?php print( $params['max_pages'] ? $params['max_pages'] : 1); ?></strong></span>
            <?php if($params['curr_page']-1 < 1): ?>
                <span class="link_prev">Previous</span>
            <?php else: ?>
                <a href="<?php print(home_url('/').TF_SEEK_HELPER::get_qstring_without(array( TF_SEEK_HELPER::get_search_parameter('page') )).(TF_SEEK_HELPER::get_search_parameter('page')).'='.($params['curr_page']-1)); ?>" class="link_prev"><?php _e('Previous','tfuse'); ?></a>
            <?php endif; ?>
            <?php if($params['curr_page']+1 > $params['max_pages']): ?>
                <span class="link_next">Next</span>
            <?php else: ?>
                <a href="<?php print(home_url('/').TF_SEEK_HELPER::get_qstring_without(array( TF_SEEK_HELPER::get_search_parameter('page') )).(TF_SEEK_HELPER::get_search_parameter('page')).'='.($params['curr_page']+1)); ?>" class="link_next"><?php _e('Next','tfuse'); ?></a>
            <?php endif; ?>
        </div>

        <?php
    }

    public static function html_jump_to_page($params = array()){
        ?>

        <div class="pages_jump">
            <span class="manage_title"><?php _e('Jump to page:','tfuse'); ?></span>
            <form action="<?php print(home_url('/')); ?>" method="get">
                <input type="hidden" name="s" value="~">
                <?php
                TF_SEEK_HELPER::print_all_hidden(array( TF_SEEK_HELPER::get_search_parameter('page') ));
                ?>
                <?php
                $step = 14;
                if($params['curr_page']+$step > $params['max_pages']){
                    $jump_to = ($params['max_pages'] ? $params['max_pages'] : 1);
                } else {
                    $jump_to = $params['curr_page']+$step;
                }
                ?>
                <input type="text" name="<?php print( TF_SEEK_HELPER::get_search_parameter('page') ); ?>" value="<?php print($jump_to); ?>" class="inputSmall"><input type="submit" class="btn-arrow" value="Go">
            </form>
        </div>

        <?php
    }

    public static function orderby($options, $params = array()){
        ?>
        <form action="<?php print(home_url('/')); ?>" method="get" class="form_sort">
            <input type="hidden" name="s" value="~">
            <?php
            TF_SEEK_HELPER::print_all_hidden(array( TF_SEEK_HELPER::get_search_parameter('page'), TF_SEEK_HELPER::get_search_parameter('orderby') ));
            ?>
            <span class="manage_title"><?php _e('Sort by:','tfuse'); ?></span>
            <select class="select_styled white_select" name="<?php print( TF_SEEK_HELPER::get_search_parameter('orderby') ); ?>" id="<?php print($params['select_id']); ?>" onchange="jQuery(this).closest('form').submit();">
                <?php $input_value = TF_SEEK_HELPER::get_input_value( TF_SEEK_HELPER::get_search_parameter('orderby') ); ?>
                <?php foreach($options as $key=>$val): ?>
                    <option value="<?php print(esc_attr($key)); ?>" <?php print($input_value==$key ? 'selected' : ''); ?> ><?php print(esc_attr($options[$key]['label'])); ?></option>
                <?php endforeach; ?>
            </select>
        </form>
        <?php
    }
}

function _filter_tf_seek_post_option_save ($value,$params)
{
    if ( ($params['options']['valtype'] == 'date'))
    {
        $date = explode('/', $value);
        if(is_array($date) && sizeof($date) == 2 && checkdate($date[0], 1, $date[1])){
            $value  = $date[1] . '-' . $date[0] . '-01';
        }else{
            $value = '0000-00-00';
        }
    }

    return $value;
}
add_filter('tf_seek_post_option_save', '_filter_tf_seek_post_option_save',10,2);


if (!function_exists('tfuse_add_additional_variables_in_admin_js')) :
    /**
     *
     *
     * To override tfuse_add_additional_variables_in_admin_js() in a child theme, add your own tfuse_add_additional_variables_in_admin_js()
     * to your child theme's theme_config/theme_includes/THEME_FUNCTIONS.php file.
     */

    function tfuse_add_additional_variables_in_admin_js()
    {
        global $TFUSE;
        $TFUSE->include->js_enq('seek_post_type', TF_SEEK_HELPER::get_post_type());
        $TFUSE->include->js_enq('currency_symbol', TF_SEEK_HELPER::get_option('currency_symbol', '$'));
        $TFUSE->include->js_enq('regular_price_suffix', TF_SEEK_HELPER::get_option('seek_property_regular_price_suffix', __(' /day', 'tfuse')));
    }
    add_action('admin_footer', 'tfuse_add_additional_variables_in_admin_js');

endif;

if (!function_exists('tfuse_seek_save_non_user_addable_terms')){
    function tfuse_seek_save_non_user_addable_terms($tfuse_post_options, $post_id){
        if (get_post_type($post_id) != TF_SEEK_HELPER::get_post_type())
            return;
        $optionTaxonomies = array( TF_THEME_PREFIX . '_vehicle_type' => TF_SEEK_HELPER::get_post_type() . '_type', TF_THEME_PREFIX . '_fuel_type' => TF_SEEK_HELPER::get_post_type() . '_fuel_type', TF_THEME_PREFIX . '_gearbox_type' => TF_SEEK_HELPER::get_post_type() . '_gearboxes', TF_THEME_PREFIX . '_status' => TF_SEEK_HELPER::get_post_type() . '_statuses', TF_THEME_PREFIX . '_color' => TF_SEEK_HELPER::get_post_type() . '_colors' );
        global $TFUSE;
        foreach($TFUSE->request->POST() as $key=>$value){
            if(isset($optionTaxonomies[$key]))
                wp_set_post_terms( $post_id, array($value), $optionTaxonomies[$key], false );
        }
    }
    add_action('tf_save_post_options_extra_processing', 'tfuse_seek_save_non_user_addable_terms', 9, 2);
}

if (!function_exists('tfuse_seek_non_public_meta_boxes')){
    if(is_admin()){
        function tfuse_seek_non_public_meta_boxes(){
            remove_meta_box('tagsdiv-' . TF_SEEK_HELPER::get_post_type() . '_type', TF_SEEK_HELPER::get_post_type(), 'normal');
            remove_meta_box('tagsdiv-' . TF_SEEK_HELPER::get_post_type() . '_fuel_type', TF_SEEK_HELPER::get_post_type(), 'normal');
            remove_meta_box('tagsdiv-' . TF_SEEK_HELPER::get_post_type() . '_gearboxes', TF_SEEK_HELPER::get_post_type(), 'normal');
            remove_meta_box('tagsdiv-' . TF_SEEK_HELPER::get_post_type() . '_statuses', TF_SEEK_HELPER::get_post_type(), 'normal');
            remove_meta_box('tagsdiv-' . TF_SEEK_HELPER::get_post_type() . '_colors', TF_SEEK_HELPER::get_post_type(), 'normal');
        }
        add_action( 'admin_menu', 'tfuse_seek_non_public_meta_boxes' );
    }
}

function tfuse_seek_change_post_type_capabilities(){

    $seek_post_type = get_post_type_object(TF_SEEK_HELPER::get_post_type());

    $args = array(
        'labels'            => array(
            'name'          => $seek_post_type->labels->name,
            'singular_name' => $seek_post_type->labels->singular_name,
            'add_new_item'  => $seek_post_type->labels->add_new_item,
            'not_found'     => $seek_post_type->labels->not_found,
            'all_items'     => $seek_post_type->labels->all_items,
        ),
        'public'            => true,
        'has_archive'       => true,
        'publicly_queryable'=> true,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'rewrite'      => array(
            'slug' => apply_filters('tf_seek_post_type_slug', TF_SEEK_HELPER::get_post_type())
        ),
        'query_var'         => true,
        'menu_position'     => 5,
        'capability_type' => array('seek_post', 'seek_posts'),
        'capabilities' => array(
            'edit_post' => 'edit_seek_post',
            'read_post' => 'read_seek_post',
            'delete_post' => 'delete_seek_post',
            'edit_posts' => 'edit_seek_posts',
            'edit_others_posts' => 'edit_others_seek_posts',
            'publish_posts' => 'publish_seek_posts',
            'read_private_posts' => 'read_private_seek_posts',
            'delete_posts' => 'delete_seek_posts',
            'delete_private_posts'  => 'delete_private_seek_posts',
            'delete_published_posts' => 'delete_published_seek_posts',
            'delete_others_posts' => 'delete_others_seek_posts',
            'edit_private_posts'  => 'edit_private_seek_posts',
            'edit_published_posts' => 'edit_published_seek_posts'
        )
    );

    register_post_type(TF_SEEK_HELPER::get_post_type(), $args);
}
add_action('init', 'tfuse_seek_change_post_type_capabilities', 9);

function tfuse_seek_allow_image_upload_for_sellers(){
    register_post_type('tfuse_gallery_group', array(
        'labels' => array(
            'name' => __('ThemeFuse Gallery Group', 'tfuse'),
        ),
        'public' => true,
        'show_ui' => false,
        'capability_type' => 'post',
        'hierarchical' => false,
        'rewrite' => false,
        'supports' => array('title', 'editor'),
        'query_var' => false,
        'can_export' => true,
        'show_in_nav_menus' => false,
        'capabilities' => array(
            'edit_post' => 'edit_gallery_group_post',
        )
    ));

    register_post_type('tfuse_download_group', array(
        'labels' => array(
            'name' => __('ThemeFuse Download Group', 'tfuse'),
        ),
        'public' => true,
        'show_ui' => false,
        'capability_type' => 'post',
        'hierarchical' => false,
        'rewrite' => false,
        'supports' => array('title', 'editor'),
        'query_var' => false,
        'can_export' => true,
        'show_in_nav_menus' => false,
        'capabilities' => array(
            'edit_post' => 'edit_download_group_post',
        )
    ));
}
add_action('init', 'tfuse_seek_allow_image_upload_for_sellers', 11);

function tfuse_seek_set_capabilities_for_seek_post_type()
{

    $roles = array('administrator', 'editor');
    foreach($roles as $role){
        $temp = get_role($role);

        $temp->add_cap('edit_gallery_group_post');
        $temp->add_cap('edit_download_group_post');

        $temp->add_cap('assign_seek_terms');

        $temp->add_cap('edit_seek_post');
        $temp->add_cap('read_seek_post');
        $temp->add_cap('delete_seek_post');
        $temp->add_cap('edit_seek_posts');
        $temp->add_cap('edit_others_seek_posts');
        $temp->add_cap('publish_seek_posts');
        $temp->add_cap('read_private_seek_posts');
        $temp->add_cap('delete_seek_posts');
        $temp->add_cap('delete_private_seek_posts');
        $temp->add_cap('delete_published_seek_posts');
        $temp->add_cap('delete_others_seek_posts');
        $temp->add_cap('edit_private_seek_posts');
        $temp->add_cap('edit_published_seek_posts');

    }

    $role = get_role('author');

    $role->add_cap('edit_gallery_group_post');
    $role->add_cap('edit_download_group_post');

    $role->add_cap('assign_seek_terms');

    $role->add_cap('edit_seek_posts');
    $role->add_cap('publish_seek_posts');
    $role->add_cap('delete_seek_posts');
    $role->add_cap('delete_published_seek_posts');
    $role->add_cap('edit_published_seek_posts');


    $role = get_role('contributor');

    $role->add_cap('upload_files');
    $role->add_cap('assign_seek_terms');

    $role->add_cap('edit_gallery_group_post');
    $role->add_cap('edit_download_group_post');

    $role->add_cap('assign_seek_terms');

    $role->add_cap('edit_seek_posts');
    $role->add_cap('edit_published_seek_posts');

    add_role( 'seller', 'Seller', array(
        'read'                           => true,
        'upload_files'                   => true,

        'assign_seek_terms'              => true,

        'create_product'                 => true,
        'edit_post'                      => true,
        'edit_gallery_group_post'        => true,
        'edit_download_group_post'       => true,


        'edit_seek_post'                 => false,
        'read_seek_post'                 => false,
        'delete_seek_post'               => false,
        'edit_seek_posts'                => true,
        'edit_others_seek_posts'         => false,
        'publish_seek_posts'             => true,
        'read_private_seek_posts'        => false,
        'delete_seek_posts'              => true,
        'delete_private_seek_posts'      => false,
        'delete_published_seek_posts'    => true,
        'delete_others_seek_posts'       => false,
        'edit_private_seek_posts'        => false,
        'edit_published_seek_posts'      => true
    ) );

}
add_action( 'init', 'tfuse_seek_set_capabilities_for_seek_post_type');

function tfuse_seek_map_meta_cap( $caps, $cap, $user_id, $args ) {

    if($cap == 'edit_seek_post' || $cap == 'delete_seek_post'){
        $post = get_post( $args[0] );

        if ( $user_id == $post->post_author ){
            $caps = array();
        }
    }

    return $caps;
}
add_filter( 'map_meta_cap', 'tfuse_seek_map_meta_cap', 10, 4 );

if ( !function_exists('tfuse_create_custom_post_types') ) :
    /**
     * Retrieve the requested data of the author of the current post.
     *
     * @param array $fields first_name,last_name,email,url,aim,yim,jabber,facebook,twitter etc.
     * @return null|array The author's spefified fields from the current author's DB object.
     */
    function tfuse_create_custom_post_types()
    {
        // TESTIMONIALS
        $labels = array(
            'name' => __('Testimonials','tfuse'),
            'singular_name' => __('Testimonial','tfuse'),
            'add_new' => __('Add New', 'tfuse'),
            'add_new_item' => __('Add New Testimonial', 'tfuse'),
            'edit_item' => __('Edit Testimonial', 'tfuse'),
            'new_item' => __('New Testimonial', 'tfuse'),
            'all_items' => __('All Testimonials', 'tfuse'),
            'view_item' => __('View Testimonial', 'tfuse'),
            'search_items' => __('Search Testimonials', 'tfuse'),
            'not_found' =>  __('Nothing found', 'tfuse'),
            'not_found_in_trash' => __('Nothing found in Trash', 'tfuse'),
            'parent_item_colon' => ''
        );

        $args = array(
            'labels' => $labels,
            'public' => false,
            'publicly_queryable' => false,
            'show_ui' => true,
            'query_var' => true,
            //'menu_icon' => get_template_directory_uri() . '/images/icons/testimonials.png',
            'rewrite' => true,
            'menu_position' => 5,
            'supports' => array('title','editor')
        );

        register_post_type( 'testimonials' , $args );

        $labels = array(
            'name' => __('Reservation','tfuse'),
            'singular_name' => __('Reservation','tfuse'),
            'add_new' => __('Add New', 'tfuse'),
            'add_new_item' => __('Add New Reservation', 'tfuse'),
            'edit_item' => __('Edit Reservation info', 'tfuse'),
            'new_item' => __('New Reservation', 'tfuse'),
            'all_items' => __('All Reservations', 'tfuse'),
            'view_item' => __('View Reservation info', 'tfuse'),
            'parent_item_colon' => ''
        );

        $reservationform_rewrite = apply_filters('tfuse_reservationform_rewrite','reservationform_list');

        $res_args = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => false,
            'show_ui' => false,
            'query_var' => true,
            'exclude_from_search'=>true,
            'has_archive' => true,
            'rewrite' => array('slug'=> $reservationform_rewrite),
            'menu_position' => 6,
            'supports' => array(null)
        );

        register_taxonomy('reservations', array('reservations'), array(
            'hierarchical' => true,
            'labels' => array(
                'name' => __('Reservation Forms', 'tfuse'),
                'singular_name' => __('Reservation Form', 'tfuse'),
                'add_new_item' => __('Add New Reservation Form', 'tfuse'),
            ),
            'show_ui' => false,
            'query_var' => true,
            'show_in_nav_menus' => false,
            'rewrite' => array('slug' => $reservationform_rewrite)
        ));

        register_post_type( 'reservations' , $res_args );

        // Services
        $labels = array(
            'name' => __('Services','tfuse'),
            'singular_name' => __('Service','tfuse'),
            'add_new' => __('Add New', 'tfuse'),
            'add_new_item' => __('Add New', 'tfuse'),
            'edit_item' => __('Edit Service info', 'tfuse'),
            'new_item' => __('New Service', 'tfuse'),
            'all_items' => __('All Services', 'tfuse'),
            'view_item' => __('View Service info', 'tfuse'),
            'search_items' => __('Search Service', 'tfuse'),
            'not_found' =>  __('Nothing found', 'tfuse'),
            'not_found_in_trash' => __('Nothing found in Trash', 'tfuse'),
            'parent_item_colon' => ''
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'query_var' => true,
            'has_archive' => true,
            'rewrite' => array('slug'=> apply_filters('tfuse_all_service_list_rewrite', 'all-services')),
            'menu_position' => 5,
            'supports' => array('title','editor','excerpt','comments')
        );

        // Add new taxonomy, make it hierarchical (like categories)
        $labels = array(
            'name' => __('Categories','tfuse'),
            'singular_name' => __('Category','tfuse'),
            'search_items' => __('Search Categories','tfuse'),
            'all_items' => __('All Categories','tfuse'),
            'parent_item' => __('Parent Category','tfuse'),
            'parent_item_colon' => __('Parent Category:','tfuse'),
            'edit_item' => __('Edit Category','tfuse'),
            'update_item' => __('Update Category','tfuse'),
            'add_new_item' => __('Add New Category','tfuse'),
            'new_item_name' => __('New Category Name','tfuse')
        );

        register_taxonomy('services', array('service'), array(
            'hierarchical' => true,
            'labels' => $labels,
            'show_ui' => true,
            'query_var' => true,
            'rewrite' => array('slug' => apply_filters('tfuse_service_list_rewrite', 'services-list'))
        ));
        register_post_type( 'service' , $args );

        // FAQ
        $labels = array(
            'name' => __('FAQ','tfuse'),
            'singular_name' => __('FAQ','tfuse'),
            'add_new' => __('Add New', 'tfuse'),
            'add_new_item' => __('Add New FAQ', 'tfuse'),
            'edit_item' => __('Edit FAQ', 'tfuse'),
            'new_item' => __('New FAQ', 'tfuse'),
            'all_items' => __('All FAQs', 'tfuse'),
            'view_item' => __('View FAQ', 'tfuse'),
            'search_items' => __('Search FAQ', 'tfuse'),
            'not_found' =>  __('Nothing found', 'tfuse'),
            'not_found_in_trash' => __('Nothing found in Trash', 'tfuse'),
            'parent_item_colon' => ''
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'query_var' => true,
            'has_archive' => true,
            'menu_position' => 5,
            'supports' => array('title','editor', 'excerpt', 'comments')
        );

        // Add new taxonomy, make it hierarchical (like categories)
        $labels = array(
            'name' => __('Categories','tfuse'),
            'singular_name' => __('Category','tfuse'),
            'search_items' => __('Search Categories','tfuse'),
            'all_items' => __('All Categories','tfuse'),
            'parent_item' => __('Parent Category','tfuse'),
            'parent_item_colon' => __('Parent Category:','tfuse'),
            'edit_item' => __('Edit Category','tfuse'),
            'update_item' => __('Update Category','tfuse'),
            'add_new_item' => __('Add New Category','tfuse'),
            'new_item_name' => __('New Category Name','tfuse')
        );

        register_taxonomy('faqs', array('faq'), array(
            'hierarchical' => true,
            'labels' => $labels,
            'show_ui' => true,
            'query_var' => true,
            'rewrite' => array('slug' => apply_filters('tfuse_faq_list_rewrite', 'faq-list'))
        ));
        register_post_type( 'faq' , $args );



    }
    tfuse_create_custom_post_types();

endif;

add_action('category_add_form', 'tfuse_taxonomy_redirect_note');



if (!function_exists('tfuse_taxonomy_redirect_note')) :
    /**
     *
     *
     * To override tfuse_taxonomy_redirect_note() in a child theme, add your own tfuse_taxonomy_redirect_note()
     * to your child theme's file.
     */
    function tfuse_taxonomy_redirect_note($taxonomy){
        echo '<p><strong>Note:</strong> More options are available after you add the '.$taxonomy.'. <br />
        Click on the Edit button under the '.$taxonomy.' name.</p>';
    }

endif;