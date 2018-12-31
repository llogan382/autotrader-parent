<?php
/**
 * Vehicle Types
 *
 * To override this shortcode in a child theme, copy this file to your child theme's
 * theme_config/extensions/shortcodes/shortcodes/ folder.
 *
 */

function tfuse_vehicle_types($atts, $content) {
    /**
     * @var string $title
     * @var WP_Term[] $terms
     */
    global $slide;
    $slide = array();
    extract(shortcode_atts(array('title' => ''), $atts));
    do_shortcode($content);
    $taxonomy = TF_SEEK_HELPER::get_post_type().'_type';
    $args = array(
        'include' => $slide['category'],
        'orderby' => 'none',
    );
    $terms = get_terms($taxonomy,$args);
    $terms_list= array();
    $ids = array_flip($slide['category']);

    foreach ( $terms as $term ) {
        $item = array();
        $item['name'] = $term->name;
        $item['link'] = get_term_link( $term->term_id, $term->taxonomy );
        $item['count'] = $term->count;
        $item['image1'] = (isset($ids[$term->term_id]) && isset($slide['image1'][$ids[$term->term_id]]))
            ? $slide['image1'][$ids[$term->term_id]]
            : 0;
        $item['image2'] = (isset($ids[$term->term_id]) && isset($slide['image2'][$ids[$term->term_id]]))
            ? $slide['image2'][$ids[$term->term_id]]
            : 0;

        $terms_list[] = $item;
    }

    if ( empty( $terms_list ) ) {
        return '';
    }

    ob_start(); ?>
    <div class="car_types_list">
        <h2><?php echo tfuse_qtranslate( $title ); ?></h2>
        <ul>
            <?php foreach( $terms_list as $item ) : ?>
                <li class="type_hover" style="">
                    <a href="<?php echo $item['link']; ?>"
                       class="front"
                       style="background-image: url('<?php echo $item['image1']; ?>')"
                    >
                        <strong><?php echo $item['name']; ?></strong>
                        <em><?php echo $item['count']; ?> <?php _e( 'OFFERS', 'tfuse' ); ?></em>
                    </a>
                    <a href="<?php echo $item['link']; ?>"
                       class="back"
                       style="background-image: url('<?php echo $item['image2']; ?>')"
                    >
                        <strong><?php echo $item['name']; ?></strong>
                        <em><?php _e( 'View more', 'tfuse' ); ?></em>
                    </a>
                </li>
            <?php endforeach ?>
        </ul>
        <a href="<?php echo esc_url( home_url("/") ); ?>?s=~&tfseekfid=main_search" class="link_more"><?php _e( 'SEE ALL OUR OFFERS', 'tfuse' ); ?></a>
        <script>
            jQuery(document).ready(function () {
                jQuery(".type_hover").hover(function () {
                    jQuery(this).addClass("flip");
                }, function () {
                    jQuery(this).removeClass("flip");
                });
            })
        </script>
    </div>
    <?php return ob_get_clean();
}

$atts = array(
    'name' => __('Vehicle Types','tfuse'),
    'desc' => __('Here comes some lorem ipsum description for the shortcode.','tfuse'),
    'category' => 4,
    'options' => array(
        array(
            'name' => __('Title','tfuse'),
            'desc' => '',
            'id' => 'tf_shc_vehicle_types_title',
            'value' => 'Choose from a wide variety of vehicles',
            'type' => 'text'
        ),
        array(
            'name' => __('Vehicle Type','tfuse'),
            'desc' => __('Select the category of vehicle type','tfuse'),
            'id' => 'tf_shc_vehicle_types_category',
            'value' => '0',
            'properties' => array('class' => 'tf_shc_addable_0 tf_shc_addable'),
            'options' => tfuse_list_vehicle_types(),
            'type' => 'select'
        ),
        array(
            'name' => __('Image 1','tfuse'),
            'desc' => '',
            'id' => 'tf_shc_vehicle_types_image1',
            'value' => '',
            'properties' => array('class' => 'tf_shc_addable_1 tf_shc_addable'),
            'type' => 'text'
        ),
        array(
            'name' => __('Image 2','tfuse'),
            'desc' => '',
            'id' => 'tf_shc_vehicle_types_image2',
            'value' => '',
            'properties' => array('class' => 'tf_shc_addable_2 tf_shc_addable tf_shc_addable_last'),
            'type' => 'text'
        )

    )
);

tf_add_shortcode('vehicle_types', 'tfuse_vehicle_types', $atts);


function tfuse_vehicle_type($atts, $content = null)
{
    global $slide;
    extract(shortcode_atts(array('category' => '', 'image1' => '', 'image2' => ''), $atts));
    $slide['category'][] = $category;
    $slide['image1'][] = $image1;
    $slide['image2'][] = $image2;
}

$atts = array(
    'name' => __('Vehicle type','tfuse'),
    'desc' => __('Here comes some lorem ipsum description for the vehicle_type shortcode.','tfuse'),
    'category' => 3,
    'options' => array(
        array(
            'name' => __('Vehicle Type','tfuse'),
            'desc' => __('Select the category of vehicle type','tfuse'),
            'id' => 'tf_shc_vehicle_type_category',
            'value' => '0',
            'options' =>tfuse_list_vehicle_types(),
            'type' => 'select'
        ),
        array(
            'name' => __('Image1','tfuse'),
            'desc' => '',
            'id' => 'tf_shc_vehicle_type_image2',
            'value' => '',
            'type' => 'text'
        ),
        array(
            'name' => __('Image2','tfuse'),
            'desc' => '',
            'id' => 'tf_shc_vehicle_type_image2',
            'value' => '',
            'type' => 'text'
        )
    )
);

add_shortcode('vehicle_type', 'tfuse_vehicle_type', $atts);