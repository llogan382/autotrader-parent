<?php

if (!defined('TFUSE'))
    exit('Direct access forbidden.');
global $post;
$result = array();

foreach($options as $id=>$val){
    if ( $id == 'seek_property_mileage')
    {
        $mileage_number         = apply_filters( 'tfuse_mileage_number_format', number_format(intval($val['value'])), intval($val['value']) );
        $mileage_symbol         = ' <span>' . TF_SEEK_HELPER::get_option('seek_property_mileage_symbol', __('KM', 'tfuse')) . '</span>';
        $out                    = tfuse_symbol_position($mileage_number, $mileage_symbol, 1, ' ', true);
        $result[$id]['name']    = __('Mileage:', 'tfuse');
        $result[$id]['value']   = apply_filters('tfuse_mileage_symbol_position', $out, $mileage_number, $mileage_symbol);
        continue;
    }
    elseif( $id =='seek_property_engine_power_kw' ){
        $result[$id]['name']    = __('Power:', 'tfuse');

        $power_kw               = apply_filters('tfuse_power_kw_number_format', number_format(intval($val['value'])), intval($val['value']));
        $power_kw_symbol        = __('KW', 'tfuse');
        $power_kw_out           = tfuse_symbol_position($power_kw, $power_kw_symbol, 1, ' ', true);
        $power_kw_out           = apply_filters('tfuse_power_kw_symbol_position', $power_kw_out, $power_kw, $power_kw_symbol);

        $power_bhp              = apply_filters('tfuse_power_bhp_number_format', TF_SEEK_HELPER::get_post_option('property_engine_power_bhp', 0, $post->ID));
        $power_bhp_symbol       = __(' BHP', 'tfuse');
        $power_bhp_out          = tfuse_symbol_position($power_bhp, $power_bhp_symbol, 1, ' ', true);
        $power_bhp_out          = apply_filters('tfuse_power_bhp_symbol_position', $power_bhp_out, $power_bhp, $power_bhp_symbol);

        $out                    = $power_kw_out . __(' (', 'tfuse') .  $power_bhp_out  . __(')', 'tfuse');

        $result[$id]['value']   = apply_filters('tfuse_kw_bhp_symbol_position', $out, $power_kw_out, $power_bhp_out);
        continue;
    }
    elseif( $id =='seek_property_engine_size' ){
        $result[$id]['name']    = __('Motor Capacity:', 'tfuse');

        $engine_size            = apply_filters('tfuse_engine_size_number_format', number_format(intval($val['value'])), ($val['value']));
        $engine_size_symbol     = '<span>' . __('cm³','tfuse') . '</span>';
        $out                    = tfuse_symbol_position($engine_size, $engine_size_symbol, 1, ' ', true);

        $result[$id]['value']   = apply_filters('tfuse_engine_size_symbol_position', $out, $engine_size, $engine_size_symbol);
        continue;
    }
    elseif( $id == 'seek_property_engine_power_bhp'){
        $result[$id]['name'] = __('Power:', 'tfuse');

        $power_kw           = apply_filters('tfuse_power_kw_number_format', TF_SEEK_HELPER::get_post_option('property_engine_power_kw', 0, $post->ID));
        $power_kw_symbol    = __(' KW', 'tfuse');
        $power_kw_out       = tfuse_symbol_position($power_kw, $power_kw_symbol, 1, ' ', true);
        $power_kw_out       = apply_filters('tfuse_power_kw_symbol_position', $power_kw_out, $power_kw, $power_kw_symbol);

        $power_bhp          = apply_filters('tfuse_power_bhp_number_format', number_format(intval($val['value'])), intval($val['value']));
        $power_bhp_symbol   = __(' BHP', 'tfuse');
        $power_bhp_out      = tfuse_symbol_position($power_bhp, $power_bhp_symbol, 1, ' ', true);
        $power_bhp_out      = apply_filters('tfuse_power_bhp_symbol_position', $power_bhp_out, $power_bhp, $power_bhp_symbol);

        $out                = $power_bhp_out . __(' (', 'tfuse') .  $power_kw_out  . __(')', 'tfuse');

        $result[$id]['value'] = apply_filters('tfuse_bhp_kw_symbol_position', $out, $power_kw_out, $power_bhp_out);
        continue;
    }
    elseif( $id == 'seek_property_price'){
        $result[$id]['name'] = __('Price:', 'tfuse');

        $price_number_str   = apply_filters( 'tfuse_price_number_format', number_format(intval($val['value']),0,'', ','), intval($val['value']) );
        $price_symbol_str   = ' <span class="symbol_price_right">' .  TF_SEEK_HELPER::get_option('seek_property_currency_symbol','$') . '</span>';
        $price_symbol_pos   = TF_SEEK_HELPER::get_option('seek_property_currency_symbol_pos', 0);

        $result[$id]['value'] = tfuse_symbol_position($price_number_str, $price_symbol_str, $price_symbol_pos);
        continue;
    }
    elseif( $id == 'seek_property_origin'){
        $result[$id]['name'] = __('Country of Origin:', 'tfuse');
        $result[$id]['value'] = $val['value'];
        continue;
    }
    elseif( $id == 'seek_property_year'){
        $result[$id]['name'] = __('Registration Year:', 'tfuse');
        $result[$id]['value'] = $val['value'];
        continue;
    }
    elseif( $id == 'seek_property_emission'){
        $result[$id]['name'] = __('CO2 emissions:', 'tfuse');
        $result[$id]['value'] = $val['value'];
        continue;
    }
    elseif ($id == 'seek_property_consumption') {
	    $result[$id]['value'] = $val['value'] . ' ' . apply_filters('seek_property_consumption_symbol', TF_SEEK_HELPER::get_option('seek_property_consumption_symbol', 'L/100km'));
	    $result[$id]['name'] = __('Consumption:', 'tfuse');
    }
    elseif($val['type']=='taxonomy')
    {
        $terms = '';
        if(sizeof($val['value'])){
            foreach($val['value'] as $tax){
                if($tax->taxonomy=='vehicle_type')
                    $terms .= '<a href="'.get_term_link($tax->slug,'vehicle_type').'">'.$tax->name .'</a>'. __(', ', 'tfuse');
                elseif($tax->taxonomy=='vehicle_gearboxes')
                    $terms .= '<a href="'.get_term_link($tax->slug,'vehicle_gearboxes').'">'.$tax->name .'</a>'. __(', ', 'tfuse');
                elseif($tax->taxonomy=='vehicle_colors')
                    $terms .= '<a href="'.get_term_link($tax->slug,'vehicle_colors').'">'.$tax->name .'</a>'. __(', ', 'tfuse');
                elseif($tax->taxonomy=='vehicle_fuel_type')
                    $terms .= '<a href="'.get_term_link($tax->slug,'vehicle_fuel_type').'">'.$tax->name .'</a>'. __(', ', 'tfuse');
                elseif($tax->taxonomy=='vehicle_models')
                    $terms .= '<a href="'.get_term_link($tax->slug,'vehicle_models').'">'.$tax->name .'</a>'. __(', ', 'tfuse');
                elseif($tax->taxonomy=='vehicle_locations')
                    $terms .= '<a href="'.get_term_link($tax->slug,'vehicle_locations').'">'.$tax->name .'</a>'. __(', ', 'tfuse');
                else
                    $terms .= $tax->name . __(', ', 'tfuse');
            }
            $terms = substr_replace($terms ,"",-2);
        }
        if($id == 'vehicle_type'){
            $result[$id]['name'] = __('Body Type:', 'tfuse');
        }
        elseif( $id == 'vehicle_gearboxes'){
            $result[$id]['name'] = __('Gearbox Type:', 'tfuse');
        }
        elseif( $id == 'vehicle_colors'){
            $result[$id]['name'] = __('Color:', 'tfuse');
        }
        elseif( $id == 'vehicle_fuel_type'){
            $result[$id]['name'] = __('Fuel Type:', 'tfuse');
        }
        elseif( $id == 'vehicle_safely_features'){
            $result[$id]['name'] = __('Safety Features:', 'tfuse');
        }
        elseif( $id == 'vehicle_safely_features'){
            $result[$id]['name'] = __('Safety Features:', 'tfuse');
        }
        elseif( $id == 'vehicle_statuses'){
            $result[$id]['name'] = __('Status:', 'tfuse');
        }
        elseif( $id == 'vehicle_models'){
            $result[$id]['name'] = __('Model:', 'tfuse');
        }
        elseif( $id == 'vehicle_locations'){
            $result[$id]['name'] = __('Location:', 'tfuse');
        }
        elseif( $id == 'vehicle_extras'){
            $result[$id]['name'] = __('Extras:', 'tfuse');
        }
        elseif( $id == 'vehicle_interior_features'){
            $result[$id]['name'] = __('Interior Features:', 'tfuse');
        }
        elseif( $id == 'vehicle_exterior_features'){
            $result[$id]['name'] = __('Exterior Features:', 'tfuse');
        }
        else $result[$id]['name'] = '';
        $result[$id]['value'] = '<span>' . $terms . '</span>';
        continue;
    }
    else
    {
        continue;
    }
} ?>

<?php if (sizeof($result) || true) : ?>
    <div class="offer_specification">
        <ul>
            <?php foreach($result as $key=>$value){
                if($value['value']!='' && $value['value']!='<span></span>')
                    echo '<li><span class="spec_name '.$key.' ">'.$value['name'].'</span> <strong class="spec_value '.$key.' ">'.$value['value'].'</strong> </li>';
            } ?>
        </ul>
    </div>
<?php endif; ?>