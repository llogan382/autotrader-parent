<?php
/**
 * MG_Twitter
 * 
 * To override this shortcode in a child theme, copy this file to your child theme's
 * theme_config/extensions/shortcodes/shortcodes/ folder.
 * 
 * Optional arguments:
 * items: 5
 * username:
 * title:
 * post_date:
 */

function tfuse_mgtwitter($atts, $content = null)
{
    extract(shortcode_atts(array(
            'items' => 5,
            'username' => '',
            'title' => '',
            'post_date' => '',
            'follow' =>''
    ), $atts));
    
    $return_html = '';
    if ( !empty($username) )
    {
        $tweets = tfuse_get_tweets($username,$items);

        $return_html .= '<div class="widget-container widget_twitter">';
        if (!empty($title))
            $return_html .= '<h3 class="widget-title">' . tfuse_qtranslate($title) . '</h3><div class="tweet_list">';

        foreach ( $tweets as $tweet )
        {
            if( isset($tweet->text) )
                $return_html .= '<div class="tweet_item clearfix">
                <div class="tweet_image"><img src="'.$tweet->user->profile_image_url.'" width="30" height="30" alt=""></div>
                    <div class="tweet_text">
                        <div class="inner">'.$tweet->text.'<span class="tweet_time">'.ucfirst($tweet->created_at).'</span></div></div>
                </div>';
        }

        $return_html .= '</div>';
        if (!empty($follow))$return_html .='<a href="https://twitter.com/'.$username.'" class="link-more" hidefocus="true" style="outline: none;">'.$follow.'</a>';
        $return_html .= '</div>';
    }
    return $return_html;
}

$atts = array(
    'name' => __('MG Twitter','tfuse'),
    'desc' => __('Here comes some lorem ipsum description for the box shortcode.','tfuse'),
    'category' => 12,
    'options' => array(
        array(
            'name' => __('Title','tfuse'),
            'desc' => __('Specifies the title of an shortcode','tfuse'),
            'id' => 'tf_shc_mgtwitter_title',
            'value' => 'Twitter Activity',
            'type' => 'text'
        ),
        array(
            'name' => __('Username','tfuse'),
            'desc' => __('Twitter username','tfuse'),
            'id' => 'tf_shc_mgtwitter_username',
            'value' => 'themefuse',
            'type' => 'text'
        ),
        array(
            'name' => __('Items','tfuse'),
            'desc' => __('Enter the number of tweets','tfuse'),
            'id' => 'tf_shc_mgtwitter_items',
            'value' => '2',
            'type' => 'text'
        ),
        array(
            'name' => __('Folow us','tfuse'),
            'desc' => __('Enter the text for link follow us on twitter','tfuse'),
            'id' => 'tf_shc_mgtwitter_follow',
            'value' => 'FOLLOW US ON TWITTER',
            'type' => 'text'
        ),
    )
);

tf_add_shortcode('mgtwitter', 'tfuse_mgtwitter', $atts);