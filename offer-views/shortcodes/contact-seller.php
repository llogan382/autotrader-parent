<?php wp_enqueue_script( 'offer_forms' );
global $post;
$author_id = $post->post_author; ?>

<form action="#" class="details_form" id="offer_contact">
    <div class="form_col_1">
        <div class="row input_styled inlinelist">
            <label class="label_title"><?php _e('Form of Address:','tfuse'); ?></label>
            <input type="radio" name="title" value="<?php _e('Mrs.','tfuse'); ?>" id="mrs" checked> <label for="mrs"><?php _e('Mrs.','tfuse'); ?></label>
            <input type="radio" name="title" value="<?php _e('Mr.','tfuse'); ?>" id="mr"> <label for="mr"><?php _e('Mr.','tfuse'); ?></label>
            <input type="radio" name="title" value="<?php _e('Company','tfuse'); ?>" id="company"> <label for="company"><?php _e('Company','tfuse'); ?></label>
        </div>
        <div class="row">
            <label class="label_title"><?php _e('Your Full Name:','tfuse'); ?></label>
            <input id="client_name" type="text" name="client_name" class="inputField required" value="">
        </div>
        <div class="row">
            <label class="label_title"><?php _e('Your Email Address:','tfuse'); ?></label>
            <input id="client_email" type="text" name="client_email" class="inputField" value="">
        </div>
        <label style="margin-left: 15px; color: #23ad14; display: none;" class="offer_send_success"><?php _e('Your message has been sent.','tfuse'); ?></label>
        <label style="margin-left: 0; color: #ff0000; display: none;" class="offer_send_error"><?php _e('Oops something went wrong. Please try again later','tfuse'); ?></label>
    </div><!--/ form col 1 -->

    <div class="form_col_2">
        <div class="row">
            <label class="label_title"><?php _e('Message:','tfuse'); ?></label>
            <textarea id="seller_message" rows="4" cols="5" name="message" class="textareaField required"></textarea>
        </div>
		<?php
		$enable_contact_widget_gdpr = tfuse_options( 'contact_widget_gdpr', false );
		if ( $enable_contact_widget_gdpr ) :
			echo apply_filters(
				'tfuse_contact_widget_gdpr_checkbox',
				'<div class="tfuse_contact_widget_gdpr"><input type="checkbox" name="tfuse_contact_widget_gdpr" value="" /><label>'.tfuse_options( 'contact_widget_gdpr_text', '' ).'</label></div><div class="clear"></div>'
			);
		endif;
		?>
        <div class="row rowSubmit">
            <a href="#" class="link_reset" onclick="document.getElementById('offer_contact').reset();return false"><?php _e('Reset All Contact Form Fields','tfuse'); ?></a>
            <input id="send_seller_message" type="submit" value="<?php _e('SEND MESSAGE','tfuse'); ?>" class="btn_send">
            <input type="hidden" value="<?php echo $author_id; ?>" id="author_id" name="author_id">
            <input type="hidden" value="<?php echo $post->ID; ?>" id="post_id" name="post_id">
        </div>
    </div><!--/ form col 2 -->

</form>