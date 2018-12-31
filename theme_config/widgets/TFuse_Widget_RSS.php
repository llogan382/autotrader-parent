<?php
/* Subscribe to RSS */

class TF_Widget_RSS extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'widget_rss', 'description' => __( "Subsribe to RSS","tfuse") );
        parent::__construct('rss', __('TFuse RSS','tfuse'), $widget_ops);
    }

    function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', $instance['title'], $instance, $this->id_base);
        if(empty($title)) $title = __('Subcribe','tfuse');
        if (isset($instance['disable_box']) && $instance['disable_box'])
        {
            $before_box = '';
            $after_box ='';
        }
        else {
            $before_box = '<div class="box">';
            $after_box ='</div>';
        }
        echo $before_box; ?>

        <div class="widget-container subscribe_btn">
            <a href="<?php $feedb_url = tfuse_options('feedburner_url','#'); print $feedb_url; ?>" class="btn btn_rss"><span><?php echo $title; ?></span></a>
        </div>

        <?php echo $after_box;
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $new_instance = wp_parse_args((array) $new_instance, array( 'title' => ''));
        $instance['title'] = $new_instance['title'];
        $instance['disable_box'] = isset($new_instance['disable_box']);
        return $instance;
    }

    function form( $instance ) {
        $instance = wp_parse_args( (array) $instance, array( 'title' => '') );
        $title = $instance['title']; ?>

        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','tfuse'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
        <p><input id="<?php echo $this->get_field_id('disable_box'); ?>" name="<?php echo $this->get_field_name('disable_box'); ?>" type="checkbox" <?php checked(isset($instance['disable_box']) ? $instance['disable_box'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('disable_box'); ?>"><?php _e('Disable box','tfuse'); ?></label></p>
    <?php }
}

function TFuse_Unregister_WP_Widget_RSS() {
    unregister_widget('WP_Widget_RSS');
}
add_action('widgets_init','TFuse_Unregister_WP_Widget_RSS');

register_widget('TF_Widget_RSS');