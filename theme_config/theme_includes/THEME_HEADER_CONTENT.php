<?php

if ( ! function_exists( 'tfuse_header_content' ) ):
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * Note that this function is hooked into the after_setup_theme hook, which runs
     * before the init hook. The init hook is too late for some features, such as indicating
     * support post thumbnails.
     *
     * To override tfuse_slider_type() in a child theme, add your own tfuse_slider_type to your child theme's
     * functions.php file.
     */

    function tfuse_header_content($location = false, $results_number = 0)
    {
        global $TFUSE,$post,$header_element,$is_tf_front_page,$is_tf_blog_page,$header_image,$header_title;
        $posts = $header_element = $header_title = $header_image = $slider = null;
        if (!$location) return;
        switch ($location)
        {
            case 'header' :
                if ($is_tf_blog_page)
                {
                    $header_element = tfuse_options('header_element_blog', 'none');
                    if ( 'slider' == $header_element )
                        $slider = tfuse_options('select_slider_blog');
                    elseif ( 'image' == $header_element ){
                        $header_image = tfuse_options('header_image_blog');
                        $header_title = tfuse_options('header_title_blog');
                    }
                }
                elseif ($is_tf_front_page)
                {
                    if(tfuse_options('use_page_options') && tfuse_options('homepage_category')=='page'){
                        $ID = get_the_ID();
                        $header_element = tfuse_page_options('header_element','',$ID);
                        if ( 'slider' == $header_element )
                            $slider = tfuse_page_options('select_slider','',$ID);
                        elseif ( 'image' == $header_element ){
                            $header_image = tfuse_page_options('header_image','',$ID);
                            $header_title = tfuse_page_options('header_title','',$ID);
                        }
                    }
                    else{
                        $header_element = tfuse_options('header_element','none');
                        if ( 'slider' == $header_element )
                            $slider = tfuse_options('select_slider');
                        elseif ( 'image' == $header_element ){
                            $header_image = tfuse_options('header_image');
                            $header_title = tfuse_options('header_title');
                        }
                    }
                }
                elseif(is_search()){
                    if($TFUSE->request->isset_GET(TF_SEEK_HELPER::get_search_parameter('form_id')))
                    {
                        $header_element = tfuse_options('header_element_search_seek', 'none');
                        if ( 'slider' == $header_element )
                            $slider = tfuse_options('select_slider_search_seek');
                        elseif ( 'image' == $header_element ){
                            $header_image = tfuse_options('header_image_search_seek');
                            $header_title = tfuse_options('header_title_search_seek');
                            $header_title = str_replace('%%results_number%%', $results_number, $header_title);
                        }
                    }
                    else{
                        $header_element = tfuse_options('header_element_search', 'none');
                        if ( 'slider' == $header_element )
                            $slider = tfuse_options('select_slider_search');
                        elseif ( 'image' == $header_element ){
                            $header_image = tfuse_options('header_image_search');
                            $header_title = tfuse_options('header_title_search');
                            $header_title = str_replace('%%results_number%%', $results_number, $header_title);
                        }
                    }
                }
                elseif(is_404()){
                    $header_element = tfuse_options('header_element_404', 'none');
                    if ( 'slider' == $header_element )
                        $slider = tfuse_options('select_slider_404');
                    elseif ( 'image' == $header_element ){
                        $header_image = tfuse_options('header_image_404');
                        $header_title = tfuse_options('header_title_404');
                    }
                }
                elseif(is_author()){
                    $header_element = tfuse_options('header_element_author', 'none');
                    if ( 'slider' == $header_element )
                        $slider = tfuse_options('select_slider_author');
                    elseif ( 'image' == $header_element ){
                        $header_image = tfuse_options('header_image_author');
                        $header_title = tfuse_options('header_title_author');
                    }
                }
                elseif(is_tag()){
                    $header_element = tfuse_options('header_element_tag', 'none');
                    if ( 'slider' == $header_element )
                        $slider = tfuse_options('select_slider_tag');
                    elseif ( 'image' == $header_element ){
                        $header_image = tfuse_options('header_image_tag');
                        $header_title = tfuse_options('header_title_tag');
                    }
                }
                elseif ( is_singular() )
                {
                    $ID = $post->ID;
                    $header_element = tfuse_page_options('header_element','none');
                    if ( 'slider' == $header_element )
                        $slider = tfuse_page_options('select_slider');
                    elseif ( 'image' == $header_element ){
                        $header_image = tfuse_page_options('header_image');
                        $header_title = tfuse_page_options('header_title');
                    }
                }
                elseif ( is_category() )
                {
                    $ID = get_query_var('cat');
                    $header_element = tfuse_options('header_element_cat', 'none', $ID);
                    if ( 'slider' == $header_element )
                        $slider = tfuse_options('select_slider_cat', null, $ID);
                    elseif ( 'image' == $header_element ){
                        $header_image = tfuse_options('header_image_cat', null, $ID);
                        $header_title = tfuse_options('header_title_cat', null, $ID);
                    }
                }
                elseif ( is_tax() )
                {
                    $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
                    $ID = $term->term_id;
                    $header_element = tfuse_options('header_element_cat', 'none', $ID);
                    if ( 'slider' == $header_element )
                        $slider = tfuse_options('select_slider_cat', null, $ID);
                    elseif ( 'image' == $header_element ){
                        $header_image = tfuse_options('header_image_cat', null, $ID);
                        $header_title = tfuse_options('header_title_cat', null, $ID);
                    }
                }
                elseif( is_archive() ){
                    $header_element = tfuse_options('header_element_archive', 'none');
                    if ( 'slider' == $header_element )
                        $slider = tfuse_options('select_slider_archive', null);
                    elseif ( 'image' == $header_element ){
                        $header_image = tfuse_options('header_image_archive', null);
                        $header_title = tfuse_options('header_title_archive', null);
                    }
                }
            break;
            case 'content' :
                if ($is_tf_blog_page)
                {
                    $header_element = tfuse_options('footer_element_blog', 'none');
                    if ( 'slider' == $header_element )
                        $slider = tfuse_options('select_slider_footer_blog');
                    else return;
                }
                elseif ($is_tf_front_page)
                {
                    if(tfuse_options('use_page_options') && tfuse_options('homepage_category')=='page'){
	                    $ID = get_the_ID();
                        $header_element = tfuse_page_options('footer_element','',$ID);
                        if ( 'slider' == $header_element )
                            $slider = tfuse_page_options('select_slider_footer','',$ID);
                        else return;
                    }
                    else{
                        $header_element = tfuse_options('footer_element','none');
                        if ( 'slider' == $header_element )
                            $slider = tfuse_options('select_slider_footer');
                        else return;
                    }
                }
                else if( is_search() ){
                    if($TFUSE->request->isset_GET(TF_SEEK_HELPER::get_search_parameter('form_id')))
                    {
                        $header_element = tfuse_options('footer_element_search_seek','none');
                        if ( 'slider' == $header_element )
                            $slider = tfuse_options('select_slider_footer_search_seek');
                        else return;
                    }
                    else{
                        $header_element = tfuse_options('footer_element_search','none');
                        if ( 'slider' == $header_element )
                            $slider = tfuse_options('select_slider_footer_search');
                        else return;
                    }
                }
                else if( is_404() ){
                    $header_element = tfuse_options('footer_element_404','none');
                    if ( 'slider' == $header_element )
                        $slider = tfuse_options('select_slider_footer_404');
                    else return;
                }
                else if( is_tag() ){
                    $header_element = tfuse_options('footer_element_tag','none');
                    if ( 'slider' == $header_element )
                        $slider = tfuse_options('select_slider_footer_tag');
                    else return;
                }
                else if ( is_singular() )
                {
                    $ID = $post->ID;
                    $header_element = tfuse_page_options('footer_element','none');
                    if ( 'slider' == $header_element )
                        $slider = tfuse_page_options('select_slider_footer');
                    else return;
                }
                elseif ( is_category() )
                {
                    $ID = get_query_var('cat');
                    $header_element = tfuse_options('footer_element_cat', 'none', $ID);
                    if ( 'slider' == $header_element )
                        $slider = tfuse_options('select_slider_footer_cat', null, $ID);
                    else return;
                }
                elseif ( is_tax() )
                {
                    $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
                    $ID = $term->term_id;
                    $header_element = tfuse_options('footer_element_cat', 'none', $ID);
                    if ( 'slider' == $header_element )
                        $slider = tfuse_options('select_slider_footer_cat', null, $ID);
                    else return;
                }
                elseif ( is_archive() )
                {
                    $header_element = tfuse_options('footer_element_archive', 'none');
                    if ( 'slider' == $header_element )
                        $slider = tfuse_options('select_slider_footer_archive', null);
                    else return;
                }
            break;
        }

        if ( $header_element == 'image' ||  $header_element=='none' )
        {
            get_template_part( 'header', 'image' );
            return;
        }
        elseif ( $header_element == 'map' )
        {
            get_template_part( 'header', 'map' );
            return;
        }
        elseif ( !$slider )
            return;

        $slider = $TFUSE->ext->slider->model->get_slider($slider);

        switch ($slider['type']):
            case 'custom':
                if ( is_array($slider['slides']) ) :
                    $slider_image_resize = ( isset($slider['general']['slider_image_resize']) && $slider['general']['slider_image_resize'] == 'true' ) ? true : false;
                    $design = $slider['design'];
                    if($design=='featured' || $design=='fullbanner' ){
                        $width = 1680;
                        $height = 617;
                    }
                    elseif($design=='image_video'){
                        $width = 640;
                        $height = 360;
                    }
                    else{
                        $width = 230;
                        $height = 162;
                    }
                    foreach ($slider['slides'] as $k => $slide) :
                        $image = new TF_GET_IMAGE();
                        $slider['slides'][$k]['slide_src'] = $image->width($width)->height($height)->src($slide['slide_src'])->resize($slider_image_resize)->get_src();
                    endforeach;
                endif;
                break;

            case 'posts':
                $slides_posts = array();
                $latest_posts = false;
                if($slider['general']['posts_select_type'] == 'categories')
                {
                    if($slider['general']['posts_select_population_type']=='latest'){
                        $latest_posts = true;
                        $posts_number = $slider['general']['sliders_posts_number'];
                        $args = array(
                            'posts_per_page' => (int)$posts_number,
                            'post_type'      => 'post',
                            'suppress_filters' => false

                        );
                        $posts = get_posts($args);
                    }
                    else{
                        $post__in = tf_lang_object_ids( explode(',',$slider['general']['posts_select_cat']), 'post');
                        $args = array( 'post__in' => $post__in );
                        $slides_posts = $post__in;
                    }
                }
                else
                {
                    if($slider['general']['posts_select_population_type']=='latest'){
                        $latest_posts = true;
                        $posts_number = $slider['general']['sliders_posts_number'];
                        $args = array(
                            'posts_per_page' => (int)$posts_number,
                            'post_type'      => TF_SEEK_HELPER::get_post_type(),
                            'suppress_filters' => false

                        );
                        $posts = get_posts($args);
                    }
                    else{
                        $post__in = tf_lang_object_ids( explode(',',$slider['general']['posts_select_portf']), TF_SEEK_HELPER::get_post_type());
                        $args = array( 'post__in' => $post__in );
                        $slides_posts = $post__in;
                    }
                }
                if(!$latest_posts){
                    foreach($slides_posts as $slide_posts):
                        $posts[] = get_post($slide_posts);
                    endforeach;
                    $posts = array_reverse($posts);
                }
                $args = apply_filters('tfuse_slider_posts_args', $args, $slider);
                $args = apply_filters('tfuse_slider_posts_args_'.$ID, $args, $slider);
                break;

            case 'categories':
                if($slider['general']['posts_select_type'] == 'categories')
                {
                    $args = 'cat='.$slider['general']['posts_select_cat'];
                    if( (isset($slider['general']['sliders_posts_number'])) AND ($slider['general']['sliders_posts_number'] != ''))
                    {
                        $args .= "&posts_per_page=" . $slider['general']['sliders_posts_number'];
                    }
                    $args = apply_filters('tfuse_slider_categories_args', $args, $slider);
                    $args = apply_filters('tfuse_slider_categories_args_'.$ID, $args, $slider);
                    $posts = get_posts($args);
                }
                else
                {
                    $args = 'cat='.$slider['general']['posts_select_portf'];
                    $args = apply_filters('tfuse_slider_categories_args', $args, $slider);
                    $args = apply_filters('tfuse_slider_categories_args_'.$ID, $args, $slider);
                    $slides_posts = explode(',',$slider['general']['posts_select_portf']);
                    $args = array(
                        'posts_per_page' => -1,
                        'relation' => 'AND',
                        'tax_query' => array(
                            array(
                                'taxonomy' => TF_SEEK_HELPER::get_post_type().'_type',
                                'field' => 'id',
                                'terms' => $slides_posts
                            )
                        )
                    );
                    $query = new WP_Query($args);
                    $posts  = $query->get_posts();
                }
                break;
        endswitch;

        if ( is_array($posts) ) :
            $slider['slides'] = tfuse_get_slides_from_posts($posts,$slider);
        endif;

        if ( !is_array($slider['slides']) ) return;

        include_once(locate_template( '/theme_config/extensions/slider/designs/'.$slider['design'].'/template.php' ));
    }

endif;
add_action('tfuse_header_content', 'tfuse_get_header_content');


if ( ! function_exists( 'tfuse_get_slides_from_posts' ) ):
    /**
     * Note that this function is hooked into the after_setup_theme hook, which runs
     * before the init hook. The init hook is too late for some features, such as indicating
     * support post thumbnails.
     *
     * To override tfuse_slider_type() in a child theme, add your own tfuse_slider_type to your child theme's
     * functions.php file.
     */
    function tfuse_get_slides_from_posts( $posts=array(), $slider = array() )
    {
        global $post;
        $c = 0;
        $slides = array();
        $slider_image_resize = ( isset($slider['general']['slider_image_resize']) && $slider['general']['slider_image_resize'] == 'true' ) ? $slider['general']['slider_image_resize'] : false;
        $price_symbol = TF_SEEK_HELPER::get_option("seek_property_currency_symbol","$");
        $fuel_symbol = TF_SEEK_HELPER::get_option("seek_property_consumption_symbol","MPG");
        $design = $slider['design'];
        if($design=='featured' || $design=='fullbanner' ){
            $width = 1680;
            $height = 617;
        }
        elseif($design=='image_video'){
            $width = 640;
            $height = 360;
        }
        else{
            $width = 230;
            $height = 162;
        }

        foreach ($posts as $k => $post) : setup_postdata( $post );
            $tfuse_image = $image = null;
            $single_image = tfuse_page_options('single_image');
            if( ($single_image =='' || $single_image == null) && $slider['design'] == 'carousel')
                $single_image = tfuse_page_options('thumbnail_image', null, $post->ID);
            if($slider['design'] == 'image_video'){
                $attachments = tfuse_get_gallery_images($post->ID,TF_THEME_PREFIX . '_slider_images');
                $price = TF_SEEK_HELPER::get_post_option('property_price', 0, $post->ID);
                $mileage = TF_SEEK_HELPER::get_post_option('property_mileage', 0, $post->ID);
                $fuel_consum = TF_SEEK_HELPER::get_post_option('property_consumption', 0, $post->ID);
                $date = '01/'.TF_SEEK_HELPER::get_post_option('property_year', '11/2010', $post->ID);
                $date = date("d/m/Y", strtotime($date));
                $date = date("F Y",strtotime($date));
                $vehicle_details = '<span>'.__('FIRST REGISTRATION:','tfuse').'</span> '.$date.'
                                    <span>'.__('FUEL CONSUMPTION:','tfuse').'</span> '.$fuel_consum.' '.$fuel_symbol.'
                                    <span>'.__('NO. OF KILOMETERS:','tfuse').'</span> '.number_format($mileage,0,'', ',');
                $slider_images = array();
                if ($attachments) {
                    foreach($attachments as $attachment){
                        $slider_images[] = array(
                            'id'            => $attachment->ID,
                            'order'         => $attachment->menu_order,
                            'img_full'      => $attachment->guid,
                            'image_options' => $attachment->image_options
                        );
                    }
                }
                if(sizeof($slider_images)) :
                    $slider_images = tfuse_arsort($slider_images,'order');
                    foreach($slider_images as $attachment){
                        if(isset($attachment['image_options']['imgmain_check']) && $attachment['image_options']['imgmain_check']=='yes'){
                            $single_image = $attachment['img_full'];
                            break;
                        }
                        else $single_image = $attachment['img_full'];
                    }
                endif;
            }

            if ( !$single_image )continue;
            $c++;
            $image = new TF_GET_IMAGE();
            $tfuse_image = $image->width($width)->height($height)->src($single_image)->resize($slider_image_resize)->get_src();

            $slides[$k]['slide_src'] = $tfuse_image;
            $slides[$k]['slide_link_title'] =  get_permalink();
            $slides[$k]['slide_title'] = get_the_title();

            if ( $subtitle = tfuse_page_options('slide_subtitle') )
                $slides[$k]['slide_subtitle'] = $subtitle;
            else
                $slides[$k]['slide_subtitle'] = strip_tags(tfuse_substr( get_the_excerpt(), 50 ));

            if ( $slider['design'] == 'image_video' ){
                $slides[$k]['slide_type_slide'] = 'image';
                $slides[$k]['slide_list'] = $vehicle_details;
                $slides[$k]['slide_subtitle'] = '<span>'.__('Price (incl. VAT):','tfuse').'</span> <span class="symbol_price_left">'.$price_symbol.'</span>'.number_format($price,0,'', ',');
            }
            if($slider['type']!='posts'){
                if($c == $slider['general']['sliders_posts_number'] )break;
            }

        endforeach;
        wp_reset_query();
        return $slides;
    }
endif;