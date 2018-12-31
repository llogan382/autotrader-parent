<?php
/**
 * Popular Brands
 * 
 * To override this shortcode in a child theme, copy this file to your child theme's
 * theme_config/extensions/shortcodes/shortcodes/ folder.
 *
 */

function tfuse_popular_brands($atts, $content = null) {
    extract(shortcode_atts(array('title' => '', 'multi' => '', 'link' => '', 'text_link' => ''), $atts));
    $arr_ids = explode(',',$multi);
    $args = array(
        'hide_empty'    => true,
        'parent'        => 0,
        'child_of'      => 0,
        'fields'        => 'all',
        'hierarchical'  => true,
        'include'       => $arr_ids
    );
    $terms = get_terms( TF_SEEK_HELPER::get_post_type().'_models', $args );
    // for order in initial order
    $term_arr_ord = array();
    foreach($arr_ids as $key=>$ord){
        foreach($terms as $unord){
            if($ord==$unord->term_id) {
                $term_arr_ord[$key] = $unord;
                continue;
            }
        }
    }
    $out = '';
    $out .= '<div class="brand_list">
        <h2>'.tfuse_qtranslate($title).'</h2>
        <ul>';
            foreach($term_arr_ord as $term){
                $logo = tfuse_options('maker_logo','',$term->term_id);
                if( $logo !='' ){
                    $out .='<li>
                        <a href="'.get_term_link($term->slug,$term->taxonomy).'"><img src="'.$logo.'" alt=""></a>
                    </li>';
                }
            }
        $out .= '</ul><a href="'.$link.'" class="link_more">'.tfuse_qtranslate($text_link).'</a></div>';

    return $out;
}

$atts = array(
    'name' => __('Popular Brands','tfuse'),
    'desc' => __('Here comes some lorem ipsum description for the popular_brands shortcode.','tfuse'),
    'category' => 6,
    'options' => array(
        array(
            'name' => __('Title','tfuse'),
            'desc' => __('Specifies the title','tfuse'),
            'id' => 'tf_shc_popular_brands_title',
            'value' => 'MOST POPULAR BRANDS:',
            'type' => 'text'
        ),
        array(
            'name' => __('Enter the brand','tfuse'),
            'desc' => __('Select the brand.','tfuse'),
            'id' => 'tf_shc_popular_brands_multi',
            'value' => '',
            'type' => 'multi',
            'subtype' => TF_SEEK_HELPER::get_post_type().'_models',
        ),
        array(
            'name' => __('Link','tfuse'),
            'desc' => __('Enter the link for all brands.','tfuse'),
            'id' => 'tf_shc_popular_brands_link',
            'value' => '#',
            'type' => 'text'
        ),
        array(
            'name' => __('Text Link','tfuse'),
            'desc' => __('Enter the text link for all brands.','tfuse'),
            'id' => 'tf_shc_popular_brands_text_link',
            'value' => 'View All Popular Brands',
            'type' => 'text'
        )
    )
);

tf_add_shortcode('popular_brands', 'tfuse_popular_brands', $atts);