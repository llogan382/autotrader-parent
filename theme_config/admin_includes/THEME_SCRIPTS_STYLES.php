<?php
/**
 * Include javascript and css files for dashboard
 *
 */

if ( ! function_exists( 'tfuse_add_admin_css' ) ) {
    /**
     * This function include files of css.
     */
    function tfuse_add_admin_css()
    {

        wp_register_style( 'custom_admin', tfuse_get_file_uri('/css/custom_admin.css') );
        wp_enqueue_style( 'custom_admin' );

    }
    add_action( 'admin_enqueue_scripts', 'tfuse_add_admin_css' );
}


if ( ! function_exists( 'tfuse_add_admin_js' ) ) {
    /**
     * This function include files of javascript.
     */
    function tfuse_add_admin_js()
    {
        $screen = get_current_screen();

        if ( $screen->base == 'post' && $screen->post_type == TF_SEEK_HELPER::get_post_type() ) {
            wp_enqueue_script( 'jquery' );
            wp_enqueue_script( 'jquery-ui-core' );
            wp_enqueue_script( 'jquery-ui-button' );
            wp_enqueue_script( 'jquery-ui-datepicker' );

            wp_register_script( 'jquery.masked.input', tfuse_get_file_uri('/js/jquery.maskedinput.js'), array('jquery'), '1.3.1', true );
            wp_enqueue_script( 'jquery.masked.input' );

            wp_register_script( 'monthPicker', tfuse_get_file_uri('/js/monthPicker.2.1.min.js'), array('jquery', 'jquery-ui-core', 'jquery-ui-button', 'jquery-ui-datepicker'), '2.1', true );
            wp_enqueue_script( 'monthPicker' );
        }

		wp_register_script( 'advanced', tfuse_get_file_uri('/js/advanced.js'), array('jquery'), '1.0.2', true );
		wp_enqueue_script( 'advanced' );

		$vat_rate = TF_SEEK_HELPER::get_option('seek_property_vat_rate',0);
		wp_localize_script( 'advanced', 'tfuse_vat_rate', $vat_rate );

    }
    add_action( 'admin_enqueue_scripts', 'tfuse_add_admin_js' );
}