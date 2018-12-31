<?php if ( ! defined( 'TFUSE' ) ) {
	exit( 'Direct access forbidden.' );
}

function tfuse_months() {
	return array(
		__( "January" ),
		__( "February" ),
		__( "March" ),
		__( "April" ),
		__( "May" ),
		__( "June" ),
		__( "July" ),
		__( "August" ),
		__( "September" ),
		__( "October" ),
		__( "November" ),
		__( "December" )
	);
}

function tfuse_months_short() {
	return array(
		__( "Jan" ),
		__( "Feb" ),
		__( "Mar" ),
		__( "Apr" ),
		__( "May" ),
		__( "Jun" ),
		__( "Jul" ),
		__( "Aug" ),
		__( "Sep" ),
		__( "Oct" ),
		__( "Nov" ),
		__( "Dec" )
	);
}

function tfuse_days() {
	return array(
		__( "Sunday" ),
		__( "Monday" ),
		__( "Tuesday" ),
		__( "Wednesday" ),
		__( "Thursday" ),
		__( "Friday" ),
		__( "Saturday" )
	);
}

function tfuse_days_short() {
	return array(
		__( "Su" ),
		__( "Mo" ),
		__( "Tu" ),
		__( "We" ),
		__( "Th" ),
		__( "Fr" ),
		__( "Sa" )
	);
}

function tfuse_days_min() {
	return array(
		__( "Sun" ),
		__( "Mon" ),
		__( "Tue" ),
		__( "Wed" ),
		__( "Thu" ),
		__( "Fri" ),
		__( "Sat" )
	);
}

function _action_tfuse_translate_jquery_datepicker() {
	?>
	<script>
		jQuery(function ($) {
			$.datepicker.regional['<?php echo substr( get_locale(), 0, 2 ) ?>'] = {
				closeText: "<?php  _ex( 'Done', 'date-picker-calendar', 'tfuse' ) ?>",
				prevText: "<?php  _ex( 'Prev', 'date-picker-calendar', 'tfuse' ) ?>",
				nextText: "<?php  _ex( 'Next', 'date-picker-calendar', 'tfuse' ) ?>",
				currentText: "<?php  _ex( 'Today', 'date-picker-calendar', 'tfuse' ) ?>",
				monthNames: <?php echo json_encode( tfuse_months() ) ?>,
				monthNamesShort: <?php echo json_encode( tfuse_months_short() ) ?>,
				dayNames: <?php echo json_encode( tfuse_days() ) ?>,
				dayNamesMin: <?php echo json_encode( tfuse_days_min() ) ?>,
				dayNamesShort: <?php echo json_encode( tfuse_days_short() ) ?>,
				weekHeader: "<?php  _ex( 'Wk', 'date-picker-calendar', 'tfuse' ) ?>",
				dateFormat: "<?php echo apply_filters( 'datepicker-calendar-date-format', 'mm/dd/yy' ) ?>",
				firstDay: 0,
				isRTL: <?php echo is_rtl() ? 'true' : 'false' ?>,
				showMonthAfterYear: false,
				yearSuffix: ""
			};

			$.datepicker.setDefaults($.datepicker.regional['<?php echo substr( get_locale(), 0, 2 ) ?>']);
		});
	</script>
	<?php
}

function _action_enable_date_picker_i10n() {
	global $wp_scripts;

	if ( in_array( 'jquery-ui-datepicker', $wp_scripts->queue ) ) {
		_action_tfuse_translate_jquery_datepicker();
	}
}

add_action( 'wp_print_footer_scripts', '_action_enable_date_picker_i10n' );