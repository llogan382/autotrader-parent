<?php
class TF_Widget_Meta extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'widget_meta', 'description' => __( "Log in/out, admin, feed and WP links","tfuse") );
		parent::__construct('meta', __('TFuse Meta','tfuse'), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters('widget_title', empty($instance['title']) ? __('Meta','tfuse') : $instance['title'], $instance, $this->id_base);

        if (isset($instance['disable_box']) && $instance['disable_box'])
        {
            $before_box = '';
            $after_box ='';
        }
        else {
            $before_box = '<div class="box">';
            $after_box ='</div>';
        }

		$before_widget = ' <div id="meta-'.$args['widget_id'].'" class="widget-container widget_meta">';
		$after_widget = '</div>';
		$before_title = '<h3 class="widget-title">';
		$after_title = '</h3>';

        echo $before_box.$before_widget;
		$title = tfuse_qtranslate($title);
		if ( $title ) echo $before_title . tfuse_qtranslate($title) . $after_title;
?>
			<ul>
                <?php wp_register(); ?>
                <li><?php wp_loginout(); ?></li>
                <li><a href="<?php bloginfo('rss2_url'); ?>" title="<?php echo esc_attr(__('Syndicate this site using RSS 2.0','tfuse')); ?>"><?php _e('Entries <abbr title="Really Simple Syndication">RSS</abbr>','tfuse'); ?></a></li>
                <li><a href="<?php bloginfo('comments_rss2_url'); ?>" title="<?php echo esc_attr(__('The latest comments to all posts in RSS','tfuse')); ?>"><?php _e('Comments <abbr title="Really Simple Syndication">RSS</abbr>','tfuse'); ?></a></li>
                <li><a href="//wordpress.org/" title="<?php echo esc_attr(__('Powered by WordPress, state-of-the-art semantic personal publishing platform.','tfuse')); ?>">WordPress.org</a></li>
                <?php wp_meta(); ?>
			</ul>
<?php
        echo $after_widget.$after_box;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];
        $instance['disable_box'] = isset($new_instance['disable_box']);
		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'footer' => '' ) );
		$title = $instance['title'];
?>

		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','tfuse'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
        <p><input id="<?php echo $this->get_field_id('disable_box'); ?>" name="<?php echo $this->get_field_name('disable_box'); ?>" type="checkbox" <?php checked(isset($instance['disable_box']) ? $instance['disable_box'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('disable_box'); ?>"><?php _e('Disable box','tfuse'); ?></label></p>
    <?php
	}
}

function TFuse_Unregister_WP_Widget_Meta() {
	unregister_widget('WP_Widget_Meta');
}
add_action('widgets_init','TFuse_Unregister_WP_Widget_Meta');

register_widget('TF_Widget_Meta');