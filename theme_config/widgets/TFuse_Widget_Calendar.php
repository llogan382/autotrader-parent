<?php
class TF_Widget_Calendar extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'widget_calendar', 'description' => __( 'A calendar of your site&#8217;s posts','tfuse') );
		parent::__construct('calendar', __('TFuse Calendar','tfuse'), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);

        if (isset($instance['disable_box']) && $instance['disable_box'])
        {
            $before_box = '';
            $after_box ='';
        }
        else {
            $before_box = '<div class="box">';
            $after_box ='</div>';
        }
		$before_widget = ' <div id="calendar-'.$args['widget_id'].'" class="widget-container widget_calendar"> ';
		$after_widget = '</div>';
		$before_title = '<h3 class="widget-title">';
		$after_title = '</h3>';

        echo $before_box.$before_widget;
		if ( $title ) echo $before_title . tfuse_qtranslate($title) . $after_title;
		echo '<div id="calendar_wrap">';
		get_calendar();
		echo '</div>';
        echo $after_widget.$after_box;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
        $instance['disable_box'] = isset($new_instance['disable_box']);
		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '') );
		$title = strip_tags($instance['title']);
?>

		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','tfuse'); ?></label>
		    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>

        <p><input id="<?php echo $this->get_field_id('disable_box'); ?>" name="<?php echo $this->get_field_name('disable_box'); ?>" type="checkbox" <?php checked(isset($instance['disable_box']) ? $instance['disable_box'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('disable_box'); ?>"><?php _e('Disable box','tfuse'); ?></label></p>
<?php
	}
}

function TFuse_Unregister_WP_Widget_Calendar() {
	unregister_widget('WP_Widget_Calendar');
}
add_action('widgets_init','TFuse_Unregister_WP_Widget_Calendar');

register_widget('TF_Widget_Calendar');