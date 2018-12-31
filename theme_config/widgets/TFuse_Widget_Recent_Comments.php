<?php
/* Recent Comments */

class TF_Widget_Recent_Comments extends WP_Widget
{
    private function get_comment_preview( $text ){
        if (mb_strlen($text, 'UTF-8')<=60) return $text;
        return substr($text,0,60) . '...';
    }

	function __construct() {
		$widget_ops = array('classname' => 'widget_recent_comments', 'description' => __( 'The most recent comments','tfuse' ) );
		parent::__construct('recent-comments', __('TFuse Recent Comments','tfuse'), $widget_ops);
		$this->alt_option_name = 'widget_recent_comments';
		add_action( 'comment_post', array(&$this, 'flush_widget_cache') );
		add_action( 'transition_comment_status', array(&$this, 'flush_widget_cache') );
	}

	function recent_comments_style() { ?>
	    <style type="text/css">.recentcomments a{display:inline !important;padding:0 !important;margin:0 !important;}</style>
    <?php }

	function flush_widget_cache() {
		wp_cache_delete('widget_recent_comments', 'widget');
	}

	function widget( $args, $instance ) {
		global $comments, $comment;
		$cache = wp_cache_get('widget_recent_comments', 'widget');
		if ( ! is_array( $cache ) ) $cache = array();

		if ( isset( $cache[$args['widget_id']] ) ) {
			echo $cache[$args['widget_id']];
			return;
		}

		extract($args, EXTR_SKIP);
		$output = '';
		$title = apply_filters('widget_title', $instance['title']);
		$before_widget = '<div class="widget-container widget_recent_comments">';
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

		if ( ! $number = (int) $instance['number'] ) $number = 5;
		else if ( $number < 1 ) $number = 1;

		$comments = get_comments( array( 'number' => $number, 'status' => 'approve' ) );
		$output .= $before_box.$before_widget;
		$title = tfuse_qtranslate($title);
		if ( !empty($title) ) $output .= $before_title . $title . $after_title;

		$output .= '<ul>';
		if ( $comments )
        {
			foreach ( (array) $comments as $comment)
            {
                $commentContent = $this->get_comment_preview($comment->comment_content);
                if(!$comment->comment_author_url) $comment->comment_author_url = '#';
				$output .=  '<li class="recentcomments">';
                $output .= '<a href="' . esc_url( get_comment_link($comment->comment_ID) ) . '" class="comment-link">' . $commentContent . '</a>';
                $output .= '<div class="comment-meta"><span class="author">'. __('by', 'tfuse');
                $output .= ' <a href="' . $comment->comment_author_url . '" rel="external nofollow" class="url">' . $comment->comment_author . '</a>';
                $output .= '</span> <span class="comment-date">' . get_comment_date('m. d. y ') . '</span></div>';
                $output .= '</li>';
			}
		}
		$output .= '</ul>';
        $output .= $after_widget.$after_box;

		echo $output;
		$cache[$args['widget_id']] = $output;
		wp_cache_set('widget_recent_comments', $cache, 'widget');
	}

	function update( $new_instance, $old_instance )
    {
		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];
		$instance['number'] = (int) $new_instance['number'];
        $instance['disable_box'] = isset($new_instance['disable_box']);
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['widget_recent_comments']) ) delete_option('widget_recent_comments');

		return $instance;
	}

	function form( $instance )
    {
		$instance = wp_parse_args( (array) $instance, array(  'title' => '',) );
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$number = isset($instance['number']) ? absint($instance['number']) : 5; ?>

		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','tfuse'); ?></label>
		    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
		<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of comments to show:','tfuse'); ?></label>
		    <input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" />
        </p>
        <p><input id="<?php echo $this->get_field_id('disable_box'); ?>" name="<?php echo $this->get_field_name('disable_box'); ?>" type="checkbox" <?php checked(isset($instance['disable_box']) ? $instance['disable_box'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('disable_box'); ?>"><?php _e('Disable box','tfuse'); ?></label></p>
    <?php
	}
}

function TFuse_Unregister_WP_Widget_Recent_Comments()
{
    unregister_widget('WP_Widget_Recent_Comments');
}
add_action('widgets_init','TFuse_Unregister_WP_Widget_Recent_Comments');

register_widget('TF_Widget_Recent_Comments');