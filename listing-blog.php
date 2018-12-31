<?php
/**
 * The template for displaying posts on archive pages.
 * To override this template in a child theme, copy this file 
 * to your child theme's folder.
 *
 * @since AutoTrader 1.0
 */
  global $more;
    $more = apply_filters('tfuse_more_tag',0);
?>

<div class="post-item" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="post-title">
        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
    </div>
    <div class="post-aside clearfix">
        <?php tfuse_media(); ?>

        <?php if ( tfuse_page_options('enable_post_meta',tfuse_options('enable_post_meta',true) ) ) { ?>
            <div class="post-meta">
                <?php if ( tfuse_page_options('enable_published_date',tfuse_options('enable_published_date',true) ) ) { ?>
                    <div class="info_row"><span><?php _e('Posted On:','tfuse'); ?></span> <?php echo get_the_date(); ?></span></div>
                <?php } ?>

                <?php if ( tfuse_page_options('enable_author_post',tfuse_options('enable_author_post',true) ) ) { ?>
                    <div class="info_row"><span><?php _e('Written BY:','tfuse'); ?></span> <?php the_author(); ?></div>
                <?php } ?>

                <?php if ( tfuse_page_options('enable_comments',tfuse_options('enable_posts_comments',true) ) ) { ?>
                    <div class="info_row"><span><?php _e('Comments:','tfuse'); ?></span> <a href="<?php comments_link(); ?>"><?php comments_number('0','1','%'); ?></a></div>
                <?php } ?>

                <a href="<?php the_permalink(); ?>" class="link_more"><?php _e('READ MORE','tfuse'); ?> <span>&gt;</span></a>
            </div>
        <?php } ?>

        <div class="entry">
            <?php if ( tfuse_options('post_content') == 'content' ) the_content(''); else the_excerpt(); ?>
        </div>
    </div><!-- /.post-aside-->
</div><!-- /.post-item-->