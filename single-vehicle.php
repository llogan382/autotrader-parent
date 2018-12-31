<?php
get_header();
$sidebar_position = tfuse_sidebar_position();
tfuse_shortcode_content('top');
?>

<div id="middle" class="full_width">
    <div class="container clearfix">
        <?php tfuse_category_ads(); tfuse_hook(); ?>
		<?php tfuse_vehicle_title(); ?>
        <div class="offer_details clearfix">
            <div class="content">
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