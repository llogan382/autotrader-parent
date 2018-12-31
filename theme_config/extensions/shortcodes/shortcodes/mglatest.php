<?php
/**
 * MG Latest News
 * 
 * To override this shortcode in a child theme, copy this file to your child theme's
 * theme_config/extensions/shortcodes/shortcodes/ folder.
 *
 */

function tfuse_mglatest_post($atts, $content = null)
{
    extract(shortcode_atts(array('items' => 3, 'title' => 'Latest News' ), $atts));
    $return_html = '';
    $latest_posts = tfuse_shortcode_posts(array(
                                               'sort' => 'recent',
                                               'items' => $items,
                                               'image_post' => false,
                                               'date_post' => true,
                                          ));
    $return_html .= '<div class="widget-container widget_recent_entries">';
    $return_html .= !empty($title) ? '<h3 class="widget-title">' . tfuse_qtranslate($title) . '</h3>' : '';
    $return_html .= '<ul>';

    foreach ($latest_posts as $post_val):
        $return_html .= '<li class="clearfix">
                            <a href="'.$post_val['post_link'].'" class="link-name">'.$post_val['post_title'].'</a>
                            <div class="post-meta"><span class="post-date">'.__('posted on ','tfuse') .$post_val['post_date_post'].'</span> <a href="'.$post_val['post_link'].'#comments" hidefocus="true" style="outline: none;">'.strtolower(strip_tags($post_val['post_comnt_numb_link'])).'</a></div>
                         </li>';
    endforeach;
    $return_html .='</ul></div>';
    return $return_html;
}

$atts = array(
    'name' => __('MG Latest News','tfuse'),
    'desc' => __('Here comes some lorem ipsum description for the box shortcode.','tfuse'),
    'category' => 12,
    'options' => array(
        array(
            'name' => __('Title','tfuse'),
            'desc' => __('Specifies the title for an shortcode','tfuse'),
            'id' => 'tf_shc_mglatest_posts_title',
            'value' => 'Latest News',
            'type' => 'text'
        ),
        array(
            'name' => __('Items','tfuse'),
            'desc' => __('Specifies the number of the post to show','tfuse'),
            'id' => 'tf_shc_mglatest_posts_items',
            'value' => '3',
            'type' => 'text'
        )
    )
);

tf_add_shortcode('mglatest_posts', 'tfuse_mglatest_post', $atts);
