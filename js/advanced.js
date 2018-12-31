jQuery(document).ready(function() {
    var $ = jQuery;

    var autotrader_comments_gdpr = jQuery('#autotrader_comments_gdpr');
    if (autotrader_comments_gdpr.is(':checked')) {
        jQuery('.autotrader_comments_gdpr_text').show();
    }
    else {
        jQuery('.autotrader_comments_gdpr_text').hide();
    }
    
    autotrader_comments_gdpr.on('change', function () {
        if (jQuery(this).is(':checked')) {
            jQuery('.autotrader_comments_gdpr_text').show();
        }
        else {
            jQuery('.autotrader_comments_gdpr_text').hide();
        }
    });
    
    var autotrader_newsletter_gdpr = jQuery('#autotrader_newsletter_gdpr');
    if (autotrader_newsletter_gdpr.is(':checked')) {
        jQuery('.autotrader_newsletter_gdpr_text').show();
    }
    else {
        jQuery('.autotrader_newsletter_gdpr_text').hide();
    }
    
    autotrader_newsletter_gdpr.on('change', function () {
        if (jQuery(this).is(':checked')) {
            jQuery('.autotrader_newsletter_gdpr_text').show();
        }
        else {
            jQuery('.autotrader_newsletter_gdpr_text').hide();
        }
    });

    var autotrader_contact_widget_gdpr = jQuery('#autotrader_contact_widget_gdpr');
    if (autotrader_contact_widget_gdpr.is(':checked')) {
        jQuery('.autotrader_contact_widget_gdpr_text').show();
    }
    else {
        jQuery('.autotrader_contact_widget_gdpr_text').hide();
    }

    autotrader_contact_widget_gdpr.on('change', function () {
        if (jQuery(this).is(':checked')) {
            jQuery('.autotrader_contact_widget_gdpr_text').show();
        }
        else {
            jQuery('.autotrader_contact_widget_gdpr_text').hide();
        }
    });

    if( jQuery('#seek_property_year').length ) {
        jQuery('#seek_property_year').MonthPicker({
            ShowIcon: false,
            UseInputMask: true
        });
    }

    // hide options of select for type fullbanner and featured slider's
    jQuery('.over_thumb ').bind('click', function(){
        window.setTimeout(function(){
            var sel = jQuery('#slider_design_type').val();
            if(sel == 'fullbanner' || sel == 'featured'){
                jQuery('#slider_type').html('<option value="">Choose your slider type</option><option value="custom">Manually, I\'ll upload the images myself</option>');
            }
            else{
                jQuery('#slider_type').html('<option value="">Choose your slider type</option><option value="custom">Manually, I\'ll upload the images myself</option><option value="categories">Automatically, fetch images from categories</option><option value="posts">Automatically, fetch images from posts</option>');
            }
        },12);
    });
    // transform on complete bhp in kw and kw in bhp
    jQuery('#seek_property_engine_power_bhp').keyup(function(){
        bhp = parseInt( jQuery(this).val() ) * 0.7457;
        bhp = Math.round(bhp);
        if( isNaN(bhp) ) bhp = 0;
        jQuery('#seek_property_engine_power_kw').val(bhp);
    });

    jQuery('#seek_property_engine_power_kw').keyup(function(){
        kw = parseInt( jQuery(this).val() ) * 1.355818;
        kw = Math.round(kw);
        if( isNaN(kw) ) kw = 0;
        jQuery('#seek_property_engine_power_bhp').val(kw);
    });
    // transform on complete price
    tfuse_vat_rate = parseInt(tfuse_vat_rate);
    if(tfuse_vat_rate!=0 && !isNaN(tfuse_vat_rate)){
        vat_price = jQuery('.seek_property_vat_price');
        vat_price.show();
        vat_price.next('.divider').show();
        jQuery('#seek_property_price').keyup(function(){
            price = parseInt( jQuery(this).val() / (tfuse_vat_rate/100 + 1) );
            price = Math.round(price);
            if( isNaN(price) ) price = 0;
            jQuery('#seek_property_vat_price').val(price);
        });
        jQuery('#seek_property_vat_price').keyup(function(){
            price = parseInt( jQuery(this).val()*(100+tfuse_vat_rate)/100 );
            price = Math.round(price);
            if( isNaN(price) ) price = 0;
            jQuery('#seek_property_price').val(price);
        });
    }
    else {
        vat_price = jQuery('.seek_property_vat_price');
        vat_price.hide();
        vat_price.next('.divider').hide();
    }

    // general banners (framework)
    from_general = jQuery('#autotrader_top_ads_space');
    if(from_general.is(':checked'))
        jQuery('.autotrader_top_ads_image,.autotrader_top_ads_url,.autotrader_top_ads_adsense').hide();
    else
        jQuery('.autotrader_top_ads_image,.autotrader_top_ads_url,.autotrader_top_ads_adsense').show();

    from_general.on('change',function () {
        if(jQuery(this).is(':checked')){
            jQuery('.autotrader_top_ads_image,.autotrader_top_ads_url,.autotrader_top_ads_adsense').hide();
        }
        else{
            jQuery('.autotrader_top_ads_image,.autotrader_top_ads_url,.autotrader_top_ads_adsense').show();
        }
    });

    from_general2 = jQuery('#autotrader_content_ads_space');
    if(from_general2.is(':checked'))
        jQuery('.autotrader_hook_image_admin,.autotrader_hook_url_admin,.autotrader_hook_adsense_admin').hide();
    else
        jQuery('.autotrader_hook_image_admin,.autotrader_hook_url_admin,.autotrader_hook_adsense_admin').show();

    from_general2.on('change',function () {
        if(jQuery(this).is(':checked')){
            jQuery('.autotrader_hook_image_admin,.autotrader_hook_url_admin,.autotrader_hook_adsense_admin').hide();
        }
        else{
            jQuery('.autotrader_hook_image_admin,.autotrader_hook_url_admin,.autotrader_hook_adsense_admin').show();
        }
    });


    var options = new Array();

    options['autotrader_page_title'] = jQuery('#autotrader_page_title').val();
    jQuery('#autotrader_page_title').bind('change', function() {
        options['autotrader_page_title'] = jQuery(this).val();
        tfuse_toggle_options(options);
    });
    options['autotrader_header_element'] = jQuery('#autotrader_header_element').val();
    jQuery('#autotrader_header_element').bind('change', function() {
        options['autotrader_header_element'] = jQuery(this).val();
        tfuse_toggle_options(options);
    });
    options['autotrader_footer_element'] = jQuery('#autotrader_footer_element').val();
    jQuery('#autotrader_footer_element').bind('change', function() {
        options['autotrader_footer_element'] = jQuery(this).val();
        tfuse_toggle_options(options);
    });
    options['autotrader_header_element_cat'] = jQuery('#autotrader_header_element_cat').val();
    jQuery('#autotrader_header_element_cat').bind('change', function() {
        options['autotrader_header_element_cat'] = jQuery(this).val();
        tfuse_toggle_options(options);
    });
    options['autotrader_footer_element_cat'] = jQuery('#autotrader_footer_element_cat').val();
    jQuery('#autotrader_footer_element_cat').bind('change', function() {
        options['autotrader_footer_element_cat'] = jQuery(this).val();
        tfuse_toggle_options(options);
    });
    options['autotrader_homepage_category'] = jQuery('#autotrader_homepage_category').val();
    jQuery('#autotrader_homepage_category').bind('change', function() {
        options['autotrader_homepage_category'] = jQuery(this).val();
        tfuse_toggle_options(options);
    });
    options['autotrader_blogpage_category'] = jQuery('#autotrader_blogpage_category').val();
    jQuery('#autotrader_blogpage_category').bind('change', function() {
        options['autotrader_blogpage_category'] = jQuery(this).val();
        tfuse_toggle_options(options);
    });
    options['autotrader_header_element_blog'] = jQuery('#autotrader_header_element_blog').val();
    jQuery('#autotrader_header_element_blog').bind('change', function() {
        options['autotrader_header_element_blog'] = jQuery(this).val();
        tfuse_toggle_options(options);
    });
    options['autotrader_footer_element_blog'] = jQuery('#autotrader_footer_element_blog').val();
    jQuery('#autotrader_footer_element_blog').bind('change', function() {
        options['autotrader_footer_element_blog'] = jQuery(this).val();
        tfuse_toggle_options(options);
    });
    options['autotrader_header_element_search'] = jQuery('#autotrader_header_element_search').val();
    jQuery('#autotrader_header_element_search').bind('change', function() {
        options['autotrader_header_element_search'] = jQuery(this).val();
        tfuse_toggle_options(options);
    });
    options['autotrader_footer_element_search'] = jQuery('#autotrader_footer_element_search').val();
    jQuery('#autotrader_footer_element_search').bind('change', function() {
        options['autotrader_footer_element_search'] = jQuery(this).val();
        tfuse_toggle_options(options);
    });
    options['autotrader_header_element_search_seek'] = jQuery('#autotrader_header_element_search_seek').val();
    jQuery('#autotrader_header_element_search_seek').bind('change', function() {
        options['autotrader_header_element_search_seek'] = jQuery(this).val();
        tfuse_toggle_options(options);
    });
    options['autotrader_footer_element_search_seek'] = jQuery('#autotrader_footer_element_search_seek').val();
    jQuery('#autotrader_footer_element_search_seek').bind('change', function() {
        options['autotrader_footer_element_search_seek'] = jQuery(this).val();
        tfuse_toggle_options(options);
    });
    options['autotrader_header_element_404'] = jQuery('#autotrader_header_element_404').val();
    jQuery('#autotrader_header_element_404').bind('change', function() {
        options['autotrader_header_element_404'] = jQuery(this).val();
        tfuse_toggle_options(options);
    });
    options['autotrader_footer_element_404'] = jQuery('#autotrader_footer_element_404').val();
    jQuery('#autotrader_footer_element_404').bind('change', function() {
        options['autotrader_footer_element_404'] = jQuery(this).val();
        tfuse_toggle_options(options);
    });
    options['autotrader_header_element_tag'] = jQuery('#autotrader_header_element_tag').val();
    jQuery('#autotrader_header_element_tag').bind('change', function() {
        options['autotrader_header_element_tag'] = jQuery(this).val();
        tfuse_toggle_options(options);
    });
    options['autotrader_footer_element_tag'] = jQuery('#autotrader_footer_element_tag').val();
    jQuery('#autotrader_footer_element_tag').bind('change', function() {
        options['autotrader_footer_element_tag'] = jQuery(this).val();
        tfuse_toggle_options(options);
    });
    options['autotrader_header_element_archive'] = jQuery('#autotrader_header_element_archive').val();
    jQuery('#autotrader_header_element_archive').bind('change', function() {
        options['autotrader_header_element_archive'] = jQuery(this).val();
        tfuse_toggle_options(options);
    });
    options['autotrader_footer_element_archive'] = jQuery('#autotrader_footer_element_archive').val();
    jQuery('#autotrader_footer_element_archive').bind('change', function() {
        options['autotrader_footer_element_archive'] = jQuery(this).val();
        tfuse_toggle_options(options);
    });
    options['autotrader_home_top_ad_space'] = jQuery('#autotrader_home_top_ad_space').val();
    jQuery('#autotrader_home_top_ad_space').bind('change', function() {
        options['autotrader_home_top_ad_space'] = jQuery(this).val();
        tfuse_toggle_options(options);
    });
    options['autotrader_blog_top_ad_space'] = jQuery('#autotrader_blog_top_ad_space').val();
    jQuery('#autotrader_blog_top_ad_space').bind('change', function() {
        options['autotrader_blog_top_ad_space'] = jQuery(this).val();
        tfuse_toggle_options(options);
    });
    options['autotrader_top_ad_space'] = jQuery('#autotrader_top_ad_space').val();
    jQuery('#autotrader_top_ad_space').bind('change', function() {
        options['autotrader_top_ad_space'] = jQuery(this).val();
        tfuse_toggle_options(options);
    });
    options['autotrader_home_hook_space'] = jQuery('#autotrader_home_hook_space').val();
    jQuery('#autotrader_home_hook_space').bind('change', function() {
        options['autotrader_home_hook_space'] = jQuery(this).val();
        tfuse_toggle_options(options);
    });
    options['autotrader_blog_hook_space'] = jQuery('#autotrader_blog_hook_space').val();
    jQuery('#autotrader_blog_hook_space').bind('change', function() {
        options['autotrader_blog_hook_space'] = jQuery(this).val();
        tfuse_toggle_options(options);
    });
    options['autotrader_home_bfcontent_ads_space'] = jQuery('#autotrader_home_bfcontent_ads_space').val();
    jQuery('#autotrader_home_bfcontent_ads_space').bind('change', function() {
        options['autotrader_home_bfcontent_ads_space'] = jQuery(this).val();
        tfuse_toggle_options(options);
    });
    options['autotrader_home_bfcontent_number'] = jQuery('#autotrader_home_bfcontent_number').val();
    jQuery('#autotrader_home_bfcontent_number').bind('change', function() {
        options['autotrader_home_bfcontent_number'] = jQuery(this).val();
        tfuse_toggle_options(options);
    });
    options['autotrader_home_bfcontent_type'] = jQuery('#autotrader_home_bfcontent_type').val();
    jQuery('#autotrader_home_bfcontent_type').bind('change', function() {
        options['autotrader_home_bfcontent_type'] = jQuery(this).val();
        tfuse_toggle_options(options);
    });
    options['autotrader_blog_bfcontent_ads_space'] = jQuery('#autotrader_blog_bfcontent_ads_space').val();
    jQuery('#autotrader_blog_bfcontent_ads_space').bind('change', function() {
        options['autotrader_blog_bfcontent_ads_space'] = jQuery(this).val();
        tfuse_toggle_options(options);
    });
    options['autotrader_blog_bfcontent_number'] = jQuery('#autotrader_blog_bfcontent_number').val();
    jQuery('#autotrader_blog_bfcontent_number').bind('change', function() {
        options['autotrader_blog_bfcontent_number'] = jQuery(this).val();
        tfuse_toggle_options(options);
    });
    options['autotrader_blog_bfcontent_type'] = jQuery('#autotrader_blog_bfcontent_type').val();
    jQuery('#autotrader_blog_bfcontent_type').bind('change', function() {
        options['autotrader_blog_bfcontent_type'] = jQuery(this).val();
        tfuse_toggle_options(options);
    });
    options['autotrader_hook_space'] = jQuery('#autotrader_hook_space').val();
    jQuery('#autotrader_hook_space').bind('change', function() {
        options['autotrader_hook_space'] = jQuery(this).val();
        tfuse_toggle_options(options);
    });
    options['autotrader_bfcontent_type'] = jQuery('#autotrader_bfcontent_type').val();
    jQuery('#autotrader_bfcontent_type').bind('change', function() {
        options['autotrader_bfcontent_type'] = jQuery(this).val();
        tfuse_toggle_options(options);
    });
    options['autotrader_bfcontent_ads_space'] = jQuery('#autotrader_bfcontent_ads_space').val();
    jQuery('#autotrader_bfcontent_ads_space').bind('change', function() {
        options['autotrader_bfcontent_ads_space'] = jQuery(this).val();
        tfuse_toggle_options(options);
    });
    options['autotrader_bfcontent_type1'] = jQuery('#autotrader_bfcontent_type1').val();
    jQuery('#autotrader_bfcontent_type1').bind('change', function() {
        options['autotrader_bfcontent_type1'] = jQuery(this).val();
        tfuse_toggle_options(options);
    });
    options['autotrader_bfcontent_number'] = jQuery('#autotrader_bfcontent_number').val();
    jQuery('#autotrader_bfcontent_number').bind('change', function() {
        options['autotrader_bfcontent_number'] = jQuery(this).val();
        tfuse_toggle_options(options);
    });
    options['posts_select_type'] = jQuery('#posts_select_type').val();
    jQuery('#posts_select_type').bind('change', function() {
        options['posts_select_type'] = jQuery(this).val();
        tfuse_toggle_options(options);
    });
    options['posts_select_population_type'] = jQuery('#posts_select_population_type').val();
    jQuery('#posts_select_population_type').bind('change', function() {
        options['posts_select_population_type'] = jQuery(this).val();
        tfuse_toggle_options(options);
    });

    tfuse_toggle_options(options);

    function tfuse_toggle_options(options)
    {
        if(options['autotrader_page_title']=='custom_title')
            jQuery('.autotrader_custom_title').show();
        else
            jQuery('.autotrader_custom_title').hide();

        // slider options of post,categories,tags
        if(options['posts_select_type'] =='categories'){
            jQuery('input[name="posts_select_portf_entries"]').parent().parent().parent().parent().hide();
            jQuery('input[name="posts_select_cat_entries"]').parent().parent().parent().parent().show();
        }
        else{
            jQuery('input[name="posts_select_portf_entries"]').parent().parent().parent().parent().show();
            jQuery('input[name="posts_select_cat_entries"]').parent().parent().parent().parent().hide();
        }

        if(options['posts_select_population_type']=='latest'){
            jQuery('#sliders_posts_number').parent().parent().parent().show();
            jQuery('input[name="posts_select_portf_entries"],input[name="posts_select_cat_entries"]').parent().parent().parent().parent().hide();
        }
        else if(options['posts_select_population_type']=='specific'){
            jQuery('#sliders_posts_number').parent().parent().parent().hide();
            if(options['posts_select_type'] =='categories')
                jQuery('input[name="posts_select_cat_entries]').parent().parent().parent().parent().show();
            else
                jQuery('input[name="posts_select_portf_entries"]').parent().parent().parent().parent().show();
        }

        if(options['autotrader_header_element']=='image'){
            jQuery('.autotrader_header_image,.autotrader_header_title').show();
            jQuery('.autotrader_select_slider,.autotrader_page_map,.autotrader_map_text,.autotrader_map_zoom').hide();
        }
        else if(options['autotrader_header_element']=='slider'){
            jQuery('.autotrader_select_slider').show();
            jQuery('.autotrader_header_image,.autotrader_header_title,.autotrader_page_map,.autotrader_map_text,.autotrader_map_zoom').hide();
        }
        else if(options['autotrader_header_element']=='map'){
            jQuery('.autotrader_page_map,.autotrader_map_text,.autotrader_map_zoom').show();
            jQuery('.autotrader_header_image,.autotrader_header_title,.autotrader_select_slider').hide();
        }
        else{
            jQuery('.autotrader_header_image,.autotrader_header_title,.autotrader_select_slider,.autotrader_page_map,.autotrader_map_text,.autotrader_map_zoom').hide();
        }

        if(options['autotrader_footer_element']=='slider')
            jQuery('.autotrader_select_slider_footer').show();
        else
            jQuery('.autotrader_select_slider_footer').hide();

        if(options['autotrader_header_element_cat']=='image'){
            jQuery('#autotrader_header_image_cat').parent().parent().parent().parent().parent().parent().show();
            jQuery('#autotrader_header_title_cat').parent().parent().show();
            jQuery('#autotrader_page_map_cat_x').parent().parent().parent().hide();
            jQuery('#autotrader_select_slider_cat,#autotrader_map_text_cat,#autotrader_map_zoom_cat').parent().parent().hide();
        }
        else if(options['autotrader_header_element_cat']=='slider'){
            jQuery('#autotrader_header_image_cat').parent().parent().parent().parent().parent().parent().hide();
            jQuery('#autotrader_page_map_cat_x').parent().parent().parent().hide();
            jQuery('#autotrader_map_text_cat,#autotrader_map_zoom_cat,#autotrader_header_title_cat').parent().parent().hide();
            jQuery('#autotrader_select_slider_cat').parent().parent().show();
        }
        else if(options['autotrader_header_element_cat']=='map'){
            jQuery('#autotrader_header_image_cat').parent().parent().parent().parent().parent().parent().hide();
            jQuery('#autotrader_page_map_cat_x').parent().parent().parent().show();
            jQuery('#autotrader_select_slider_cat,#autotrader_map_text_cat,#autotrader_map_zoom_cat').parent().parent().show();
            jQuery('#autotrader_select_slider_cat,#autotrader_header_title_cat').parent().parent().hide();
        }
        else{
            jQuery('#autotrader_header_image_cat').parent().parent().parent().parent().parent().parent().hide();
            jQuery('#autotrader_page_map_cat_x').parent().parent().parent().hide();
            jQuery('#autotrader_select_slider_cat,#autotrader_map_text_cat,#autotrader_map_zoom_cat,#autotrader_header_title_cat').parent().parent().hide();
        }

        if(options['autotrader_footer_element_cat']=='slider'){
            jQuery('#autotrader_select_slider_footer_cat').parent().parent().show();
        }
        else{
            jQuery('#autotrader_select_slider_footer_cat').parent().parent().hide();
        }

        if(options['autotrader_homepage_category']=='specific'){
            jQuery('.autotrader_home_page,.autotrader_use_page_options').hide();
            jQuery('.autotrader_categories_select_categ').show();
            jQuery('#autotrader_content_top,#autotrader_header_element,#autotrader_home_top_ad_space').closest('.postbox').show();
        }
        else if(options['autotrader_homepage_category']=='page'){
            jQuery('.autotrader_home_page,.autotrader_use_page_options').show();
            jQuery('.autotrader_categories_select_categ').hide();

            if(jQuery('#autotrader_use_page_options').is(':checked')) jQuery('#autotrader_content_top,#autotrader_header_element,#autotrader_home_top_ad_space').closest('.postbox').hide();
            jQuery('#autotrader_use_page_options').on('change',function () {
                if(jQuery(this).is(':checked'))
                    jQuery('#autotrader_content_top,#autotrader_header_element,#autotrader_home_top_ad_space').closest('.postbox').hide();
                else
                    jQuery('#autotrader_content_top,#autotrader_header_element,#autotrader_home_top_ad_space').closest('.postbox').show();
            });
        }
        else{
            jQuery('.autotrader_home_page,.autotrader_use_page_options,.autotrader_categories_select_categ').hide();
            jQuery('#autotrader_content_top,#autotrader_header_element,#autotrader_home_top_ad_space').closest('.postbox').show();
        }

        if(options['autotrader_blogpage_category']=='specific')
            jQuery('.autotrader_categories_select_categ_blog').show();
        else
            jQuery('.autotrader_categories_select_categ_blog').hide();

        // blog
        if(options['autotrader_header_element_blog'] == 'image'){
            jQuery('.autotrader_header_image_blog,.autotrader_header_title_blog').show();
            jQuery('.autotrader_select_slider_blog,.autotrader_page_map_blog,.autotrader_map_text_blog,.autotrader_map_zoom_blog').hide();
        }
        else if(options['autotrader_header_element_blog']=='slider'){
            jQuery('.autotrader_select_slider_blog').show();
            jQuery('.autotrader_header_image_blog,.autotrader_header_title_blog,.autotrader_page_map_blog,.autotrader_map_text_blog,.autotrader_map_zoom_blog').hide();
        }
        else if(options['autotrader_header_element_blog']=='map'){
            jQuery('.autotrader_page_map_blog,.autotrader_map_text_blog,.autotrader_map_zoom_blog').show();
            jQuery('.autotrader_header_image_blog,.autotrader_header_title_blog,.autotrader_select_slider_blog').hide();
        }
        else{
            jQuery('.autotrader_header_image_blog,.autotrader_header_title_blog,.autotrader_select_slider_blog,.autotrader_page_map_blog,.autotrader_map_text_blog,.autotrader_map_zoom_blog').hide();
        }
        if(options['autotrader_footer_element_blog']=='slider')
            jQuery('.autotrader_select_slider_footer_blog').show();
        else
            jQuery('.autotrader_select_slider_footer_blog').hide();
        // end blog

        // search
        if(options['autotrader_header_element_search'] == 'image'){
            jQuery('.autotrader_header_image_search,.autotrader_header_title_search').show();
            jQuery('.autotrader_select_slider_search,.autotrader_page_map_search,.autotrader_map_text_search,.autotrader_map_zoom_search').hide();
        }
        else if(options['autotrader_header_element_search']=='slider'){
            jQuery('.autotrader_select_slider_search').show();
            jQuery('.autotrader_header_image_search,.autotrader_header_title_search,.autotrader_page_map_search,.autotrader_map_text_search,.autotrader_map_zoom_search').hide();
        }
        else if(options['autotrader_header_element_search']=='map'){
            jQuery('.autotrader_page_map_search,.autotrader_map_text_search,.autotrader_map_zoom_search').show();
            jQuery('.autotrader_header_image_search,.autotrader_header_title_search,.autotrader_select_slider_search').hide();
        }
        else{
            jQuery('.autotrader_header_image_search,.autotrader_header_title_search,.autotrader_select_slider_search,.autotrader_page_map_search,.autotrader_map_text_search,.autotrader_map_zoom_search').hide();
        }
        if(options['autotrader_footer_element_search']=='slider')
            jQuery('.autotrader_select_slider_footer_search').show();
        else
            jQuery('.autotrader_select_slider_footer_search').hide();
        // end search

        // search_seek
        if(options['autotrader_header_element_search_seek'] == 'image'){
            jQuery('.autotrader_header_image_search_seek,.autotrader_header_title_search_seek').show();
            jQuery('.autotrader_select_slider_search_seek,.autotrader_page_map_search_seek,.autotrader_map_text_search_seek,.autotrader_map_zoom_search_seek').hide();
        }
        else if(options['autotrader_header_element_search_seek']=='slider'){
            jQuery('.autotrader_select_slider_search_seek').show();
            jQuery('.autotrader_header_image_search_seek,.autotrader_header_title_search_seek,.autotrader_page_map_search_seek,.autotrader_map_text_search_seek,.autotrader_map_zoom_search_seek').hide();
        }
        else if(options['autotrader_header_element_search_seek']=='map'){
            jQuery('.autotrader_page_map_search_seek,.autotrader_map_text_search_seek,.autotrader_map_zoom_search_seek').show();
            jQuery('.autotrader_header_image_search_seek,.autotrader_header_title_search_seek,.autotrader_select_slider_search_seek').hide();
        }
        else{
            jQuery('.autotrader_header_image_search_seek,.autotrader_header_title_search_seek,.autotrader_select_slider_search_seek,.autotrader_page_map_search_seek,.autotrader_map_text_search_seek,.autotrader_map_zoom_search_seek').hide();
        }
        if(options['autotrader_footer_element_search_seek']=='slider')
            jQuery('.autotrader_select_slider_footer_search_seek').show();
        else
            jQuery('.autotrader_select_slider_footer_search_seek').hide();
        // end search_seek

        // 404
        if(options['autotrader_header_element_404'] == 'image'){
            jQuery('.autotrader_header_image_404,.autotrader_header_title_404').show();
            jQuery('.autotrader_select_slider_404,.autotrader_page_map_404,.autotrader_map_text_404,.autotrader_map_zoom_404').hide();
        }
        else if(options['autotrader_header_element_404']=='slider'){
            jQuery('.autotrader_select_slider_404').show();
            jQuery('.autotrader_header_image_404,.autotrader_header_title_404,.autotrader_page_map_404,.autotrader_map_text_404,.autotrader_map_zoom_404').hide();
        }
        else if(options['autotrader_header_element_404']=='map'){
            jQuery('.autotrader_page_map_404,.autotrader_map_text_404,.autotrader_map_zoom_404').show();
            jQuery('.autotrader_header_image_404,.autotrader_header_title_404,.autotrader_select_slider_404').hide();
        }
        else{
            jQuery('.autotrader_header_image_404,.autotrader_header_title_404,.autotrader_select_slider_404,.autotrader_page_map_404,.autotrader_map_text_404,.autotrader_map_zoom_404').hide();
        }
        if(options['autotrader_footer_element_404']=='slider')
            jQuery('.autotrader_select_slider_footer_404').show();
        else
            jQuery('.autotrader_select_slider_footer_404').hide();
        // end 404

        // tag
        if(options['autotrader_header_element_tag'] == 'image'){
            jQuery('.autotrader_header_image_tag,.autotrader_header_title_tag').show();
            jQuery('.autotrader_select_slider_tag,.autotrader_page_map_tag,.autotrader_map_text_tag,.autotrader_map_zoom_tag').hide();
        }
        else if(options['autotrader_header_element_tag']=='slider'){
            jQuery('.autotrader_select_slider_tag').show();
            jQuery('.autotrader_header_image_tag,.autotrader_header_title_tag,.autotrader_page_map_tag,.autotrader_map_text_tag,.autotrader_map_zoom_tag').hide();
        }
        else if(options['autotrader_header_element_tag']=='map'){
            jQuery('.autotrader_page_map_tag,.autotrader_map_text_tag,.autotrader_map_zoom_tag').show();
            jQuery('.autotrader_header_image_tag,.autotrader_header_title_tag,.autotrader_select_slider_tag').hide();
        }
        else{
            jQuery('.autotrader_header_image_tag,.autotrader_header_title_tag,.autotrader_select_slider_tag,.autotrader_page_map_tag,.autotrader_map_text_tag,.autotrader_map_zoom_tag').hide();
        }
        if(options['autotrader_footer_element_tag']=='slider')
            jQuery('.autotrader_select_slider_footer_tag').show();
        else
            jQuery('.autotrader_select_slider_footer_tag').hide();
        // end tag

        // archive
        if(options['autotrader_header_element_archive'] == 'image'){
            jQuery('.autotrader_header_image_archive,.autotrader_header_title_archive').show();
            jQuery('.autotrader_select_slider_archive,.autotrader_page_map_archive,.autotrader_map_text_archive,.autotrader_map_zoom_archive').hide();
        }
        else if(options['autotrader_header_element_archive']=='slider'){
            jQuery('.autotrader_select_slider_archive').show();
            jQuery('.autotrader_header_image_archive,.autotrader_header_title_archive,.autotrader_page_map_archive,.autotrader_map_text_archive,.autotrader_map_zoom_archive').hide();
        }
        else if(options['autotrader_header_element_archive']=='map'){
            jQuery('.autotrader_page_map_archive,.autotrader_map_text_archive,.autotrader_map_zoom_archive').show();
            jQuery('.autotrader_header_image_archive,.autotrader_header_title_archive,.autotrader_select_slider_archive').hide();
        }
        else{
            jQuery('.autotrader_header_image_archive,.autotrader_header_title_archive,.autotrader_select_slider_archive,.autotrader_page_map_archive,.autotrader_map_text_archive,.autotrader_map_zoom_archive').hide();
        }
        if(options['autotrader_footer_element_archive']=='slider')
            jQuery('.autotrader_select_slider_footer_archive').show();
        else
            jQuery('.autotrader_select_slider_footer_archive').hide();
        // end archive

        // banners ...
        // homepage
        if(options['autotrader_home_top_ad_space']=='true')
            jQuery('.autotrader_home_top_ad_image,.autotrader_home_top_ad_url,.autotrader_home_top_ad_adsense').show();
        else
            jQuery('.autotrader_home_top_ad_image,.autotrader_home_top_ad_url,.autotrader_home_top_ad_adsense').hide();

        if(options['autotrader_home_hook_space']=='true')
            jQuery('.autotrader_home_hook_image,.autotrader_home_hook_url,.autotrader_home_hook_adsense').show();
        else
            jQuery('.autotrader_home_hook_image,.autotrader_home_hook_url,.autotrader_home_hook_adsense').hide();

        jQuery('.autotrader_home_bfcontent_type,.autotrader_home_bfcontent_number,.autotrader_home_bfcontent_ads_image1,.autotrader_home_bfcontent_ads_url1,.autotrader_home_bfcontent_ads_adsense1,.autotrader_home_bfcontent_ads_image2,.autotrader_home_bfcontent_ads_url2,.autotrader_home_bfcontent_ads_adsense2,.autotrader_home_bfcontent_ads_image3,.autotrader_home_bfcontent_ads_url3,.autotrader_home_bfcontent_ads_adsense3,.autotrader_home_bfcontent_ads_image4,.autotrader_home_bfcontent_ads_url4,.autotrader_home_bfcontent_ads_adsense4,.autotrader_home_bfcontent_ads_image5,.autotrader_home_bfcontent_ads_url5,.autotrader_home_bfcontent_ads_adsense5,.autotrader_home_bfcontent_ads_image6,.autotrader_home_bfcontent_ads_url6,.autotrader_home_bfcontent_ads_adsense6,.autotrader_home_bfcontent_ads_image7,.autotrader_home_bfcontent_ads_url7,.autotrader_home_bfcontent_ads_adsense7').hide();
        if(options['autotrader_home_bfcontent_ads_space']=='true'){
            jQuery('.autotrader_home_bfcontent_type,.autotrader_home_bfcontent_number').show();
            if(options['autotrader_home_bfcontent_type']=='image'){
                if(options['autotrader_home_bfcontent_number']=='one'){
                    jQuery('.autotrader_home_bfcontent_ads_image1,.autotrader_home_bfcontent_ads_url1').show();
                }
                else if(options['autotrader_home_bfcontent_number']=='two'){
                    jQuery('.autotrader_home_bfcontent_ads_image1,.autotrader_home_bfcontent_ads_url1,.autotrader_home_bfcontent_ads_image2,.autotrader_home_bfcontent_ads_url2').show();
                }
                else if(options['autotrader_home_bfcontent_number']=='three'){
                    jQuery('.autotrader_home_bfcontent_ads_image1,.autotrader_home_bfcontent_ads_url1,.autotrader_home_bfcontent_ads_image2,.autotrader_home_bfcontent_ads_url2,.autotrader_home_bfcontent_ads_image3,.autotrader_home_bfcontent_ads_url3').show();
                }
                else if(options['autotrader_home_bfcontent_number']=='four'){
                    jQuery('.autotrader_home_bfcontent_ads_image1,.autotrader_home_bfcontent_ads_url1,.autotrader_home_bfcontent_ads_image2,.autotrader_home_bfcontent_ads_url2,.autotrader_home_bfcontent_ads_image3,.autotrader_home_bfcontent_ads_url3,.autotrader_home_bfcontent_ads_image4,.autotrader_home_bfcontent_ads_url4').show();
                }
                else if(options['autotrader_home_bfcontent_number']=='five'){
                    jQuery('.autotrader_home_bfcontent_ads_image1,.autotrader_home_bfcontent_ads_url1,.autotrader_home_bfcontent_ads_image2,.autotrader_home_bfcontent_ads_url2,.autotrader_home_bfcontent_ads_image3,.autotrader_home_bfcontent_ads_url3,.autotrader_home_bfcontent_ads_image4,.autotrader_home_bfcontent_ads_url4,.autotrader_home_bfcontent_ads_image5,.autotrader_home_bfcontent_ads_url5').show();
                }
                else if(options['autotrader_home_bfcontent_number']=='six'){
                    jQuery('.autotrader_home_bfcontent_ads_image1,.autotrader_home_bfcontent_ads_url1,.autotrader_home_bfcontent_ads_image2,.autotrader_home_bfcontent_ads_url2,.autotrader_home_bfcontent_ads_image3,.autotrader_home_bfcontent_ads_url3,.autotrader_home_bfcontent_ads_image4,.autotrader_home_bfcontent_ads_url4,.autotrader_home_bfcontent_ads_image5,.autotrader_home_bfcontent_ads_url5,.autotrader_home_bfcontent_ads_image6,.autotrader_home_bfcontent_ads_url6').show();
                }
                else if(options['autotrader_home_bfcontent_number']=='seven'){
                    jQuery('.autotrader_home_bfcontent_ads_image1,.autotrader_home_bfcontent_ads_url1,.autotrader_home_bfcontent_ads_image2,.autotrader_home_bfcontent_ads_url2,.autotrader_home_bfcontent_ads_image3,.autotrader_home_bfcontent_ads_url3,.autotrader_home_bfcontent_ads_image4,.autotrader_home_bfcontent_ads_url4,.autotrader_home_bfcontent_ads_image5,.autotrader_home_bfcontent_ads_url5,.autotrader_home_bfcontent_ads_image6,.autotrader_home_bfcontent_ads_url6,.autotrader_home_bfcontent_ads_image7,.autotrader_home_bfcontent_ads_url7').show();
                }
            }
            else{
                if(options['autotrader_home_bfcontent_number']=='one'){
                    jQuery('.autotrader_home_bfcontent_ads_adsense1').show();
                }
                else if(options['autotrader_home_bfcontent_number']=='two'){
                    jQuery('.autotrader_home_bfcontent_ads_adsense1,.autotrader_home_bfcontent_ads_adsense2').show();
                }
                else if(options['autotrader_home_bfcontent_number']=='three'){
                    jQuery('.autotrader_home_bfcontent_ads_adsense1,.autotrader_home_bfcontent_ads_adsense2,.autotrader_home_bfcontent_ads_adsense3').show();
                }
                else if(options['autotrader_home_bfcontent_number']=='four'){
                    jQuery('.autotrader_home_bfcontent_ads_adsense1,.autotrader_home_bfcontent_ads_adsense2,.autotrader_home_bfcontent_ads_adsense3,.autotrader_home_bfcontent_ads_adsense4').show();
                }
                else if(options['autotrader_home_bfcontent_number']=='five'){
                    jQuery('.autotrader_home_bfcontent_ads_adsense1,.autotrader_home_bfcontent_ads_adsense2,.autotrader_home_bfcontent_ads_adsense3,.autotrader_home_bfcontent_ads_adsense4,.autotrader_home_bfcontent_ads_adsense5').show();
                }
                else if(options['autotrader_home_bfcontent_number']=='six'){
                    jQuery('.autotrader_home_bfcontent_ads_adsense1,.autotrader_home_bfcontent_ads_adsense2,.autotrader_home_bfcontent_ads_adsense3,.autotrader_home_bfcontent_ads_adsense4,.autotrader_home_bfcontent_ads_adsense5,.autotrader_home_bfcontent_ads_adsense6').show();
                }
                else if(options['autotrader_home_bfcontent_number']=='seven'){
                    jQuery('.autotrader_home_bfcontent_ads_adsense1,.autotrader_home_bfcontent_ads_adsense2,.autotrader_home_bfcontent_ads_adsense3,.autotrader_home_bfcontent_ads_adsense4,.autotrader_home_bfcontent_ads_adsense5,.autotrader_home_bfcontent_ads_adsense6,.autotrader_home_bfcontent_ads_adsense7').show();
                }
            }
        }
        // end homepage

        // blog page
        if(options['autotrader_blog_top_ad_space']=='true')
            jQuery('.autotrader_blog_top_ad_image,.autotrader_blog_top_ad_url,.autotrader_blog_top_ad_adsense').show();
        else
            jQuery('.autotrader_blog_top_ad_image,.autotrader_blog_top_ad_url,.autotrader_blog_top_ad_adsense').hide();

        if(options['autotrader_blog_hook_space']=='true')
            jQuery('.autotrader_blog_hook_image,.autotrader_blog_hook_url,.autotrader_blog_hook_adsense').show();
        else
            jQuery('.autotrader_blog_hook_image,.autotrader_blog_hook_url,.autotrader_blog_hook_adsense').hide();

        jQuery('.autotrader_blog_bfcontent_type,.autotrader_blog_bfcontent_number,.autotrader_blog_bfcontent_ads_image1,.autotrader_blog_bfcontent_ads_url1,.autotrader_blog_bfcontent_ads_adsense1,.autotrader_blog_bfcontent_ads_image2,.autotrader_blog_bfcontent_ads_url2,.autotrader_blog_bfcontent_ads_adsense2,.autotrader_blog_bfcontent_ads_image3,.autotrader_blog_bfcontent_ads_url3,.autotrader_blog_bfcontent_ads_adsense3,.autotrader_blog_bfcontent_ads_image4,.autotrader_blog_bfcontent_ads_url4,.autotrader_blog_bfcontent_ads_adsense4,.autotrader_blog_bfcontent_ads_image5,.autotrader_blog_bfcontent_ads_url5,.autotrader_blog_bfcontent_ads_adsense5,.autotrader_blog_bfcontent_ads_image6,.autotrader_blog_bfcontent_ads_url6,.autotrader_blog_bfcontent_ads_adsense6,.autotrader_blog_bfcontent_ads_image7,.autotrader_blog_bfcontent_ads_url7,.autotrader_blog_bfcontent_ads_adsense7').hide();
        if(options['autotrader_blog_bfcontent_ads_space']=='true'){
            jQuery('.autotrader_blog_bfcontent_type,.autotrader_blog_bfcontent_number').show();
            if(options['autotrader_blog_bfcontent_type']=='image'){
                if(options['autotrader_blog_bfcontent_number']=='one'){
                    jQuery('.autotrader_blog_bfcontent_ads_image1,.autotrader_blog_bfcontent_ads_url1').show();
                }
                else if(options['autotrader_blog_bfcontent_number']=='two'){
                    jQuery('.autotrader_blog_bfcontent_ads_image1,.autotrader_blog_bfcontent_ads_url1,.autotrader_blog_bfcontent_ads_image2,.autotrader_blog_bfcontent_ads_url2').show();
                }
                else if(options['autotrader_blog_bfcontent_number']=='three'){
                    jQuery('.autotrader_blog_bfcontent_ads_image1,.autotrader_blog_bfcontent_ads_url1,.autotrader_blog_bfcontent_ads_image2,.autotrader_blog_bfcontent_ads_url2,.autotrader_blog_bfcontent_ads_image3,.autotrader_blog_bfcontent_ads_url3').show();
                }
                else if(options['autotrader_blog_bfcontent_number']=='four'){
                    jQuery('.autotrader_blog_bfcontent_ads_image1,.autotrader_blog_bfcontent_ads_url1,.autotrader_blog_bfcontent_ads_image2,.autotrader_blog_bfcontent_ads_url2,.autotrader_blog_bfcontent_ads_image3,.autotrader_blog_bfcontent_ads_url3,.autotrader_blog_bfcontent_ads_image4,.autotrader_blog_bfcontent_ads_url4').show();
                }
                else if(options['autotrader_blog_bfcontent_number']=='five'){
                    jQuery('.autotrader_blog_bfcontent_ads_image1,.autotrader_blog_bfcontent_ads_url1,.autotrader_blog_bfcontent_ads_image2,.autotrader_blog_bfcontent_ads_url2,.autotrader_blog_bfcontent_ads_image3,.autotrader_blog_bfcontent_ads_url3,.autotrader_blog_bfcontent_ads_image4,.autotrader_blog_bfcontent_ads_url4,.autotrader_blog_bfcontent_ads_image5,.autotrader_blog_bfcontent_ads_url5').show();
                }
                else if(options['autotrader_blog_bfcontent_number']=='six'){
                    jQuery('.autotrader_blog_bfcontent_ads_image1,.autotrader_blog_bfcontent_ads_url1,.autotrader_blog_bfcontent_ads_image2,.autotrader_blog_bfcontent_ads_url2,.autotrader_blog_bfcontent_ads_image3,.autotrader_blog_bfcontent_ads_url3,.autotrader_blog_bfcontent_ads_image4,.autotrader_blog_bfcontent_ads_url4,.autotrader_blog_bfcontent_ads_image5,.autotrader_blog_bfcontent_ads_url5,.autotrader_blog_bfcontent_ads_image6,.autotrader_blog_bfcontent_ads_url6').show();
                }
                else if(options['autotrader_blog_bfcontent_number']=='seven'){
                    jQuery('.autotrader_blog_bfcontent_ads_image1,.autotrader_blog_bfcontent_ads_url1,.autotrader_blog_bfcontent_ads_image2,.autotrader_blog_bfcontent_ads_url2,.autotrader_blog_bfcontent_ads_image3,.autotrader_blog_bfcontent_ads_url3,.autotrader_blog_bfcontent_ads_image4,.autotrader_blog_bfcontent_ads_url4,.autotrader_blog_bfcontent_ads_image5,.autotrader_blog_bfcontent_ads_url5,.autotrader_blog_bfcontent_ads_image6,.autotrader_blog_bfcontent_ads_url6,.autotrader_blog_bfcontent_ads_image7,.autotrader_blog_bfcontent_ads_url7').show();
                }
            }
            else{
                if(options['autotrader_blog_bfcontent_number']=='one'){
                    jQuery('.autotrader_blog_bfcontent_ads_adsense1').show();
                }
                else if(options['autotrader_blog_bfcontent_number']=='two'){
                    jQuery('.autotrader_blog_bfcontent_ads_adsense1,.autotrader_blog_bfcontent_ads_adsense2').show();
                }
                else if(options['autotrader_blog_bfcontent_number']=='three'){
                    jQuery('.autotrader_blog_bfcontent_ads_adsense1,.autotrader_blog_bfcontent_ads_adsense2,.autotrader_blog_bfcontent_ads_adsense3').show();
                }
                else if(options['autotrader_blog_bfcontent_number']=='four'){
                    jQuery('.autotrader_blog_bfcontent_ads_adsense1,.autotrader_blog_bfcontent_ads_adsense2,.autotrader_blog_bfcontent_ads_adsense3,.autotrader_blog_bfcontent_ads_adsense4').show();
                }
                else if(options['autotrader_blog_bfcontent_number']=='five'){
                    jQuery('.autotrader_blog_bfcontent_ads_adsense1,.autotrader_blog_bfcontent_ads_adsense2,.autotrader_blog_bfcontent_ads_adsense3,.autotrader_blog_bfcontent_ads_adsense4,.autotrader_blog_bfcontent_ads_adsense5').show();
                }
                else if(options['autotrader_blog_bfcontent_number']=='six'){
                    jQuery('.autotrader_blog_bfcontent_ads_adsense1,.autotrader_blog_bfcontent_ads_adsense2,.autotrader_blog_bfcontent_ads_adsense3,.autotrader_blog_bfcontent_ads_adsense4,.autotrader_blog_bfcontent_ads_adsense5,.autotrader_blog_bfcontent_ads_adsense6').show();
                }
                else if(options['autotrader_blog_bfcontent_number']=='seven'){
                    jQuery('.autotrader_blog_bfcontent_ads_adsense1,.autotrader_blog_bfcontent_ads_adsense2,.autotrader_blog_bfcontent_ads_adsense3,.autotrader_blog_bfcontent_ads_adsense4,.autotrader_blog_bfcontent_ads_adsense5,.autotrader_blog_bfcontent_ads_adsense6,.autotrader_blog_bfcontent_ads_adsense7').show();
                }
            }
        }
        // end blog page

        // general banners (fuse framework)
        jQuery('.autotrader_bfcontent_ads_image1,.autotrader_bfcontent_ads_url1,.autotrader_bfcontent_ads_adsense1,.autotrader_bfcontent_ads_image2,.autotrader_bfcontent_ads_url2,.autotrader_bfcontent_ads_adsense2,.autotrader_bfcontent_ads_image3,.autotrader_bfcontent_ads_url3,.autotrader_bfcontent_ads_adsense3,.autotrader_bfcontent_ads_image4,.autotrader_bfcontent_ads_url4,.autotrader_bfcontent_ads_adsense4,.autotrader_bfcontent_ads_image5,.autotrader_bfcontent_ads_url5,.autotrader_bfcontent_ads_adsense5,.autotrader_bfcontent_ads_image6,.autotrader_bfcontent_ads_url6,.autotrader_bfcontent_ads_adsense6,.autotrader_bfcontent_ads_image7,.autotrader_bfcontent_ads_url7,.autotrader_bfcontent_ads_adsense7').hide();
        // hide number and type for single and page
        jQuery('.autotrader_bfcontent_type,.autotrader_bfcontent_number').hide();

        if(options['autotrader_bfcontent_type1']=='image'){
            // autotrader_bfcontent_type1 --- is for general banners (last tab in fuse framework)
            jQuery('.autotrader_bfcontent_type,.autotrader_bfcontent_number').show();
            if(options['autotrader_bfcontent_number']=='one'){
                jQuery('.autotrader_bfcontent_ads_image1,.autotrader_bfcontent_ads_url1').show();
            }
            else if(options['autotrader_bfcontent_number']=='two'){
                jQuery('.autotrader_bfcontent_ads_image1,.autotrader_bfcontent_ads_url1,.autotrader_bfcontent_ads_image2,.autotrader_bfcontent_ads_url2').show();
            }
            else if(options['autotrader_bfcontent_number']=='three'){
                jQuery('.autotrader_bfcontent_ads_image1,.autotrader_bfcontent_ads_url1,.autotrader_bfcontent_ads_image2,.autotrader_bfcontent_ads_url2,.autotrader_bfcontent_ads_image3,.autotrader_bfcontent_ads_url3').show();
            }
            else if(options['autotrader_bfcontent_number']=='four'){
                jQuery('.autotrader_bfcontent_ads_image1,.autotrader_bfcontent_ads_url1,.autotrader_bfcontent_ads_image2,.autotrader_bfcontent_ads_url2,.autotrader_bfcontent_ads_image3,.autotrader_bfcontent_ads_url3,.autotrader_bfcontent_ads_image4,.autotrader_bfcontent_ads_url4').show();
            }
            else if(options['autotrader_bfcontent_number']=='five'){
                jQuery('.autotrader_bfcontent_ads_image1,.autotrader_bfcontent_ads_url1,.autotrader_bfcontent_ads_image2,.autotrader_bfcontent_ads_url2,.autotrader_bfcontent_ads_image3,.autotrader_bfcontent_ads_url3,.autotrader_bfcontent_ads_image4,.autotrader_bfcontent_ads_url4,.autotrader_bfcontent_ads_image5,.autotrader_bfcontent_ads_url5').show();
            }
            else if(options['autotrader_bfcontent_number']=='six'){
                jQuery('.autotrader_bfcontent_ads_image1,.autotrader_bfcontent_ads_url1,.autotrader_bfcontent_ads_image2,.autotrader_bfcontent_ads_url2,.autotrader_bfcontent_ads_image3,.autotrader_bfcontent_ads_url3,.autotrader_bfcontent_ads_image4,.autotrader_bfcontent_ads_url4,.autotrader_bfcontent_ads_image5,.autotrader_bfcontent_ads_url5,.autotrader_bfcontent_ads_image6,.autotrader_bfcontent_ads_url6').show();
            }
            else if(options['autotrader_bfcontent_number']=='seven'){
                jQuery('.autotrader_bfcontent_ads_image1,.autotrader_bfcontent_ads_url1,.autotrader_bfcontent_ads_image2,.autotrader_bfcontent_ads_url2,.autotrader_bfcontent_ads_image3,.autotrader_bfcontent_ads_url3,.autotrader_bfcontent_ads_image4,.autotrader_bfcontent_ads_url4,.autotrader_bfcontent_ads_image5,.autotrader_bfcontent_ads_url5,.autotrader_bfcontent_ads_image6,.autotrader_bfcontent_ads_url6,.autotrader_bfcontent_ads_image7,.autotrader_bfcontent_ads_url7').show();
            }
        }
        else if(options['autotrader_bfcontent_type1']=='adsense'){
            jQuery('.autotrader_bfcontent_type,.autotrader_bfcontent_number').show();
            if(options['autotrader_bfcontent_number']=='one'){
                jQuery('.autotrader_bfcontent_ads_adsense1').show();
            }
            else if(options['autotrader_bfcontent_number']=='two'){
                jQuery('.autotrader_bfcontent_ads_adsense1,.autotrader_bfcontent_ads_adsense2').show();
            }
            else if(options['autotrader_bfcontent_number']=='three'){
                jQuery('.autotrader_bfcontent_ads_adsense1,.autotrader_bfcontent_ads_adsense2,.autotrader_bfcontent_ads_adsense3').show();
            }
            else if(options['autotrader_bfcontent_number']=='four'){
                jQuery('.autotrader_bfcontent_ads_adsense1,.autotrader_bfcontent_ads_adsense2,.autotrader_bfcontent_ads_adsense3,.autotrader_bfcontent_ads_adsense4').show();
            }
            else if(options['autotrader_bfcontent_number']=='five'){
                jQuery('.autotrader_bfcontent_ads_adsense1,.autotrader_bfcontent_ads_adsense2,.autotrader_bfcontent_ads_adsense3,.autotrader_bfcontent_ads_adsense4,.autotrader_bfcontent_ads_adsense5').show();
            }
            else if(options['autotrader_bfcontent_number']=='six'){
                jQuery('.autotrader_bfcontent_ads_adsense1,.autotrader_bfcontent_ads_adsense2,.autotrader_bfcontent_ads_adsense3,.autotrader_bfcontent_ads_adsense4,.autotrader_bfcontent_ads_adsense5,.autotrader_bfcontent_ads_adsense6').show();
            }
            else if(options['autotrader_bfcontent_number']=='seven'){
                jQuery('.autotrader_bfcontent_ads_adsense1,.autotrader_bfcontent_ads_adsense2,.autotrader_bfcontent_ads_adsense3,.autotrader_bfcontent_ads_adsense4,.autotrader_bfcontent_ads_adsense5,.autotrader_bfcontent_ads_adsense6,.autotrader_bfcontent_ads_adsense7').show();
            }
        }
        //

        if(options['autotrader_bfcontent_ads_space']=='true')
        {
            jQuery('.autotrader_bfcontent_type,.autotrader_bfcontent_number').show();
            if(options['autotrader_bfcontent_type']=='image'){
                if(options['autotrader_bfcontent_number']=='one'){
                    jQuery('.autotrader_bfcontent_ads_image1,.autotrader_bfcontent_ads_url1').show();
                }
                else if(options['autotrader_bfcontent_number']=='two'){
                    jQuery('.autotrader_bfcontent_ads_image1,.autotrader_bfcontent_ads_url1,.autotrader_bfcontent_ads_image2,.autotrader_bfcontent_ads_url2').show();
                }
                else if(options['autotrader_bfcontent_number']=='three'){
                    jQuery('.autotrader_bfcontent_ads_image1,.autotrader_bfcontent_ads_url1,.autotrader_bfcontent_ads_image2,.autotrader_bfcontent_ads_url2,.autotrader_bfcontent_ads_image3,.autotrader_bfcontent_ads_url3').show();
                }
                else if(options['autotrader_bfcontent_number']=='four'){
                    jQuery('.autotrader_bfcontent_ads_image1,.autotrader_bfcontent_ads_url1,.autotrader_bfcontent_ads_image2,.autotrader_bfcontent_ads_url2,.autotrader_bfcontent_ads_image3,.autotrader_bfcontent_ads_url3,.autotrader_bfcontent_ads_image4,.autotrader_bfcontent_ads_url4').show();
                }
                else if(options['autotrader_bfcontent_number']=='five'){
                    jQuery('.autotrader_bfcontent_ads_image1,.autotrader_bfcontent_ads_url1,.autotrader_bfcontent_ads_image2,.autotrader_bfcontent_ads_url2,.autotrader_bfcontent_ads_image3,.autotrader_bfcontent_ads_url3,.autotrader_bfcontent_ads_image4,.autotrader_bfcontent_ads_url4,.autotrader_bfcontent_ads_image5,.autotrader_bfcontent_ads_url5').show();
                }
                else if(options['autotrader_bfcontent_number']=='six'){
                    jQuery('.autotrader_bfcontent_ads_image1,.autotrader_bfcontent_ads_url1,.autotrader_bfcontent_ads_image2,.autotrader_bfcontent_ads_url2,.autotrader_bfcontent_ads_image3,.autotrader_bfcontent_ads_url3,.autotrader_bfcontent_ads_image4,.autotrader_bfcontent_ads_url4,.autotrader_bfcontent_ads_image5,.autotrader_bfcontent_ads_url5,.autotrader_bfcontent_ads_image6,.autotrader_bfcontent_ads_url6').show();
                }
                else if(options['autotrader_bfcontent_number']=='seven'){
                    jQuery('.autotrader_bfcontent_ads_image1,.autotrader_bfcontent_ads_url1,.autotrader_bfcontent_ads_image2,.autotrader_bfcontent_ads_url2,.autotrader_bfcontent_ads_image3,.autotrader_bfcontent_ads_url3,.autotrader_bfcontent_ads_image4,.autotrader_bfcontent_ads_url4,.autotrader_bfcontent_ads_image5,.autotrader_bfcontent_ads_url5,.autotrader_bfcontent_ads_image6,.autotrader_bfcontent_ads_url6,.autotrader_bfcontent_ads_image7,.autotrader_bfcontent_ads_url7').show();
                }
            }
            else if(options['autotrader_bfcontent_type']=='adsense'){
                if(options['autotrader_bfcontent_number']=='one'){
                    jQuery('.autotrader_bfcontent_ads_adsense1').show();
                }
                else if(options['autotrader_bfcontent_number']=='two'){
                    jQuery('.autotrader_bfcontent_ads_adsense1,.autotrader_bfcontent_ads_adsense2').show();
                }
                else if(options['autotrader_bfcontent_number']=='three'){
                    jQuery('.autotrader_bfcontent_ads_adsense1,.autotrader_bfcontent_ads_adsense2,.autotrader_bfcontent_ads_adsense3').show();
                }
                else if(options['autotrader_bfcontent_number']=='four'){
                    jQuery('.autotrader_bfcontent_ads_adsense1,.autotrader_bfcontent_ads_adsense2,.autotrader_bfcontent_ads_adsense3,.autotrader_bfcontent_ads_adsense4').show();
                }
                else if(options['autotrader_bfcontent_number']=='five'){
                    jQuery('.autotrader_bfcontent_ads_adsense1,.autotrader_bfcontent_ads_adsense2,.autotrader_bfcontent_ads_adsense3,.autotrader_bfcontent_ads_adsense4,.autotrader_bfcontent_ads_adsense5').show();
                }
                else if(options['autotrader_bfcontent_number']=='six'){
                    jQuery('.autotrader_bfcontent_ads_adsense1,.autotrader_bfcontent_ads_adsense2,.autotrader_bfcontent_ads_adsense3,.autotrader_bfcontent_ads_adsense4,.autotrader_bfcontent_ads_adsense5,.autotrader_bfcontent_ads_adsense6').show();
                }
                else if(options['autotrader_bfcontent_number']=='seven'){
                    jQuery('.autotrader_bfcontent_ads_adsense1,.autotrader_bfcontent_ads_adsense2,.autotrader_bfcontent_ads_adsense3,.autotrader_bfcontent_ads_adsense4,.autotrader_bfcontent_ads_adsense5,.autotrader_bfcontent_ads_adsense6,.autotrader_bfcontent_ads_adsense7').show();
                }
            }
        }

        // single page, post
        if(options['autotrader_top_ad_space']=='true')
            jQuery('.autotrader_top_ad_image,.autotrader_top_ad_url,.autotrader_top_ad_adsense').show();
        else
            jQuery('.autotrader_top_ad_image,.autotrader_top_ad_url,.autotrader_top_ad_adsense').hide();

        if(options['autotrader_hook_space']=='true')
            jQuery('.autotrader_hook_image,.autotrader_hook_url,.autotrader_hook_adsense').show();
        else
            jQuery('.autotrader_hook_image,.autotrader_hook_url,.autotrader_hook_adsense').hide();

        // for categories and taxonomies
        if(options['autotrader_top_ad_space']=='true')
            jQuery('#autotrader_top_ad_image,#autotrader_top_ad_url,#autotrader_top_ad_adsense').parents('.tfuse-tax-form-field').show();
        else
            jQuery('#autotrader_top_ad_image,#autotrader_top_ad_url,#autotrader_top_ad_adsense').parents('.tfuse-tax-form-field').hide();

        if(options['autotrader_hook_space']=='true')
            jQuery('#autotrader_hook_image,#autotrader_hook_url,#autotrader_hook_adsense').parents('.tfuse-tax-form-field').show();
        else
            jQuery('#autotrader_hook_image,#autotrader_hook_url,#autotrader_hook_adsense').parents('.tfuse-tax-form-field').hide();

        jQuery('#autotrader_bfcontent_type,#autotrader_bfcontent_number,#autotrader_bfcontent_ads_image1,#autotrader_bfcontent_ads_url1,#autotrader_bfcontent_ads_adsense1,#autotrader_bfcontent_ads_image2,#autotrader_bfcontent_ads_url2,#autotrader_bfcontent_ads_adsense2,#autotrader_bfcontent_ads_image3,#autotrader_bfcontent_ads_url3,#autotrader_bfcontent_ads_adsense3,#autotrader_bfcontent_ads_image4,#autotrader_bfcontent_ads_url4,#autotrader_bfcontent_ads_adsense4,#autotrader_bfcontent_ads_image5,#autotrader_bfcontent_ads_url5,#autotrader_bfcontent_ads_adsense5,#autotrader_bfcontent_ads_image6,#autotrader_bfcontent_ads_url6,#autotrader_bfcontent_ads_adsense6,#autotrader_bfcontent_ads_image7,#autotrader_bfcontent_ads_url7,#autotrader_bfcontent_ads_adsense7').parents('.tfuse-tax-form-field').hide();
        if(options['autotrader_bfcontent_ads_space']=='true')
        {
            jQuery('#autotrader_bfcontent_type,#autotrader_bfcontent_number').parents('.tfuse-tax-form-field').show();
            if(options['autotrader_bfcontent_type1']=='image' || options['autotrader_bfcontent_type']=='image'){
                if(options['autotrader_bfcontent_number']=='one'){
                    jQuery('#autotrader_bfcontent_ads_image1,#autotrader_bfcontent_ads_url1').parents('.tfuse-tax-form-field').show();
                }
                else if(options['autotrader_bfcontent_number']=='two'){
                    jQuery('#autotrader_bfcontent_ads_image1,#autotrader_bfcontent_ads_url1,#autotrader_bfcontent_ads_image2,#autotrader_bfcontent_ads_url2').parents('.tfuse-tax-form-field').show();
                }
                else if(options['autotrader_bfcontent_number']=='three'){
                    jQuery('#autotrader_bfcontent_ads_image1,#autotrader_bfcontent_ads_url1,#autotrader_bfcontent_ads_image2,#autotrader_bfcontent_ads_url2,#autotrader_bfcontent_ads_image3,#autotrader_bfcontent_ads_url3').parents('.tfuse-tax-form-field').show();
                }
                else if(options['autotrader_bfcontent_number']=='four'){
                    jQuery('#autotrader_bfcontent_ads_image1,#autotrader_bfcontent_ads_url1,#autotrader_bfcontent_ads_image2,#autotrader_bfcontent_ads_url2,#autotrader_bfcontent_ads_image3,#autotrader_bfcontent_ads_url3,#autotrader_bfcontent_ads_image4,#autotrader_bfcontent_ads_url4').parents('.tfuse-tax-form-field').show();
                }
                else if(options['autotrader_bfcontent_number']=='five'){
                    jQuery('#autotrader_bfcontent_ads_image1,#autotrader_bfcontent_ads_url1,#autotrader_bfcontent_ads_image2,#autotrader_bfcontent_ads_url2,#autotrader_bfcontent_ads_image3,#autotrader_bfcontent_ads_url3,#autotrader_bfcontent_ads_image4,#autotrader_bfcontent_ads_url4,#autotrader_bfcontent_ads_image5,#autotrader_bfcontent_ads_url5').parents('.tfuse-tax-form-field').show();
                }
                else if(options['autotrader_bfcontent_number']=='six'){
                    jQuery('#autotrader_bfcontent_ads_image1,#autotrader_bfcontent_ads_url1,#autotrader_bfcontent_ads_image2,#autotrader_bfcontent_ads_url2,#autotrader_bfcontent_ads_image3,#autotrader_bfcontent_ads_url3,#autotrader_bfcontent_ads_image4,#autotrader_bfcontent_ads_url4,#autotrader_bfcontent_ads_image5,#autotrader_bfcontent_ads_url5,#autotrader_bfcontent_ads_image6,#autotrader_bfcontent_ads_url6').parents('.tfuse-tax-form-field').show();
                }
                else if(options['autotrader_bfcontent_number']=='seven'){
                    jQuery('#autotrader_bfcontent_ads_image1,#autotrader_bfcontent_ads_url1,#autotrader_bfcontent_ads_image2,#autotrader_bfcontent_ads_url2,#autotrader_bfcontent_ads_image3,#autotrader_bfcontent_ads_url3,#autotrader_bfcontent_ads_image4,#autotrader_bfcontent_ads_url4,#autotrader_bfcontent_ads_image5,#autotrader_bfcontent_ads_url5,#autotrader_bfcontent_ads_image6,#autotrader_bfcontent_ads_url6,#autotrader_bfcontent_ads_image7,#autotrader_bfcontent_ads_url7').parents('.tfuse-tax-form-field').show();
                }
            }
            else{
                if(options['autotrader_bfcontent_number']=='one'){
                    jQuery('#autotrader_bfcontent_ads_adsense1').parents('.tfuse-tax-form-field').show();
                }
                else if(options['autotrader_bfcontent_number']=='two'){
                    jQuery('#autotrader_bfcontent_ads_adsense1,#autotrader_bfcontent_ads_adsense2').parents('.tfuse-tax-form-field').show();
                }
                else if(options['autotrader_bfcontent_number']=='three'){
                    jQuery('#autotrader_bfcontent_ads_adsense1,#autotrader_bfcontent_ads_adsense2,#autotrader_bfcontent_ads_adsense3').parents('.tfuse-tax-form-field').show();
                }
                else if(options['autotrader_bfcontent_number']=='four'){
                    jQuery('#autotrader_bfcontent_ads_adsense1,#autotrader_bfcontent_ads_adsense2,#autotrader_bfcontent_ads_adsense3,#autotrader_bfcontent_ads_adsense4').parents('.tfuse-tax-form-field').show();
                }
                else if(options['autotrader_bfcontent_number']=='five'){
                    jQuery('#autotrader_bfcontent_ads_adsense1,#autotrader_bfcontent_ads_adsense2,#autotrader_bfcontent_ads_adsense3,#autotrader_bfcontent_ads_adsense4,#autotrader_bfcontent_ads_adsense5').parents('.tfuse-tax-form-field').show();
                }
                else if(options['autotrader_bfcontent_number']=='six'){
                    jQuery('#autotrader_bfcontent_ads_adsense1,#autotrader_bfcontent_ads_adsense2,#autotrader_bfcontent_ads_adsense3,#autotrader_bfcontent_ads_adsense4,#autotrader_bfcontent_ads_adsense5,#autotrader_bfcontent_ads_adsense6').parents('.tfuse-tax-form-field').show();
                }
                else if(options['autotrader_bfcontent_number']=='seven'){
                    jQuery('#autotrader_bfcontent_ads_adsense1,#autotrader_bfcontent_ads_adsense2,#autotrader_bfcontent_ads_adsense3,#autotrader_bfcontent_ads_adsense4,#autotrader_bfcontent_ads_adsense5,#autotrader_bfcontent_ads_adsense6,#autotrader_bfcontent_ads_adsense7').parents('.tfuse-tax-form-field').show();
                }
            }
        }
    }


    // single post checkbox
    from_category = jQuery('#autotrader_content_ads_post');
    if(from_category.is(':checked')){
        jQuery('.autotrader_top_ad_image,.autotrader_top_ad_url,.autotrader_top_ad_adsense,.autotrader_top_ad_space,.autotrader_hook_space,.autotrader_hook_image,.autotrader_hook_url,.autotrader_hook_adsense,.autotrader_bfcontent_ads_space,.autotrader_bfcontent_type,.autotrader_bfcontent_number,.autotrader_bfcontent_ads_image1,.autotrader_bfcontent_ads_url1,.autotrader_bfcontent_ads_adsense1,.autotrader_bfcontent_ads_image2,.autotrader_bfcontent_ads_url2,.autotrader_bfcontent_ads_adsense2,.autotrader_bfcontent_ads_image3,.autotrader_bfcontent_ads_url3,.autotrader_bfcontent_ads_adsense3,.autotrader_bfcontent_ads_image4,.autotrader_bfcontent_ads_url4,.autotrader_bfcontent_ads_adsense4,.autotrader_bfcontent_ads_image5,.autotrader_bfcontent_ads_url5,.autotrader_bfcontent_ads_adsense5,.autotrader_bfcontent_ads_image6,.autotrader_bfcontent_ads_url6,.autotrader_bfcontent_ads_adsense6,.autotrader_bfcontent_ads_image7,.autotrader_bfcontent_ads_url7,.autotrader_bfcontent_ads_adsense7').hide();
        jQuery('.autotrader_content_ads_post,.autotrader_top_ad_adsense,.autotrader_bfcontent_ads_adsense7').next().hide();
    }
    else {
        tfuse_toggle_options(options);
        jQuery('.autotrader_content_ads_post,.autotrader_top_ad_adsense,.autotrader_bfcontent_ads_adsense7').next().show();
    }

    from_category.on('change',function () {
        if(jQuery(this).is(':checked')){
            jQuery('.autotrader_top_ad_image,.autotrader_top_ad_url,.autotrader_top_ad_adsense,.autotrader_top_ad_space,.autotrader_hook_space,.autotrader_hook_image,.autotrader_hook_url,.autotrader_hook_adsense,.autotrader_bfcontent_ads_space,.autotrader_bfcontent_type,.autotrader_bfcontent_number,.autotrader_bfcontent_ads_image1,.autotrader_bfcontent_ads_url1,.autotrader_bfcontent_ads_adsense1,.autotrader_bfcontent_ads_image2,.autotrader_bfcontent_ads_url2,.autotrader_bfcontent_ads_adsense2,.autotrader_bfcontent_ads_image3,.autotrader_bfcontent_ads_url3,.autotrader_bfcontent_ads_adsense3,.autotrader_bfcontent_ads_image4,.autotrader_bfcontent_ads_url4,.autotrader_bfcontent_ads_adsense4,.autotrader_bfcontent_ads_image5,.autotrader_bfcontent_ads_url5,.autotrader_bfcontent_ads_adsense5,.autotrader_bfcontent_ads_image6,.autotrader_bfcontent_ads_url6,.autotrader_bfcontent_ads_adsense6,.autotrader_bfcontent_ads_image7,.autotrader_bfcontent_ads_url7,.autotrader_bfcontent_ads_adsense7').hide();
            jQuery('.autotrader_content_ads_post,.autotrader_top_ad_adsense,.autotrader_bfcontent_ads_adsense7').next().hide();
        }
        else{
            jQuery('.autotrader_top_ad_image,.autotrader_top_ad_url,.autotrader_top_ad_adsense,.autotrader_top_ad_space,.autotrader_hook_space,.autotrader_hook_image,.autotrader_hook_url,.autotrader_hook_adsense,.autotrader_bfcontent_ads_space,.autotrader_bfcontent_type,.autotrader_bfcontent_number,.autotrader_bfcontent_ads_image1,.autotrader_bfcontent_ads_url1,.autotrader_bfcontent_ads_adsense1,.autotrader_bfcontent_ads_image2,.autotrader_bfcontent_ads_url2,.autotrader_bfcontent_ads_adsense2,.autotrader_bfcontent_ads_image3,.autotrader_bfcontent_ads_url3,.autotrader_bfcontent_ads_adsense3,.autotrader_bfcontent_ads_image4,.autotrader_bfcontent_ads_url4,.autotrader_bfcontent_ads_adsense4,.autotrader_bfcontent_ads_image5,.autotrader_bfcontent_ads_url5,.autotrader_bfcontent_ads_adsense5,.autotrader_bfcontent_ads_image6,.autotrader_bfcontent_ads_url6,.autotrader_bfcontent_ads_adsense6,.autotrader_bfcontent_ads_image7,.autotrader_bfcontent_ads_url7,.autotrader_bfcontent_ads_adsense7').show();
            tfuse_toggle_options(options);
            jQuery('.autotrader_content_ads_post,.autotrader_top_ad_adsense,.autotrader_bfcontent_ads_adsense7').next().show();
        }
    });

    // general 125 banners checkbox (framework)
    from_general3 = jQuery('#autotrader_bfc_ads_space');
    if(from_general3.is(':checked')){
        jQuery('.autotrader_bfcontent_type1,.autotrader_bfcontent_number,.autotrader_bfcontent_ads_image1,.autotrader_bfcontent_ads_url1,.autotrader_bfcontent_ads_adsense1,.autotrader_bfcontent_ads_image2,.autotrader_bfcontent_ads_url2,.autotrader_bfcontent_ads_adsense2,.autotrader_bfcontent_ads_image3,.autotrader_bfcontent_ads_url3,.autotrader_bfcontent_ads_adsense3,.autotrader_bfcontent_ads_image4,.autotrader_bfcontent_ads_url4,.autotrader_bfcontent_ads_adsense4,.autotrader_bfcontent_ads_image5,.autotrader_bfcontent_ads_url5,.autotrader_bfcontent_ads_adsense5,.autotrader_bfcontent_ads_image6,.autotrader_bfcontent_ads_url6,.autotrader_bfcontent_ads_adsense6,.autotrader_bfcontent_ads_image7,.autotrader_bfcontent_ads_url7,.autotrader_bfcontent_ads_adsense7').hide();
    }
    else{
        if( adminpage=='toplevel_page_themefuse')tfuse_toggle_options(options);
    }

    from_general3.on('change',function () {
        if(jQuery(this).is(':checked')){
            jQuery('.autotrader_bfcontent_type1,.autotrader_bfcontent_number,.autotrader_bfcontent_ads_image1,.autotrader_bfcontent_ads_url1,.autotrader_bfcontent_ads_adsense1,.autotrader_bfcontent_ads_image2,.autotrader_bfcontent_ads_url2,.autotrader_bfcontent_ads_adsense2,.autotrader_bfcontent_ads_image3,.autotrader_bfcontent_ads_url3,.autotrader_bfcontent_ads_adsense3,.autotrader_bfcontent_ads_image4,.autotrader_bfcontent_ads_url4,.autotrader_bfcontent_ads_adsense4,.autotrader_bfcontent_ads_image5,.autotrader_bfcontent_ads_url5,.autotrader_bfcontent_ads_adsense5,.autotrader_bfcontent_ads_image6,.autotrader_bfcontent_ads_url6,.autotrader_bfcontent_ads_adsense6,.autotrader_bfcontent_ads_image7,.autotrader_bfcontent_ads_url7,.autotrader_bfcontent_ads_adsense7').hide();
        }
        else{
            jQuery('.autotrader_bfcontent_type1,.autotrader_bfcontent_number,.autotrader_bfcontent_ads_image1,.autotrader_bfcontent_ads_url1,.autotrader_bfcontent_ads_adsense1,.autotrader_bfcontent_ads_image2,.autotrader_bfcontent_ads_url2,.autotrader_bfcontent_ads_adsense2,.autotrader_bfcontent_ads_image3,.autotrader_bfcontent_ads_url3,.autotrader_bfcontent_ads_adsense3,.autotrader_bfcontent_ads_image4,.autotrader_bfcontent_ads_url4,.autotrader_bfcontent_ads_adsense4,.autotrader_bfcontent_ads_image5,.autotrader_bfcontent_ads_url5,.autotrader_bfcontent_ads_adsense5,.autotrader_bfcontent_ads_image6,.autotrader_bfcontent_ads_url6,.autotrader_bfcontent_ads_adsense6,.autotrader_bfcontent_ads_image7,.autotrader_bfcontent_ads_url7,.autotrader_bfcontent_ads_adsense7').show();
            if( adminpage=='toplevel_page_themefuse')tfuse_toggle_options(options);
        }
    });


});