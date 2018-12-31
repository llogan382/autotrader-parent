/**
 * Send offer forms
 *
 * To override this function in a child theme, copy this file to your child theme's
 * js folder.
 * /js/offer_forms.js
 *
 * @version 1.0
 */

jQuery(document).ready(function(){
    tfuse_send_to_a_friend();
    tfuse_send_contact_seller();
});

function tfuse_send_to_a_friend()
{
    jQuery('#offer_send_friend').on('submit', function()
    {
        var my_error = false,
            offer_send_btn = jQuery(this).find('.send_friend');

        jQuery('#offer_send_friend input,#offer_send_friend textarea').each(function(i)
        {
            var surrounding_element = jQuery(this);
            var value               = jQuery(this).attr('value');
            var check_for           = jQuery(this).attr('name');
            var required            = jQuery(this).hasClass('required');

            if(check_for == 'email_from' || check_for=='email_send_to')
            {
                surrounding_element.removeClass('error valid');
                baseclases = surrounding_element.attr('class');
                if(!value.match(/^\w[\w|\.|\-]+@\w[\w|\.|\-]+\.[a-zA-Z]{2,4}$/)) {
                    surrounding_element.attr('class',baseclases).addClass('error');
                    my_error = true;
                } else {
                    surrounding_element.attr('class',baseclases).addClass('valid');
                }
            }

            if( (required && check_for != 'email_from') || (required && check_for != 'email_send_to') )
            {
                surrounding_element.removeClass('error valid');
                baseclases = surrounding_element.attr('class');
                if(value == '') {
                    surrounding_element.attr('class',baseclases).addClass('error');
                    my_error = true;
                } else {
                    surrounding_element.attr('class',baseclases).addClass('valid');
                }
            }

        });

        if (!my_error)
        {
            var email_from = jQuery('#offer_send_friend #email_from').val();
            var email_send_to = jQuery('#offer_send_friend #email_send_to').val();
            var message = jQuery('#offer_send_friend #message').val();

            var datastring = 'action=tfuse_send_to_a_friend&email_from=' + email_from + '&email_send_to=' + email_send_to + '&message=' + message;

            jQuery.ajax({
                type: 'POST',
                url: tf_script.ajaxurl,
                data: datastring,
                success: function(response)
                {
                    if (response == 'true')
                    {
                        jQuery('#offer_send_friend .offer_send_success').fadeIn(500);
                        offer_send_btn.attr('disabled', 'disabled');
                    }
                    else
                    {
                        jQuery('#offer_send_friend .offer_send_error').fadeIn(500);
                        offer_send_btn.attr('disabled', 'disabled');
                    }
                    /*setTimeout(remove_messages,3000);*/
                }
            });

        }
        return false;
    });
}

function remove_messages()
{
    jQuery('#offer_send_friend .offer_send_success').fadeOut(500);
    jQuery('#offer_send_friend .offer_send_error').fadeOut(500);
}


function tfuse_send_contact_seller()
{
    jQuery('#offer_contact').on('submit', function()
    {
        var current_send_btn = jQuery(this).find('#send_seller_message'),
            my_error = false,
            checkbox = jQuery('#offer_contact .custom-radio .checked').prev().attr('value');

        jQuery('#offer_contact input,#offer_contact textarea').each(function(i)
        {
            var surrounding_element = jQuery(this);
            var value               = jQuery(this).attr('value');
            var check_for           = jQuery(this).attr('name');
            var required            = jQuery(this).hasClass('required');

            if(check_for == 'client_email')
            {
                surrounding_element.removeClass('error valid');
                baseclases = surrounding_element.attr('class');
                if(!value.match(/^\w[\w|\.|\-]+@\w[\w|\.|\-]+\.[a-zA-Z]{2,4}$/)) {
                    surrounding_element.attr('class',baseclases).addClass('error');
                    my_error = true;
                } else {
                    surrounding_element.attr('class',baseclases).addClass('valid');
                }
            }

            if(required && check_for != 'client_email')
            {
                surrounding_element.removeClass('error valid');
                baseclases = surrounding_element.attr('class');
                if(value == '') {
                    surrounding_element.attr('class',baseclases).addClass('error');
                    my_error = true;
                } else {
                    surrounding_element.attr('class',baseclases).addClass('valid');
                }
            }

            var contactWidgetCheckbox = jQuery('.tfuse_contact_widget_gdpr input[type="checkbox"]');
            if( ! contactWidgetCheckbox.is(':checked') ) {
                contactWidgetCheckbox.parents('.tfuse_contact_widget_gdpr').find('label').css("color", "red");
                my_error = true;
            }
            else {
                contactWidgetCheckbox.parents('.tfuse_contact_widget_gdpr').find('label').css("color", "");
            }

        });

        if (!my_error)
        {
            var client_name = jQuery('#offer_contact #client_name').val();
            var client_email = jQuery('#offer_contact #client_email').val();
            var seller_message = jQuery('#offer_contact #seller_message').val();
            var author_id = jQuery('#offer_contact #author_id').val();
            var post_id = jQuery('#offer_contact #post_id').val();

            var datastring = 'action=tfuse_send_contact_seller&client_email=' + client_email + '&client_name=' + client_name + '&seller_message=' + seller_message + '&checkbox=' + checkbox + '&author_id=' + author_id+ '&post_id=' + post_id;

            jQuery.ajax({
                type: 'POST',
                url: tf_script.ajaxurl,
                data: datastring,
                success: function(response)
                {
                    if (response == 'true')
                    {
                        jQuery('#offer_contact .offer_send_success').fadeIn(500);
                        current_send_btn.attr('disabled', 'disabled');
                    }
                    else
                    {
                        jQuery('#offer_contact .offer_send_error').fadeIn(500);
                        current_send_btn.attr('disabled', 'disabled');
                    }
                    //setTimeout(remove_messages,3000);
                }
            });

        }
        return false;
    });
}