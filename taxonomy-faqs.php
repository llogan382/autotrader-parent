<?php get_header();
$sidebar_position = tfuse_sidebar_position();
tfuse_shortcode_content('top'); ?>

<div id="middle" <?php tfuse_class('middle'); ?>>
    <div class="container clearfix">
        <?php tfuse_category_ads(); tfuse_hook(); ?>
        <div class="content">
            <div class="faqlist">
                <?php
                if (have_posts())
                {
                    $count_faqs = wp_count_posts('faq');
                    $count = 0;
                    while (have_posts()) : the_post(); $count++;
                        get_template_part('listing', 'faqs');
                        if($count_faqs->publish!=$count)echo '<div class="divider_space_thin"></div>';
                    endwhile;
                }
                else { ?>
                    <h5><?php _e('Sorry, no posts matched your criteria.', 'tfuse'); ?></h5>
                    <?php
                } tfuse_pagination(); ?>

            </div><!--/ .faqlist -->
        </div><!--/ .content -->

        <?php if (($sidebar_position == 'right') || ($sidebar_position == 'left')) : ?>
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