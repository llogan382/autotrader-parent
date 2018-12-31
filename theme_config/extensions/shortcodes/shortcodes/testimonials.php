<?php
/**
 * Testimonials
 *
 * To override this shortcode in a child theme, copy this file to your child theme's
 * theme_config/extensions/shortcodes/shortcodes/ folder.
 *
 * Optional arguments:
 * title:
 * order: RAND, ASC, DESC
 */

function tfuse_testimonials($atts, $content = null) {
    global $testimonials_uniq;
    extract(shortcode_atts(array('order' => 'RAND','before' => false), $atts));
    $slide = $nav = $single = '';
    $testimonials_uniq = rand(800, 900);

    if (!empty($order) && ($order == 'ASC' || $order == 'DESC'))
        $order = '&order=' . $order;
    else
        $order = '&orderby=rand';

    $posts = get_posts('post_type=testimonials&posts_per_page=-1' . $order);
    $k = 0;
    foreach($posts as $item){
        $k++;
        $slide .= '<div class="slider-item"><div class="quote-text">' . apply_filters('the_content',$item->post_content) . '</div><div class="quote-author">' . $item->post_title . '</div></div>';
    }

    if ($k > 1) {
        $nav = '<a class="prev" id="testimonials'.$testimonials_uniq.'_prev" href="#"><span>'.__('Prev','tfuse').'</span></a>
                <a class="next" id="testimonials'.$testimonials_uniq.'_next" href="#"><span>'.__('Next','tfuse').'</span></a>';
    }
    else
        $single = ' style="display: block"';

    if($before=='true') $before_slide='<div class="footer_testimonials">';
    else $before_slide='<div class="slider slider_quotes">';

    $output = $before_slide.'
        <div id="testimonials'.$testimonials_uniq.'" class="slider_container clearfix" '. $single . '>' . $slide . '</div>' . $nav . '</div>
    <script>
        jQuery(document).ready(function($) {
            $("#testimonials'.$testimonials_uniq.'").carouFredSel({
                next : "#testimonials'.$testimonials_uniq.'_next",
                prev : "#testimonials'.$testimonials_uniq.'_prev",
                infinite: false,
                items: 1,
                auto: {
                        play: true,
						timeoutDuration: 4000
					   },
                scroll: {
                            items : 1,
							fx: "crossfade",
							easing: "linear",
							pauseOnHover: true,
                            duration: 300
                        }
            });
        });
    </script>';

    return $output;
}

$atts = array(
    'name' => __('Testimonials','tfuse'),
    'desc' => __('Here comes some lorem ipsum description for the box shortcode.','tfuse'),
    'category' => 11,
    'options' => array(
        array(
            'name' => __('Title','tfuse'),
            'desc' => __('Specifies the title of an shortcode','tfuse'),
            'id' => 'tf_shc_testimonials_title',
            'value' => 'Testimonials',
            'type' => 'text'
        ),
        array(
            'name' => __('Order','tfuse'),
            'desc' => __('Select display order','tfuse'),
            'id' => 'tf_shc_testimonials_order',
            'value' => 'DESC',
            'options' => array(
                'RAND' => __('Random','tfuse'),
                'ASC' => __('Ascending','tfuse'),
                'DESC' => __('Descending','tfuse')
            ),
            'type' => 'select'
        ),
        array(
            'name' => __('Use in before/after content','tfuse'),
            'desc' => __('If you want to use in before/after content make this option true','tfuse'),
            'id' => 'tf_shc_testimonials_before',
            'value' => false,
            'type' => 'checkbox'
        ),
    )
);

tf_add_shortcode('testimonials', 'tfuse_testimonials', $atts);