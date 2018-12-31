<?php
// =============================== Flickr widget ======================================

class TFuse_flickr extends WP_Widget {

	function __construct() {
            $widget_ops = array('description' => '' );
            parent::__construct(false, __('TFuse - Flickr', 'tfuse'),$widget_ops);
	}

	function widget($args, $instance) {
        extract( $args );
        $flickr_id = esc_attr($instance['flickr_id']);
        $number = esc_attr($instance['number']);
        $title = apply_filters( 'widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
        $before_widget = '<div class="widget-container flickr">';
        $after_widget = '</div>';
        $before_title = '<h3 class="widget-title">';
        $after_title = '</h3>';
        if (isset($instance['disable_box']) && $instance['disable_box'])
        {
            $before_box = '';
            $after_box ='';
        }
        else {
            $before_box = '<div class="box">';
            $after_box ='</div>';
        }

        echo $before_box.$before_widget;
        $title = tfuse_qtranslate($title);
        if ( !empty( $title ) ) { echo $before_title . $title . $after_title; } ?>
        <script type="text/javascript" src="//www.flickr.com/badge_code_v2.gne?count=<?php echo $number; ?>&amp;display=random&amp;size=s&amp;layout=x&amp;source=user&amp;user=<?php echo $flickr_id; ?>"></script>

	   <?php echo $after_widget.$after_box;
    }

    function update($new_instance, $old_instance) {
        $instance['title'] = $new_instance['title'];
        $instance['disable_box'] = isset($new_instance['disable_box']);
        return $new_instance;
    }

    function form($instance) {
		$instance = wp_parse_args( (array) $instance, array(  'title' => '', 'flickr_id' => '', 'number' => '') );
		$id = esc_attr($instance['flickr_id']);
        $number = esc_attr($instance['number']);
        $title = $instance['title'];
        if(isset($instance['disable_box']) && $instance['disable_box']=='on') $instance['disable_box'] = 1; ?>

        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','tfuse'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('flickr_id'); ?>"><?php _e('Flickr ID:','tfuse'); ?> (<a href="//www.idgettr.com" target="_blank">idGettr</a>):</label>
            <input type="text" name="<?php echo $this->get_field_name('flickr_id'); ?>" value="<?php echo $id; ?>" class="widefat" id="<?php echo $this->get_field_id('flickr_id'); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of photos','tfuse'); ?>:</label>
            <input type="text" name="<?php echo $this->get_field_name('number'); ?>" value="<?php echo $number; ?>" class="widefat" id="<?php echo $this->get_field_id('number'); ?>" />
        </p>

        <p><input id="<?php echo $this->get_field_id('disable_box'); ?>" name="<?php echo $this->get_field_name('disable_box'); ?>" type="checkbox" <?php checked(isset($instance['disable_box']) ? $instance['disable_box'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('disable_box'); ?>"><?php _e('Disable box','tfuse'); ?></label></p>
		<?php
	}
}
register_widget('TFuse_flickr');