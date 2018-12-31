<?php

/* ----------------------------------------------------------------------------------- */
/* Initializes all the theme settings option fields for posts area. */
/* ----------------------------------------------------------------------------------- */

$options = array(
    /* ----------------------------------------------------------------------------------- */
    /* Sidebar */
    /* ----------------------------------------------------------------------------------- */

    /* Single Post */
    array('name' => __('Single Post','tfuse'),
        'id' => TF_THEME_PREFIX . '_side_media',
        'type' => 'metabox',
        'context' => 'side',
        'priority' => 'low' /* high/low */
    ),
    // Page Title
    array('name' => __('Page Title','tfuse'),
        'desc' => __('Select your preferred Page Title.','tfuse'),
        'id' => TF_THEME_PREFIX . '_page_title',
        'value' => 'default_title',
        'options' => array('hide_title' => __('Hide Page Title','tfuse'), 'default_title' => __('Default Title','tfuse'), 'custom_title' => __('Custom Title','tfuse')),
        'type' => 'select'
    ),
    // Custom Title
    array('name' => __('Custom Title','tfuse'),
        'desc' => __('Enter your custom title for this page.','tfuse'),
        'id' => TF_THEME_PREFIX . '_custom_title',
        'value' => '',
        'type' => 'text'
    ),
    /* ----------------------------------------------------------------------------------- */
    /* After Textarea */
    /* ----------------------------------------------------------------------------------- */

    /* Post Media */
    array('name' => __('Media','tfuse'),
        'id' => TF_THEME_PREFIX . '_media',
        'type' => 'metabox',
        'context' => 'normal'
    ),
    // Thumbnail Image
    array('name' => __('Thumbnail','tfuse'),
        'desc' => __('This is the thumbnail for your post. Upload one from your computer, or specify an online address for your image (Ex: http://yoursite.com/image.png).','tfuse'),
        'id' => TF_THEME_PREFIX . '_thumbnail_image',
        'value' => '',
        'type' => 'upload',
        'hidden_children' => array(
            TF_THEME_PREFIX . '_thumbnail_dimensions'
        )
    ),
    // Posts Thumbnail Dimensions
    array('name' => __('Thumbnail Dimension (px)','tfuse'),
        'desc' => __('These are the default width and height values. If you want to resize the thumbnail change the values with your own. If you input only one, the thumbnail will get resized with constrained proportions based on the one you specified.','tfuse'),
        'id' => TF_THEME_PREFIX . '_thumbnail_dimensions',
        'value' => array(350,210),
        'type' => 'textarray',
    ),
    // Image subtitle
    array('name' => __('Image subtitle','tfuse'),
        'desc' => __('This is subtitle for image and apear under image.','tfuse'),
        'id' => TF_THEME_PREFIX . '_image_subtitle',
        'value' => '',
        'type' => 'text',
        'divider' => true
    ),
    // Front Icon
    array('name' => __('Front Icon','tfuse'),
        'desc' => __('This is the front icon for service','tfuse'),
        'id' => TF_THEME_PREFIX . '_image1',
        'value' => '',
        'type' => 'upload',
    ),
    // Front Icon
    array('name' => __('Back Icon','tfuse'),
        'desc' => __('This is the back icon for service','tfuse'),
        'id' => TF_THEME_PREFIX . '_image2',
        'value' => '',
        'type' => 'upload',
    ),

);

?>