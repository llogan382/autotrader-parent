<?php

if (!defined('TFUSE'))
    exit('Direct access forbidden.');

/* ----------------------------------------------------------------------------------- */
/* Initializes all the seek settings option fields. */
/* ----------------------------------------------------------------------------------- */

$options = array(
    'tabs' => array(
        array(
            'name'      => TF_SEEK_HELPER::get_option('seek_property_name_plural', 'Seek Posts'),
            'type'      => 'tab',
            'id'        => TF_THEME_PREFIX . '_seek_general',
            'headings'  => array(
                array(
                    'name'      => __('General Settings', 'tfuse'),
                    'options'   => array(
                        array(
                            'name'      => __('Vehicle Name, singular', 'tfuse'),
                            'desc'      => __('The name of the vehicle being sold. (i.e. property, house, automobile) in singular form.', 'tfuse'),
                            'id'        => 'seek_property_name_singular',
                            'value'     => 'Vehicle',
                            'type'      => 'text'
                        ),
                        array(
                            'name'      => __('Vehicle Name, plural', 'tfuse'),
                            'desc'      => __('The name of the vehicle being sold. (i.e. properties, houses, automobiles) in plural form.', 'tfuse'),
                            'id'        => 'seek_property_name_plural',
                            'value'     => 'Vehicles',
                            'type'      => 'text',
                            'divider'   => true
                        ),
                        array(
                            'name'      => __('Money Currency, singular', 'tfuse'),
                            'desc'      => __('The name of the currency being used. (i.e. Dollar, Euro, Pound) in singular form.', 'tfuse'),
                            'id'        => 'seek_property_currency_singular',
                            'value'     => 'Dollar',
                            'type'      => 'text'
                        ),
                        array(
                            'name'      => __('Money Currency, plural', 'tfuse'),
                            'desc'      => __('The name of the currency being used. (i.e. Dollars, Euros, Pounds) in plural form.', 'tfuse'),
                            'id'        => 'seek_property_currency_plural',
                            'value'     => 'Dollars',
                            'type'      => 'text'
                        ),
                        array(
                            'name'      => __('Money Currency, symbol','tfuse'),
                            'desc'      => __('The symbol of the currency being used. (i.e. $, €, £) in singular form.', 'tfuse'),
                            'id'        => 'seek_property_currency_symbol',
                            'value'     => '$',
                            'type'      => 'text'
                        ),
                        array(
                            'name'      => __('Money Symbol position','tfuse'),
                            'desc'      => __('The symbol the position from against price: Left / Right', 'tfuse'),
                            'id'        => 'seek_property_currency_symbol_pos',
                            'value'     => 'right',
                            'options'   => array(0 => __('Left', 'tfuse'), 1 => __('Right', 'tfuse')),
                            'type'      => 'select'
                        ),
                        array(
                            'name'      => __('Mileage symbol','tfuse'),
                            'desc'      => __('Mileage symbol (i.e. KM, MI)', 'tfuse'),
                            'id'        => 'seek_property_mileage_symbol',
                            'value'     => 'KM',
                            'type'      => 'text'
                        ),
                        array(
                            'name'      => __('Consumption symbol','tfuse'),
                            'desc'      => __('Input consumption symbol', 'tfuse'),
                            'id'        => 'seek_property_consumption_symbol',
                            'value'     => 'L/100km',
                            'type'      => 'text'
                        ),
                        array(
                            'name'      => __('VAT rate','tfuse'),
                            'desc'      => __('Enter the VAT rate.Ex: 20', 'tfuse'),
                            'id'        => 'seek_property_vat_rate',
                            'value'     => '0',
                            'type'      => 'text'
                        ),
                        array(
                            'name'      => __('VAT rate show format','tfuse'),
                            'desc'      => __('Enter the format for show VAT rate.Ex:%%price%% EUR (Net)  %%VAT%% VAT', 'tfuse'),
                            'id'        => 'seek_property_vat_show',
                            'value'     => '%%price%% EUR (Net)  %%VAT%%% VAT',
                            'type'      => 'text'
                        ),
                        array(
                            'name'      => __('Subject for email','tfuse'),
                            'desc'      => __('Enter the subject for email for text box button', 'tfuse'),
                            'id'        => 'seek_property_subject_email',
                            'value'     => 'New Enquire',
                            'type'      => 'text'
                        ),
                        array(
                            'name'      => __('Content for email','tfuse'),
                            'desc'      => __('Enter the content for email for text box button', 'tfuse'),
                            'id'        => 'seek_property_content_email',
                            'value'     => "I'm \n\n interesting out more about this car on your website:",
                            'type'      => 'text'
                        ),
                        array(
                            'name'      => __('Text Box','tfuse'),
                            'desc'      => __('This textarea for your custom html after vehicle. This text is for all vehicles, and apears after vehicle tabs.<br>You can use %%email_content%%, %%link%% and %%email_subject%% for specifies the content of email content and email subject', 'tfuse'),
                            'id'        => 'seek_property_text_box',
                            'value'     => '<p><a href="#" class="btn btn_big btn_white"><span>CALL US AT: <strong>1 800 956 756</strong></span></a>
                <a href="mailto:email@themefuse.com?subject=%%email_subject%%&Content-type=text/html&body=%%email_content%% %%link%%" class="btn btn_big btn_orange"><span>EMAIL US ABOUT THIS CAR</span></a></p>
            	<p><em>Prices are subject to change. Please see our <a href="#">Privacy Policy</a> for more info</em></p>',
                            'type'      => 'textarea'
                        )
                    )
                ),
                array(
                    'name'      => __('Details Tabs Template', 'tfuse'),
                    'options'   => array(
                        array(
                            'id' => TF_THEME_PREFIX . '_details_tabs',
                            'btn_labels'=>array('Add Tab','Delete Tab'),
                            'class' => 'tf-offer-tabs',
                            'style' => '',
                            'default_value' => array(
                                'tab_title'=>'',
                                'tab_content'=>''
                            ),
                            'value' => array(
                                array(
                                    'tab_title'     =>  'OVERVIEW',
                                    'tab_content'   =>  '%%interior_features%%%%exterior_features%%%%safely_features%%%%extras%%'
                                ),
                                array(
                                    'tab_title'     =>  'DESCRIPTION',
                                    'tab_content'   =>  '<h3>Full Description</h3>%%content%%'
                                ),
                                array(
                                    'tab_title'     =>  'CONTACT US',
                                    'tab_content'   =>  '%%contact_seller%%'
                                ),
                                array(
                                    'tab_title'     =>  'SEND TO A FRIEND',
                                    'tab_content'   =>  '%%send_to_a_friend%%'
                                )
                            ),
                            'type' => 'div_table',
                            'columns' => array(
                                array(
                                    'id' =>  'tab_title',
                                    'type' => 'text',
                                    'properties' => array('placeholder' => __('Tab name', 'tfuse'))
                                ),
                                array(
                                    'id' => 'tab_content',
                                    'value' => '',
                                    'type' => 'textarea',
                                    'properties' => array('placeholder' => __('Tab content', 'tfuse'))
                                )
                            )
                        )
                    )
                )
            )
        ),
    )
);