<?php
/**
 * MG Fresh Posts
 *
 * To override this shortcode in a child theme, copy this file to your child theme's
 * theme_config/extensions/shortcodes/shortcodes/ folder.
 *
 */

function tfuse_fresh_posts($atts, $content = null)
{
    remove_filter('excerpt_length', 'tfuse_custom_excerpt_length');
    add_filter( 'excerpt_length', 'tfuse_custom_excerpt_length_short', 99 );
    extract(shortcode_atts(array('title' => '','posts' => ''), $atts));
    
    $output = '';
    if(!empty($posts)):
        $posts = explode(',',$posts);
        $output .= '<div class="widget-container widget_featured_posts">';
            if(!empty($title)) $output .= '<h3 class="widget-title">' . tfuse_qtranslate($title) . '</h3>';
            $output .= '<ul>';
            $posts = get_posts(array('post__in' => $posts));
            if(!empty($posts)):
                foreach ($posts as $post)
                {
                    $post_image_src = tfuse_page_options('thumbnail_image',tfuse_page_options('single_image',null,$post->ID),$post->ID);
                    $image = new TF_GET_IMAGE();
                    $img = $image->properties(array('class' => 'thumbnail'))->width(250)->height(85)->src($post_image_src)->get_img();
                    $link = get_permalink( $post->ID );
                    $output .='<li class="post-item">
                        <div class="post-title"><a href="'.$link.'">'.$post->post_title.'</a></div>
                        <div class="post-meta">'.__('written by ','tfuse').'<span class="post-author">'.get_the_author().'</span></div>
                        <div class="post-image"><a href="'.$link.'">'.$img.'</a></div>
                        <div class="post-descr">'.$post->post_excerpt.'</div>
                        <div class="post-more"><a href="'.$link.'">'.__('READ THE ARTICLE','tfuse').'</a></div>
                    </li>';
                }
            endif;
        $output .= '</ul></div>';
    endif;

    return $output;
}

$atts = array(
    'name' => __('MG Fresh Posts','tfuse'),
    'desc' => __('Here comes some lorem ipsum description for the box shortcode.','tfuse'),
    'category' => 12,
    'options' => array(
        array(
            'name' => __('Title','tfuse'),
            'desc' => '',
            'id' => 'tf_shc_fresh_posts_title',
            'value' => '',
            'type' => 'text'
        ),
        array(
            'name' => __('Posts','tfuse'),
            'desc' => __('Type posts','tfuse'),
            'id' => 'tf_shc_fresh_posts_posts',
            'value' => '',
            'type' => 'multi',
            'subtype' => 'post'
        )
    )
);

tf_add_shortcode('fresh_posts', 'tfuse_fresh_posts', $atts);