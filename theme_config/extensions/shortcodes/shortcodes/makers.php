<?php

/**
 * Makers
 * 
 * To override this shortcode in a child theme, copy this file to your child theme's
 * theme_config/extensions/shortcodes/shortcodes/ folder.
 *
 */
function tfuse_makers($atts, $content = null) {
    extract(shortcode_atts(array('number' => '4', 'order_by' => 'name', 'order' => ''), $atts));
    $count = 0;
    $args = array(
        'orderby'       => $order_by,
        'order'         => $order,
        'hide_empty'    => true,
        'parent'        => 0,
        'child_of'      => 0,
        'number'        => '',
        'fields'        => 'all',
        'hierarchical'  => true,
    );
    $terms = get_terms( TF_SEEK_HELPER::get_post_type().'_models', $args );
    $html = '<div class="brand_list2">
        <ul>';
        foreach($terms as $term){
            $logo = tfuse_options('maker_logo','',$term->term_id);
            if( $logo !='' ){
                $count++;
                $link = get_term_link($term->slug,$term->taxonomy);
                $term_posts = tfuse_total_cat_post_count($term->term_id, $term->taxonomy, TF_SEEK_HELPER::get_post_type());
                if($term_posts==1) $name = __('offer','tfuse');
                else $name = __('offers','tfuse');
                $html .='<li><a href="'.$link.'" class="brand_logo"><img src="'.$logo.'" alt=""></a> <a href="'.$link.'">'.$term->name.' - '.$term_posts. ' ' .$name .'</a></li>';
                if($number == $count) break;
            }
        }
        $html .= '</ul>
    </div>';
    return $html;
}

$atts = array(
    'name' => __('Makers','tfuse'),
    'desc' => __('Here comes some lorem ipsum description for the makers shortcode.','tfuse'),
    'category' => 7,
    'options' => array(
        array(
            'name' => __('Number of makers','tfuse'),
            'desc' => __('Specifies the number of makers','tfuse'),
            'id' => 'tf_shc_makers_number',
            'value' => '4',
            'type' => 'text'
        ),
        array(
            'name' => __('Order By','tfuse'),
            'desc' => __('Select the order.','tfuse'),
            'id' => 'tf_shc_makers_order_by',
            'value' => 'name',
            'options' => array(
                'id' => 'Id',
                'count' => __('Count','tfuse'),
                'name' => __('Name','tfuse'),
                'slug' => __('Slug','tfuse')
            ),
            'type' => 'select'
        ),
        array(
            'name' => __('Order','tfuse'),
            'desc' => __('Select the order.','tfuse'),
            'id' => 'tf_shc_makers_order',
            'value' => 'ASC',
            'options' => array(
                'ASC' => __('ASC','tfuse'),
                'DESC' => __('DESC','tfuse'),
            ),
            'type' => 'select'
        )
    )
);

tf_add_shortcode('makers', 'tfuse_makers', $atts);