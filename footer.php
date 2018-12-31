<div class="footer">
    <div class="container clearfix">
        <?php tfuse_footer(); ?>
        <div class="clear"></div>
        <div class="footer_social"><?php tfuse_footer_social(); ?></div>

        <div class="footer_contacts">
            <span class="phone"><?php echo tfuse_options('custom_phone'); ?></span>
            <span class="email"><?php echo tfuse_options('custom_email'); ?></span>
        </div>

        <div class="copyright">
            <?php echo tfuse_options('custom_copyright'); ?>
        </div>

    </div><!-- /.container-->
</div><!-- /.footer-->

</div><!-- /.body_wrap-->
<?php wp_footer(); ?>
</body>
</html>