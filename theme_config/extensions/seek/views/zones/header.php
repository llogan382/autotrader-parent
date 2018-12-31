<?php

if (!defined('TFUSE'))
    exit('Direct access forbidden.');
global $post;
$result = array();

foreach($options as $id=>$val){
    if ( $id == 'seek_property_mileage')
    {
        $mileage_number     = apply_filters( 'tfuse_mileage_number_format', number_format(intval($val['value'])), intval($val['value']) );
        $mileage_symbol     = '<span>' . TF_SEEK_HELPER::get_option('seek_property_mileage_symbol', __('KM', 'tfuse')) . '</span>';

        $out                = tfuse_symbol_position($mileage_number, $mileage_symbol, 1, ' ', true);
        $result[]           = apply_filters('tfuse_mileage_symbol_position', $out, $mileage_number, $mileage_symbol);
        continue;
    }
    if ( $id == 'seek_property_price')
    {
        $price_number_str   = apply_filters( 'tfuse_price_number_format', number_format(intval($val['value']),0,'', '.'), intval($val['value']) );
        $price_symbol_str   = ' <span class="symbol_price_right">' .  TF_SEEK_HELPER::get_option('seek_property_currency_symbol','$') . '</span>';
        $price_symbol_pos   = TF_SEEK_HELPER::get_option('seek_property_currency_symbol_pos', 0);

        $result[] =  tfuse_symbol_position($price_number_str, $price_symbol_str, $price_symbol_pos) ;
        continue;
    }
    elseif ( $id == 'seek_property_engine_power_kw')
    {
        $power_kw           = apply_filters('tfuse_power_kw_number_format', number_format(intval($val['value'])), intval($val['value']));
        $power_kw_symbol    = __('KW', 'tfuse');
        $power_kw_out       = tfuse_symbol_position($power_kw, $power_kw_symbol, 1, ' ', true);
        $power_kw_out       = apply_filters('tfuse_power_kw_symbol_position', $power_kw_out, $power_kw, $power_kw_symbol);

        $power_bhp          = apply_filters('tfuse_power_bhp_number_format', TF_SEEK_HELPER::get_post_option('property_engine_power_bhp', 0, $post->ID));
        $power_bhp_symbol   = __(' BHP', 'tfuse');
        $power_bhp_out      = tfuse_symbol_position($power_bhp, $power_bhp_symbol, 1, ' ', true);
        $power_bhp_out      = apply_filters('tfuse_power_bhp_symbol_position', $power_bhp_out, $power_bhp, $power_bhp_symbol);

        $out                = $power_kw_out . __(' (', 'tfuse') .  $power_bhp_out  . __(')', 'tfuse');
        $result[]           = apply_filters('tfuse_kw_bhp_symbol_position', $out, $power_kw_out, $power_bhp_out);
        continue;
    }
    elseif ( $id == 'seek_property_engine_power_bhp')
    {
        $power_kw           = apply_filters('tfuse_power_kw_number_format', TF_SEEK_HELPER::get_post_option('property_engine_power_kw', 0, $post->ID));
        $power_kw_symbol    = __(' KW', 'tfuse');
        $power_kw_out       = tfuse_symbol_position($power_kw, $power_kw_symbol, 1, ' ', true);
        $power_kw_out       = apply_filters('tfuse_power_kw_symbol_position', $power_kw_out, $power_kw, $power_kw_symbol);

        $power_bhp          = apply_filters('tfuse_power_bhp_number_format', number_format(intval($val['value'])), intval($val['value']));
        $power_bhp_symbol   = __(' BHP', 'tfuse');
        $power_bhp_out      = tfuse_symbol_position($power_bhp, $power_bhp_symbol, 1, ' ', true);
        $power_bhp_out      = apply_filters('tfuse_power_bhp_symbol_position', $power_bhp_out, $power_bhp, $power_bhp_symbol);

        $out                = $power_bhp_out . __(' (', 'tfuse') .  $power_kw_out  . __(')', 'tfuse');
        $result[]           = apply_filters('tfuse_bhp_kw_symbol_position', $out, $power_kw_out, $power_bhp_out);
        continue;
    }
    elseif ( $id == 'seek_property_engine_size'){
        $engine_size        = apply_filters('tfuse_engine_size_number_format', number_format(intval($val['value'])), ($val['value']));
        $engine_size_symbol = '<span>' . __('cm³','tfuse') . '</span>';
        $out                = tfuse_symbol_position($engine_size, $engine_size_symbol, 1, ' ', true);
        $result[]           = apply_filters('tfuse_engine_size_symbol_position', $out, $engine_size, $engine_size_symbol);
        continue;
    }
    elseif ($id == 'seek_property_consumption'){
		$result[] = $val['value'] . ' ' . apply_filters('seek_property_consumption_symbol', TF_SEEK_HELPER::get_option('seek_property_consumption_symbol', 'L/100km'));
    }
    elseif ( $id == 'seek_property_year' || $id == 'seek_property_origin' || $id == 'seek_property_emission' ){
        $result[] = apply_filters('tfuse_vehicle_year', $val['value']);
        continue;
    }
    elseif($val['type']=='taxonomy')
    {
        if(sizeof($val['value']))
        {
            $terms = '';
            foreach($val['value'] as $tax)
            {
                $terms .= $tax->name . __(', ', 'tfuse');
            }
            $terms = substr_replace($terms ,"",-2);
            $taxonomy = get_taxonomy($id);
            $taxonomy_name = (sizeof($val['value']) == 1) ? $taxonomy->labels->singular_name : $taxonomy->labels->name;
            if( ($id == TF_SEEK_HELPER::get_post_type() . '_fuel_type') || ($id == TF_SEEK_HELPER::get_post_type() . '_locations') || ($id == TF_SEEK_HELPER::get_post_type() . '_models') || ($id == 'vehicle_type') || ($id == 'vehicle_gearboxes') || ($id == 'vehicle_statuses')|| ($id == 'vehicle_colors')){
                $result[]  =  $terms;
            }else
            {
                $result[]  = '<span>' . $taxonomy_name  . __(': ', 'tfuse') . '</span>' . $terms;
            }
        }
        else
        {
            continue;
        }
    }
    else {
        continue;
    }
} ?>

<?php if (sizeof($result)) : ?>
    <div class="offer_data">
        <ul><?php foreach($result as $value)  echo '<li>' . $value . '</li>'; ?></ul>
    </div>
<?php endif; ?>