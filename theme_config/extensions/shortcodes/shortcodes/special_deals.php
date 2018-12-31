<?php
/**
 * Special Deals
 *
 * To override this shortcode in a child theme, copy this file to your child theme's
 * theme_config/extensions/shortcodes/shortcodes/ folder.
 *
 */

function tfuse_special_deals($atts, $content = null)
{
    extract( shortcode_atts(array('title' => '','vehicles' => '','link' => '' ), $atts) );
    $html = '';
    $offers = explode(',',$vehicles);
    $args = array(
        'posts_per_page'  => -1,
        'orderby'         => 'post_date',
        'order'           => 'DESC',
        'include'         => $offers,
        'post_type'       => TF_SEEK_HELPER::get_post_type(),
        'post_status'     => 'publish',
    );
    $posts_array = get_posts( $args );

    $html .= '<div class="special_offers">
        <h2>'.tfuse_qtranslate($title).'</h2>
        <div id="special_offers">';
        $price_symbol = TF_SEEK_HELPER::get_option("seek_property_currency_symbol","$");
        $fuel_symbol = TF_SEEK_HELPER::get_option("seek_property_consumption_symbol","MPG");
        foreach($posts_array as $vehicle){
            $vehicle_link = get_permalink($vehicle);
            $image = new TF_GET_IMAGE();
            $img = $image->width(310)->height(134)->src(tfuse_get_vehicle_thumbnail($vehicle->ID))->get_img();
            $price = TF_SEEK_HELPER::get_post_option('property_price', 0, $vehicle->ID);
            $fuel = apply_filters('tfuse_fuel_number_format', TF_SEEK_HELPER::get_post_option('property_consumption', 0, $vehicle->ID));
            $date = '01/'.TF_SEEK_HELPER::get_post_option('property_year', '11/2010', $vehicle->ID);
            $date = date("d/m/Y", strtotime($date));
            $date = strtoupper(date("M Y",strtotime($date)));

            $fuel_out = apply_filters('tfuse_power_fuel_symbol_position', tfuse_symbol_position($fuel, $fuel_symbol, 1, ' ', true), $fuel, $fuel_symbol);

            $price_number_str   = apply_filters( 'tfuse_price_number_format', number_format($price,0,'', ','), $price );
            $price_symbol_str   = '<span class="symbol_price_left">' . $price_symbol .'</span>';
            $price_symbol_pos   = TF_SEEK_HELPER::get_option('seek_property_currency_symbol_pos', 0);
            $out = tfuse_symbol_position($price_number_str, $price_symbol_str, $price_symbol_pos, '', true);

            $html.='<div class="special_item">
                <div class="special_image"><a href="'.$vehicle_link.'">'.$img.'</a></div>
                <div class="special_text">
                    <h3><a href="'.$vehicle_link.'">'.$vehicle->post_title.'</a></h3>
                    <div class="info_row"><span>'.__('FIRST REG:','tfuse').'</span> '.$date.'</div>
                    <div class="info_row"><span>'.__('FUEL CONS:','tfuse').'</span> '. $fuel_out .'</div>
                    <div class="info_row"><span>'.__('MILEAGE','tfuse').'</span> '
                   . apply_filters(
                       'tfuse_mileage_number_format',
                       number_format( TF_SEEK_HELPER::get_post_option( 'property_mileage', 1, $vehicle->ID ), 0, '', ',' ),
                       TF_SEEK_HELPER::get_post_option( 'property_mileage', 1, $vehicle->ID )
                   )
                   .'</div>
                    <div class="special_price">' . $out . '</div>
                </div>
            </div>';
        }

        $html .= '</div><a class="prev" id="special_offers_prev" href="#"></a><a class="next" id="special_offers_next" href="#"></a>
        <div class="link_more"><a href="'.$link.'">'.__('View All Special Offers','tfuse').'</a></div>
        <script>
        jQuery(document).ready(function() {
            function carSpecicalInit() {
                var car_specical = jQuery("#special_offers");
                            car_specical.carouFredSel({
                                prev : "#special_offers_prev",
                                next : "#special_offers_next",
                                infinite: true,
                                circular: false,
                                auto: false,
                                width: "100%",
                                direction: "down",
                                scroll: {
                                    items : 1
                                }
                            });
                        }
            jQuery(window).load(function() {
                carSpecicalInit();
            });
            var resizeTimer;
            jQuery(window).resize(function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(carSpecicalInit, 100);
            });
        });
        </script>
    </div>';

    return $html;
}

$atts = array(
    'name' => __('Special Deals','tfuse'),
    'desc' => __('Here comes some lorem ipsum description for the box shortcode.','tfuse'),
    'category' => 7,
    'options' => array(
        array(
            'name' => __('Title','tfuse'),
            'desc' => __('Text to display above the box','tfuse'),
            'id' => 'tf_shc_special_deals_title',
            'value' => 'SPECIAL DEALS',
            'type' => 'text'
        ),
        array(
            'name' => __('Specify Vehicle','tfuse'),
            'desc' => __('Specify the vehicles for special deals','tfuse'),
            'id' => 'tf_shc_special_deals_vehicles',
            'value' => '',
            'type' => 'multi',
            'subtype' => TF_SEEK_HELPER::get_post_type()
        ),
        array(
            'name' => __('Link','tfuse'),
            'desc' => __('Link for all offers','tfuse'),
            'id' => 'tf_shc_special_deals_link',
            'value' => '#',
            'type' => 'text'
        ),
    )
);

tf_add_shortcode('special_deals', 'tfuse_special_deals', $atts);