<?php

/**
 * WARNING: This file is part of the core ThemeFuse Framework. It is not recommended to edit this section
 *
 * @package ThemeFuse
 * @since 2.0
 */


require_once(TEMPLATEPATH . '/framework/BootsTrap.php');
require_once(TEMPLATEPATH . '/theme_config/theme_includes/AJAX_CALLBACKS.php');


add_action( 'wp_enqueue_scripts', 'ls_scripts_styles', 20 );

function ls_scripts_styles() {
	wp_enqueue_style( 'lightslidercss', get_stylesheet_directory_uri(). '/css/lightslider.css' , array(), '1.0.0', 'all' );
	wp_enqueue_script( 'lightsliderjs', get_stylesheet_directory_uri() . '/js/lightslider.min.js', array( 'jquery' ), '1.0.0', true );
	wp_enqueue_script( 'lightsliderinit', get_stylesheet_directory_uri() . '/js/lightslider-init.js', array( 'lightsliderjs' ), '1.0.0', true );
}

