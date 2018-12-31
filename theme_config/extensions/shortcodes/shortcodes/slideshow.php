<?php
/**
 * Slide Show
 *
 * To override this shortcode in a child theme, copy this file to your child theme's
 * theme_config/extensions/shortcodes/shortcodes/ folder.
 *
 * Optional arguments:
 * width:
 * height:
 *
 * Slides documentation http://slidesjs.com/
 */

function tfuse_slideshow($atts, $content) {
    global $slide;
    $slide = array();
    extract(shortcode_atts(array('type_size' => ''), $atts));
    $get_slideshow = do_shortcode($content);
    $uniq = rand(1, 400);
    $i = 0;
    $output = '<div class="slider slider_'.$type_size.'">
        <div class="slider_container clearfix" id="slider'.$uniq.'">';
    while (isset($slide['type'][$i])) {
        if( $slide['type'][$i]=='image' )
        {
            if($type_size=='medium'){
                $width = 600;
                $height = 338;
            }
            elseif($type_size=='small'){
                $width = 430;
                $height = 242;
            }
            else{
                $width = 240;
                $height = 135;
            }
            $getimage = new TF_GET_IMAGE();
            $img = $getimage->width($width)->height($height)->src($slide['content'][$i])->get_img();
            $output .= '<div class="slider-item">'.$img .'</div>';
        }
        else $output .= ' <div class="slider-item"><div class="inner">'.$slide['content'][$i].'</div></div>';
        $i++;
    }
    $output .= '</div>
            <div class="slider_pagination" id="slider'.$uniq.'_pag"></div>
        </div>
        <script>
            jQuery(document).ready(function($) {
                $("#slider'.$uniq.'").carouFredSel({
                    pagination : "#slider'.$uniq.'_pag",
                    infinite: false,
                    auto: false,
                    height: "auto",
                    items: 1,
                    scroll: {
                        fx: "fade",
                        duration: 200
                    }
                });
            });
        </script>';
    return $output;
}

$atts = array(
    'name' => __('Slideshow', 'tfuse'),
    'desc' => __('Here comes some lorem ipsum description for the shortcode.','tfuse'),
    'category' => 4,
    'options' => array(
        array(
            'name' => __('Size Type','tfuse'),
            'desc' => __('Select size of the slideshow','tfuse'),
            'id' => 'tf_shc_slideshow_type_size',
            'value' => 'medium',
            'options' => array(
                'medium' => __('Medium (600x338)','tfuse'),
                'small' => __('Small (430x242)','tfuse'),
                'mini' => __('Mini (240x135)','tfuse'),
            ),
            'type' => 'select'
        ),
        array(
            'name' => __('Type','tfuse'),
            'desc' => '',
            'id' => 'tf_shc_slideshow_type',
            'value' => 'image',
            'options' => array(
                'image' => __('Image','tfuse'),
                'text' => __('Text','tfuse')
            ),
            'properties' => array('class' => 'tf_shc_addable_0 tf_shc_addable'),
            'type' => 'select'
        ),
        array(
            'name' => __('Content','tfuse'),
            'desc' => '',
            'id' => 'tf_shc_slideshow_content',
            'value' => '',
            'properties' => array('class' => 'tf_shc_addable_1 tf_shc_addable tf_shc_addable_last'),
            'type' => 'text'
        )

    )
);

tf_add_shortcode('slideshow', 'tfuse_slideshow', $atts);


function tfuse_slide($atts, $content = null)
{
    global $slide;
    extract(shortcode_atts(array('type' => '', 'content' => ''), $atts));
    $slide['type'][] = $type;
    $slide['content'][] = $content;
}

$atts = array(
    'name' => __('Slide','tfuse'),
    'desc' => __('Here comes some lorem ipsum description for the box shortcode.','tfuse'),
    'category' => 3,
    'options' => array(
        array(
            'name' => __('Type','tfuse'),
            'desc' => '',
            'id' => 'tf_shc_slide_type',
            'value' => 'image',
            'options' => array(
                'image' => __('Image','tfuse'),
                'text' => __('Text','tfuse')
            ),
            'type' => 'select'
        ),
        array(
            'name' => __('Content','tfuse'),
            'desc' => '',
            'id' => 'tf_shc_slide_content',
            'value' => '',
            'type' => 'text'
        )
    )
);

add_shortcode('slide', 'tfuse_slide', $atts);