<?php
/**
 * MG_Text
 * 
 * To override this shortcode in a child theme, copy this file to your child theme's
 * theme_config/extensions/shortcodes/shortcodes/ folder.
 */

function tfuse_mgtext($atts, $content = null)
{
    extract(shortcode_atts(array('title' => '', 'text' => '' ), $atts));
    $return_html = '';
    if ( !empty($text) )
    {
        $return_html .= '<div class="widget-container widget_text">
            <h3 class="widget-title">'.tfuse_qtranslate($title).'</h3>
            <div class="textwidget">'.tfuse_qtranslate($text).'</div>
        </div>';
    }
    return $return_html;
}

$atts = array(
    'name' => __('MG Text','tfuse'),
    'desc' => __('Here comes some lorem ipsum description for the box shortcode.','tfuse'),
    'category' => 12,
    'options' => array(
        array(
            'name' => __('Title','tfuse'),
            'desc' => __('Specifies the title of an shortcode','tfuse'),
            'id' => 'tf_shc_mgtext_title',
            'value' => 'Text Widget',
            'type' => 'text'
        ),
        array(
            'name' => __('Text','tfuse'),
            'desc' => __('Enter the text for this shortcode','tfuse'),
            'id' => 'tf_shc_mgtext_text',
            'value' => '',
            'type' => 'textarea'
        )
    )
);

tf_add_shortcode('mgtext', 'tfuse_mgtext', $atts);