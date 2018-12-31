<?php

add_action( 'wp_print_styles', 'tfuse_add_css' );
add_action( 'wp_print_scripts', 'tfuse_add_js' );

if ( ! function_exists( 'tfuse_add_css' ) ) :
/**
 * This function include files of css.
*/
    function tfuse_add_css()
    {

        wp_register_style( 'style.css', tfuse_get_file_uri('/style.css'), array(), false );
        wp_enqueue_style( 'style.css' );

        wp_register_style( 'screen_css', tfuse_get_file_uri('/screen.css'));
        wp_enqueue_style( 'screen_css' );

        wp_register_style( 'prettyPhoto', TFUSE_ADMIN_CSS . '/prettyPhoto.css', false, '' );
        wp_enqueue_style( 'prettyPhoto' );

        wp_register_style( 'custom', tfuse_get_file_uri('/custom.css'), array(), false );
        wp_enqueue_style( 'custom' );
        
        wp_register_style( 'jslider', tfuse_get_file_uri('/css/jslider.css'), array(), false );
        wp_enqueue_style( 'jslider' );

        wp_register_style( 'cusel.css', tfuse_get_file_uri('/css/cusel.css'), array(), false );
        wp_enqueue_style( 'cusel.css' );

        wp_register_style( 'shCore', tfuse_get_file_uri('/css/shCore.css'), true, '' );
        wp_enqueue_style( 'shCore' );

        wp_register_style( 'shThemeDefault', tfuse_get_file_uri('/css/shThemeDefault.css'), true, '' );
        wp_enqueue_style( 'shThemeDefault' );

        wp_register_style( 'settings', tfuse_get_file_uri('/rs-plugin/css/settings.css'), true, '' );
        wp_enqueue_style( 'settings' );

	}
endif;


if ( ! function_exists( 'tfuse_add_js' ) ) :
/**
 * This function include files of javascript.
*/
    function tfuse_add_js()
    {

        wp_register_script( 'modernizr', tfuse_get_file_uri('/js/libs/modernizr.min.js'), array(), false, true);
        wp_enqueue_script( 'modernizr' );

        wp_register_script( 'respond', tfuse_get_file_uri('/js/libs/respond.min.js'), array(), false, true);
        wp_enqueue_script( 'respond' );

        wp_enqueue_script( 'jquery' );

        wp_register_script( 'prettyPhoto', TFUSE_ADMIN_JS . '/jquery.prettyPhoto.js', array('jquery'), '3.1.4', true );
        wp_enqueue_script( 'prettyPhoto' );

        wp_register_script( 'jquery.easing', tfuse_get_file_uri('/js/jquery.easing.min.js'), array('jquery'), '1.3', true );
        wp_enqueue_script( 'jquery.easing' );

        wp_register_script( 'hover', tfuse_get_file_uri('/js/hoverIntent.js'), array('jquery'), '1.0', true );
        wp_enqueue_script( 'hover' );

        // general.js can be overridden in a child theme by copying it in child theme's js folder
        wp_register_script( 'general', tfuse_get_file_uri('/js/general.js'), array('jquery'), '2.0', true );
        wp_enqueue_script( 'general' );

        $translation_array = array(
            'save_offer'            => __( 'SAVE VEHICLE', 'tfuse' ),
            'remove_offer'          => __('REMOVE VEHICLE', 'tfuse'),
            'seek_post_singular'    => sprintf(__('Saved %s', 'tfuse'), TF_SEEK_HELPER::get_option('seek_property_name_singular','Vehicle')),
            'seek_post_plural'      => sprintf(__('Saved %s', 'tfuse'), TF_SEEK_HELPER::get_option('seek_property_name_plural','Vehicles')),
        );
        wp_localize_script( 'general', 'tfuse_translations', $translation_array );

        wp_register_script( 'carouFredSel', tfuse_get_file_uri('/js/jquery.carouFredSel.min.js'), array('jquery'), '1.0', true );
        wp_enqueue_script( 'carouFredSel' );

        // range sliders
        wp_register_script( 'jquery.slider.bundle', tfuse_get_file_uri('/js/jquery.slider.bundle.js'), array('jquery'), '2.0', true );
        wp_enqueue_script( 'jquery.slider.bundle' );

        wp_register_script( 'jquery.customInput', tfuse_get_file_uri('/js/jquery.customInput.js'), array('jquery'), '2.0', true );
        wp_enqueue_script( 'jquery.customInput' );
        
        wp_register_script( 'jquery.tools', tfuse_get_file_uri('/js/jquery.tools.min.js'), array('jquery'), '1.2.5', true );
        wp_enqueue_script('jquery.tools');

        //styled select
        wp_register_script( 'cusel-min', tfuse_get_file_uri('/js/cusel-min.js'), array('jquery'), '2.0', true );
        wp_enqueue_script( 'cusel-min' );

        //rs-plugin
        wp_register_script( 'themepunch.plugins', tfuse_get_file_uri('/rs-plugin/js/jquery.themepunch.plugins.min.js'), array('jquery'), '2.0', true );
        wp_enqueue_script( 'themepunch.plugins' );

        wp_register_script( 'themepunch.revolution', tfuse_get_file_uri('/rs-plugin/js/jquery.themepunch.revolution.min.js'), array('jquery'), '2.0', true );
        wp_enqueue_script( 'themepunch.revolution' );

        //multiselect
        wp_register_script( 'jquery.mousewheel', tfuse_get_file_uri('/js/jquery.mousewheel.js'), array('jquery'), '2.0', true );
        wp_enqueue_script( 'jquery.mousewheel' );

        wp_register_script( 'jScrollPane.min', tfuse_get_file_uri('/js/jquery.jscrollpane.min.js'), array('jquery'), '2.0', true );
        wp_enqueue_script( 'jScrollPane.min' );

        wp_register_script( 'offer_forms', tfuse_get_file_uri('/js/offer_forms.js'), array('jquery'), '1.0.1', true );

        wp_register_script( 'shCore', tfuse_get_file_uri('/js/shCore.js'), array('jquery'), '2.1.382', true );
        wp_enqueue_script( 'shCore' );

        wp_register_script( 'shBrushPlain', tfuse_get_file_uri('/js/shBrushPlain.js'), array('jquery'), '2.1.382', true );
        wp_enqueue_script( 'shBrushPlain' );
    }
endif;
