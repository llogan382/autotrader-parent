<?php
/**
 * The template for displaying content-author in the single.php template.
 * To override this template in a child theme, copy this file 
 * to your child theme's folder.
 *
 * @since AutoTrader 1.0
 */
?>

<?php
    $author_description = get_the_author_meta('description');
    $enable_author_info = tfuse_page_options('enable_author_info',tfuse_options('enable_author_info',true));
    if ( $enable_author_info && !empty($author_description) ) {
?>
    <!-- author description -->
    <div class="author-description author_box_bottom">
        <div class="author-text">
            <p><span class="author-title"><?php _e('About the author:','tfuse'); ?></span> <?php echo $author_description; ?></p>
            <div class="author-name"><strong><?php echo get_the_author(); ?></strong>, <?php _e('author','tfuse'); ?></div>
        </div>

        <div class="author-image">
            <?php echo get_avatar( get_the_author_meta( 'ID' ), '161' ); ?>
            <span class="circle"></span>
        </div>
    </div><!--/ author description -->

<?php } ?>