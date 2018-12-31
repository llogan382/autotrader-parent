<?php
/**
 * Newsletter
 *
 * To override this shortcode in a child theme, copy this file to your child theme's
 * theme_config/extensions/shortcodes/shortcodes/ folder.
 *
 * Optional arguments:
 * title: e.g. Newsletter signup
 * text: e.g. Thank you for your subscription.
 * action: URL where to send the form data.
 * rss_feed:
 */

function tfuse_newsletter($atts, $content = null)
{
    extract(shortcode_atts(array('title' => '', 'text' => '', 'rss_feed' => ''), $atts));

    if (empty($title))
        $title = __('Newsletter', 'tfuse');
    if (empty($text))
        $text = __('', 'tfuse');

    $out = '
    <div class="widget-container newsletterBox newsletter_subscription_box">
        <div class="inner">
            <h3>' . tfuse_qtranslate($title) . '</h3>
            <div class="newsletter_subscription_messages before-text">
                <div class="newsletter_subscription_message_success">
                    '.__('Thank you for your subscription.','tfuse').'
                </div>
                <div class="newsletter_subscription_message_wrong_email">
                    '.__('Your email format is wrong!','tfuse') .'
                </div>
                <div class="newsletter_subscription_message_failed">
                    '. __('Sad, but we couldn\'t add you to our mailing list ATM.','tfuse') .'
                </div>
            </div>

            <form action="#" method="post" class="newsletter_subscription_form">
                <label>'.$text.'</label><br>
                <input type="text" value="" name="newsletter" id="newsletter" class="newsletter_subscription_email inputField" />
                <input type="submit" value="Submit" class="btn-arrow newsletter_subscription_submit" />
                <div class="newsletter_subscription_ajax">'. __('Loading...','tfuse') .'</div>
                <div class="newsletter_text">';
    if ($rss_feed == 'true') {
        $out .= '<a href="'.tfuse_options('feedburner_url', get_bloginfo_rss('rss2_url')).'" class="link-news-rss">'. __('Also subscribe to ', 'tfuse').'<span>'. __('our RSS feed', 'tfuse').'</span></a>';
    }
    $out .= '</div><div class="clear"></div>';

	$enable_newsletter_gdpr = tfuse_options( 'newsletter_gdpr', false );
	if ( $enable_newsletter_gdpr ) :
		$out .= apply_filters(
			'tfuse_newsletter_gdpr_checkbox',
			'<div class="tfuse_newsletter_gdpr"><input type="checkbox" name="tfuse_newsletter_gdpr" value="" /><label>'.tfuse_options( 'newsletter_gdpr_text', '' ).'</label></div><div class="clear"></div>'
		);
	endif;

            $out .= '</form>
        </div>
    </div>';

    return $out;
}

$atts = array(
    'name' => __('Newsletter','tfuse'),
    'desc' => __('Here comes some lorem ipsum description for the box shortcode.','tfuse'),
    'category' => 11,
    'options' => array(
        array(
            'name' => __('Title','tfuse'),
            'desc' => __('Enter the title of the Newsletter form','tfuse'),
            'id' => 'tf_shc_newsletter_title',
            'value' => 'Newsletter',
            'type' => 'text'
        ),
        array(
            'name' => __('Text','tfuse'),
            'desc' => __('Specify the newsletter message','tfuse'),
            'id' => 'tf_shc_newsletter_text',
            'value' => 'Sign up for our weekly newsletter to receive updates, news, and promos:',
            'type' => 'textarea'
        ),
        array(
            'name' => __('RSS Feed','tfuse'),
            'desc' => __('Show RSS Feed link?','tfuse'),
            'id' => 'tf_shc_newsletter_rss_feed',
            'value' => 'false',
            'type' => 'checkbox'
        )
    )
);

tf_add_shortcode('newsletter', 'tfuse_newsletter', $atts);
