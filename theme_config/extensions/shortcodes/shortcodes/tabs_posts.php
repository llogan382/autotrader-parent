<?php
//Recent / Most Commented Shortcodes

function tfuse_tabs_posts($atts) {
    extract(shortcode_atts(array('items' => ''), $atts));

    $popular_posts  = tfuse_shortcode_posts(array(
                                'sort' => 'popular',
                                'items' => $items,
                                'image_post' => true,
                                'image_width' => 62,
                                'image_height' => 62,
                                'image_class' => 'thumbnail',
                                'date_format' => 'M j, Y',
                                'date_post' => true
                                ));
    
    $latest_posts = tfuse_shortcode_posts(array(
                                'sort' => 'commented',
                                'items' => $items,
                                'image_post' => true,
                                'image_width' => 62,
                                'image_height' => 62,
                                'image_class' => 'thumbnail',
                                'date_format' => 'M j, Y',
                                'date_post' => true,
                            ));
    $return_html = '';
    $numb = 1;
    $return_html .='<div class="tf_sidebar_tabs tabs_framed">
        <ul class="tabs">
            <li><a href="#tf_tabs_1">'.__('Recent Posts','tfuse').'</a></li>
            <li><a href="#tf_tabs_2">'.__('Most Commented','tfuse').'</a></li>
        </ul>';

    $return_html .= '<div id="tf_tabs_1" class="tabcontent">
        <ul class="post_list recent_posts">';
            foreach ($popular_posts as $post_val) {
                $return_html .= '<li class="clearfix">';
                $return_html .= '<a href="' . $post_val['post_link'] . '" >' . $post_val['post_img'] . '</a>'. ' <a href="' . $post_val['post_link'] . '" >' . $post_val['post_title'] . '</a>';

                if(!tfuse_options('date_time')):
                    $return_html .=' <div class="date">' . $post_val['post_date_post'] . '</div>';
                endif;
                $return_html .= '</li>';
            }
    $return_html .='</ul>
        </div>

        <div id="tf_tabs_2" class="tabcontent">
                    <ul class="post_list popular_posts">';
                        foreach ($latest_posts as $post_val) {
                            $return_html .= '<li class="clearfix">';
                            $return_html .= '<a href="' . $post_val['post_link'] . '" >' . $post_val['post_img'] . '</a> ';

                            $return_html .= '<a href="' . $post_val['post_link'] . '" >&nbsp;' . $post_val['post_title'] . '</a>';
							if(!tfuse_options('date_time')):
								$return_html .=' <div class="date">' . $post_val['post_date_post'] . '</div>';
                            endif;
                            $return_html .= '</li>';
                            $numb++;
                        }
    $return_html .= '</ul>
        </div>
    </div>';

    return $return_html;
}

$atts = array(
    'name' => __('Tab Posts','tfuse'),
    'desc' => __('Here comes some lorem ipsum description for the box shortcode.','tfuse'),
    'category' => 2,
    'options' => array(
        array(
            'name' => __('Items','tfuse'),
            'desc' => __('Specifies the number of the post to show','tfuse'),
            'id' => 'tf_shc_tabs_posts_items',
            'value' => '5',
            'type' => 'text'
        ),
    )
);

tf_add_shortcode('tabs_posts','tfuse_tabs_posts', $atts);