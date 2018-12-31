<?php
/* Custom Menu Widget */

class TF_Nav_Menu_Widget extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'description' => __('Add a custom menus as a widget.','tfuse') );
		parent::__construct( 'nav_menu', __('TFuse Custom Menu','tfuse'), $widget_ops );
	}

	function widget($args, $instance) {
		// Get menu
		$nav_menu = wp_get_nav_menu_object( $instance['nav_menu'] );
		if ( !$nav_menu ) return;
		$instance['title'] = apply_filters('widget_title', $instance['title'], $instance, $this->id_base);
        if (isset($instance['disable_box']) && $instance['disable_box'])
        {
            $before_box = '';
            $after_box ='';
        }
        else {
            $before_box = '<div class="box">';
            $after_box ='</div>';
        }
		$args['before_widget'] = '<div class="widget-container widget_nav_menu">';
		$args['after_widget'] = '</div>';
		$args['before_title'] = '<h3 class="widget-title">';
		$args['after_title'] = '</h3>';

		echo $before_box.$args['before_widget'];
		$instance['title'] = tfuse_qtranslate($instance['title']);
		if ( !empty($instance['title']) ) echo $args['before_title'] . $instance['title'] . $args['after_title'];
        wp_nav_menu( array( 'fallback_cb' => '', 'menu' => $nav_menu, 'link_before'=> '<span>', 'link_after' => '</span>') );
        echo $args['after_widget'].$after_box;
	}

	function update( $new_instance, $old_instance ) {
		$instance['title'] =  $new_instance['title'] ;
		$instance['nav_menu'] = (int) $new_instance['nav_menu'];
        $instance['disable_box'] = isset($new_instance['disable_box']);
		return $instance;
	}

	function form( $instance ) {
		$title = isset( $instance['title'] ) ? $instance['title'] : '';
		$nav_menu = isset( $instance['nav_menu'] ) ? $instance['nav_menu'] : '';
		// Get menus
		$menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );
		// If no menus exists, direct the user to go and create some.
		if ( !$menus ) {
			echo '<p>'. sprintf( __('No menus have been created yet. <a href="%s">Create some</a>.','tfuse'), admin_url('nav-menus.php') ) .'</p>';
			return;
		} ?>

		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','tfuse') ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" />
		</p>
        <p><input id="<?php echo $this->get_field_id('disable_box'); ?>" name="<?php echo $this->get_field_name('disable_box'); ?>" type="checkbox" <?php checked(isset($instance['disable_box']) ? $instance['disable_box'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('disable_box'); ?>"><?php _e('Disable box','tfuse'); ?></label></p>
		<p><label for="<?php echo $this->get_field_id('nav_menu'); ?>"><?php _e('Select Menu:','tfuse'); ?></label>
			<select id="<?php echo $this->get_field_id('nav_menu'); ?>" name="<?php echo $this->get_field_name('nav_menu'); ?>">
		        <?php foreach ( $menus as $menu ) {
                    $selected = $nav_menu == $menu->term_id ? ' selected="selected"' : '';
                    echo '<option'. $selected .' value="'. $menu->term_id .'">'. $menu->name .'</option>';
                } ?>
			</select>
		</p>
    <?php }
}

function TFuse_Unregister_WP_Nav_Menu_Widget() {
	unregister_widget('WP_Nav_Menu_Widget');
}
add_action('widgets_init','TFuse_Unregister_WP_Nav_Menu_Widget');

register_widget('TF_Nav_Menu_Widget');