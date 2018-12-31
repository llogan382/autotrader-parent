<?php
/* Contact Widget */

class TFuse_Widget_Contact extends WP_Widget
{
    function __construct() {
        $widget_ops = array('classname' => 'widget_contact', 'description' => __( 'Add Contact in Sidebar','tfuse') );
        parent::__construct('contact', __('TFuse Contact Widgets','tfuse'), $widget_ops);
    }

    function widget( $args, $instance )
    {
        extract($args);
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
        $before_widget = '<div class="footer_address">';
        $after_widget = '</div>';
        $tfuse_title = (!empty($title)) ? '<h3>' .tfuse_qtranslate($title) .'</h3>' : '';

        echo $tfuse_title;
        echo $before_widget;
        if ( $instance['adress'] != '')
            echo'<div class="address">'.tfuse_qtranslate($instance['adress']).'</div>';

        if ( $instance['hours'] != '')
            echo '<div class="hours">'.tfuse_qtranslate($instance['hours']).'</div>';

        if ( $instance['text_link'] != '') $text_link = tfuse_qtranslate($instance['text_link']);
        else $text_link = __('View Bigger Map','tfuse');

        if ( $instance['link'] != '')
            echo '<a href="'.$instance['link'].'" class="notice">'.tfuse_qtranslate($text_link).'</a>';

        echo $after_widget;

        if ( $instance['shortcode'] != '')
            echo '<div class="footer_map">'.apply_filters('themefuse_shortcodes', tfuse_qtranslate($instance['shortcode'])).'</div>';

    }

    function update( $new_instance, $old_instance )
    {
        $instance = $old_instance;
        $new_instance = wp_parse_args( (array) $new_instance, array( 'title'=>'', 'hours' => '','adress' => '','text_link' => '','link' => '','shortcode' => '') );

        $instance['title']      = $new_instance['title'];
        $instance['text_link']  = $new_instance['text_link'];
        $instance['hours']      = $new_instance['hours'];
        $instance['adress']     = $new_instance['adress'];
        $instance['link']       = $new_instance['link'];
        $instance['shortcode']  = $new_instance['shortcode'];
        return $instance;
    }

    function form( $instance )
    {
        $instance = wp_parse_args( (array) $instance, array( 'title'=>'', 'hours' => '','adress' => '','text_link' => '','link' => '','shortcode' => '') );
        $title = $instance['title']; ?>

    <p>
        <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','tfuse'); ?></label><br/>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
    </p>
    <p>
        <label for="<?php echo $this->get_field_id('adress'); ?>"><?php _e('Address:','tfuse'); ?></label><br/>
        <input class="widefat " id="<?php echo $this->get_field_id('adress'); ?>" name="<?php echo $this->get_field_name('adress'); ?>" type="text" value="<?php echo esc_attr($instance['adress']); ?>" />
    </p>
    <p>
        <label for="<?php echo $this->get_field_id('hours'); ?>"><?php _e('Hours:','tfuse'); ?></label><br/>
        <input class="widefat " id="<?php echo $this->get_field_id('hours'); ?>" name="<?php echo $this->get_field_name('hours'); ?>" type="text" value="<?php echo  esc_attr($instance['hours']); ?>"  />
    </p>
    <p>
        <label for="<?php echo $this->get_field_id('text_link'); ?>"><?php _e('Text Link:','tfuse'); ?></label><br/>
        <input class="widefat " id="<?php echo $this->get_field_id('text_link'); ?>" name="<?php echo $this->get_field_name('text_link'); ?>" type="text" value="<?php echo esc_attr($instance['text_link']); ?>" />
    </p>
    <p>
        <label for="<?php echo $this->get_field_id('link'); ?>"><?php _e('Link:','tfuse'); ?></label><br/>
        <input class="widefat " id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>" type="text" value="<?php echo esc_attr($instance['link']); ?>" />
    </p>
    <p>
        <label for="<?php echo $this->get_field_id('shortcode'); ?>"><?php _e('Map Shortcode (194x194)','tfuse'); ?></label><br/>
        <textarea class="widefat" rows="6" cols="10" id="<?php echo $this->get_field_id('shortcode'); ?>" name="<?php echo $this->get_field_name('shortcode'); ?>"><?php echo esc_attr($instance['shortcode']); ?></textarea>
    </p>
    <?php }
}
register_widget('TFuse_Widget_Contact');