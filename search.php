<?php
    global $TFUSE,$wp_query;
    if ($TFUSE->request->isset_GET('tfseekfid')) {
        get_template_part('template', 'search');
        die();
    }
    get_header();
    tfuse_header_content('header',$wp_query->found_posts);
    tfuse_breadcrumbs();
    tfuse_header_search();
    $sidebar_position = tfuse_sidebar_position();
    tfuse_shortcode_content('top');
?>

<div <?php tfuse_class('middle'); ?>>
    <div class="container clearfix">
        <div class="content">
            <div class="postlist">
                <?php if (have_posts()) : $count = 0; ?>

                    <?php while (have_posts()) : the_post(); $count++; ?>
                        <?php get_template_part('listing', 'blog'); ?>
                    <?php endwhile; else: ?>

                    <h5><?php _e('Sorry, no posts matched your criteria.', 'tfuse') ?></h5>
                <?php endif; ?>
            </div><!-- /.postlist -->

            <?php tfuse_pagination(); ?>
        </div><!-- /.content -->

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