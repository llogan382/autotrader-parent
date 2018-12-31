<?php
// =============================== Recent Posts Widget ======================================

class TFuse_Recent_Posts extends WP_Widget {

    function __construct() {
        $widget_ops = array('description' => '' );
        parent::__construct(false, __('TFuse - Recent Posts', 'tfuse'),$widget_ops);
    }

    function widget($args, $instance) {
        extract( $args );
        $title = apply_filters('widget_title', $instance['title'], $instance, $this->id_base);
        $number = esc_attr($instance['number']);
        if ($number>0) {} else $number = 6;
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

        <div class="widget-container widget_popular recent_posts">
            <?php if(!empty($title)){ ?><h3 class="widget-title"><?php echo tfuse_qtranslate($title); ?></h3><?php } ?>
            <ul>
                <?php
                $pop_posts =  tfuse_shortcode_posts(array(
                                    'sort' => 'recent',
                                    'items' => $number,
                                    'image_post' => false,
                                    'date_post' => false
                                ));

                foreach($pop_posts as $post_val): ?>
                    <li><a href="<?php echo $post_val['post_link']; ?>"><?php echo $post_val['post_title']; ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>

    <?php echo $after_box;
    }

   function update($new_instance, $old_instance) {
       $instance['disable_box'] = isset($new_instance['disable_box']);
       return $new_instance;
   }

    function form($instance) {
        $instance = wp_parse_args( (array) $instance, array(  'title' => '', 'number' => '') );
        $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
        $number = esc_attr($instance['number']);
        if(isset($instance['disable_box']) && $instance['disable_box']=='on' ) $instance['disable_box'] = 1; ?>

        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','tfuse'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>
        <p>
            <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts','tfuse'); ?>:</label>
            <input type="text" name="<?php echo $this->get_field_name('number'); ?>" value="<?php echo $number; ?>" class="widefat" id="<?php echo $this->get_field_id('number'); ?>" />
        </p>
        <p><input id="<?php echo $this->get_field_id('disable_box'); ?>" name="<?php echo $this->get_field_name('disable_box'); ?>" type="checkbox" <?php checked(isset($instance['disable_box']) ? $instance['disable_box'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('disable_box'); ?>"><?php _e('Disable box','tfuse'); ?></label></p>

    <?php
    }
}

function TFuse_Unregister_WP_Widget_Recent_Posts() {
    unregister_widget('WP_Widget_Recent_Posts');
}
add_action('widgets_init','TFuse_Unregister_WP_Widget_Recent_Posts');

register_widget('TFuse_Recent_Posts');