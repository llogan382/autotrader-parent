<?php
/**
 * Banner Slider
 * To override this shortcode in a child theme, copy this file to your child theme's
 * theme_config/extensions/shortcodes/shortcodes/ folder.
 *
 */

function tfuse_banner_slider($atts, $content) {
    global $slide;
    $slide = array();
    $i = 0;
    $uniq = rand(1, 400);
    extract(shortcode_atts(array('target' => ''), $atts));
    $get_banner_slider = do_shortcode($content);

    $output = '<div class="adv_left">
        <div class="banner_slider" id="banner_slider'.$uniq.'">';
            while (isset($slide['image'][$i])) {
                $getimage = new TF_GET_IMAGE();
                $img = $getimage->width(630)->height(250)->src($slide['image'][$i])->get_img();
                $output .= '<div class="banner_item"><a target="'.$target.'" href="'.$slide['url'][$i].'">'.$img.'</a></div>';
                $i++;
            }
        $output .= '</div>
        <div class="slider_pag" id="banner_slider_pag'.$uniq.'"></div>
        <script>
        jQuery(document).ready(function() {
            jQuery("#banner_slider'.$uniq.'").carouFredSel({
                pagination : "#banner_slider_pag'.$uniq.'",
                infinite: false,
                circular: true,
                auto: {
                    play: true,
                    timeoutDuration: 4000
                },
                width: "100%",
                scroll: {
                    items : 1,
                    fx : "crossfade",
                    pauseOnHover: true
                }
            });
        });
        </script>
    </div>';

    return $output;
}

$atts = array(
    'name' => __('Banner Slider','tfuse'),
    'desc' => __('Here comes some lorem ipsum description for the shortcode.','tfuse'),
    'category' => 4,
    'options' => array(
        array(
            'name' => __('Target','tfuse'),
            'desc' => __('Specifies where to open the linked shortcode','tfuse'),
            'id' => 'tf_shc_banner_slider_target',
            'value' => '',
            'options' => array(
                '_self' => __('In the same frame as it was clicked','tfuse'),
                '_blank' => __('In a new window or tab','tfuse'),
                '_parent' => __('In the parent frame','tfuse'),
                '_top' => __('In the full body of the window','tfuse'),
            ),
            'type' => 'select'
        ),
        array(
            'name' => __('Image','tfuse'),
            'desc' => __('Insert the sourse of image (630x250)','tfuse'),
            'id' => 'tf_shc_banner_slider_image',
            'value' => '',
            'properties' => array('class' => 'tf_shc_addable_0 tf_shc_addable'),
            'type' => 'text'
        ),
        array(
            'name' => __('URL','tfuse'),
            'desc' => __('URL of the site for image','tfuse'),
            'id' => 'tf_shc_banner_slider_url',
            'value' => '',
            'properties' => array('class' => 'tf_shc_addable_1 tf_shc_addable tf_shc_addable_last'),
            'type' => 'text'
        )

    )
);

tf_add_shortcode('banner_slider', 'tfuse_banner_slider', $atts);


function tfuse_bslide($atts, $content = null)
{
    global $slide;
    extract(shortcode_atts(array('image' => '', 'url' => ''), $atts));
    $slide['image'][] = $image;
    $slide['url'][] = $url;
}

$atts = array(
    'name' => __('Slide','tfuse'),
    'desc' => __('Here comes some lorem ipsum description for the box shortcode.','tfuse'),
    'category' => 3,
    'options' => array(
        array(
            'name' => __('Image','tfuse'),
            'desc' => '',
            'id' => 'tf_shc_bslide_image',
            'value' => 'image',
            'options' => array(
                'image' => __('Image','tfuse'),
                'text' => __('Text','tfuse')
            ),
            'type' => 'select'
        ),
        array(
            'name' => __('URL','tfuse'),
            'desc' => '',
            'id' => 'tf_shc_bslide_url',
            'value' => '',
            'type' => 'text'
        )
    )
);

add_shortcode('bslide', 'tfuse_bslide', $atts);