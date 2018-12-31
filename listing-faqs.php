<?php
/**
 * The template for displaying faqs on archive pages.
 * To override this template in a child theme, copy this file 
 * to your child theme's folder.
 *
 * @since AutoTrader 1.0
 */
?>
<div class="row clearfix">
    <div class="col col_3_4">
        <h2><span><?php _e('Q:','tfuse'); ?></span> <?php the_title(); ?> </h2>
        <?php the_excerpt(); ?>
    </div>

    <div class="col col_1_4">
        <div class="text-center">
            <br>
            <p><a href="<?php the_permalink(); ?>" class="btn btn_default"><span><?php _e('FIND OUT MORE','tfuse'); ?></span></a></p>
        </div>
    </div>

</div><!-- /.row -->