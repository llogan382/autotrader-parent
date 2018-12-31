<?php
global $wp_query, $is_tf_blog_page;
get_header();
if ($is_tf_blog_page) die();
$sidebar_position = tfuse_sidebar_position();
tfuse_shortcode_content('top');
?>

<div <?php tfuse_class('middle'); ?>>
    <div class="container clearfix">
        <div class="content">
            <div class="entry">
                <div class="text-center">
                    <?php echo tfuse_options('text_404',''); ?>
                    <p>&nbsp;</p>
                    <p><a href="javascript:history.go(-1)" class="btn btn_default" hidefocus="true" style="outline: none;"><span><?php _e('GO BACK TO PREVIOUS PAGE','tfuse'); ?></span></a></p>
                </div>
            </div><!--/ .entry -->

        </div><!--/ content -->

        <?php if ($sidebar_position == 'left' || $sidebar_position == 'right') : ?>
            <div class="sidebar">
                <?php get_sidebar(); ?>
            </div><!--/ .sidebar -->
        <?php endif; ?>

    </div><!--/ .container -->
</div><!--/ #middle -->

<?php tfuse_shortcode_content('bottom1'); ?>
<?php tfuse_header_content('content'); ?>
<?php tfuse_shortcode_content('bottom'); ?>
<?php tfuse_shortcode_content('bottom2'); ?>
<?php get_footer(); ?>