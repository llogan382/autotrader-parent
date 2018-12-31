<?php
/**
 * Promo Offer
 *
 * To override this shortcode in a child theme, copy this file to your child theme's
 * theme_config/extensions/shortcodes/shortcodes/ folder.
 *
 */

function tfuse_promo_offer($atts, $content = null)
{
    extract( shortcode_atts(array('title' => '','vehicle' => ''), $atts) );
    $link = get_permalink($vehicle);
    $vehicle_post = get_post($vehicle);
    $price = TF_SEEK_HELPER::get_post_option('property_price', 0, $vehicle);
    $image = new TF_GET_IMAGE();
    $img = $image->width(430)->height(280)->src(tfuse_get_vehicle_thumbnail($vehicle))->get_img();

    $price_number_str   = apply_filters( 'tfuse_price_number_format', number_format($price,0,'', ','), $price );
    $price_symbol_str   = '<span class="symbol_price_left">'.TF_SEEK_HELPER::get_option("seek_property_currency_symbol","$").'</span>';
    $price_symbol_pos   = TF_SEEK_HELPER::get_option('seek_property_currency_symbol_pos', 0);
    $out = tfuse_symbol_position($price_number_str, $price_symbol_str, $price_symbol_pos, '', true);

    $html = '<div class="week_offer">
            <h2>'.tfuse_qtranslate($title).'</h2>
            <div class="offer_box">
                <div class="offer_image"><a href="'.$link.'">'.$img.'</a></div>
                <div class="offer_text">
                    <h3><a href="'.$link.'">'.$vehicle_post->post_title.'</a></h3>
                    <div class="offer_price">' . $out .'</div>
                    <div class="offer_descr">'.$vehicle_post->post_excerpt.'</div></div>
                <div class="link_more"><a href="'.$link.'">'.__('View More Details','tfuse').'</a></div></div>
        </div>';

    return $html;
}
$atts = array(
    'name' => __('Promo Offer','tfuse'),
    'desc' => __('Here comes some lorem ipsum description for the box shortcode.','tfuse'),
    'category' => 7,
    'options' => array(
        array(
            'name' => __('Title','tfuse'),
            'desc' => __('Text to display above the box','tfuse'),
            'id' => 'tf_shc_promo_offer_title',
            'value' => 'OFFER OF THE WEEK',
            'type' => 'text'
        ),
        array(
            'name' => __('Select Vehicle','tfuse'),
            'desc' => __('Select the vehicle for promo offer','tfuse'),
            'id' => 'tf_shc_promo_offer_vehicle',
            'value' => '0',
            'options' => tfuse_list_vehicle_post(),
            'type' => 'select'
        )
    )
);

tf_add_shortcode('promo_offer', 'tfuse_promo_offer', $atts);