<?php
class TF_Widget_Tag_Cloud extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'description' => __( "Your most used tags in cloud format","tfuse") );
		parent::__construct('tag_cloud', __('TFuse Tag Cloud','tfuse'), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		$current_taxonomy = $this->_get_current_taxonomy($instance);
		if ( !empty($instance['title']) ) {
			$title = $instance['title'];
		}
        else {
			if ( 'post_tag' == $current_taxonomy ) {
				$title = __('Tags','tfuse');
			}
            else {
				$tax = get_taxonomy($current_taxonomy);
				$title = $tax->labels->name;
			}
		}
		$title = apply_filters('widget_title', $title, $instance, $this->id_base);
        if (isset($instance['disable_box']) && $instance['disable_box'])
        {
            $before_box = '';
            $after_box ='';
        }
        else {
            $before_box = '<div class="box">';
            $after_box ='</div>';
        }

		$before_widget = '<div id="tag_cloud-'.$args['widget_id'].'" class="widget-container widget_tag_cloud">';
		$after_widget = '</div>';
		$before_title = '<h3 class="widget-title">';
		$after_title = '</h3>';

        echo $before_box.$before_widget;
		$title = tfuse_qtranslate($title);
		if ( $title )echo $before_title . $title. $after_title;
		echo '<div class="tagcloud">';
		wp_tag_cloud( apply_filters('widget_tag_cloud_args', array('taxonomy' => $current_taxonomy) ) );
		echo "</div>\n";
        echo $after_widget.$after_box;
	}

	function update( $new_instance, $old_instance ) {
		$instance['title'] = $new_instance['title'];
		$instance['taxonomy'] = stripslashes($new_instance['taxonomy']);
        $instance['disable_box'] = isset($new_instance['disable_box']);
		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'template' => '') );
		$current_taxonomy = $this->_get_current_taxonomy($instance); ?>

        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','tfuse') ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php if (isset ( $instance['title'])) {echo esc_attr( $instance['title'] );} ?>" /></p>
        <p><label for="<?php echo $this->get_field_id('taxonomy'); ?>"><?php _e('Taxonomy:','tfuse') ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('taxonomy'); ?>" name="<?php echo $this->get_field_name('taxonomy'); ?>">
            <?php foreach ( get_object_taxonomies('post') as $taxonomy ) :
                        $tax = get_taxonomy($taxonomy);
                        if ( !$tax->show_tagcloud || empty($tax->labels->name) ) continue;
            ?>
            <option value="<?php echo esc_attr($taxonomy) ?>" <?php selected($taxonomy, $current_taxonomy) ?>><?php echo $tax->labels->name; ?></option>
        <?php endforeach; ?>
        </select></p>

        <p><input id="<?php echo $this->get_field_id('disable_box'); ?>" name="<?php echo $this->get_field_name('disable_box'); ?>" type="checkbox" <?php checked(isset($instance['disable_box']) ? $instance['disable_box'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('disable_box'); ?>"><?php _e('Disable box','tfuse'); ?></label></p>
    <?php }

	function _get_current_taxonomy($instance) {
		if ( !empty($instance['taxonomy']) && taxonomy_exists($instance['taxonomy']) )
			return $instance['taxonomy'];

		return 'post_tag';
	}
}

function TFuse_Unregister_WP_Widget_Tag_Cloud() {
	unregister_widget('WP_Widget_Tag_Cloud');
}
add_action('widgets_init','TFuse_Unregister_WP_Widget_Tag_Cloud');

register_widget('TF_Widget_Tag_Cloud');