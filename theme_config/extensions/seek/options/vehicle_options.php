<?php

if ( ! defined( 'TFUSE' ) ) {
	exit( 'Direct access forbidden.' );
}


if ( ! function_exists( 'tf_seek_post_type_options__html_script_google_maps_input' ) ):
	function tf_seek_post_type_options__html_script_google_maps_input( $map_id, $input_id_lat, $input_id_lng ) {
		ob_start();

		?>
		<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3<?php echo tfuse_gmap_key(); ?>"></script>

		<div style="height:500px;width:500px;" id="<?php print $map_id; ?>"></div>

		<script type="text/javascript">
			jQuery(document).ready(function ($) {

				new (function () {
					this.map = null;
					this.lat_element = $('input#<?php print $input_id_lat; ?>');
					this.lng_element = $('input#<?php print $input_id_lng; ?>');
					this.mapDiv = $('#<?php print $map_id; ?>');
					this.marker = null;

					this.__construct = function () {

						if (This.map !== null) {
							return;
						}

						var getFloatVal = function (value) {
							value = parseFloat(value);

							if (String(value) == 'NaN') {
								value = 0;
							}

							return value;
						}

						// ------------

						var coods = {
							lat: getFloatVal(This.lat_element.val()),
							lng: getFloatVal(This.lng_element.val())
						}

						var myLatlng = new google.maps.LatLng(coods.lat, coods.lng);
						var myOptions = {
							zoom: 4,
							center: myLatlng,
							mapTypeId: google.maps.MapTypeId.ROADMAP,
							streetViewControl: false
						}
						This.map = new google.maps.Map(document.getElementById("<?php print $map_id; ?>"), myOptions);

						This.marker = new google.maps.Marker({
							position: myLatlng,
							map: This.map,
							icon: new google.maps.MarkerImage('<?php echo get_template_directory_uri(); ?>/images/gmap_marker.png',
								new google.maps.Size(34, 40),
								new google.maps.Point(0, 0),
								new google.maps.Point(16, 40)
							),
							draggable: true,
							animation: google.maps.Animation.DROP
						});

						// ------------

						var placeMarker = function (position, noUpdateInputs) {
							if (noUpdateInputs === undefined) {
								noUpdateInputs = false;
							}

							coods.lat = position.lat();
							coods.lng = position.lng();

							if (!noUpdateInputs) {
								This.lat_element.val(String(coods.lat));
								This.lng_element.val(String(coods.lng));
							}

							This.marker.setPosition(position);
							// This.map.setCenter(position);
						}
						google.maps.event.addListener(This.map, 'click', function (event) {
							placeMarker(event.latLng);
						});
						google.maps.event.addListener(This.marker, 'dragend', function (event) {
							placeMarker(event.latLng);
						});


						// ------------
						var change_input = function () {
							coods = {
								lat: getFloatVal(This.lat_element.val()),
								lng: getFloatVal(This.lng_element.val())
							}

							var newLatlng = new google.maps.LatLng(coods.lat, coods.lng);

							placeMarker(newLatlng, true);
							This.map.setCenter(newLatlng);
						}
						This.lat_element.bind('blur change keyup', change_input);
						This.lng_element.bind('blur change keyup', change_input);
					};

					// -----------------
					var This = this;

					$('#seek_property_maps_has_position').bind('change', function () {

						if ($(this).is(':checked')) {
							This.lat_element.removeAttr('disabled').closest('.option').fadeIn();
							This.lng_element.removeAttr('disabled').closest('.option').fadeIn();
							This.mapDiv.closest('.option').fadeIn();
						} else {
							This.lat_element.attr('disabled', 'disabled').closest('.option').fadeOut();
							This.lng_element.attr('disabled', 'disabled').closest('.option').fadeOut();
							This.mapDiv.closest('.option').fadeOut();
						}
					}).trigger('change');

					if (This.mapDiv.is(":visible")) {
						This.__construct();
					}

					(function () { // Fix map shift in hidden elements
						var resizeFunction = function () {
							google.maps.event.trigger(This.map, 'resize');

							if (This.marker !== null) {
								This.map.setCenter(This.marker.getPosition());
							}
						};
						var mapDivState = This.mapDiv.is(":visible");
						var click_function = function () {
							var newState = This.mapDiv.is(":visible");

							if (This.map === null && newState) {
								This.__construct();
							}

							if (mapDivState != newState) {
								mapDivState = newState;

								if (newState) {
									resizeFunction();
								}
							}
						};

						$(document.body).bind('click', click_function);

						var interval = setInterval(function () { // wait until tabs are loaded
							var tabs = $('.ui-tabs-nav', This.mapDiv.closest('.tf_meta_tabs'));
							mapDivState = false;
							if (tabs.length) {
								$('a', tabs).click(click_function);
								click_function();
								clearInterval(interval);
							}
						}, 1000);
					})();

				})();
			});
		</script>
		<?php

		return ob_get_clean();
	}
endif;

if ( ! function_exists( 'tfuse_get_array_with_terms_for_a_taxonomy' ) ) {
	function tfuse_get_array_with_terms_for_a_taxonomy(
		$taxonomy,
		$args = array( 'hide_empty' => false, 'orderby' => 'count', 'order' => 'DESC' )
	) {
		$args   = array_merge( array( 'hide_empty' => false, 'orderby' => 'count', 'order' => 'DESC' ), (array) $args );
		$terms  = get_terms( $taxonomy, $args );
		$result = array();
		if ( ! is_wp_error( $terms ) && $terms ) {
			foreach ( $terms as $term ) {
				$result[ $term->name ] = $term->name;
			}
		}

		return $result;

	}
}

/* ----------------------------------------------------------------------------------- */

/* HELP: Option structure */
array(
	'name'          => __( 'Price', 'tfuse' ),
	// This is used as label
	'pluralization' => array(
		// if item value is int, you can show name in plural or singular (abbreviated or not) depends on value
		// user helper function to show proper name: TF_SEEK_HELPER::get_property_pluralization_name(...)
		'single'      => __( 'Asking Price', 'tfuse' ),
		'plural'      => __( 'Asking Price', 'tfuse' ),
		'single_abbr' => __( 'Price', 'tfuse' ),
		'plural_abbr' => __( 'Price', 'tfuse' )
	),
	'desc'          => sprintf( __( 'Enter the %s price without the currency symbol. You can <a href="admin.php?page=themefuse">change the global currency options</a> in the Fuse Framework.',
		'tfuse' ),
		mb_strtolower( TF_SEEK_HELPER::get_option( 'seek_property_name_singular' ), 'UTF-8' ) ),
	// description shown near option when editing post
	'id'            => 'seek_property_price',
	// unique option id
	'value'         => '1',
	// default value
	'type'          => 'text',
	// input type
	'searchable'    => true,
	// set this to true if you want to make this option searchable
	// if set tot true, for this option will be created a column in seek index table in database
	// / then you can use its id and make sql in some sql generator for some form items
	// Attention!!! once column is created in database table for this option id, it can't be deleted automatically
	// / you have to delete it manually from database table
	'valtype'       => 'int',
	// set valtype for mysql column in seek index table if you set this option as searchable
	// available values: 'int', 'float', 'varchar','date'
);
/* ^ */

$options = array(
	/* Post Media */
	array(
		'name'    => sprintf( __( '%s details', 'tfuse' ),
			ucfirst( TF_SEEK_HELPER::get_option( 'seek_property_name_singular' ) ) ),
		'id'      => 'seek_media',
		'type'    => 'metabox',
		'context' => 'normal'
	),
	// Slider Images
	array(
		'name'       => __( 'Images', 'tfuse' ),
		'desc'       => __( 'Manage the ' . mb_strtolower( TF_SEEK_HELPER::get_option( 'seek_property_name_singular' ) ) . ' images by pressing the "Upload" button. These images will automatically form the slider from the property detail page',
			'tfuse' ),
		'id'         => TF_THEME_PREFIX . '_slider_images',
		'value'      => '',
		'type'       => 'multi_upload',
		'divider'    => true,
		'searchable' => false,
	),
	// Thumbnail Image
	array(
		'name'       => __( 'Thumbnail <br>(300px x 210px)', 'tfuse' ),
		'desc'       => __( 'This is the thumbnail for your ',
				'tfuse' ) . mb_strtolower( TF_SEEK_HELPER::get_option( 'seek_property_name_singular' ),
				'UTF-8' ) . __( ' and it will be displayed in various sliders on the website and in the property listings. Upload one from your computer, or specify an online address for your image (Ex: http://yoursite.com/image.png).',
				'tfuse' ),
		'id'         => TF_THEME_PREFIX . '_thumbnail_image',
		'value'      => '',
		'type'       => 'upload',
		'divider'    => true,
		'searchable' => false,
	),
	array(
		'name'                   => __( 'Price', 'tfuse' ),
		'pluralization'          => array(
			'single'      => __( 'Asking Price', 'tfuse' ),
			'plural'      => __( 'Asking Price', 'tfuse' ),
			'single_abbr' => __( 'Price', 'tfuse' ),
			'plural_abbr' => __( 'Price', 'tfuse' )
		),
		'desc'                   => sprintf( __( 'Enter the %s price without the currency symbol. You can <a href="admin.php?page=themefuse">change the global currency options</a> in the Fuse Framework.',
			'tfuse' ),
			mb_strtolower( TF_SEEK_HELPER::get_option( 'seek_property_name_singular' ), 'UTF-8' ) ),
		'id'                     => 'seek_property_price',
		'value'                  => '',
		'type'                   => 'text',
		'divider'                => true,
		'searchable'             => true,
		'valtype'                => 'int',
		'template_zone'          => '',
		'template_zone_priority' => 0
	),
	array(
		'name'                   => __( 'Net Price', 'tfuse' ),
		'pluralization'          => array(
			'single'      => __( 'Asking Net Price', 'tfuse' ),
			'plural'      => __( 'Asking Net Price', 'tfuse' ),
			'single_abbr' => __( 'Net Price', 'tfuse' ),
			'plural_abbr' => __( 'Net Price', 'tfuse' )
		),
		'desc'                   => sprintf( __( 'This price is calculated automatic. You can <a href="admin.php?page=themefuse">change the global VAT rate</a> in the Fuse Framework.',
			'tfuse' ),
			mb_strtolower( TF_SEEK_HELPER::get_option( 'seek_property_name_singular' ), 'UTF-8' ) ),
		'id'                     => 'seek_property_vat_price',
		'value'                  => '',
		'type'                   => 'text',
		'divider'                => true,
		'valtype'                => 'int',
		'template_zone'          => '',
		'template_zone_priority' => 0
	),
	array(
		'name'                   => __( 'Mileage', 'tfuse' ),
		'pluralization'          => array(
			'single'      => __( 'Mileage', 'tfuse' ),
			'plural'      => __( 'Mileage', 'tfuse' ),
			'single_abbr' => __( 'Mileage', 'tfuse' ),
			'plural_abbr' => __( 'Mileage', 'tfuse' )
		),
		'desc'                   => __( 'Number of kilometers. Ex: 100000. Symbol for kilometers is set in <a href="admin.php?page=themefuse">Fuse Framework</a>',
			'tfuse' ),
		'id'                     => 'seek_property_mileage',
		'value'                  => '',
		'type'                   => 'text',
		'divider'                => true,
		'searchable'             => true,
		'valtype'                => 'int',
		'template_zone'          => '',
		'template_zone_priority' => 0
	),
	array(
		'name'                   => __( 'Capacity', 'tfuse' ),
		'desc'                   => __( 'Motor capacity. Ex: 3200',
			'tfuse' ),
		'id'                     => 'seek_property_engine_size',
		'value'                  => '',
		'type'                   => 'text',
		'divider'                => true,
		'searchable'             => true,
		'valtype'                => 'int',
		'template_zone'          => 'header',
		'template_zone_priority' => 1
	),
	array(
		'name'                   => __( 'Power BHP', 'tfuse' ),
		'desc'                   => __( 'Motor Power in BHP', 'tfuse' ),
		'id'                     => 'seek_property_engine_power_bhp',
		'value'                  => '',
		'type'                   => 'text',
		'divider'                => true,
		'searchable'             => true,
		'valtype'                => 'int',
		'template_zone'          => 'header',
		'template_zone_priority' => 1
	),
	array(
		'name'                   => __( 'Power KW', 'tfuse' ),
		'desc'                   => __( 'Motor Power in KW', 'tfuse' ),
		'id'                     => 'seek_property_engine_power_kw',
		'value'                  => '',
		'type'                   => 'text',
		'divider'                => true,
		'searchable'             => true,
		'valtype'                => 'int',
		'template_zone'          => 'header',
		'template_zone_priority' => 1
	),
	// Discount
	array(
		'name'       => __( 'Discount %', 'tfuse' ),
		'desc'       => __( 'Select the discount % from the initial price. Note that the discount is not applied automatically, you\'ll have to input the price already discounted in the Price field',
			'tfuse' ),
		'id'         => 'seek_property_reduction',
		'value'      => 0,
		'type'       => 'text',
		'divider'    => true,
		'searchable' => true,
		'valtype'    => 'int'
	),
	// Consumption
	array(
		'name'                   => __( 'Consumption ',
				'tfuse' ) . TF_SEEK_HELPER::get_option( 'seek_property_consumption_symbol' ),
		'desc'                   => __( 'Consumption to 100 km, consumtion symbol is set in <a href="admin.php?page=themefuse">Fuse Framework</a>',
			'tfuse' ),
		'id'                     => 'seek_property_consumption',
		'value'                  => 0,
		'type'                   => 'text',
		'divider'                => true,
		'searchable'             => true,
		'valtype'                => 'float',
		'template_zone'          => '',
		'template_zone_priority' => 0,
	),
	array(
		'name'                   => __( 'Origin country', 'tfuse' ),
		'desc'                   => __( 'Country of origin / production', 'tfuse' ),
		'id'                     => 'seek_property_origin',
		'value'                  => '',
		'type'                   => 'text',
		'searchable'             => false,
		'template_zone'          => '',
		'template_zone_priority' => 0,
		'divider'                => true
	),
	array(
		'name'                   => __( 'Emission Class', 'tfuse' ),
		'desc'                   => __( 'Emission Class Ex: EURO 5 (428 g/KM)', 'tfuse' ),
		'id'                     => 'seek_property_emission',
		'value'                  => '',
		'type'                   => 'text',
		'searchable'             => false,
		'template_zone'          => '',
		'template_zone_priority' => 0,
		'divider'                => true
	),

	array(
		'name'       => __( 'Vehicle type', 'tfuse' ),
		'desc'       => sprintf( __( 'For add more types use %s this %s link.', 'tfuse' ),
			'<a href="edit-tags.php?taxonomy=' . TF_SEEK_HELPER::get_post_type() . '_type&post_type=' . TF_SEEK_HELPER::get_post_type() . '">',
			'</a>' ),
		'id'         => TF_THEME_PREFIX . '_vehicle_type',
		'value'      => '',
		'type'       => 'select',
		'options'    => tfuse_get_array_with_terms_for_a_taxonomy( TF_SEEK_HELPER::get_post_type() . '_type' ),
		'searchable' => false,
		'divider'    => true,
	),

	array(
		'name'       => __( 'Fuel type', 'tfuse' ),
		'desc'       => sprintf( __( 'For add more types use %s this %s link.', 'tfuse' ),
			'<a href="edit-tags.php?taxonomy=' . TF_SEEK_HELPER::get_post_type() . '_fuel_type&post_type=' . TF_SEEK_HELPER::get_post_type() . '">',
			'</a>' ),
		'id'         => TF_THEME_PREFIX . '_fuel_type',
		'value'      => '',
		'type'       => 'select',
		'options'    => tfuse_get_array_with_terms_for_a_taxonomy( TF_SEEK_HELPER::get_post_type() . '_fuel_type' ),
		'searchable' => false,
		'divider'    => true,
	),

	array(
		'name'       => __( 'Gearbox type', 'tfuse' ),
		'desc'       => sprintf( __( 'For add more types use %s this %s link.', 'tfuse' ),
			'<a href="edit-tags.php?taxonomy=' . TF_SEEK_HELPER::get_post_type() . '_gearboxes&post_type=' . TF_SEEK_HELPER::get_post_type() . '">',
			'</a>' ),
		'id'         => TF_THEME_PREFIX . '_gearbox_type',
		'value'      => '',
		'type'       => 'select',
		'options'    => tfuse_get_array_with_terms_for_a_taxonomy( TF_SEEK_HELPER::get_post_type() . '_gearboxes' ),
		'searchable' => false,
		'divider'    => true,
	),

	array(
		'name'       => __( 'Status', 'tfuse' ),
		'desc'       => sprintf( __( 'For add more types use %s this %s link.', 'tfuse' ),
			'<a href="edit-tags.php?taxonomy=' . TF_SEEK_HELPER::get_post_type() . '_statuses&post_type=' . TF_SEEK_HELPER::get_post_type() . '">',
			'</a>' ),
		'id'         => TF_THEME_PREFIX . '_status',
		'value'      => '',
		'type'       => 'select',
		'options'    => tfuse_get_array_with_terms_for_a_taxonomy( TF_SEEK_HELPER::get_post_type() . '_statuses' ),
		'searchable' => false,
		'divider'    => true
	),
	array(
		'name'       => __( 'Color', 'tfuse' ),
		'desc'       => sprintf( __( 'For add more types use %s this %s link.', 'tfuse' ),
			'<a href="edit-tags.php?taxonomy=' . TF_SEEK_HELPER::get_post_type() . '_colors&post_type=' . TF_SEEK_HELPER::get_post_type() . '">',
			'</a>' ),
		'id'         => TF_THEME_PREFIX . '_color',
		'value'      => '',
		'type'       => 'select',
		'options'    => tfuse_get_array_with_terms_for_a_taxonomy( TF_SEEK_HELPER::get_post_type() . '_colors' ),
		'searchable' => false,
		'divider'    => true
	),

	array(
		'name'                   => __( 'Registration Year', 'tfuse' ),
		'desc'                   => __( 'Month and year of registration / production', 'tfuse' ),
		'id'                     => 'seek_property_year',
		'value'                  => '',
		'type'                   => 'text',
		'valtype'                => 'date',
		'searchable'             => true,
		'template_zone'          => '',
		'template_zone_priority' => 0
	),
);