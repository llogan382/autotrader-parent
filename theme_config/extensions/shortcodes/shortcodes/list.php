<?php
/**
 * List Styles
 * 
 * To override this shortcode in a child theme, copy this file to your child theme's
 * theme_config/extensions/shortcodes/shortcodes/ folder.
 */

function tfuse_check_list($atts, $content = null)
{
    extract( shortcode_atts(array('class' => ''), $atts) );
    $div_class = (!$class)? '' : ' ' . $class;
    return '<div class="list_check ' . $div_class . '">'. do_shortcode($content) . '</div>';
}

$atts = array(
    'name' => __('Check List','tfuse'),
    'desc' => __('Here comes some lorem ipsum description for the box shortcode.','tfuse'),
    'category' => 2,
    'options' => array(
        /* add the fllowing option in case shortcode has content */
        array(
            'name' => __('Content','tfuse'),
            'desc' => __('Use the &lt;ul&gt; tag together with the &lt;li&gt; tag to create check lists','tfuse'),
            'id' => 'tf_shc_check_list_content',
            'value' => '
<ul>
    <li>item 1</li>
    <li>item 2</li>
    <li>item 3</li>
</ul>
            ',
            'type' => 'textarea'
        ),
        array(
            'name' => __('Class','tfuse'),
            'desc' => __('Specifies one or more class names for an shortcode, separated by space.','tfuse'),
            'id' => 'tf_shc_check_list_class',
            'value' => '',
            'type' => 'text'
        )
    )
);

tf_add_shortcode('check_list', 'tfuse_check_list', $atts);


function tfuse_square_list($atts, $content = null)
{
    extract( shortcode_atts(array('class' => ''), $atts) );
    $div_class = (!$class)? '' : ' ' . $class;
    return '<div class="list_square ' . $div_class . '">'. do_shortcode($content) . '</div>';
}

$atts = array(
    'name' => __('Square List','tfuse'),
    'desc' => __('Here comes some lorem ipsum description for the box shortcode.','tfuse'),
    'category' => 2,
    'options' => array(
        /* add the fllowing option in case shortcode has content */
        array(
            'name' => __('Content','tfuse'),
            'desc' => __('Use the &lt;ul&gt; tag together with the &lt;li&gt; tag to create square lists','tfuse'),
            'id' => 'tf_shc_square_list_content',
            'value' => '
<ul>
    <li>item 1</li>
    <li>item 2</li>
    <li>item 3</li>
</ul>
            ',
            'type' => 'textarea'
        ),
        array(
            'name' => __('Class','tfuse'),
            'desc' => __('Specifies one or more class names for an shortcode, separated by space.','tfuse'),
            'id' => 'tf_shc_square_list_class',
            'value' => '',
            'type' => 'text'
        )
    )
);

tf_add_shortcode('square_list', 'tfuse_square_list', $atts);


function tfuse_dots_list($atts, $content = null)
{
    extract( shortcode_atts(array('class' => ''), $atts) );
    $div_class = (!$class)? '' : ' ' . $class;
    return '<div class="list_dots ' . $div_class . '">'. do_shortcode($content) . '</div>';
}

$atts = array(
    'name' => __('Dots List','tfuse'),
    'desc' => __('Here comes some lorem ipsum description for the box shortcode.','tfuse'),
    'category' => 2,
    'options' => array(
        /* add the fllowing option in case shortcode has content */
        array(
            'name' => __('Content','tfuse'),
            'desc' => __('Use the &lt;ul&gt; tag together with the &lt;li&gt; tag to create dots lists','tfuse'),
            'id' => 'tf_shc_dots_list_content',
            'value' => '
<ul>
    <li>item 1</li>
    <li>item 2</li>
    <li>item 3</li>
</ul>
            ',
            'type' => 'textarea'
        ),
        array(
            'name' => __('Class','tfuse'),
            'desc' => __('Specifies one or more class names for an shortcode, separated by space.','tfuse'),
            'id' => 'tf_shc_dots_list_class',
            'value' => '',
            'type' => 'text'
        )
    )
);

tf_add_shortcode('dots_list', 'tfuse_dots_list', $atts);


function tfuse_delete_list($atts, $content = null)
{
    extract( shortcode_atts(array('class' => ''), $atts) );
    $div_class = (!$class)? '' : ' ' . $class;
    return '<div class="list_delete ' . $div_class . '">' . do_shortcode($content) . '</div>';
}

$atts = array(
    'name' => __('Delete List','tfuse'),
    'desc' => __('Here comes some lorem ipsum description for the box shortcode.','tfuse'),
    'category' => 2,
    'options' => array(
        /* add the fllowing option in case shortcode has content */
        array(
            'name' => __('Content','tfuse'),
            'desc' => __('Use the &lt;ul&gt; tag together with the &lt;li&gt; tag to create delete lists','tfuse'),
            'id' => 'tf_shc_delete_list_content',
            'value' => '
<ul>
    <li>item 1</li>
    <li>item 2</li>
    <li>item 3</li>
</ul>
            ',
            'type' => 'textarea'
        ),
        array(
            'name' => __('Class','tfuse'),
            'desc' => __('Specifies one or more class names for an shortcode, separated by space.','tfuse'),
            'id' => 'tf_shc_delete_list_class',
            'value' => '',
            'type' => 'text'
        )
    )
);

tf_add_shortcode('delete_list', 'tfuse_delete_list', $atts);
