<?php

if (!defined('TFUSE'))
    exit('Direct access forbidden.');
$options = array();
if(isset($settings['from']) && isset($settings['to'])){
        if(!empty($settings['step'])){
            $step = intval($settings['step']);
        }else{
            $step = 1;
        }
        foreach(range($settings['from'], $settings['to'], $step) as $value ){
            $options[$value] = $value;
        }

}elseif(!empty($settings['options']) && is_array($settings['options'])){
    $options = $settings['options'];
}
if(!sizeof($options)) return false;
$getValue  = TF_SEEK_HELPER::get_input_value($parameter_name);

if(is_null($getValue) || empty($getValue))
{
    $default_value = $vars['default_option'];
}
else
{
    $suffix = (!empty($settings['options_suffix'])) ? $settings['options_suffix'] : '';
    $suffix_pos = (!empty($settings['suffix_pos'])) ? $settings['suffix_pos'] : 0;
    $default_value = tfuse_symbol_position($options[$getValue], $suffix, $suffix_pos, '', true);
}

$default_checked = (is_null($getValue)) ? 'checked' : '';
?>

<div class="row field_multiselect parent_multi_select closable">
    <?php if(!empty($vars['label'])): ?><label class="label_title <?php if(!empty($vars['label_class'])) echo esc_attr( $vars['label_class'] ); ?>" ><?php echo esc_attr( $vars['label'] ); ?>:</label><?php endif; ?>
    <div class="multi_select <?php echo $item_id; ?>">
        <span class="multi_select_text"><?php echo $default_value ?></span>
        <div class="multi_select_box input_styled checklist radiolist <?php echo $item_id; ?>">
            <div class="select_row">
                <input type="radio" name="<?php echo $parameter_name; ?>" id="<?php echo $item_id . '_default'?>" value="" <?php echo $default_checked ?>/>
                <label for="<?php echo $item_id . '_default'?>"><?php echo $vars['default_option'] ?></label>
            </div>
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
        </div>
    </div>
    <script>
        jQuery(document).ready(function(){
            add_style_for_multi_select('<?php echo $item_id; ?>');
        });
    </script>
</div>