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

<?php
function themeprefix_lightslider_thumbslider() {
	$images = get_field('lwd_vehicle_images'); //add your correct field name
		if( $images ): ?>
	
			<ul id="light-slider" class="image-gallery">
			
			<?php foreach( $images as $image ): ?>
			
				<li data-thumb="<?php echo $image['url']; ?>">
					<a href=""><img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" /></a>
				</li>
	
			<?php endforeach; ?>
			</ul>
		<?php endif; 
	}
?>

<!-- offer left -->
<div class="offer_gallery">
    <div class="gallery_images">
        <div id="gallery_images">

        <?php themeprefix_lightslider_thumbslider();?>
            
        </div>
    </div>


</div>

<!--/ offer left -->