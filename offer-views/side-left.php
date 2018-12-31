<?php
    global $post;
    $attachments = tfuse_get_gallery_images($post->ID,TF_THEME_PREFIX . '_slider_images');
    $slider_images = array();
    if ($attachments) {
        foreach ($attachments as $attachment){
            if( isset($attachment->image_options['imgexcludefromslider_check']) ) continue;

            $slider_images[] = array(
                'id'            => $attachment->ID,
                'title'         => apply_filters('the_title', $attachment->post_title),
                'order'         => $attachment->menu_order,
                'img_full'      => $attachment->guid
            );
        }
    }
?>
<!-- offer left -->

<!--/ offer left -->