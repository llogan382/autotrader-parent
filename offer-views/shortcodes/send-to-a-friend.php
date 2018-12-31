<?php wp_enqueue_script( 'offer_forms' ); ?>
<?php
    $link =  get_permalink();
    $directory = get_template_directory_uri();
?>
<form action="#" class="details_form" id="offer_send_friend">
    <div class="form_col_1">
        <div class="row">
            <label class="label_title"><?php _e('Your Email Address:','tfuse'); ?></label>
            <input id="email_from" type="text" name="email_from" class="inputField" value="">
        </div>
        <div class="row">
            <label class="label_title"><?php _e('Your Friend’s Email Address:','tfuse'); ?></label>
            <input id="email_send_to" type="text" name="email_send_to" class="inputField" value="">
        </div>
        <label style="margin-left: 15px; color: #23ad14; display: none;" class="offer_send_success"><?php _e('Your message has been sent.','tfuse'); ?></label>
        <label style="margin-left: 0; color: #ff0000; display: none;" class="offer_send_error"><?php _e('Oops something went wrong. Please try again later','tfuse'); ?></label>
    </div><!--/ form col 1 -->

    <div class="form_col_2 col_thin">
        <div class="row">
            <label class="label_title"><?php _e('Message:','tfuse'); ?></label>
            <textarea id="message" rows="4" cols="5" name="message" class="textareaField"><?php _e("Hi,\n\nCheck out this cool car:","tfuse");
                echo "\n".$link; ?></textarea>
        </div>
        <div class="row rowSubmit">
            <a href="#" class="link_reset" onclick="document.getElementById('offer_send_friend').reset();return false"><?php _e('Reset All Form Fields','tfuse'); ?></a>
            <input type="submit" value="<?php _e('SEND MESSAGE','tfuse'); ?>" class="btn_send send_friend">
        </div>
    </div><!--/ form col 2 -->

    <div class="form_col_3">
        <div class="row">
            <label class="label_title"><?php _e('Share on:','tfuse'); ?></label>
            <a href="//twitter.com/share?url=<?php echo $link; ?>&amp;text=<?php the_title();?>&amp;count=horiztonal" class="btn_share"><img src="<?php echo $directory; ?>/images/btn_share_tweet.png" alt=""></a>
            <a href="//www.facebook.com/sharer.php?u=<?php echo encodeURIComponent($link);?>%2F&t=<?php echo encodeURIComponent(get_the_title());?>" class="btn_share"><img src="<?php echo $directory; ?>/images/btn_share_like.png" alt=""></a>
            <a href="//plus.google.com/share?url=<?php echo $link; ?>" class="btn_share"><img src="<?php echo $directory; ?>/images/btn_share_g1.png" alt=""></a>
        </div>
    </div><!--/ form col 3 -->
</form>