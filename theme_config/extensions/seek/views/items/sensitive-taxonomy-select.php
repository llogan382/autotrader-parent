<?php

if (!defined('TFUSE'))
    exit('Direct access forbidden.');

$getValue  = TF_SEEK_HELPER::get_input_value($parameter_name);
?>
<div class="row field_select">
    <?php if(!empty($vars['label'])): ?><label class="label_title <?php if(!empty($vars['label_class'])) echo esc_attr( $vars['label_class'] ); ?>" ><?php echo esc_attr( $vars['label'] ); ?>:</label><?php endif; ?>
    <select class="select_styled<?php echo (!empty($vars['select_class'])) ? ' ' . $vars['select_class']: '' ;?>" name="<?php echo $parameter_name; ?>" id="<?php echo $item_id; ?>">
        <option value="<?php echo $getValue; ?>"></option>
    </select>
    <script type="text/javascript">
        window.<?php echo $settings['taxonomy']; ?>_terms = jQuery.parseJSON( jQuery('#<?php echo $settings['taxonomy']; ?>_terms').val() );
        function populate_<?php echo $item_id; ?>_select_onchange_parent(){
            var items = '';
            var selectedText = '<?php if(!empty($settings['no_children_text'])) echo $settings['no_children_text']; else _e('No Children','tfuse'); ?>';
            var selectedChanged = false;
            var default_option = <?php if(!empty($vars['default_option'])) echo '\'' . $vars['default_option'] . '\''; else echo '\'\''; ?>;

            for(var i in window.<?php echo $settings['taxonomy']; ?>_terms)
            {
                if(window.<?php echo $settings['taxonomy']; ?>_terms[i].parent == jQuery(this).val()){
                    if(!selectedChanged) {
                        selectedText = window.<?php echo $settings['taxonomy']; ?>_terms[i].name;
                        var activeElement = ' class="cuselActive"';
                        if (default_option){
                            activeElement = '';
                        }
                        items += '<span val="' + window.<?php echo $settings['taxonomy']; ?>_terms[i].term_id + '"' + activeElement + '>' + window.<?php echo $settings['taxonomy']; ?>_terms[i].name + '</span>';
                        selectedChanged = true;
                    }else{
                        items += '<span val="' + window.<?php echo $settings['taxonomy']; ?>_terms[i].term_id + '">' + window.<?php echo $settings['taxonomy']; ?>_terms[i].name + '</span>';
                    }
                }
            }

            if(default_option && items){
                selectedText = default_option;
                items = '<span val="all" class="cuselActive">' + default_option + '</span>' + items;
                selectedChanged = true;
            }

            if(selectedChanged) {
                jQuery('#cuselFrame-<?php echo $item_id; ?> .cuselText').html(selectedText);
                jQuery('#cusel-scroll-<?php echo $item_id; ?>').html(items);

                var params = {
                    refreshEl: "#<?php echo $item_id; ?>",
                    changedEl: ".select_styled",
                    visRows: 8,
                    scrollArrows: true
                };
                cuSelRefresh(params);
            }else{
                jQuery('#cuselFrame-<?php echo $item_id; ?> .cuselText').html(selectedText);
                jQuery('#cuselFrame-<?php echo $item_id; ?> .jScrollPaneContainer').hide();
            }
        }
        function populate_<?php echo $item_id; ?>_select(){
            var getValue = '<?php echo $getValue; ?>';
            var items = '';
            var selectedText = '<?php if(!empty($settings['no_children_text'])) echo $settings['no_children_text']; else _e('No Children','tfuse'); ?>';
            var default_option = <?php if(!empty($vars['default_option'])) echo '\'' . $vars['default_option'] . '\''; else echo '\'\''; ?>;

            for(var i in window.<?php echo $settings['taxonomy']; ?>_terms)
            {
                if(window.<?php echo $settings['taxonomy']; ?>_terms[i].parent == jQuery('[name="<?php echo $settings['parent_name']; ?>"]').val()){
                    var activeElement = '';
                    if (getValue == window.<?php echo $settings['taxonomy']; ?>_terms[i].term_id){
                        activeElement = ' class="cuselActive"';
                        selectedText = window.<?php echo $settings['taxonomy']; ?>_terms[i].name;
                    }
                    items += '<span val="' + window.<?php echo $settings['taxonomy']; ?>_terms[i].term_id + '"' + activeElement + '>' + window.<?php echo $settings['taxonomy']; ?>_terms[i].name + '</span>';
                }
            }

            if(default_option && items){
                if(getValue == 'all'){
                    items = '<span val="all" class="cuselActive">' + default_option + '</span>' + items;
                    selectedText = default_option;
                }else{
                    items = '<span val="all">' + default_option + '</span>' + items;
                }
            }

            jQuery('#cuselFrame-<?php echo $item_id; ?> .cuselText').html(selectedText);
            jQuery('#cusel-scroll-<?php echo $item_id; ?>').html(items);

            var params = {
                refreshEl: "#<?php echo $item_id; ?>",
                changedEl: ".select_styled",
                visRows: 8,
                scrollArrows: true
            };
            cuSelRefresh(params);
        }
        jQuery(document).on('change', 'input[name="<?php echo $settings['parent_name']; ?>"]', populate_<?php echo $item_id; ?>_select_onchange_parent);
        jQuery(document).ready(populate_<?php echo $item_id; ?>_select);
    </script>
</div>