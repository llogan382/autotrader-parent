<?php

if (!defined('TFUSE'))
    exit('Direct access forbidden.');
?>
<?php
if(empty($settings['taxonomy']) || !taxonomy_exists($settings['taxonomy'])) return false;
$args = array();
if(!empty($settings['get_terms_args']) && is_array($settings['get_terms_args'])) $args = $settings['get_terms_args'];
$terms = get_terms($settings['taxonomy'], $args );
if(!sizeof($terms)) return false;
$getValue  = TF_SEEK_HELPER::get_input_value($parameter_name);

$options = array();
foreach($terms as $term)
{
    $options[$term->term_id] = $term->name;
}
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
    <?php
    if(!empty($settings['have_child'])){
        $args = array(
            'orderby'       => 'name',
            'order'         => 'ASC',
            'hide_empty'    => false,
            'exclude'       => array(),
            'exclude_tree'  => array(),
            'include'       => array(),
            'fields'        => 'all',
            'hierarchical'  => true,
            'child_of'      => 0,
            'pad_counts'    => false,
            'cache_domain'  => 'core'
        );
        $terms = get_terms($settings['taxonomy'], $args);
        echo "<input type='hidden' value='" . json_encode($terms) . "' id='" . $settings['taxonomy'] . "_terms'>";
    }
    ?>
</div>
