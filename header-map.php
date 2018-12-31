<?php
$template_directory = get_template_directory_uri();
wp_register_script('maps.google.com', 'https://maps.google.com/maps/api/js' . tfuse_gmap_key(), array('jquery'), '', true);
wp_register_script('jquery.gmap', $template_directory . '/js/jquery.gmap.min.js', array('jquery', 'maps.google.com'), '', true);
wp_print_scripts('maps.google.com');
wp_print_scripts('jquery.gmap');

global $is_tf_blog_page,$header_map;

if ( $is_tf_blog_page )
{
    $tmp_conf['post_id'] = $post->ID;
    $tmp_conf ['show_all_markers'] = false;
    $coords = explode(':', tfuse_options('page_map_blog'));

    if((!$coords[0]) || (!$coords[1]))
    {
        $tmp_conf ['show_all_markers'] = true;
    }
    else
    {
        $tmp_conf['post_coords']['lat']     = preg_replace('[^0-9\.]', '', $coords[0]);
        $tmp_conf['post_coords']['lng']     = preg_replace('[^0-9\.]', '', $coords[1]);
        $tmp_conf['post_coords']['html']    = tfuse_options('map_text_blog');
        $tmp_conf['post_coords']['zoom']    = tfuse_options('map_zoom_blog');
    }
}
elseif (is_front_page())
{
    $page_id = tfuse_options('home_page');
    if(tfuse_options('use_page_options') && tfuse_options('homepage_category')=='page')
    {   
        $tmp_conf['post_id'] = $page_id;
        $tmp_conf ['show_all_markers'] = false;
        $coords = explode(':', tfuse_page_options('page_map','',$page_id));
        $tmp_conf['post_coords']['html']    = tfuse_page_options('map_text','',$page_id);
        $tmp_conf['post_coords']['zoom']    = tfuse_page_options('map_zoom','',$page_id);
    }
    else
    {   
        $tmp_conf['post_id'] = $post->ID;
        $tmp_conf ['show_all_markers'] = false;
        $coords = explode(':', tfuse_options('page_map'));
        $tmp_conf['post_coords']['html']    = tfuse_options('map_text');
        $tmp_conf['post_coords']['zoom']    = tfuse_options('map_zoom');
    }
     if((!$coords[0]) || (!$coords[1]))
        {
            $tmp_conf ['show_all_markers'] = true;
        }
        else
        {
            $tmp_conf['post_coords']['lat']     = preg_replace('[^0-9\.]', '', $coords[0]);
            $tmp_conf['post_coords']['lng']     = preg_replace('[^0-9\.]', '', $coords[1]);
        }
}
elseif ( is_search() )
{
    global $TFUSE;
    $tmp_conf ['show_all_markers'] = false;
    if($TFUSE->request->isset_GET(TF_SEEK_HELPER::get_search_parameter('form_id'))){
        $coords = explode(':', tfuse_options('page_map_search_seek'));

        if((!$coords[0]) || (!$coords[1]))
        {
            $tmp_conf ['show_all_markers'] = true;
        }
        else
        {
            $tmp_conf['post_coords']['lat']     = preg_replace('[^0-9\.]', '', $coords[0]);
            $tmp_conf['post_coords']['lng']     = preg_replace('[^0-9\.]', '', $coords[1]);
            $tmp_conf['post_coords']['html']    = tfuse_options('map_text_search_seek');
            $tmp_conf['post_coords']['zoom']    = tfuse_options('map_zoom_search_seek');
        }
    }
    else{
        $coords = explode(':', tfuse_options('page_map_search'));

        if((!$coords[0]) || (!$coords[1]))
        {
            $tmp_conf ['show_all_markers'] = true;
        }
        else
        {
            $tmp_conf['post_coords']['lat']     = preg_replace('[^0-9\.]', '', $coords[0]);
            $tmp_conf['post_coords']['lng']     = preg_replace('[^0-9\.]', '', $coords[1]);
            $tmp_conf['post_coords']['html']    = tfuse_options('map_text_search');
            $tmp_conf['post_coords']['zoom']    = tfuse_options('map_zoom_search');
        }
    }
}
elseif ( is_404() )
{
    $tmp_conf ['show_all_markers'] = false;
    $coords = explode(':', tfuse_options('page_map_404'));
    if((!$coords[0]) || (!$coords[1]))
    {
        $tmp_conf ['show_all_markers'] = true;
    }
    else
    {
        $tmp_conf['post_coords']['lat']     = preg_replace('[^0-9\.]', '', $coords[0]);
        $tmp_conf['post_coords']['lng']     = preg_replace('[^0-9\.]', '', $coords[1]);
        $tmp_conf['post_coords']['html']    = tfuse_options('map_text_404');
        $tmp_conf['post_coords']['zoom']    = tfuse_options('map_zoom_404');
    }
}
elseif ( is_author() )
{
    $tmp_conf['post_id'] = $post->ID;
    $tmp_conf ['show_all_markers'] = false;
    $coords = explode(':', tfuse_options('page_map_author'));

    if((!$coords[0]) || (!$coords[1]))
    {
        $tmp_conf ['show_all_markers'] = true;
    }
    else
    {
        $tmp_conf['post_coords']['lat']     = preg_replace('[^0-9\.]', '', $coords[0]);
        $tmp_conf['post_coords']['lng']     = preg_replace('[^0-9\.]', '', $coords[1]);
        $tmp_conf['post_coords']['html']    = tfuse_options('map_text_author');
        $tmp_conf['post_coords']['zoom']    = tfuse_options('map_zoom_author');
    }
}
elseif ( is_tag() )
{
    $tmp_conf['post_id'] = $post->ID;
    $tmp_conf ['show_all_markers'] = false;
    $coords = explode(':', tfuse_options('page_map_tag'));

    if((!$coords[0]) || (!$coords[1]))
    {
        $tmp_conf ['show_all_markers'] = true;
    }
    else
    {
        $tmp_conf['post_coords']['lat']     = preg_replace('[^0-9\.]', '', $coords[0]);
        $tmp_conf['post_coords']['lng']     = preg_replace('[^0-9\.]', '', $coords[1]);
        $tmp_conf['post_coords']['html']    = tfuse_options('map_text_tag');
        $tmp_conf['post_coords']['zoom']    = tfuse_options('map_zoom_tag');
    }
}
elseif (is_category())
{
    $ID = get_query_var('cat');
    $tmp_conf ['show_all_markers'] = false;
    $coords = explode(':', tfuse_options('page_map_cat',null,$ID));
    if((!$coords[0]) || (!$coords[1]))
    {
        $tmp_conf ['show_all_markers'] = true;
    }
    else
    {
        $tmp_conf['post_coords']['lat']     = preg_replace('[^0-9\.]', '', $coords[0]);
        $tmp_conf['post_coords']['lng']     = preg_replace('[^0-9\.]', '', $coords[1]);
        $tmp_conf['post_coords']['html']    = tfuse_options('map_text_cat',null,$ID);
        $tmp_conf['post_coords']['zoom']    = tfuse_options('map_zoom_cat',null,$ID);
    }
}
elseif (is_tax())
{
    $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
    $ID = $term->term_id;
    $tmp_conf ['show_all_markers'] = false;
    $coords = explode(':', tfuse_options('page_map_cat',null,$ID));
    if((!$coords[0]) || (!$coords[1]))
    {
        $tmp_conf ['show_all_markers'] = true;
    }
    else
    {
        $tmp_conf['post_coords']['lat']     = preg_replace('[^0-9\.]', '', $coords[0]);
        $tmp_conf['post_coords']['lng']     = preg_replace('[^0-9\.]', '', $coords[1]);
        $tmp_conf['post_coords']['html']    = tfuse_options('map_text_cat',null,$ID);
        $tmp_conf['post_coords']['zoom']    = tfuse_options('map_zoom_cat',null,$ID);
    }
}
elseif ( is_page() || is_single() )
{
    //if is page
    $tmp_conf['post_id'] = $post->ID;
    $tmp_conf ['show_all_markers'] = false;
    $coords = explode(':', tfuse_page_options('page_map'));
    if((!$coords[0]) || (!$coords[1]))
    {
        $tmp_conf ['show_all_markers'] = true;
    }
    else
    {
        $tmp_conf['post_coords']['lat']     = preg_replace('[^0-9\.]', '', $coords[0]);
        $tmp_conf['post_coords']['lng']     = preg_replace('[^0-9\.]', '', $coords[1]);
        $tmp_conf['post_coords']['html']    = tfuse_page_options('map_text');
        $tmp_conf['post_coords']['zoom']    = tfuse_page_options('map_zoom');
    }
}
elseif (is_archive() )
{
    $tmp_conf ['show_all_markers'] = false;
    $coords = explode(':', tfuse_options('page_map_archive',null));
    if((!$coords[0]) || (!$coords[1]))
    {
        $tmp_conf ['show_all_markers'] = true;
    }
    else
    {
        $tmp_conf['post_coords']['lat']     = preg_replace('[^0-9\.]', '', $coords[0]);
        $tmp_conf['post_coords']['lng']     = preg_replace('[^0-9\.]', '', $coords[1]);
        $tmp_conf['post_coords']['html']    = tfuse_options('map_text_archive',null);
        $tmp_conf['post_coords']['zoom']    = tfuse_options('map_zoom_archive',null);
    }
}

if(!empty($tmp_conf['post_coords']['lat']) || !empty($tmp_conf['post_coords']['lng'])): ?>
    <!-- header -->
    <div class="header header_map">
        <div id="header_map"></div>
        <script>
            jQuery(window).ready(function () {
            jQuery("#header_map").gMap({
                scrollwheel: false,
                markers: [{
                    latitude: <?php echo $tmp_conf['post_coords']['lat']; ?>,
                    longitude: <?php echo  $tmp_conf['post_coords']['lng'];?>,
                    html:"<?php echo $tmp_conf['post_coords']['html'];?>",
                    popup: false
                }],
                zoom: <?php echo $tmp_conf['post_coords']['zoom']; ?>
                });
            });
        </script>
    </div><!--/ .header -->
<?php endif; ?>