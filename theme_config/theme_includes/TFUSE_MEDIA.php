<?php
if (!function_exists('tfuse_media')) :
/**
 * Display post media.
 * 
 * To override tfuse_media() in a child theme, add your own tfuse_media() 
 * to your child theme's file.
 */
function tfuse_media($vehicleID = null,$return=false)
{
    if ($vehicleID) :
        $output = '';
        $vehicleID = (int)$vehicleID;

        $image = new TF_GET_IMAGE();
        $image->removeSizeParams = true;
        $output .=  $image->width(270)->height(2000)->src(tfuse_get_vehicle_thumbnail($vehicleID))->get_img();

        if( $return ) return $output;
        else echo $output;
        return false;
    endif;

    global $post, $is_tf_front_page, $is_tf_blog_page;
    $output = $img_out = $video_out = '';
    $tfuse_media = tfuse_get_media_information();

    if ( is_singular() )
    {
        $tf_media_add_space = '';
        if ($tfuse_media['video_enabled'] && $tfuse_media['video_link'] )
        {
            $video_margins = '';

            if ( $tfuse_media['img_enabled'] && $tfuse_media['video_enabled']) $tf_media_add_space = ' tf_media_add_space';

            if ( (!$tfuse_media ['img_enabled'])|| empty($tfuse_media['image']))
            {
                if ($tfuse_media['video_position'] == 'alignleft')  $video_margins = ' margin-right:10px;';
                if ($tfuse_media['video_position'] == 'alignright') $video_margins = ' margin-left:10px;';
            }
            $video_out .= '<div class="video_embed '. $tfuse_media['video_position'].'" style="width:'.$tfuse_media['video_dimensions'][0].'px;' . $video_margins . '">';
            $video = new TF_GET_EMBED();
            $video_out .= $video->width($tfuse_media['video_dimensions'][0])->height($tfuse_media['video_dimensions'][1])->source('video_link')->get();        //$output .= tfuse_get_embed($tfuse_media['media_width'], $tfuse_media['media_height'], PREFIX . "_post_video");
            $video_out .= '</div><!--/.video_embed  -->';

        }

        if ($tfuse_media['img_enabled'] && (!empty($tfuse_media['image'])) && ($tfuse_media['enable_single_lightbox']))
        {
            $tfuse_image = '<div class="post-image ' . $tfuse_media['img_position'] . '">';
            $image = new TF_GET_IMAGE();
            $image->removeSizeParams = true;
            $tfuse_image .=  $image->before('<a href="'.$tfuse_media['image'].'" rel="prettyPhoto" data-rel="prettyPhoto[gallery' . $post->ID . ']">')->after('</a>')->width($tfuse_media['img_dimensions'][0])->height($tfuse_media['img_dimensions'][1])->
                properties(array('class' =>'', 'rel'=>'prettyPhoto', 'data-rel' => 'prettyPhoto[gallery' . $post->ID . ']'))->src($tfuse_media['image'])->get_img();

            $attachments = get_children( array('post_parent' => $post->ID, 'numberposts' => -1, 'post_type' => 'attachment', 'post_mime_type' => 'image') );
            if( !empty($attachments) )
            {
                foreach ($attachments as $att_id => $attachment)
                {
                    $tfuse_src = wp_get_attachment_image_src($att_id, 'full', true);
                    $tfuse_image_link_attach = $tfuse_src[0];
                    $tfuse_image .= '<a style="display:none" href="'. $tfuse_image_link_attach . '" data-rel="prettyPhoto[gallery'.$post->ID.']" rel="prettyPhoto">'.$tfuse_media['image'].'</a>';
                }
            }
            if ( $tfuse_media['video_enabled']) $tfuse_image .= '<a style="display:none" href="'. $tfuse_media['video_link'].'" data-rel="prettyPhoto[gallery'.$post->ID.']" rel="prettyPhoto">'.$tfuse_media['image'].'</a>';
            $tfuse_image .= '</div>';
            $img_out = $tfuse_image;
        }
        elseif ($tfuse_media['img_enabled'] && (!empty($tfuse_media['image'])) && (!$tfuse_media['enable_single_lightbox']))
        {
            $image = new TF_GET_IMAGE();
            $tfuse_image = $image->width($tfuse_media['img_dimensions'][0])->height($tfuse_media['img_dimensions'][1])->
                properties(array('class' => ''.$tf_media_add_space))->src($tfuse_media['image'])->get_img();
            $img_out = $tfuse_image;
        }
    }
    elseif (is_archive() || is_search() || $is_tf_front_page || $is_tf_blog_page)
    {
        if ($tfuse_media['img_enabled'] && (!empty($tfuse_media['image'])) && ($tfuse_media['enable_listing_lightbox']))
        {
            $tfuse_image = '<div class="post-image ' . $tfuse_media['img_position'] . '">';
            $image = new TF_GET_IMAGE();
            $tfuse_image .=  $image->before('<a href="'.$tfuse_media['image'].'" rel="prettyPhoto" data-rel="prettyPhoto[gallery' . $post->ID . ']">')->after('</a>')->width($tfuse_media['img_dimensions'][0])->height($tfuse_media['img_dimensions'][1])->
                properties(array('class' => '', 'rel'=>'prettyPhoto', 'data-rel' => 'prettyPhoto[gallery' . $post->ID . ']'))->src($tfuse_media['image'])->get_img();

            $attachments = get_children( array('post_parent' => $post->ID, 'numberposts' => -1, 'post_type' => 'attachment', 'post_mime_type' => 'image') );
            if( !empty($attachments) )
            {
                foreach ($attachments as $att_id => $attachment)
                {
                    $tfuse_src = wp_get_attachment_image_src($att_id, 'full', true);
                    $tfuse_image_link_attach = $tfuse_src[0];
                    $tfuse_image .= '<a href="'. $tfuse_image_link_attach . '" data-rel="prettyPhoto[gallery'.$post->ID.']" rel="prettyPhoto">'.$tfuse_media['image'].'</a>';
                }
            }
            if ( $tfuse_media['video_enabled']) $tfuse_image .= '<a href="'. $tfuse_media['video_link'].'" data-rel="prettyPhoto[gallery'.$post->ID.']" rel="prettyPhoto">'.$tfuse_media['image'].'</a>';
            $tfuse_image .= '</div>';
            $img_out = $tfuse_image;
        }
        elseif ($tfuse_media['img_enabled'] && (!empty($tfuse_media['image'])) && (!$tfuse_media['enable_listing_lightbox']))
        {
            $tfuse_image = '<div class="post-image ' . $tfuse_media['img_position'] . '"><a href="' . get_permalink() . '">';

            $image = new TF_GET_IMAGE();
            $tfuse_image .= $image->width($tfuse_media['img_dimensions'][0])->height($tfuse_media['img_dimensions'][1])->
                properties(array('class' => ''))->src($tfuse_media['image'])->get_img();
            $tfuse_image .= '</a></div>';
            $img_out = $tfuse_image;
        }
    }

        $output .= $video_out . $img_out;

        if( $return )
            return $output;
        else
            echo $output;
    }
endif; // tfuse_media


if (!function_exists('tfuse_get_media_information')) :
    /**
     * Display post media.
     *
     * To override tfuse_get_media_information() in a child theme, add your own tfuse_media()
     * to your child theme's file.
     */
    function tfuse_get_media_information()
    {
        global $post,$is_tf_front_page,$is_tf_blog_page;

        $tfuse_media = array();
        $tfuse_media['img_enabled'] = $tfuse_media['video_enabled'] = false;
        $tfuse_media['enable_listing_lightbox'] = tfuse_options('enable_listing_lightbox',true);
        $tfuse_media['enable_single_lightbox']  = tfuse_options('enable_single_lightbox',true);

        $tfuse_media['video_link']              = tfuse_page_options('video_link', null, $post->ID);
        $tfuse_media['enable_video']           = tfuse_page_options('enable_video',tfuse_options('enable_video',true), null, $post->ID);

        if (is_single())
        {
            if ( !empty($tfuse_media['video_link'] ) && $tfuse_media['enable_video'] )
            {
                $tfuse_media['video_enabled'] = true;
                $tfuse_media['video_dimensions']    = tfuse_page_options('video_dimensions',tfuse_options('video_dimensions'), null, $post->ID);
                $tfuse_media['video_position']      = tfuse_page_options('video_position',tfuse_options('video_position'), null, $post->ID);
            }

            $tfuse_media['image']                   = tfuse_page_options('single_image', null, $post->ID);
            $tfuse_media['enable_image']            = tfuse_page_options('enable_image',tfuse_options('enable_image',true), $post->ID);

            if ( $tfuse_media['enable_image'] && $tfuse_media['image'])
            {
                $tfuse_media['img_enabled'] = true;
                $tfuse_media['img_dimensions']      = tfuse_page_options('single_img_dimensions',tfuse_options('single_image_dimensions'), $post->ID);
                $tfuse_media['img_position']        = tfuse_page_options('single_img_position',tfuse_options('single_image_position'), $post->ID);
            }
        }
        elseif (is_archive() || is_search() || $is_tf_front_page || $is_tf_blog_page)
        {
            if ( !empty($tfuse_media['video_link'] ) && $tfuse_media['enable_video'] )
            {
                $tfuse_media['video_enabled'] = true;
            }
            $tfuse_media['img_enabled'] = true;
            $tfuse_media['image']               = tfuse_page_options('thumbnail_image',tfuse_page_options('single_image', null, $post->ID));
            $tfuse_media['img_dimensions']      = tfuse_page_options('thumbnail_dimensions',tfuse_options('thumbnail_dimensions'), $post->ID);
            $tfuse_media['img_position']        = tfuse_page_options('thumbnail_position',tfuse_options('thumbnail_position'), $post->ID);
        }

        return $tfuse_media;
    }
endif; // tfuse_get_media_information