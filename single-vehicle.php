<?php
get_header();
$sidebar_position = tfuse_sidebar_position();
tfuse_shortcode_content('top');
?>
<?php
// tl_slide_images = Gallery Field
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

<div id="middle" class="full_width">
    <div class="container clearfix">
        <?php tfuse_category_ads(); tfuse_hook(); ?>
		<?php tfuse_vehicle_title(); ?>
        <div class="offer_details clearfix">
            <div class="content">
            <?php themeprefix_lightslider_thumbslider();?>

                <?php get_template_part('offer-views/side', 'left'); ?>
                <?php get_template_part('offer-views/side', 'right'); ?>
            </div><!--/ content -->
        </div>
        <?php get_template_part('offer-views/details', 'tabs'); ?>
        <?php echo tfuse_get_text_box_vehicles(); ?>
        <?php if ( tfuse_page_options('enable_comments') ) tfuse_comments(); ?>
    </div><!--/ .container  -->
</div><!--/ middle -->

<?php tfuse_shortcode_content('bottom1'); ?>
<?php tfuse_header_content('content'); ?>
<?php tfuse_shortcode_content('bottom'); ?>
<?php tfuse_shortcode_content('bottom2'); ?>
<?php get_footer(); ?>