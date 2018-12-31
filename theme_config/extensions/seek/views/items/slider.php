<?php

if (!defined('TFUSE'))
    exit('Direct access forbidden.');

if(!function_exists('__get_num_short_prefix_version')):
    function __get_num_short_prefix_version($num, $prefix = '', $max_round = true)
    {
        $num = intval($num);

        if($num >= 1000000){
            $num /= 10000000;
            $num = round($num,2);
            $num *=10;

            $num = apply_filters('tfuse_slider_milliones_number_format', number_format($num), $num);
            $symbol =  __('Mil', 'tfuse');
            $out1 = apply_filters('tfuse_slider_measure_symbol_position', tfuse_symbol_position($num, $symbol, 1, '', true));
            $out2 = apply_filters('tfuse_slider_prefix_symbol_position', tfuse_symbol_position($out1, $prefix, 0, '', true));

            return $out2;
        }

        if($num >= 1000){
            $num /= 10000;
            $num = round($num,2);
            $num *=10;

            $num = apply_filters('tfuse_kilo_number_format', number_format($num), $num);
            $symbol =  __('k', 'tfuse');
            $out1 = apply_filters('tfuse_slider_measure_symbol_position', tfuse_symbol_position($num, $symbol, 1, '', true));
            $out2 = apply_filters('tfuse_slider_prefix_symbol_position', tfuse_symbol_position($out1, $prefix, 0, '', true));

            return $out2;
        }

        $num = apply_filters('tfuse_slider_number_format', number_format($num), $num);

        return apply_filters('tfuse_slider_prefix_symbol_position', tfuse_symbol_position($num, $prefix, 0, '', true));
    }
endif;


if(!empty($settings['auto_options']))
{

    global $wpdb;

    $col_name   = trim($wpdb->prepare('%s', $sql_generator_options['search_on_id']), "'");
    $db_min_max = $wpdb->get_row( "SELECT
        MAX(". $col_name .") as max, MIN(". $col_name .") as min
            FROM ". trim($wpdb->prepare('%s', TF_SEEK_HELPER::get_db_table_name()), "'") ." si
        INNER JOIN " . $wpdb->prefix . "posts AS p ON p.ID = si.post_id
            WHERE p.post_status = 'publish'
        LIMIT 1", ARRAY_A);

    if(!sizeof($db_min_max)) return;
    if($db_min_max['min'] == $db_min_max['max']) return;

    $settings['step']     = $settings['auto_step'];
    $settings['from']     = $db_min_max['min']  - ($db_min_max['min'] % $settings['step']);
    $settings['to']       = $db_min_max['max'];
    $settings['scale']    = array();
    $settings['scale'][]  = __get_num_short_prefix_version($settings['from'] , (!empty($settings['dimension'])) ? $settings['dimension'] : '', false);
    $settings['scale'][]  = __get_num_short_prefix_version($settings['to'], (!empty($settings['dimension'])) ? $settings['dimension'] : '');
	$settings['selected_values'] = array();
}

$selected_min = $settings['from'];
$selected_max = $settings['to'];
if(is_array($settings['selected_values']) && sizeof($settings['selected_values']) == 2){
    $selected_min = min($settings['selected_values']);
    $selected_max = max($settings['selected_values']);
}
$selected_values = TF_SEEK_HELPER::get_input_value($parameter_name, false);
$values = array();
if(!empty($settings['listen_items']['min'])){
    $values[0] = TF_SEEK_HELPER::get_input_value($settings['listen_items']['min'],false);
}
if(!empty($settings['listen_items']['max'])){
    $values[1] = TF_SEEK_HELPER::get_input_value($settings['listen_items']['max'],false);
}
if(!empty($selected_values)){
    $temp = explode(';',$selected_values);
    if(is_numeric(@$temp[0]) && is_numeric(@$temp[1])){
        $values = $temp;
    }
}
if(!isset($values[0]) || !is_numeric($values[0]))
    $values[0] = $selected_min;
if(!isset($values[1]) || !is_numeric($values[1]))
    $values[1] = $selected_max;
?>
<style type="text/css">
    <?php $direction = TF_SEEK_HELPER::get_option('seek_property_currency_symbol_pos', 0);?>
    .jslider .jslider-value span{
        float: <?php echo ($direction == '1') ? 'left' : 'right'; ?>;
    }
</style>
<div class="row rangeField">
    <?php if(isset($vars['label'])): ?><label class="<?php print esc_attr( $vars['label_class'] ); ?>" ><?php print esc_attr( $vars['label'] ); ?>:</label><?php endif; ?>
    <div class="range-slider">
        <input id="range_field_<?php print esc_attr($item_id); ?>" type="text" name="<?php print esc_attr($parameter_name); ?>" value="<?php echo $values[0] . ';'. $values[1];  ?>">
    </div>
    <div class="clear"></div>
</div>
<script type="text/javascript" >
    jQuery(document).ready(function() {
        // Price Range Input
        var current_item = jQuery("#range_field_<?php print esc_attr($item_id); ?>");
        current_item.slider({
            from: <?php print $settings['from']; ?>,
            to: <?php print $settings['to']; ?>,
            smooth: <?php print (@$settings['smooth'] ? 'true' : 'false'); ?>,
            scale: <?php print str_replace('\\/', '/', json_encode($settings['scale']) ); ?>,
            skin: "<?php print @$settings['skin']; ?>",
            limits: <?php print (@$settings['limits'] ? 'true' : 'false'); ?>
            <?php if(isset($settings['step'])): ?>,
                step: <?php echo $settings['step']; endif; ?>
            <?php if(!empty($settings['dimension'])): ?>,
                dimension: '<?php print $settings['dimension']; ?>',
            <?php endif; ?>
        });
    });
</script>
