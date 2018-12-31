<?php
/**
 * The template for displaying content in the single.php template.
 * To override this template in a child theme, copy this file 
 * to your child theme's folder.
 *
 * @since AutoTrader 1.0
 */
?>
<article class="post-item post-detail">
    <div class="post-title">
        <h2><?php the_title(); ?></h2>
    </div>
    <div class="post-aside clearfix">
        <?php tfuse_media(); ?>

        <?php if ( tfuse_page_options('enable_post_meta',tfuse_options('enable_post_meta',true) ) ) { ?>
            <div class="post-meta">
                <?php if ( tfuse_page_options('enable_share_buttons',tfuse_options('enable_share_buttons',true) ) ) { ?>
                    <div class="post-share"><span><?php _e('Share:','tfuse'); ?></span>
                        <a href="//plus.google.com/share?url=<?php the_permalink(); ?>" class="share_google"><?php _e('Google +1','tfuse'); ?></a>
                        <a href="//www.facebook.com/sharer.php?u=<?php echo encodeURIComponent(get_permalink());?>%2F&t=<?php echo encodeURIComponent(get_the_title()); ?>" class="share_facebook"><?php _e('Facebook','tfuse'); ?></a>
                        <a href="//twitter.com/share?url=<?php the_permalink(); ?>&amp;text=<?php the_title(); ?>&amp;count=horiztonal" class="share_twitter"><?php _e('Twitter','tfuse'); ?></a>
                    </div>
                <?php } ?>

                <?php if ( tfuse_page_options('enable_published_date',tfuse_options('enable_published_date',true) ) ) { ?>
                    <div class="info_row"><span><?php _e('Posted On:','tfuse'); ?></span> <?php echo get_the_date(); ?></span></div>
                <?php } ?>

                <?php if ( tfuse_page_options('enable_author_post',tfuse_options('enable_author_post',true) ) ) { ?>
                    <div class="info_row"><span><?php _e('Written BY:','tfuse'); ?></span> <?php the_author(); ?></div>
                <?php } ?>

                <?php if ( tfuse_page_options('enable_comments',tfuse_options('enable_posts_comments',true) ) ) { ?>
                    <div class="info_row"><span><?php _e('Comments:','tfuse'); ?></span> <a href="<?php comments_link(); ?>" class="anchor"><?php comments_number('0','1','%'); ?></a></div>
                <?php } ?>
            </div><!-- /.post-meta-->
        <?php } ?>

        <div class="entry">
            <?php the_content(); ?>
            <?php _e('Categories: ','fw'); echo get_the_term_list( $id, 'category', '', ', ' , ''); ?>
            <?php $tags = get_the_tags();
            if (!empty($tags)) : ?>
                <div class="fw-tag-links"><span><?php _e('Tags: ','fw'); ?></span><?php the_tags( '', ', ', '' ); ?></div>
            <?php endif; ?>
            <?php wp_link_pages(); ?>
        </div>
        <?php get_template_part( 'content', 'author' ); ?>
    </div><!-- /.post-aside-->
</article><!-- /.post-item-->