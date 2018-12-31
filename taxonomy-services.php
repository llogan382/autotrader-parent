<?php get_header();
tfuse_shortcode_content('top');
tfuse_before_service(); ?>

<div id="middle" class="full_width">
    <div class="container clearfix">
        <?php tfuse_category_ads(); tfuse_hook(); ?>
        <div class="content">
            <div class="text_box text_box_top">
                <?php if(tfuse_options('button_text','',tfuse_get_term_id())!='' ){ ?>
                    <p><a href="#" class="btn btn_big btn_white"><span><?php echo tfuse_options('button_text','',tfuse_get_term_id()); ?></span></a></p>
                <?php } ?>
            </div>
            <div class="entry clearfix">
                <?php
                $args = array(
                    'post_type' => 'service',
                    'posts_per_page' => -1,
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'services',
                            'field' => 'slug',
                            'terms' => get_query_var('term')
                        )
                    )
                );
                $query = new WP_Query( $args );
                if (have_posts())
                {
                    foreach($query->posts as $service){ ?>
                        <div class="service_item clearfix" id="service_<?php echo $service->ID; ?>">
                            <?php tfuse_custom_title($service->ID); ?>
                            <div class="service_image">
                                <?php tfuse_service_image($service->ID); ?>
                                <?php if( tfuse_page_options('image_subtitle','',$service->ID) != '' ){ ?>
                                    <div class="caption"><?php echo tfuse_page_options('image_subtitle','',$service->ID); ?></div>
                                <?php } ?>
                            </div>
                            <div class="service_descr"><?php echo $service->post_content; ?></div>
                        </div>
                    <?php }
                }
                else
                { ?>
                    <h5><?php _e('Sorry, no posts matched your criteria.', 'tfuse'); ?></h5>
                <?php } ?>

            </div><!--/ entry -->
        </div><!--/ content -->
    </div><!--/ container -->
</div><!--/ .middle -->

<?php tfuse_shortcode_content('bottom1'); ?>
<?php tfuse_header_content('content'); ?>
<?php tfuse_shortcode_content('bottom'); ?>
<?php tfuse_shortcode_content('bottom2'); ?>
<?php get_footer(); ?>