<?php

if (!defined('TFUSE'))
    exit('Direct access forbidden.');

if(empty($settings['taxonomy']) || !taxonomy_exists($settings['taxonomy'])) return false;
$args = array();
if(!empty($settings['get_terms_args']) && is_array($settings['get_terms_args'])) $args = $settings['get_terms_args'];
$terms          = get_terms($settings['taxonomy'], $args );
if(!sizeof($terms)) return false;
$selectedValues = explode(';',TF_SEEK_HELPER::get_input_value($parameter_name));
?>
<div class="row field_multiselect parent_multi_select">
    <?php if(!empty($vars['label'])): ?><label class="label_title <?php if(!empty($vars['label_class'])) echo esc_attr( $vars['label_class'] ); ?>" ><?php echo esc_attr( $vars['label'] ); ?>:</label><?php endif; ?>
    <div class="multi_select <?php echo $item_id; ?>">
        <span class="multi_select_text"><?php echo $vars['default_option']; ?></span>
        <div class="multi_select_box input_styled checklist <?php echo $item_id; ?>">
            <?php
                foreach($terms as $term) :
                    $is_checked = (in_array($term->term_id, $selectedValues)) ? ' checked' : '';
                    echo '<div class="select_row"><input type="checkbox" name="' . $item_id . '" id="' . $item_id . '_' . $term->term_id . '" value="' . $term->term_id . '" '. $is_checked . '> <label for="' . $item_id . '_' . $term->term_id . '">' . $term->name .  '</label></div>';
                endforeach;
            ?>
        </div>
    </div>

<?php
if(!empty($settings['have_child']) ){
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
    echo "<input type='hidden' value='" . json_encode($terms, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE) . "' id='" . $settings['taxonomy'] . "_terms'>";
}
?>
    <input type="hidden" class="multi_select_taxonomy <?php echo $item_id; ?>" name="<?php echo $parameter_name; ?>" value="all">
</div>

<script>
    jQuery(document).ready(function(){

            var $=jQuery;

            add_style_for_multi_select('<?php echo $item_id; ?>');
            change_values_for_multi_select('<?php echo $item_id; ?>');
            $('.field_multiselect .<?php echo $item_id; ?> input[type="checkbox"]').change(function(){
                change_values_for_multi_select('<?php echo $item_id; ?>');
            });
    });
</script>