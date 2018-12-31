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
<?php 

$images = get_field('lwd_vehicle_images');

if( $images ): ?>
    <div id="slider" class="flexslider">
        <ul class="slides">
            <?php foreach( $images as $image ): ?>
                <li>
                    <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
                    <p><?php echo $image['caption']; ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div id="carousel" class="flexslider">
        <ul class="slides">
            <?php foreach( $images as $image ): ?>
                <li>
                    <img src="<?php echo $image['sizes']['thumbnail']; ?>" alt="<?php echo $image['alt']; ?>" />
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<!--/ offer left -->