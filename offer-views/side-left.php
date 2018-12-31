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
<?
function themeprefix_lightslider_thumbslider() {
	$images = get_field('vehicle_images'); //add your correct field name
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
            <?php foreach($slider_images as $key=>$slide) : ?>
            <div class="gallery_image_item">

                <img src="<?php echo $slide['img_full']; ?>" alt="">
                <a href="<?php echo $slide['img_full']; ?>" data-rel="prettyPhoto[gal]">
                    <span><?php echo $slide['title']; ?><em class="ico_large"></em></span></a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php if(!empty($slider_images)){ ?>
        <div class="gallery_thumbs">
            <div id="gallery_thumbs">
                <?php foreach($slider_images as $key=>$slide) : ?>
                    <a href="#"><img src="<?php echo $slide['img_full']; ?>" alt=""></a>
                <?php endforeach; ?>
            </div>
            <a href="#" class="prev" id="gallery_thumbs_prev"></a>
            <a href="#" class="next" id="gallery_thumbs_next"></a>
        </div>

        <script>
            jQuery(document).ready(function() {
                var $ = jQuery;
                function carGalleryInit() {
                    $('#gallery_thumbs').children().each(function(i) {
                        $(this).addClass( 'itm'+i );
                        $(this).click(function() {
                            $('#gallery_images').trigger('slideTo',[i, 0, true]);
                            $('#gallery_thumbs a').removeClass('selected');
                            $(this).addClass('selected');
                            return false;
                        });
                    });
                    $('#gallery_thumbs a.itm0').addClass('selected');

                    $('#gallery_images').carouFredSel({
                        infinite: false,
                        circular: false,
                        auto: false,
                        width: '100%',
                        scroll: {
                            items : 1,
                            fx : "crossfade"
                        }
                    });
                    $('#gallery_thumbs').carouFredSel({
                        prev : "#gallery_thumbs_prev",
                        next : "#gallery_thumbs_next",
                        infinite: false,
                        circular: false,
                        auto: false,
                        width: '100%',
                        scroll: {
                            items : 1
                        }
                    });
                }

                $(window).load(function() {
                    carGalleryInit();
                });
                var resizeTimer;
                $(window).resize(function() {
                    clearTimeout(resizeTimer);
                    resizeTimer = setTimeout(carGalleryInit, 100);
                });
            });
        </script>
    <?php } ?>

</div>
<!--/ offer left -->