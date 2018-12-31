<?php if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}

function tf_minify_autoload( $class_name ) {
	$class_name = str_replace( '_', '-', strtolower( $class_name ) );
	if ( file_exists( dirname( __FILE__ ) . "/class-$class_name.php" ) ) {
		include_once dirname( __FILE__ ) . "/class-$class_name.php";
	} elseif ( file_exists( dirname( __FILE__ ) . "/exceptions/class-$class_name.php" ) ) {
		include_once dirname( __FILE__ ) . "/exceptions/class-$class_name.php";
	}
}

spl_autoload_register( 'tf_minify_autoload' );