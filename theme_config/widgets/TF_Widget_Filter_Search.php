<?php

// =============================== Search widget ======================================

class TF_Widget_Filter_Results extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'widget_adv_filter', 'description' => __( "TFuse - Filter Results","tfuse") );
        parent::__construct('filter_results', __('TFuse Filter Results','tfuse'), $widget_ops);
    }

    function widget($args, $instance) {
		global $TFUSE;
	    if(!($TFUSE->request->isset_GET('tfseekfid'))) {
		    return '';
	    }

        extract($args);
        $title = apply_filters('widget_title', empty( $instance['title'] ) ? __( 'ADJUST SEARCH RESULTS','tfuse' ) : $instance['title'], $instance, $this->id_base);
        $boxed = (isset($instance['disable_box']) && $instance['disable_box']) ? false : true;
        if($boxed) echo '<div class="box">';
        ?>
        <!-- filter -->
        <div class="widget-container widget_adv_filter">
            <h3 class="widget-title"><?php echo tfuse_qtranslate($title); ?></h3>
            <?php TF_SEEK_HELPER::print_form('filter_search'); ?>
        </div><!--/ filter -->

    <?php
        if($boxed) echo '</div>';
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $new_instance = wp_parse_args((array) $new_instance, array( 'title' => ''));
        $instance['disable_box'] = isset($new_instance['disable_box']);
        $instance['title'] = $new_instance['title'];
        return $instance;
    }

    function form( $instance ) {
        $instance = wp_parse_args( (array) $instance, array( 'title' => '') );
        $title = $instance['title'];
        ?>
    <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','tfuse'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
    <p>
        <input id="<?php echo $this->get_field_id('disable_box'); ?>" name="<?php echo $this->get_field_name('disable_box'); ?>" type="checkbox" <?php checked(isset($instance['disable_box']) ? $instance['disable_box'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('disable_box'); ?>"><?php _e('Disable box','tfuse'); ?></label>
    </p>
    <p><?php _e('Note: "This widget appear only on seach page for vehicles"','tfuse'); ?></p>
    
    <?php
    }
}

function TFuse_Unregister_WP_Widget_Filter_Results() {
    unregister_widget('WP_Widget_Search');
}

register_widget('TF_Widget_Filter_Results');