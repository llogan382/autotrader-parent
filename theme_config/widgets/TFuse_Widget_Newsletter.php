<?php
// =============================== Newsletetr widget ======================================

class TFuse_newsletter extends WP_Widget {

    function __construct() {
        $widget_ops = array('description' => '');
        parent::__construct(false, __('TFuse - Newsletter', 'tfuse'), $widget_ops);
    }

    function widget($args, $instance) {
        extract($args);
        $newsletter_title = esc_attr($instance['newsletter_title']);
        $rss = empty($instance['rss']) ? '' : esc_attr($instance['rss']);
        if (isset($instance['disable_box']) && $instance['disable_box'])
        {
            $before_box = '';
            $after_box ='';
        }
        else {
            $before_box = '<div class="box">';
            $after_box ='</div>';
        }
        echo $before_box; ?>

        <div class="widget-container newsletter_subscription_box newsletterBox">
            <div class="inner">
                <?php if ($newsletter_title != '') { ?><h3><?php echo tfuse_qtranslate($newsletter_title); ?></h3><?php } ?>

                <form action="#" method="post" class="newsletter_subscription_form">
                    <label><?php _e('Enter your email address:','tfuse'); ?></label><br>
                    <input type="text" value="" name="newsletter" class="newsletter_subscription_email inputField" />
                    <input type="submit" value="<?php _e('Send', 'tfuse'); ?>" class="btn-arrow newsletter_subscription_submit" />
                    <?php if ($rss != '') { ?>
                        <div class="newsletter_text">
                            <a href="<?php $feedb_url = tfuse_options('feedburner_url','#'); print $feedb_url; ?>" class="link-news-rss"><?php _e('Also subscribe to <span>our RSS feed</span>','tfuse'); ?></a>
                        </div>
                    <?php } ?>

					<?php
					$enable_newsletter_gdpr = tfuse_options( 'newsletter_gdpr', false );
					if ( $enable_newsletter_gdpr ) :
						echo apply_filters(
							'tfuse_newsletter_gdpr_checkbox',
							'<div class="tfuse_newsletter_gdpr"><input type="checkbox" name="tfuse_newsletter_gdpr" value="" /><label>'.tfuse_options( 'newsletter_gdpr_text', '' ).'</label></div><div class="clear"></div>'
						);
					endif;
					?>

                </form>

                <div class="newsletter_subscription_messages before-text" style="margin-left: 10px">
                    <div class="newsletter_subscription_message_success">
                        <?php _e('Thank you for your subscription.','tfuse') ?>
                    </div>
                    <div class="newsletter_subscription_message_wrong_email">
                        <?php _e('Your email format is wrong!','tfuse') ?>
                    </div>
                    <div class="newsletter_subscription_message_failed">
                        <?php _e('Sad, but we couldn\'t add you to our mailing list ATM.','tfuse') ?>
                    </div>
                </div>

                <div class="newsletter_subscription_ajax" style="margin-left: 10px"><?php _e('Loading...','tfuse'); ?></div>
            </div>
        </div>
    <?php echo $after_box;
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['newsletter_title'] = $new_instance['newsletter_title'];
        $instance['rss'] = isset($new_instance['rss']);
        $instance['disable_box'] = isset($new_instance['disable_box']);
        return $instance;
    }

    function form($instance) {
        $instance = wp_parse_args((array) $instance, array('newsletter_title' => '', 'rss' => ''));
        $newsletter_title = esc_attr($instance['newsletter_title']); ?>

        <p>
            <label for="<?php echo $this->get_field_id('newsletter_title'); ?>"><?php _e('Title:', 'tfuse'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('newsletter_title'); ?>" value="<?php echo $newsletter_title; ?>" class="widefat" id="<?php echo $this->get_field_id('newsletter_title'); ?>" />
        </p>
        <p>
            <input type="checkbox" <?php checked(isset($instance['rss']) ? $instance['rss'] : 0); ?> name="<?php echo $this->get_field_name('rss'); ?>" class="checkbox" id="<?php echo $this->get_field_id('rss'); ?>" />
            <label for="<?php echo $this->get_field_id('rss'); ?>"><?php _e('Activate RSS', 'tfuse'); ?></label>
        </p>
        <p><input id="<?php echo $this->get_field_id('disable_box'); ?>" name="<?php echo $this->get_field_name('disable_box'); ?>" type="checkbox" <?php checked(isset($instance['disable_box']) ? $instance['disable_box'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('disable_box'); ?>"><?php _e('Disable box','tfuse'); ?></label></p>
    <?php
    }
}

register_widget('TFuse_newsletter');