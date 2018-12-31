<?php
/**
 * Image-Video slider's configurations
 *
 * @since  AutoTrader 1.0
 */

$options = array(
    'tabs' => array(
        array(
            'name' => __('Slider Settings','tfuse'),
            'id' => 'slider_settings', #do no t change this ID
            'headings' => array(
                array(
                    'name' => __('Slider Settings','tfuse'),
                    'options' => array(
                        array('name' => __('Slider Title','tfuse'),
                            'desc' => __('Change the title of your slider. Only for internal use (Ex: Homepage)','tfuse'),
                            'id' => 'slider_title',
                            'value' => '',
                            'type' => 'text',
                            'divider' => true),
                        array('name' => __('Background Image','tfuse'),
                            'desc' => __('Upload an image for background','tfuse'),
                            'id' => 'slider_image',
                            'value' => '',
                            'type' => 'upload',
                            'divider' => true),
                        array('name' => __('Text position','tfuse'),
                            'desc' => __('Select the text postition in slider','tfuse'),
                            'id' => 'slider_text_position',
                            'options' =>  array('left_right' => __('Left and Right','tfuse'), 'left' => __('Left','tfuse'), 'right' => __('Right','tfuse')),
                            'value' => 'left_right',
                            'type' => 'select',
                            'divider' => true),
                        array('name' => __('Slides Interval','tfuse'),
                            'desc' => __('Enter the slides interval','tfuse'),
                            'id' => 'slider_interval',
                            'value' => '4000',
                            'type' => 'text',
                            'divider' => true
                        ),
                        array('name' => __('Resize images?','tfuse'),
                            'desc' => __('Want to let our script to resize the images for you? Or do you want to have total control and upload images with the exact slider image size?','tfuse'),
                            'id' => 'slider_image_resize',
                            'value' => 'false',
                            'type' => 'checkbox')
                    )
                )
            )
        ),
        array(
            'name' => __('Add/Edit Slides','tfuse'),
            'id' => 'slider_setup', #do not change ID
            'headings' => array(
                array(
                    'name' => __('Add New Slide', 'tfuse'), #do not change
                    'options' => array(
                        array('name' => __('Type','tfuse'),
                            'desc' => __('Select the type of slide','tfuse'),
                            'id' => 'slide_type_slide',
                            'options' =>  array('image' => __('Image','tfuse'), 'video' => __('Video','tfuse')),
                            'value' => 'image',
                            'type' => 'select',
                            'divider' => true),
                        array('name' => __('Title','tfuse'),
                            'desc' => __('The Title is displayed on the image and will be visible by the users','tfuse'),
                            'id' => 'slide_title',
                            'value' => '',
                            'type' => 'text',
                            'required' => TRUE,),
                        array('name' => __('Link title','tfuse'),
                            'desc' => __('The link for title','tfuse'),
                            'id' => 'slide_link_title',
                            'value' => '#',
                            'type' => 'text',
                            'divider' => true),
                        array('name' => __('Subtitle','tfuse'),
                            'desc' => __('The Subtitle is displayed on the image and will be visible by the users','tfuse'),
                            'id' => 'slide_subtitle',
                            'value' => '',
                            'type' => 'text',
                            'divider' => true),
                        array('name' => __('Video (640 x 360)','tfuse'),
                            'desc' => __('Enter the video frame if type of slide is video, else upload an image.','tfuse'),
                            'id' => 'slide_video_frame',
                            'value' => '',
                            'type' => 'text'),
                        // Custom Image
                        array('name' => __('Image (640 x 360)','tfuse'),
                            'desc' => __('You can upload an image from your hard drive or use one that was already uploaded by pressing  "Insert into Post" button from the image uploader plugin.','tfuse'),
                            'id' => 'slide_src',
                            'value' => '',
                            'type' => 'upload',
                            'media' => 'image',
                            'required' => false,
                            'divider' => true),
                        array('name' => __('List','tfuse'),
                            'desc' => __('A list of features which will be displayed on the slider.Press Enter for new item.You can use span and strong tag.','tfuse'),
                            'id' => 'slide_list',
                            'value' => '',
                            'type' => 'textarea',
                        )
                    )
                )
            )
        ),
        array(
            'name' => __('Category Setup','tfuse'),
            'id' => 'slider_type_categories',
            'headings' => array(
                array(
                    'name' => __('Category options','tfuse'),
                    'options' => array(
                        array(
                            'name' => __('Categories','tfuse'),
                            'desc' => __('Select specific categories.','tfuse'),
                            'id' => 'posts_select_type',
                            'value' => 'portfolio',
                            'options' => array('portfolio' => __('From Seek Posts','tfuse')),
                            'type' => 'select'
                        ),
                        array(
                            'name' => __('Select specific Seek Posts','tfuse'),
                            'desc' => __('Enter the specific seek posts type','tfuse'),
                            'id' => 'posts_select_portf',
                            'type' => 'multi',
                            'subtype' => TF_SEEK_HELPER::get_post_type().'_type'
                        ),
                        array(
                            'name' => __('Number of images in the slider','tfuse'),
                            'desc' => __('How many images do you want in the slider?','tfuse'),
                            'id' => 'sliders_posts_number',
                            'value' => 6,
                            'type' => 'text'
                        )
                    )
                )
            )
        ),
        array(
            'name' => __('Posts Setup','tfuse'),
            'id' => 'slider_type_posts',
            'headings' => array(
                array(
                    'name' => __('Posts options','tfuse'),
                    'options' => array(
                        array(
                            'name' => __('Posts','tfuse'),
                            'desc' => __('Select posts.','tfuse'),
                            'id' => 'posts_select_type',
                            'value' => 'portfolio',
                            'options' => array('portfolio' => __('From Seek Posts','tfuse')),
                            'type' => 'select'
                        ),
                        array(
                            'name' => __('Select population type','tfuse'),
                            'desc' => __('Select population type.','tfuse'),
                            'id' => 'posts_select_population_type',
                            'value' => 'specific',
                            'options' => array('specific' => __('Specific Posts','tfuse'), 'latest' => __('Latest Posts','tfuse')),
                            'type' => 'select'
                        ),
                        array(
                            'name' => __('Enter the number of posts','tfuse'),
                            'desc' => __('Enter the number of posts.','tfuse'),
                            'id' => 'sliders_posts_number',
                            'value' => 5,
                            'type' => 'text'
                        ),
                        array(
                            'name' => __('Select specific Seek Posts','tfuse'),
                            'desc' => __('Pick one or more <a target="_new" href="' . get_admin_url() . 'edit.php">posts</a> by starting to type the Post name. The slider will be populated with images from the posts you selected.','tfuse'),
                            'id' => 'posts_select_portf',
                            'type' => 'multi',
                            'subtype' => TF_SEEK_HELPER::get_post_type()
                        )
                    )
                )
            )
        ),

    )
);

$options['extra_options'] = array();
?>