<?php

if (!defined('TFUSE'))
    exit('Direct access forbidden.');
$options = array();
if( (isset($settings['auto_options'])) && ($settings['auto_options'] === false) && !empty($settings['custom_options']) && is_array($settings['custom_options']) ){

        $options = $settings['custom_options'];

}else{
    global $wpdb;
    $sql = "SELECT MIN(" . $settings['complete_from'] . ") AS min, MAX(" . $settings['complete_from'] . ") AS max FROM " . TF_SEEK_HELPER::get_db_table_name() . ";";
    $sql_results = $wpdb->get_row($sql);
    $step_count = (intval($settings['auto_steps'])) ? intval($settings['auto_steps']) : 5;
    $step = ($sql_results->max - $sql_results->min) / $step_count;
    $step = round($step, intval($settings['auto_round_precision']));

    for($i=1;$i<$step_count;$i++){
        $value_1 = strval($sql_results->min+($step*$i) - $step);
        $value_2 = strval(($sql_results->min+($step*($i+1))) - $step);
        $label = str_replace('%%value1%%', $value_1, $settings['auto_label_format']);
        $label = str_replace('%%value2%%', $value_2, $label);
        $label = str_replace('%%suffix%%', $settings['auto_options_suffix'], $label);
        $options[$value_1 . '-' . $value_2] = $label;
    }

    $value_1 = intval($value_2);
    if($value_1 > $sql_results->max || ($sql_results->max-$value_1)<($step/3)){
        $value_2 = $value_1+$step;
    }else{
        $value_2 = $sql_results->max;
    }
    $value_1 = strval($value_1);
    $value_2 = strval($value_2);
    $label = str_replace('%%value1%%', $value_1, $settings['auto_label_format']);
    $label = str_replace('%%value2%%', $value_2, $label);
    $label = str_replace('%%suffix%%', $settings['auto_options_suffix'], $label);
    $options[$value_1 . '-' . $value_2] = $label;
}
if(!sizeof($options)) return false;
$getValue  = TF_SEEK_HELPER::get_input_value($parameter_name);
if(is_null($getValue) || empty($getValue))
{
    $default_value = ( isset($vars['default_option']) ) ? $vars['default_option'] : __('All', 'tfuse');
}
else
{
    $suffix = (!empty($settings['options_suffix'])) ? $settings['options_suffix'] : '';
    $suffix_pos = (!empty($settings['suffix_pos'])) ? $settings['suffix_pos'] : 0;
    $default_value = tfuse_symbol_position($options[$getValue], $suffix, $suffix_pos, '', true);
}
?>
<div class="row field_multiselect parent_multi_select closable">
    <?php if(!empty($vars['label'])): ?><label class="label_title <?php if(!empty($vars['label_class'])) echo esc_attr( $vars['label_class'] ); ?>" ><?php echo esc_attr( $vars['label'] ); ?>:</label><?php endif; ?>
    <div class="multi_select <?php echo $item_id; ?>">
        <span class="multi_select_text"><?php echo $default_value ?></span>
        <div class="multi_select_box input_styled checklist radiolist <?php echo $item_id; ?>">
            <?php if(isset($vars['default_option'])) : ?>
                <div class="select_row">
                    <input type="radio" name="<?php echo $parameter_name; ?>" id="<?php echo $item_id . '_default'?>" value="" />
                    <label for="<?php echo $item_id . '_default'?>"><?php echo $vars['default_option'] ?></label>
                </div>
            <?php endif; ?>
            <?php
            foreach($options as $key=>$value) :
                $suffix = (!empty($settings['options_suffix'])) ? $settings['options_suffix'] : '';
                $suffix_pos = (!empty($settings['suffix_pos'])) ? $settings['suffix_pos'] : 0;
                $option_str = tfuse_symbol_position($value, $suffix, $suffix_pos, '', true);
                $checked = ($key==$getValue ? ' checked' : '');
                ?>
                <div class="select_row">
                    <input type="radio" name="<?php echo $parameter_name; ?>" id="<?php echo $item_id . '_' . $key?>" value="<?php echo $key ?>" <?php echo $checked ?>/>
                    <label for="<?php echo $item_id . '_' . $key?>"><?php echo $option_str ?></label>
                </div>
            <?php endforeach; ?>
            <?php
            if(!empty($settings['max_unlimited']) && $settings['max_unlimited']) : ?>
                <div class="select_row">
                    <input type="radio" name="<?php echo $parameter_name; ?>" id="<?php echo $item_id . '_unlimited'?>" value="unlimited" />
                    <label for="<?php echo $item_id . '_unlimited'?>"><?php echo ($settings['unlimited_label']) ?  $settings['unlimited_label'] : __('Unlimited', 'tfuse') ?></label>
                </div>
            <?php endif ?>
        </div>
    </div>
    <script>
        jQuery(document).ready(function(){
            add_style_for_multi_select('<?php echo $item_id; ?>');
        });
    </script>
</div>