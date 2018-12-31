<?php

if (!defined('TFUSE'))
    exit('Direct access forbidden.');
$getValue       = TF_SEEK_HELPER::get_input_value($parameter_name);
$parentGetValue = TF_SEEK_HELPER::get_input_value($settings['parent_name']);
?>
<div class="row field_multiselect">
    <?php if(!empty($vars['label'])): ?><label class="label_title <?php if(!empty($vars['label_class'])) echo esc_attr( $vars['label_class'] ); ?>" ><?php echo esc_attr( $vars['label'] ); ?>:</label><?php endif; ?>
    <div class="multi_select <?php echo $item_id; ?>">
        <span class="multi_select_text"><?php echo $vars['default_option']; ?></span>
        <div class="multi_select_box input_styled checklist  <?php echo $item_id; ?>">
        </div>
    </div>
    <input type="hidden" class="multi_select_taxonomy <?php echo $item_id; ?>" name="<?php echo $parameter_name; ?>" value="all">
    <script type="text/javascript">
        jQuery(document).ready(function(){
            var $=jQuery;
            $('.field_multiselect .<?php echo $item_id; ?>').change(function(){
                var checked_terms = [];
                $(this).find('.select_row input:checked').each(function(){
                    checked_terms.push($(this).val());
                });
                $('.multi_select_taxonomy.<?php echo $item_id; ?>').val((checked_terms.length) ? checked_terms.join(';') : 'all');
            });

            add_style_for_sensitive_multi_select('<?php echo $item_id; ?>');

            window.<?php echo $settings['taxonomy']; ?>_terms = jQuery.parseJSON( jQuery('#<?php echo $settings['taxonomy']; ?>_terms').val() );

            function populate_<?php echo $item_id; ?>_multi_select_onchange_parent(e, params){
                var selected = [];
                var items = '';
                var itemId = '<?php echo $item_id; ?>';

                $('.field_multiselect .' + itemId +' input:checked').each(function(){
                    selected.push($(this).val());
                });

                for(var i in window.<?php echo $settings['taxonomy']; ?>_terms ){
                    if(jQuery.inArray(window.<?php echo $settings['taxonomy']; ?>_terms[i].parent, params.terms) != -1){
                        var checked = '';
                        if(jQuery.inArray(window.<?php echo $settings['taxonomy']; ?>_terms[i].term_id, selected) != -1) checked = ' checked';
                        items += '<div class="select_row" style="height: 25px;"><input type="checkbox" name="' + itemId + '" id="' + itemId + '_' + window.<?php echo $settings['taxonomy']; ?>_terms[i].term_id + '" value="' + window.<?php echo $settings['taxonomy']; ?>_terms[i].term_id + '"' + checked + '> <label for="' + itemId + '_' + window.<?php echo $settings['taxonomy']; ?>_terms[i].term_id + '">' + window.<?php echo $settings['taxonomy']; ?>_terms[i].name + '</label></div>';
                    }
                }
                $('.multi_select_box.' + itemId).html(items);
                add_style_for_sensitive_multi_select(itemId);
            }

            jQuery('.multi_select_taxonomy.<?php echo $settings['parent_id']; ?>').on('terms_changed', populate_<?php echo $item_id; ?>_multi_select_onchange_parent);
            function populate_<?php echo $item_id; ?>_multi_select(){
                var itemId = '<?php echo $item_id; ?>';
                var getValue = '<?php echo $getValue; ?>';
                var selectedValues = getValue.split(';');
                var parentGetValue = '<?php echo $parentGetValue; ?>';
                var parentSelectedValues = parentGetValue.split(';');
                var items = '';

                for(var i in window.<?php echo $settings['taxonomy']; ?>_terms ){
                    if(jQuery.inArray(window.<?php echo $settings['taxonomy']; ?>_terms[i].parent.toString(), parentSelectedValues) != -1){
                        var checked = '';
                        if(jQuery.inArray(window.<?php echo $settings['taxonomy']; ?>_terms[i].term_id.toString(), selectedValues) != -1) checked = ' checked';
                        items += '<div class="select_row" style="height: 25px;"><input type="checkbox" name="' + itemId + '" id="' + itemId + '_' + window.<?php echo $settings['taxonomy']; ?>_terms[i].term_id + '" value="' + window.<?php echo $settings['taxonomy']; ?>_terms[i].term_id + '"' + checked + '> <label for="' + itemId + '_' + window.<?php echo $settings['taxonomy']; ?>_terms[i].term_id + '">' + window.<?php echo $settings['taxonomy']; ?>_terms[i].name + '</label></div>';
                    }
                }
                $('.multi_select_box.' + itemId).html(items);

                add_style_for_sensitive_multi_select(itemId);

                $('.multi_select_taxonomy.' + itemId ).val((selectedValues.length) ? selectedValues.join(';') : 'all');
            }
            jQuery(document).ready(populate_<?php echo $item_id; ?>_multi_select);
        });

    </script>
</div>