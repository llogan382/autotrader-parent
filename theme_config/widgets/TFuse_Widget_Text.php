<?php
class TF_Widget_Text extends WP_Widget_Text {

    function __construct() {
        $widget_ops = array('classname' => 'widget_text', 'description' => __('Arbitrary text or HTML','tfuse'));
        $control_ops = array('width' => 400, 'height' => 350);
        parent::__construct('text', __('TFuse Text','tfuse'), $widget_ops, $control_ops);
    }

    function widget( $args, $instance ) {
        extract($args);
        $title = apply_filters( 'widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);

        if (isset($instance['disable_box']) && $instance['disable_box'])
        {
            $before_box = '';
            $after_box ='';
        }
        else {
            $before_box = '<div class="box">';
            $after_box ='</div>';
        }

		$is_visual_text_widget = ( ! empty( $instance['visual'] ) || ! empty( $instance['filter'] ) );
		if ( $is_visual_text_widget ) {
			$instance['filter'] = true;
			$instance['visual'] = true;
		}
		else {
			$instance['filter'] = false;
		}

        $text = apply_filters( 'widget_text', $instance['text'], $instance );
        $tf_class = ( @$instance['nopadding'] ) ? '' : 'class="widget-container widget_text "';
        $before_widget = '<div id="text-'.$args['widget_id'].'" '.$tf_class.'>';
        $after_widget = '</div>';
        $before_title = '<h3 class="widget-title">';
        $after_title = '</h3>';

        echo $before_box.$before_widget;
        $title = tfuse_qtranslate($title);
        if ( !empty( $title ) ) { echo $before_title . $title . $after_title; } ?>
        <div class="textwidget"><?php echo $instance['filter'] ? wpautop($text) : $text; ?></div>
    <?php
        echo $after_widget.$after_box;
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = $new_instance['title'];
        $instance['class'] = $new_instance['class'];
        if ( current_user_can('unfiltered_html') )
            $instance['text'] =  $new_instance['text'];
        else
            $instance['text'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['text']) ) ); // wp_filter_post_kses() expects slashed
        $instance['filter'] = isset($new_instance['filter']);
        $instance['nopadding'] = isset($new_instance['nopadding']);
        $instance['disable_box'] = isset($new_instance['disable_box']);
        return $instance;
    }

    function form( $instance ) {
        $instance = wp_parse_args( (array) $instance, array( 'title' => '', 'disable_box' => '', 'text' => '', 'nopadding' => '' ) );

		if( version_compare( get_bloginfo('version'),'4.8', '>=' ) ) :
			wp_enqueue_editor();
			wp_enqueue_script( 'text-widgets' );

			if ( user_can_richedit() ) {
				add_filter( 'the_editor_content', 'format_for_editor', 10, 2 );
				$default_editor = 'tinymce';
			} else {
				$default_editor = 'html';
			}

			/** This filter is documented in wp-includes/class-wp-editor.php */
			$text = apply_filters( 'the_editor_content', $instance['text'], $default_editor );

			// Reset filter addition.
			if ( user_can_richedit() ) {
				remove_filter( 'the_editor_content', 'format_for_editor' );
			}

			// Prevent premature closing of textarea in case format_for_editor() didn't apply or the_editor_content filter did a wrong thing.
			$escaped_text = preg_replace( '#</textarea#i', '&lt;/textarea', $text );

			?>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" class="title sync-input" type="hidden" value="<?php echo esc_attr( $instance['title'] ); ?>">
			<textarea id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>" class="text sync-input" hidden><?php echo $escaped_text; ?></textarea>
			<input id="<?php echo $this->get_field_id( 'filter' ); ?>" name="<?php echo $this->get_field_name( 'filter' ); ?>" class="filter sync-input" type="hidden" value="on">
			<input id="<?php echo $this->get_field_id( 'visual' ); ?>" name="<?php echo $this->get_field_name( 'visual' ); ?>" class="visual sync-input" type="hidden" value="on">

		<?php else :
			$title = $instance['title'];
			$text = format_to_edit($instance['text']); ?>

			<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','tfuse'); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

			<textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea>

			<p><input id="<?php echo $this->get_field_id('filter'); ?>" name="<?php echo $this->get_field_name('filter'); ?>" type="checkbox" <?php checked(isset($instance['filter']) ? $instance['filter'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('filter'); ?>"><?php _e('Automatically add paragraphs','tfuse'); ?></label></p>
			<p><input id="<?php echo $this->get_field_id('nopadding'); ?>" name="<?php echo $this->get_field_name('nopadding'); ?>" type="checkbox" <?php checked(isset($instance['nopadding']) ? $instance['nopadding'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('nopadding'); ?>"><?php _e('No Margin and padding','tfuse'); ?></label></p>
		<?php endif; ?>

        <p><input id="<?php echo $this->get_field_id('disable_box'); ?>" name="<?php echo $this->get_field_name('disable_box'); ?>" type="checkbox" <?php checked(isset($instance['disable_box']) ? $instance['disable_box'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('disable_box'); ?>"><?php _e('Disable box','tfuse'); ?></label></p>
    <?php
    }
}

function TFuse_Unregister_WP_Widget_Text() {
    unregister_widget('WP_Widget_Text');
}
add_action('widgets_init','TFuse_Unregister_WP_Widget_Text');

register_widget('TF_Widget_Text');