<?php
if ( ! isset( $content_width ) ) $content_width = 900;

add_theme_support( 'automatic-feed-links' );

if (!function_exists('tfuse_class')) :
    /* This Function Add the classes for middle container
     * To override tfuse_class() in a child theme, add your own tfuse_count_post_visits()
     * to your child theme's theme_config/theme_includes/THEME_FUNCTIONS.php file.
    */

    function tfuse_class($param, $return = false) {
        $tfuse_class = '';
        $sidebar_position = tfuse_sidebar_position();
        if ($param == 'middle')
        {
            if ($sidebar_position == 'left')
                $tfuse_class = ' id="middle" class="cols2 sidebar_left"';
            elseif($sidebar_position == 'right')
                $tfuse_class = ' id="middle" class="cols2"';
            else
                $tfuse_class = ' id="middle" class="full_width"';
        }

        if ($return)
            return $tfuse_class;
        else
            echo $tfuse_class;
    }
endif;


if (!function_exists('tfuse_sidebar_position')):
    /* This Function Set sidebar position
     * To override tfuse_sidebar_position() in a child theme, add your own tfuse_count_post_visits()
     * to your child theme's theme_config/theme_includes/THEME_FUNCTIONS.php file.
    */
    function tfuse_sidebar_position() {
        global $TFUSE;
        $sidebar_position = $TFUSE->ext->sidebars->current_position;
        if ( empty($sidebar_position) ) $sidebar_position = 'full';
        return $sidebar_position;
    }

// End function tfuse_sidebar_position()
endif;


if (!function_exists('tfuse_count_post_visits')) :
    /**
     * To override tfuse_count_post_visits() in a child theme, add your own tfuse_count_post_visits()
     * to your child theme's theme_config/theme_includes/THEME_FUNCTIONS.php file.
     */

    function tfuse_count_post_visits()
    {
        if ( !is_single() ) return;
        global $post;
        $views = get_post_meta($post->ID, TF_THEME_PREFIX . '_post_viewed', true);
        $views = intval($views);
        tf_update_post_meta( $post->ID, TF_THEME_PREFIX . '_post_viewed', ++$views);
    }
    add_action('wp_head', 'tfuse_count_post_visits');

// End function tfuse_count_post_visits()
endif;


if (!function_exists('tfuse_custom_title')):
    function tfuse_custom_title($customID = false,$return = false) {
        global $post;
        if (is_numeric($customID))
            $ID = $customID;
        else
            $ID = $post->ID;

        $tfuse_title_type = tfuse_page_options('page_title', '', $ID);

        if ($tfuse_title_type == 'hide_title')
            $title = '';
        elseif ($tfuse_title_type == 'custom_title')
            $title = tfuse_page_options('custom_title', '', $ID);
        else
            $title = get_the_title($ID);

        if( $return ) return $title;

        echo ( $title ) ? '<h2 class="page_title">' . $title . '</h2>' : '';
    }
endif;


if (!function_exists('tfuse_user_profile')) :
    /**
     * Retrieve the requested data of the author of the current post.
     *
     * @param array $fields first_name,last_name,email,url,aim,yim,jabber,facebook,twitter etc.
     * @return null|array The author's spefified fields from the current author's DB object.
     */
    function tfuse_user_profile( $fields = array() )
    {
        $tfuse_meta = null;

        // Get stnadard user contact info
        $standard_meta = array(
            'first_name' => get_the_author_meta('first_name'),
            'last_name' => get_the_author_meta('last_name'),
            'email'     => get_the_author_meta('email'),
            'url'       => get_the_author_meta('url'),
            'aim'       => get_the_author_meta('aim'),
            'yim'       => get_the_author_meta('yim'),
            'jabber'    => get_the_author_meta('jabber')
        );

        // Get extended user info if exists
        $custom_meta = (array) get_the_author_meta('theme_fuse_extends_user_options');

        $_meta = array_merge($standard_meta,$custom_meta);

        foreach ($_meta as $key => $item) {
            if ( !empty($item) && in_array($key, $fields) ) $tfuse_meta[$key] = $item;
        }

        return apply_filters('tfuse_user_profile', $tfuse_meta, $fields);
    }
endif;


if (!function_exists('tfuse_action_comments')) :
    /**
     *  This function disable post commetns.
     *
     * To override tfuse_action_comments() in a child theme, add your own tfuse_count_post_visits()
     * to your child theme's theme_config/theme_includes/THEME_FUNCTIONS.php file.
     */

    function tfuse_action_comments() {
        global $post;

        if (!tfuse_page_options('disable_comments') && isset($post) && $post->comment_status == 'open')
            comments_template( '', true );
    }

    add_action('tfuse_comments', 'tfuse_action_comments');
endif;


if (!function_exists('tfuse_get_comments')):
    /**
     *  Get post comments for a specific post.
     *
     * To override tfuse_get_comments() in a child theme, add your own tfuse_count_post_visits()
     * to your child theme's theme_config/theme_includes/THEME_FUNCTIONS.php file.
     */

    function tfuse_get_comments($return = TRUE, $post_ID) {
        $num_comments = get_comments_number($post_ID);

        if (comments_open($post_ID)) {
            if ($num_comments == 0) {
                $comments = __('No Comments','tfuse');
            } elseif ($num_comments > 1) {
                $comments = $num_comments . __(' Comments','tfuse');
            } else {
                $comments = __('1 Comment','tfuse');
            }
            $write_comments = '<a class="link-comments" href="' . get_comments_link($post_ID) . '">' . $comments . '</a>';
        } else {
            $write_comments = __('Comments are off','tfuse');
        }
        if ($return)
            return $write_comments;
        else
            echo $write_comments;
    }
endif;


if (!function_exists('tfuse_shortcode_content')) :
    /**
     *  Get post comments for a specific post.
     *
     * To override tfuse_shortcode_content() in a child theme, add your own tfuse_count_post_visits()
     * to your child theme's theme_config/theme_includes/THEME_FUNCTIONS.php file.
     */

    function tfuse_shortcode_content($position, $return = false)
    {
        global $is_tf_blog_page,$is_tf_front_page,$TFUSE;
        $page_shortcodes = '';
        if($is_tf_blog_page){
            $page_shortcodes = tfuse_options('content_'.$position.'_blog', '');
        }
        elseif($is_tf_front_page){
            if(tfuse_options('homepage_category','') == 'page' && tfuse_options('use_page_options','') == true)
                $page_shortcodes = tfuse_page_options('content_'.$position, '');
            else
                $page_shortcodes = tfuse_options('content_'.$position, '');
        }
        elseif (is_search()) {
            if($TFUSE->request->isset_GET(TF_SEEK_HELPER::get_search_parameter('form_id'))){
                $page_shortcodes = tfuse_options('content_'.$position.'_search_seek', '');
            }
            else{
                $page_shortcodes = tfuse_options('content_'.$position.'_search', '');
            }
        }
        elseif (is_404()) {
            $page_shortcodes = tfuse_options('content_'.$position.'_404', '');
        }
        elseif (is_tag()) {
            $page_shortcodes = tfuse_options('content_'.$position.'_tag', '');
        }
        elseif (is_singular()) {
            global $wp_query;
            $ID = $wp_query->queried_object->ID;
            $page_shortcodes = tfuse_page_options('content_'.$position, '', $ID);
        }
        elseif ( is_tax() )
        {
            $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
            $ID = $term->term_id;
            $page_shortcodes = tfuse_options('content_'.$position, '', $ID);
        }
        elseif(is_category()){
            $ID = get_query_var('cat');
            $page_shortcodes = tfuse_options('content_'.$position, '', $ID);
        }
        elseif(is_archive()){
            $page_shortcodes = tfuse_options('content_'.$position.'_archive', '');
        }

        if($position=='top' || $position=='bottom'){
            $html_before ='<div class="middle_row row_white search_row "><div class="container ">';
            $html_after ='</div></div>';
        }
        elseif($position=='bottom1'){
            $html_before ='<div class="middle_row row_gray "><div class="container clearfix ">';
            $html_after ='</div></div>';
        }
        elseif($position=='bottom2'){
            $html_before ='<div class="middle_row row_light_gray "><div class="container ">';
            $html_after ='</div></div>';
        }
        else {
            $html_before ='';
            $html_after ='';
        }

        $page_shortcodes = tfuse_qtranslate($page_shortcodes);
        $page_shortcodes = apply_filters('themefuse_shortcodes', $page_shortcodes);

        if ($return && $page_shortcodes!='')
            return $html_before.$page_shortcodes.$html_after;
        else if($page_shortcodes!='')
            echo $html_before.$page_shortcodes.$html_after;
        else
            echo $page_shortcodes;
    }

// End function tfuse_shortcode_content()
endif;


if (!function_exists('tfuse_category_on_front_page')) :
    /**
     * Dsiplay homepage category
     *
     * To override tfuse_category_on_front_page() in a child theme, add your own tfuse_count_post_visits()
     * to your child theme's theme_config/theme_includes/THEME_FUNCTIONS.php file.
     */

    function tfuse_category_on_front_page()
    {
        if ( !is_front_page() ) return;

        global $is_tf_front_page,$homepage_categ;
        $is_tf_front_page = false;

        $homepage_category = tfuse_options('homepage_category');
        $homepage_category = explode(",",$homepage_category);
        foreach($homepage_category as $homepage)
        {
            $homepage_categ = $homepage;
        }

        if($homepage_categ == 'specific')
        {
            $is_tf_front_page = true;
            $archive = 'archive.php';
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
            $specific = tfuse_options('categories_select_categ');
            $ids = explode(",",$specific);
            $posts = array();
            foreach ($ids as $id){
                $posts[] = get_posts(array('category' => $id));
            }

            $args = array(
                'cat' => $specific,
                'orderby' => 'date',
                'paged' => $paged
            );
            query_posts($args);
            include_once(locate_template($archive));
            return;
        }
        elseif($homepage_categ == 'page')
        {
            global $front_page;
            $is_tf_front_page = true;
            $front_page = true;
            $archive = 'page.php';
            $page_id = tfuse_options('home_page');
            $args=array(
                'page_id' => $page_id,
                'post_type' => 'page',
                'post_status' => 'publish',
                'posts_per_page' => 1,
                'ignore_sticky_posts'=> 1
            );
            query_posts($args);
            include_once(locate_template($archive));
            wp_reset_query();
            return;
        }
        else
        {
            $archive = 'archive.php';
            $is_tf_front_page = true;
            wp_reset_postdata();
            include_once(locate_template($archive));
            return;
        }
    }

// End function tfuse_category_on_front_page()
endif;


if (!function_exists('tfuse_action_footer')) :
    /**
     * Dsiplay footer content
     *
     * To override tfuse_action_footer() in a child theme, add your own tfuse_action_footer()
     * to your child theme's theme_config/theme_includes/THEME_FUNCTIONS.php file.
     */

    function tfuse_action_footer()
    { ?>

        <div class="f_col f_col_1">
            <?php dynamic_sidebar('footer-1'); ?>
        </div><!--/ footer col 1 -->

        <div class="f_col f_col_2">
            <?php dynamic_sidebar('footer-2'); ?>
        </div><!--/ footer col 2 -->

        <div class="f_col f_col_3">
            <?php dynamic_sidebar('footer-3'); ?>
        </div><!--/ footer col 3 -->

        <div class="f_col f_col_4">
            <?php dynamic_sidebar('footer-4'); ?>
        </div><!--/ footer col 4 -->

    <?php }

    add_action('tfuse_footer', 'tfuse_action_footer');
endif;

if ( !function_exists('tfuse_footer_social')):
    function tfuse_footer_social(){
        if(tfuse_options('social_google')!='' || tfuse_options('social_facebook')!='' || tfuse_options('social_twitter')!='' || tfuse_options('social_dribble')!='' || tfuse_options('social_linked')!='' || tfuse_options('social_vimeo')!='' || tfuse_options('social_flickr')!='' || tfuse_options('social_devianart')!='')
            echo '<div class="social_inner">';
            if(tfuse_options('social_google')!='') { ?>
                <a href="<?php echo tfuse_options('social_google'); ?>" class="social-google" hidefocus="true" style="outline: none;"><span>Google +1</span></a>
            <?php }
            if(tfuse_options('social_facebook')!='') { ?>
                <a href="<?php echo tfuse_options('social_facebook'); ?>" class="social-fb" hidefocus="true" style="outline: none;"><span>Facebook</span></a>
            <?php }
            if(tfuse_options('social_twitter')!='') { ?>
                <a href="<?php echo tfuse_options('social_twitter'); ?>" class="social-twitter" hidefocus="true" style="outline: none;"><span>Twitter</span></a>
            <?php }
            if(tfuse_options('social_dribble')!='') { ?>
                <a href="<?php echo tfuse_options('social_dribble'); ?>" class="social-dribble" hidefocus="true" style="outline: none;"><span>Dribble</span></a>
            <?php }
            if(tfuse_options('social_linked')!='') { ?>
                <a href="<?php echo tfuse_options('social_linked'); ?>" class="social-linkedin" hidefocus="true" style="outline: none;"><span>LinkedIn</span></a>
            <?php }
            if(tfuse_options('social_vimeo')!='') { ?>
                <a href="<?php echo tfuse_options('social_vimeo'); ?>" class="social-vimeo" hidefocus="true" style="outline: none;"><span>Vimeo</span></a>
            <?php }
            if(tfuse_options('social_flickr')!='') { ?>
                <a href="<?php echo tfuse_options('social_flickr'); ?>" class="social-flickr" hidefocus="true" style="outline: none;"><span>Flickr</span></a>
            <?php }
            if(tfuse_options('social_devianart')!='') { ?>
                <a href="<?php echo tfuse_options('social_devianart'); ?>" class="social-da" hidefocus="true" style="outline: none;"><span>Devianart</span></a>
            <?php }
        if(tfuse_options('social_google')!='' || tfuse_options('social_facebook')!='' || tfuse_options('social_twitter')!='' || tfuse_options('social_dribble')!='' || tfuse_options('social_linked')!='' || tfuse_options('social_vimeo')!='' || tfuse_options('social_flickr')!='' || tfuse_options('social_devianart')!='')
            echo '</div>';
    }
endif;


if (!function_exists('encodeURIComponent')) :
    /**
     * To override encodeURIComponent() in a child theme, add your own encodeURIComponent()
     * to your child theme's theme_config/theme_includes/THEME_FUNCTIONS.php file.
     */

    function encodeURIComponent($str) {
        $revert = array('%21'=>'!', '%2A'=>'*', '%27'=>"'", '%28'=>'(', '%29'=>')');
        return strtr(rawurlencode($str), $revert);
    }

endif;


if (!function_exists('tfuse_pagination')) :
    /**
     * Display pagination to next/previous pages when applicable.
     *
     * To override tfuse_pagination() in a child theme, add your own tfuse_pagination()
     * to your child theme's theme_config/theme_includes/THEME_FUNCTIONS.php file.
     */
    function tfuse_pagination($query = '', $args = array()){

        global $wp_rewrite, $wp_query;

        if ( $query ) {
            $wp_query = $query;
        } // End IF Statement

        /* If there's not more than one page, return nothing. */
        if ( 1 >= $wp_query->max_num_pages )
            return false;

        /* Get the current page. */
        $current = ( get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1 );

        /* Get the max number of pages. */
        $max_num_pages = intval( $wp_query->max_num_pages );

        /* Set up some default arguments for the paginate_links() function. */
        $defaults = array(
            'base' => esc_url(add_query_arg( 'paged', '%#%' )),
            'format' => '',
            'total' => $max_num_pages,
            'current' => $current,
            'prev_next' => false,
            'show_all' => false,
            'end_size' => 2,
            'mid_size' => 1,
            'add_fragment' => '',
            'type' => 'plain',
            'before' => '',
            'after' => '',
            'echo' => true,
        );

        /* Add the $base argument to the array if the user is using permalinks. */
        if( $wp_rewrite->using_permalinks() )
            $defaults['base'] = user_trailingslashit( trailingslashit( get_pagenum_link() ) . 'page/%#%' );

        /* If we're on a search results page, we need to change this up a bit. */
        if ( is_search() ) {
            $search_permastruct = $wp_rewrite->get_search_permastruct();
            if ( !empty( $search_permastruct ) )
                $defaults['base'] = user_trailingslashit( trailingslashit( get_search_link() ) . 'page/%#%' );
        }

        /* Merge the arguments input with the defaults. */
        $args = wp_parse_args( $args, $defaults );

        /* Don't allow the user to set this to an array. */
        if ( 'array' == $args['type'] )
            $args['type'] = 'plain';

        /* Get the paginated links. */
        $page_links = paginate_links( $args );

        /* Remove 'page/1' from the entire output since it's not needed. */
        $page_links = str_replace( array( '&#038;paged=1\'', '/page/1\'' ), '\'', $page_links );

        /* Wrap the paginated links with the $before and $after elements. */
        $page_links = $args['before'] . $page_links . $args['after'];

        /* Return the paginated links for use in themes. */
        if ( $args['echo'] )
        { ?>

        <!-- pagination -->
        <div class="tf_pagination">
            <div class="inner">
                <?php $prev_posts = get_previous_posts_link(__('<span></span>PREV', 'tfuse')); ?>
                <?php $next_posts = get_next_posts_link(__('<span></span>NEXT', 'tfuse')); ?>
                <?php if ($prev_posts != '') { echo $prev_posts;} else { echo '<a class="page_prev" href="javascript:void(0);"><span></span>'; _e('PREV', 'tfuse'); echo '</a>'; }?>
                <?php if ($next_posts != '') { echo $next_posts;} else { echo '<a class="page_next" href="javascript:void(0);"><span></span>'; _e('NEXT', 'tfuse'); echo '</a>'; } ?>
                <?php echo $page_links; ?>

            </div>
        </div><!--/ pagination -->
        <?php
        }
        else
            return $page_links;
    }
endif; // tfuse_pagination


if (!function_exists('next_posts_link_css')) :
    /**
     * Display pagination to next/previous pages when applicable.
     *
     * To override next_posts_link_css() in a child theme, add your own next_posts_link_css()
     * to your child theme's theme_config/theme_includes/THEME_FUNCTIONS.php file.
     */
    function next_posts_link_css() {
        return 'class="page_next"';
    }
    add_filter('next_posts_link_attributes', 'next_posts_link_css' );
endif;


if (!function_exists('previous_posts_link_css')) :
    /**
     * Display pagination to next/previous pages when applicable.
     *
     * To override previous_posts_link_css() in a child theme, add your own previous_posts_link_css()
     * to your child theme's theme_config/theme_includes/THEME_FUNCTIONS.php file.
     */
    function previous_posts_link_css() {
        return 'class="page_prev"';
    }
    add_filter('previous_posts_link_attributes', 'previous_posts_link_css' );
endif; // tfuse_pagination


if (!function_exists('tfuse_enqueue_comment_reply')) :
    /**
     * To override tfuse_enqueue_comment_reply() in a child theme, add your own tfuse_enqueue_comment_reply()
     * to your child theme's theme_config/theme_includes/THEME_FUNCTIONS.php file.
     */
function tfuse_enqueue_comment_reply() {
    // on single blog post pages with comments open and threaded comments
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        // enqueue the javascript that performs in-link comment reply fanciness
        wp_enqueue_script( 'comment-reply' );
    }
}
// Hook into wp_enqueue_scripts
add_action( 'wp_head', 'tfuse_enqueue_comment_reply' );
endif;


if (!function_exists('tfuse_new_excerpt_more')) :
    /**
     * To override tfuse_new_excerpt_more() in a child theme, add your own tfuse_new_excerpt_more()
     * to your child theme's theme_config/theme_includes/THEME_FUNCTIONS.php file.
     */
function tfuse_new_excerpt_more() {
    return '...';
}

add_filter('excerpt_more', 'tfuse_new_excerpt_more' );
endif;


if (!function_exists('tfuse_custom_excerpt_length')) :
    /**
     * To override tfuse_custom_excerpt_length() in a child theme, add your own tfuse_custom_excerpt_length()
     * to your child theme's theme_config/theme_includes/THEME_FUNCTIONS.php file.
     */
    function tfuse_custom_excerpt_length( $length) {
        return 144;
    }
    add_filter( 'excerpt_length', 'tfuse_custom_excerpt_length', 99 );

endif;


if (!function_exists('tfuse_custom_excerpt_length_short')) :
    /**
     * To override tfuse_custom_excerpt_length_short() in a child theme, add your own tfuse_custom_excerpt_length()
     * to your child theme's theme_config/theme_includes/THEME_FUNCTIONS.php file.
     */
    function tfuse_custom_excerpt_length_short( $length) {
        return 18;
    }
endif;


if (!function_exists('tfuse_get_property_taxonomies')) :
    /**
     * To override tfuse_get_property_taxonomies() in a child theme, add your own tfuse_get_property_taxonomies()
     * to your child theme's file.
     */
    function tfuse_get_property_taxonomies($ID, $in_taxonomy = '')
    {
       if(empty($ID) || (!is_numeric($ID))) return false;
       if(!empty($in_taxonomy)) $in_taxonomy = TF_SEEK_HELPER::get_post_type() . '_' . $in_taxonomy;
       global $wpdb;

        $sql = "SELECT $wpdb->terms.term_id
                 , $wpdb->terms.name, $wpdb->terms.slug, $wpdb->term_taxonomy.taxonomy
                FROM
                  $wpdb->term_relationships
                INNER JOIN $wpdb->term_taxonomy
                ON $wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id
                INNER JOIN $wpdb->terms
                ON $wpdb->terms.term_id = $wpdb->term_taxonomy.term_id
                WHERE
                  $wpdb->term_relationships.object_id = '" . $ID . "'
                AND $wpdb->term_taxonomy.taxonomy = '" . $in_taxonomy . "'";

      $result =  $wpdb->get_results($sql, ARRAY_A );
      return $result;
    }

endif;

if (!function_exists('tfuse_get_count_properties_by_taxonomy_id')) :
    /**
     * To override tfuse_get_count_properties_by_taxonomy_id() in a child theme, add your own tfuse_get_count_properties_by_taxonomy_id()
     * to your child theme's file.
     */
    function tfuse_get_count_properties_by_taxonomy_id($ID)
    {
        $term_id = intval($ID);
        if (!is_numeric($term_id)) return false;
        global $wpdb;
        $term_id = (string)$term_id;

        $sql = "SELECT COUNT(*) as count
FROM
  " . TF_SEEK_HELPER::get_db_table_name() . "
WHERE
  " . TF_SEEK_HELPER::get_db_table_name() . "._terms LIKE '%". $term_id . ",%'";

        $result =  $wpdb->get_results($sql, ARRAY_A );

        return $result;
    }

endif;


if (!function_exists('tfuse_get_properties_by_taxonomy_id')) :
    /**
     * To override tfuse_get_properties_by_taxonomy_id() in a child theme, add your own tfuse_get_properties_by_taxonomy_id()
     * to your child theme's file.
     */
    function tfuse_get_properties_by_taxonomy_id($ID,$order_desc, $start, $final)
    {
        $term_id = intval($ID);
        if (!is_numeric($term_id)) return false;
        global $wpdb;
        $term_id = (string)$term_id;
        if(!$order_desc)
        {
            $order = '';
        }
        else
        {
            $order = ' DESC';
        }
        $sql = "SELECT " . TF_SEEK_HELPER::get_db_table_name() . ".post_id
     , " . TF_SEEK_HELPER::get_db_table_name() . ".seek_property_price
     , " . TF_SEEK_HELPER::get_db_table_name() . ".seek_property_mileage
     , " . TF_SEEK_HELPER::get_db_table_name() . ".seek_property_reduction
     , $wpdb->posts.post_excerpt
     , $wpdb->posts.post_title
FROM
  " . TF_SEEK_HELPER::get_db_table_name() . "
  INNER JOIN " . $wpdb->posts . "
ON " . TF_SEEK_HELPER::get_db_table_name() . ".post_id = $wpdb->posts.ID
WHERE
  " . TF_SEEK_HELPER::get_db_table_name() . "._terms LIKE '%". $term_id . ",%' ORDER BY
  " . TF_SEEK_HELPER::get_db_table_name() . ".seek_property_price" . $order . ' LIMIT ' . $start . ', ' . $final;

        $result =  $wpdb->get_results($sql,ARRAY_A);

        return $result;
    }

endif;


if (!function_exists('tfuse_arsort')) :
    /**
     * To override tfuse_arsort() in a child theme, add your own tfuse_arsort()
     * to your child theme's file. Invers sort an array by parameter key
     */
    function tfuse_arsort ($array, $key) {
        $sorter=array();
        $ret=array();
        if (!$array){$array = array();}
        reset($array);
        foreach ($array as $ii => $va) {
            $sorter[$ii]=$va[$key];
        }
        arsort($sorter);
        foreach ($sorter as $ii => $va) {
            $ret[$ii]=$array[$ii];
        }
        return $ret;
    }
endif;


if (!function_exists('tfuse_get_archive_link')) :
    /**
     * To override tfuse_get_archive_link() in a child theme, add your own tfuse_get_archive_link()
     * to your child theme's file.
     */
    function tfuse_get_archive_link ($link_html) {
        $link_html = str_replace('</a>','',$link_html);
        $link_html = str_replace('</li>','</a></li>',$link_html);
        return $link_html;
    }
    add_filter("get_archives_link", "tfuse_get_archive_link");
endif;
add_filter( 'show_recent_comments_widget_style', '__return_false' );



if (!function_exists('tfuse_breadcrumbs')) :
    /**
     * To override tfuse_breadcrumbs() in a child theme, add your own tfuse_breadcrumbs()
     * to your child theme's file.
     */
    function tfuse_breadcrumbs ($args = array()) {
        global $TFUSE;
        if(is_category()){
            $ID = get_query_var('cat');
            $enable_breadcrumbs = tfuse_options('enable_breadcrumbs',tfuse_options('enable_breadcrumbs'),$ID);
        }
        elseif(is_tax()){
            $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
            $ID = $term->term_id;
            $enable_breadcrumbs = tfuse_options('enable_breadcrumbs',tfuse_options('enable_breadcrumbs'),$ID);
        }
        elseif(is_search()){
            if($TFUSE->request->isset_GET(TF_SEEK_HELPER::get_search_parameter('form_id')))
                $enable_breadcrumbs = tfuse_options('enable_breadcrumbs_search_seek',tfuse_options('enable_breadcrumbs'));
            else
                $enable_breadcrumbs = tfuse_options('enable_breadcrumbs_search',tfuse_options('enable_breadcrumbs'));
        }
        else
            $enable_breadcrumbs = tfuse_page_options('enable_breadcrumbs',tfuse_options('enable_breadcrumbs'));
        if ( $enable_breadcrumbs || is_404() ){
            global $wp_query, $is_tf_front_page, $is_tf_blog_page;

            /* Set up the default arguments for the breadcrumb. */
            $defaults = array(
                'separator' => '<span class="separator">&gt;</span>',
                'before' => '<!-- breadcrumbs --><div class="middle_row row_white breadcrumbs"><div class="container"><p>',
                'last_before'   => '<span>',
                'last_after'    => '</span>',
                'after' => '</p><a href="' . home_url('/') . '?s=~&tfseekfid=main_search" class="link_search">'.__('Start a Car Search','tfuse').'</a></div></div><!--/ breadcrumbs -->',
                'front_page' => true,
                'show_home' => __( 'Home', 'tfuse' ),
                'for_disabled'  => '',
                'echo' => true
            );
            $args = array_merge($defaults,$args);

            $html = $args['before'];
            if ($args['front_page']) $html .= '<a href="' . site_url() . '">' . $args['show_home'] . '</a>';

            if($is_tf_front_page) if($args['echo']) { echo $args['for_disabled']; return ''; }else return $args['for_disabled'];

            if($is_tf_blog_page){
                $html .= $args['separator'] . $args['last_before'] . __('Blog','tfuse') . $args['last_after'];
            }
            elseif(is_404())
            {
                $html .= $args['separator'] . $args['last_before'] . __('404 Error Not Found','tfuse') . $args['last_after'];
            }
            elseif (is_singular('page'))
            {
                if($wp_query->queried_object->post_parent != 0 )
                {
                    $links = array();
                    $post = get_post($wp_query->queried_object->post_parent);
                    $links[] = $args['separator'] . '<a href="' . get_permalink($post->ID) . '">' . $post->post_title . '</a>';
                    while($post->post_parent != 0)
                    {
                        $post = get_post($post->post_parent);
                        $links[] = $args['separator'] . '<a href="' . get_permalink($post->ID) . '">' . $post->post_title . '</a>';
                    }
                    wp_reset_query();
                    $links = array_reverse($links);
                    foreach($links as $link) $html .= $link;
                }
                $html .= $args['separator'] . $args['last_before'] . $wp_query->queried_object->post_title . $args['last_after'];
            }
            elseif (is_singular('post') || is_singular('faq'))
            {
                $first_parent = false;
                $terms = wp_get_post_terms( $wp_query->queried_object->ID , 'category');
                if (isset($terms[0])) $first_parent = $terms[0];
                if ($TFUSE->request->isset_COOKIE('latest_category') && in_category(intval($TFUSE->request->COOKIE('latest_category')),$wp_query->queried_object->ID)) $first_parent = $TFUSE->request->COOKIE('latest_category');
                if($first_parent) :
                   $links = array();
                   $cat = get_term($first_parent,'category');
                   $links[] = $args['separator'] . '<a href="' . get_category_link($cat->term_id) . '">' . $cat->name . '</a>';
                    while($cat->parent)
                    {
                        $cat = get_term($cat->parent,'category');
                        $links[] = $args['separator'] . '<a href="' . get_category_link($cat->term_id) . '">' . $cat->name . '</a>';
                    };
                $links = array_reverse($links);
                foreach($links as $link) $html .= $link;
                endif;

                $html .= $args['separator'] . $args['last_before'] . $wp_query->queried_object->post_title . $args['last_after'];
            }
            elseif(is_singular(TF_SEEK_HELPER::get_post_type()))
            {
                $html .= $args['separator'] . $args['last_before'] . '<a href="' . home_url('/') . '?s=~&tfseekfid=main_search">' . TF_SEEK_HELPER::get_option('seek_property_name_plural', __('Vehicles', 'tfuse')) . '</a>' . $args['last_after'];
                $html .= $args['separator'] . $args['last_before'] . $wp_query->queried_object->post_title . $args['last_after'];
            }
            elseif(is_tag())
            {
                $html .= $args['separator'] . $args['last_before'] . $wp_query->queried_object->name . $args['last_after'];
            }
            elseif(is_post_type_archive(TF_SEEK_HELPER::get_post_type()))
            {
                $html .= $args['separator'] . $args['last_before'] . '<a href="javascript: return false;">' . TF_SEEK_HELPER::get_option('seek_property_name_plural','Vehicles') . '</a>' . $args['last_after'];
            }
            elseif(is_archive())
            {
                if(is_category())
                {
                    if(isset($wp_query->queried_object) && $wp_query->queried_object->parent != 0)
                    {
                        $links = array();
                        $category = get_category($wp_query->queried_object->parent);
                        $links[] = $args['separator'] . '<a href="' . get_category_link($category->term_id) . '">' . get_cat_name($category->term_id) . '</a>';
                        while($category->category_parent)
                        {
                            $category = get_category($category->parent);
                            $links[] = $args['separator'] . '<a href="' . get_category_link($category->term_id) . '">' . get_cat_name($category->term_id) . '</a>';
                        }
                        $links = array_reverse($links);
                        foreach($links as $link) $html .= $link;
                    }
                    if(isset($wp_query->queried_object->name)) $html .= $args['separator'] . $args['last_before'] . $wp_query->queried_object->name . $args['last_after'];
                    else $html .= $args['separator'] . $args['last_before'] . __('Blog','tfuse') . $args['last_after'];
                }
                elseif(is_date())
                {
                    $html .= $args['separator'] . $args['last_before'] . single_month_title(' ',false) . $args['last_after'];
                }
                elseif(is_author() && (isset($wp_query->query_vars['author_name'])))
                {
                    $html .= $args['separator'] . $args['last_before'] . $wp_query->query_vars['author_name'] . $args['last_after'];
                }
                elseif(isset($wp_query->query_vars['taxonomy']) && $wp_query->query_vars['taxonomy']=='faqs'){
                    $html .= $args['separator'] . $args['last_before'] . tfuse_qtranslate($wp_query->queried_object->name) . $args['last_after'];
                }
                elseif(isset($wp_query->query_vars['taxonomy']) && strpos($wp_query->query_vars['taxonomy'],TF_SEEK_HELPER::get_post_type()) == 0)
                {
                    $html .= $args['separator'] . $args['last_before'] . '<a href="'.home_url('/').'?s=~&tfseekfid=main_search">' . TF_SEEK_HELPER::get_option('seek_property_name_plural','Vehicles') . '</a>' . $args['last_after'];
                    $html .= $args['separator'] . $args['last_before'] . tfuse_qtranslate($wp_query->queried_object->name) . $args['last_after'];
                }
            }
            elseif(is_search())
            {
                $html .= $args['separator'] . $args['last_before'] . __('Search Results', 'tfuse') . $args['last_after'];
            }
            elseif(is_404())
            {
                $html .= $args['separator'] . $args['last_before'] . __('404', 'tfuse') . $args['last_after'];
            }

            $html .= $args['after'];
            if($args['echo']) echo tfuse_qtranslate($html);
            else return tfuse_qtranslate($html);
        }
    }
endif;


if (!function_exists('tfuse_get_vehicle_thumbnail')) :
    /**
     * To override tfuse_get_vehicle_thumbnail() in a child theme, add your own tfuse_get_vehicle_thumbnail()
     * to your child theme's file.
     */
    function tfuse_get_vehicle_thumbnail ($ID)
    {
        $src = tfuse_page_options('thumbnail_image', null, $ID);
        if(!$src) $src = get_template_directory_uri() . '/images/default_vehicle_image.jpg';
        return $src;
    }
endif;


if (!function_exists('tfuse_category_on_blog_page')) :
    /**
     * Dsiplay blogpage category
     *
     * To override tfuse_category_on_blog_page() in a child theme, add your own tfuse_count_post_visits()
     * to your child theme's theme_config/theme_includes/THEME_FUNCTIONS.php file.
     */

    function tfuse_category_on_blog_page()
    {
        global $is_tf_blog_page;
        $blogpage_categ ='';
        if ( !$is_tf_blog_page ) return;
        $is_tf_blog_page = false;

        $blogpage_category = tfuse_options('blogpage_category');
        $blogpage_category = explode(",",$blogpage_category);
        foreach($blogpage_category as $blogpage)
        {
            $blogpage_categ = $blogpage;
        }
        if($blogpage_categ == 'specific')
        {
            $is_tf_blog_page = true;
            $archive = 'archive.php';
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
            $specific = tfuse_options('categories_select_categ_blog');
            $ids = explode(",",$specific);
            $posts = array();
            foreach ($ids as $id){
                $posts[] = get_posts(array('category' => $id));
            }
            $args = array(
                'cat' => $specific,
                'orderby' => 'date',
                'paged' => $paged
            );
            query_posts($args);
            include_once(locate_template($archive));
            return;
        }
        else
        {
            $is_tf_blog_page = true;
            $archive = 'archive.php';
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
            $categories = get_categories();
            $ids = array();
            foreach($categories as $cats){
                $ids[] = $cats -> term_id;
            }
            $posts = array();

            foreach ($ids as $id){
                $posts[] = get_posts(array('category' => $id));
            }
            $args = array(
                'orderby' => 'date',
                'paged' => $paged
            );
            query_posts($args);
            include_once(locate_template($archive));
            return;
        }
    }
// End function tfuse_category_on_blog_page()
endif;


function tfuse_change_submenu_class($menu) {
    $menu = preg_replace('/ class="sub-menu"/',' class="submenu-1"',$menu);
    return $menu;
}
add_filter ('wp_nav_menu','tfuse_change_submenu_class');

if(!function_exists('tfuse_feedFilter')) :
    function tfuse_feedFilter($query) {
        if ($query->is_feed) {
            add_filter('the_content', 'tfuse_feedContentFilter');
        }
        return $query;
    }
    add_filter('pre_get_posts','tfuse_feedFilter');
    function tfuse_feedContentFilter($content) {
        global $post;
        if(get_post_type($post) == TF_SEEK_HELPER::get_post_type())
            $thumb = tfuse_page_options('thumbnail_image');
        else
            $thumb = tfuse_page_options('single_image');
        $image = '';
        if($thumb) {
            $image = '<a href="'.get_permalink().'"><img align="left" src="'. $thumb .'" width="200px" height="150px" /></a>';
            echo $image;
        }
        $content = $image . $content;
        return $content;
    }
endif;

if(!function_exists('tfuse_add_custom_post_types_in_feed')) :
    function tfuse_add_custom_post_types_in_feed($qv) {
        if (isset($qv['feed']))
            $qv['post_type'] = array('post', TF_SEEK_HELPER::get_post_type());
        return $qv;
    }
    add_filter('request', 'tfuse_add_custom_post_types_in_feed');
endif;

if(!function_exists('tfuse_seek_add_tags_to_terms')) {
    function tfuse_seek_add_tags_to_terms() {
        if(!get_option('tfuse_seek_add_tags_to_terms', false))
        {
            global $wpdb;
            $sql = 'SELECT post_id, _terms, _tags FROM ' . TF_SEEK_HELPER::get_db_table_name();
            $holidays = $wpdb->get_results($sql);
            $values = '';
            foreach($holidays as $holiday)
            {
                $values .= "(" . $holiday->post_id . ", '" . $holiday->_terms . $holiday->_tags . "'),";
            }
            $values = substr_replace($values ,"",-1);
            $sql = "INSERT INTO " . TF_SEEK_HELPER::get_db_table_name() . " (post_id, _terms) VALUES " . $values ." ON DUPLICATE KEY UPDATE post_id=VALUES(post_id),_terms=VALUES(_terms)";
            $set = $wpdb->query($sql);
            if($set){
                update_option('tfuse_seek_add_tags_to_terms', true);
            }else{
                update_option('tfuse_seek_add_tags_to_terms', 'error');
            }
        }
    }
    add_action('init', 'tfuse_seek_add_tags_to_terms');
}

if(!function_exists('tfuse_change_saved_content_tabs')){
    function tfuse_change_saved_content_tabs()
    {
        if(!get_option('tfuse_change_saved_content_tabs', false))
        {
            $query = new WP_Query(array(
                'post_type'         => TF_SEEK_HELPER::get_post_type(),
                'number_posts'      => -1,
                'posts_per_page'    => -1
            ));
            foreach($query->posts as $holiday)
            {
                $tabs = tfuse_page_options('content_tabs_table', array(), $holiday->ID);
                foreach($tabs as $key => $tab)
                {
                    if(isset($tab[0]))
                    {
                        $tab['tab_title'] = $tab[0];
                        unset($tab[0]);
                    }
                    if(isset($tab[1]))
                    {
                        $tab['tab_content'] = $tab[1];
                        unset($tab[1]);
                    }
                    $tabs[$key] = $tab;
                }
                tfuse_set_page_option('content_tabs_table', $tabs, $holiday->ID);
            }
            wp_reset_query();
            update_option('tfuse_change_saved_content_tabs', true);
        }
    }
    add_action('init', 'tfuse_change_saved_content_tabs');
	
	if ( !function_exists('tfuse_img_content')):

    function tfuse_img_content(){ 
        $content = $link = '';
		$args = array(
			'numberposts'     => -1,
		); 
        $posts_array = get_posts( $args );
        $option_name = 'thumbnail_image';
		foreach($posts_array as $post):
			$featured = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID));  
			if(tfuse_page_options('thumbnail_image',false,$post->ID)) continue;
			
			if(!empty($featured))
			{
				$value = $featured[0];
				tfuse_set_page_option($option_name, $value, $post->ID);
				tfuse_set_page_option('disable_image', true , $post->ID); 
			}
			else
			{
				$args = array(
				 'post_type' => 'attachment',
				 'numberposts' => -1,
				 'post_parent' => $post->ID
				); 
				$attachments = get_posts($args);
				if ($attachments) {
				 foreach ($attachments as $attachment) { 
								$value = $attachment->guid; 
								tfuse_set_page_option($option_name, $value, $post->ID);
								tfuse_set_page_option('disable_image', true , $post->ID); 
							 }
				}
				else
				{
					$content = $post->post_content;
						if(!empty($content))
						{   
							preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $content,$matches);
							if(!empty($matches))
							{
								$link = $matches[1]; 
								tfuse_set_page_option($option_name, $link , $post->ID);
								tfuse_set_page_option('disable_image', true , $post->ID);
							}
						}
				}
			}
                        
		endforeach;
			tfuse_set_option('enable_content_img',false, $cat_id = NULL);
    }
endif;

if ( tfuse_options('enable_content_img'))
{ 
    add_action('tfuse_head','tfuse_img_content');
}
}

if( !function_exists('tfuse_set_blog_page') ):
    function tfuse_set_blog_page(){
        global $wp_query, $is_tf_blog_page;
        $id_post = 0;
        $blog_page_id = tfuse_options('blog_page','');
        if(isset($wp_query->queried_object) && isset($wp_query->queried_object->ID)) {
            $id_post = $wp_query->queried_object->ID;
        }
        elseif(isset($wp_query->query['page_id'])) {
            $id_post = $wp_query->query['page_id'];
        }

        if(function_exists('icl_object_id')){
            $id_post = icl_object_id($id_post, 'page', false, 'en');
        }

        if($blog_page_id != 0 && $id_post == $blog_page_id) {
            $is_tf_blog_page = true;
        }
    }
    add_action('wp_head','tfuse_set_blog_page');
endif;


if(!function_exists('tfuse_header_search')){
    function tfuse_header_search(){
        global $is_tf_front_page, $is_tf_blog_page, $search, $TFUSE;
        if($is_tf_blog_page){
            if(tfuse_options('search_element_blog','none') !='none'){
                $search = tfuse_options('search_element_blog','main');
                get_template_part('before', 'content');
            }
        }
        elseif($is_tf_front_page){
            if(tfuse_options('homepage_category','') == 'page' && tfuse_options('use_page_options','') == true){
                if(tfuse_page_options('search_element','none') !='none'){
                    $search = tfuse_page_options('search_element','main');
                    get_template_part('before', 'content');
                }
            }
            else{
                if(tfuse_options('search_element','none') !='none'){
                    $search = tfuse_options('search_element','main');
                    get_template_part('before', 'content');
                }
            }
        }
        elseif(is_search()){
            if($TFUSE->request->isset_GET(TF_SEEK_HELPER::get_search_parameter('form_id'))){
                if(tfuse_options('search_element_search_seek','none') !='none'){
                    $search = tfuse_options('search_element_search_seek','main');
                    get_template_part('before', 'content');
                }
            }
            else{
                if(tfuse_options('search_element_search','none') !='none'){
                    $search = tfuse_options('search_element_search','main');
                    get_template_part('before', 'content');
                }
            }
        }
        elseif(is_404()){
            if(tfuse_options('search_element_404','none') !='none'){
                $search = tfuse_options('search_element_404','main');
                get_template_part('before', 'content');
            }
        }
        elseif(is_tag()){
            if(tfuse_options('search_element_tag','none') !='none'){
                $search = tfuse_options('search_element_tag','main');
                get_template_part('before', 'content');
            }
        }
        elseif(is_singular()){
            if(tfuse_page_options('search_element','none') !='none'){
                $search = tfuse_page_options('search_element','main');
                get_template_part('before', 'content');
            }
        }
        elseif(is_archive()){
            if(tfuse_options('search_element_archive','none') !='none'){
                $search = tfuse_options('search_element_archive','main');
                get_template_part('before', 'content');
            }
        }
    }
}


if(!function_exists('tfuse_service_image')){
    function tfuse_service_image($id){
        $src = tfuse_page_options('thumbnail_image','',$id);
        $dimensions = tfuse_page_options('thumbnail_dimensions','',$id);
        $image = new TF_GET_IMAGE();
        $tfuse_image = $image->width($dimensions[0])->height($dimensions[1])->src($src)->get_img();
        echo $tfuse_image;
    }
}

if(!function_exists('tfuse_get_term_id')){
    function tfuse_get_term_id(){
        $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
        $ID = $term->term_id;
        return $ID;
    }
}

if(!function_exists('tfuse_before_service')){
    function tfuse_before_service(){
        if(tfuse_options('icon_services',true,tfuse_get_term_id() ) ){ ?>
            <div class="middle_row row_gray thin_row">
                <div class="container clearfix">
                    <div class="car_types_list">
                        <ul>
                            <?php
                            $args = array(
                                'post_type' => 'service',
                                'posts_per_page' => -1,
                                'tax_query' => array(
                                    array(
                                        'taxonomy' => 'services',
                                        'field' => 'slug',
                                        'terms' => get_query_var('term')
                                    )
                                )
                            );
                            $query = new WP_Query( $args );
                            $count = 10;
                            foreach($query->posts as $service){ $count++; ?>
                                <?php if(tfuse_page_options('image1','',$service->ID)!=''){ ?>
                                    <li class="type_hover cart_type_<?php echo $count; ?>">
                                        <a href="#service_<?php echo $service->ID; ?>" class="front anchor"><strong><?php echo $service->post_title; ?></strong> <em><?php _e('View more','tfuse'); ?></em></a>
                                        <a href="#service_<?php echo $service->ID; ?>" class="back anchor"><strong><?php echo $service->post_title; ?></strong> <em><?php _e('View more','tfuse'); ?></em></a>
                                    </li>
                                    <style>
                                        .car_types_list li.cart_type_<?php echo $count; ?> .front {background-image:url(<?php echo tfuse_page_options('image1','',$service->ID); ?>)}
                                        .car_types_list li.cart_type_<?php echo $count; ?> .back {background-image:url(<?php echo tfuse_page_options('image2','',$service->ID); ?>)}
                                    </style>
                            <?php }
                            } ?>
                        </ul>
                    </div>
                    <script>
                        jQuery(document).ready(function($) {
                            $('.type_hover').hover(function(){
                                $(this).addClass('flip');
                            },function(){
                                $(this).removeClass('flip');
                            });
                        });
                    </script>

                </div>
            </div>
    <?php }
    }
}

if(!function_exists('tfuse_seek_pagination')){
    function tfuse_seek_pagination($num_pages){ ?>
        <?php if($num_pages>1){ ?>
            <div class="tf_pagination tf_seek_pagination">
                <div class="inner">
                    <a class="page_prev" href="#"><span></span><?php _e('PREV','tfuse'); ?></a>
                    <a class="page_next" href="#"><span></span><?php _e('NEXT','tfuse'); ?></a>
                    <?php global $TFUSE;
                    if (!$TFUSE->request->empty_GET('page')) {
                        $curent_page = (int)$TFUSE->request->GET('page');
                        if($curent_page==0)$curent_page = 1;
                    } else {
                        $curent_page = 1;
                    }

                    $stages = 1;
                    $paginate = '';
                    $lastpage = $num_pages;
                    $page = $curent_page;
                    // Pages
                    if ($lastpage < 7 + ($stages * 2))	// Not enough pages to breaking it up
                    {
                        for ($counter = 1; $counter <= $lastpage; $counter++)
                        {
                            if ($counter == $page){
                                $paginate.= "<span class='page-numbers page_current'>".$counter."</span>";
                            }else{
                                $paginate.= "<a class='page-numbers' href='#".$counter."'>$counter</a>";}
                        }
                    }
                    elseif($lastpage > 5 + ($stages * 2))	// Enough pages to hide a few?
                    {
                        // Beginning only hide later pages
                        if($page < 1 + ($stages * 2))
                        {
                            for ($counter = 1; $counter < 4 + ($stages * 2); $counter++)
                            {
                                if ($counter == $page){
                                    $paginate.= "<span class='page-numbers page_current'>".$counter."</span>";
                                }else{
                                    $paginate.= "<a class='page-numbers' href='#".$counter."'>$counter</a>";}
                            }
                            $paginate.= "...";
                            $paginate.= "<a class='page-numbers' href='#".($lastpage-1)."'>".($lastpage-1)."</a>";
                            $paginate.= "<a class='page-numbers' href='#".$lastpage."'>".$lastpage."</a>";
                        }
                        // Middle hide some front and some back
                        elseif($lastpage - ($stages * 2) > $page && $page > ($stages * 2))
                        {
                            $paginate.= "<a class='page-numbers' href='#1'>1</a>";
                            $paginate.= "...";
                            for ($counter = $page - $stages; $counter <= $page + $stages; $counter++)
                            {
                                if ($counter == $page){
                                    $paginate.= "<span class='current'>$counter</span>";
                                }else{
                                    $paginate.= "<a class='page-numbers' href='".$counter."'>$counter</a>";}
                            }
                            $paginate.= "...";
                            $paginate.= "<a class='page-numbers' href='#".($lastpage-1)."'>".($lastpage-1)."</a>";
                            $paginate.= "<a class='page-numbers' href='#".$lastpage."'>".$lastpage."</a>";
                        }
                        // End only hide early pages
                        else
                        {
                            $paginate.= "<a class='page-numbers' href='#1'>1</a>";
                            $paginate.= "<a class='page-numbers' href='#2'>2</a>";
                            $paginate.= "...";
                            for ($counter = $lastpage - (2 + ($stages * 2)); $counter <= $lastpage; $counter++)
                            {
                                if ($counter == $page){
                                    $paginate.= "<span class='page-numbers page_current'>".$counter."</span>";
                                }else{
                                    $paginate.= "<a class='page-numbers' href='#".$counter."'>$counter</a>";}
                            }
                        }
                    }
                    echo $paginate; ?>
                </div>
            </div>
    <?php }
    }
}

if(!function_exists('tfuse_search_number_pagination')){
    function tfuse_search_number_pagination($params = array()){ ?>
    <?php if($params['max_pages'] > 1){ ?>
        <div class="tf_pagination tf_search_pagination">
            <div class="inner">
                <?php if($params['curr_page']-1 < 1): ?>
                    <a class="page_prev" href="#" onclick="return false;"><span></span><?php _e('PREV','tfuse'); ?></a>
                <?php else: ?>
                    <a class="page_prev" href="<?php print(home_url('/').TF_SEEK_HELPER::get_qstring_without(array( TF_SEEK_HELPER::get_search_parameter('page') )).(TF_SEEK_HELPER::get_search_parameter('page')).'='.($params['curr_page']-1)); ?>" class="link_prev"><span></span><?php _e('PREV','tfuse'); ?></a>
                <?php endif; ?>
                <?php if($params['curr_page']+1 > $params['max_pages']): ?>
                    <a class="page_next" href="#" onclick="return false;"><span></span><?php _e('NEXT','tfuse'); ?></a>
                <?php else: ?>
                    <a class="page_next" href="<?php print(home_url('/').TF_SEEK_HELPER::get_qstring_without(array( TF_SEEK_HELPER::get_search_parameter('page') )).(TF_SEEK_HELPER::get_search_parameter('page')).'='.($params['curr_page']+1)); ?>" class="link_next"><span></span><?php _e('NEXT','tfuse'); ?></a>
                <?php endif; ?>

                <?php global $TFUSE;
                if (!$TFUSE->request->empty_GET('tfseekpage')) {
                    $curent_page = (int)$TFUSE->request->GET('tfseekpage');
                    if($curent_page==0) $curent_page = 1;
                } else {
                    $curent_page = 1;
                }
                $url =  $_SERVER["REQUEST_URI"];
                $pos = strpos($url, '&tfseekpage');
                if($pos != false){
                    $url = substr($url,0,$pos);
                }

                $stages = 1;
                $paginate = '';
                $lastpage = $params['max_pages'];
                $page = $curent_page;
                // Pages
                if ($lastpage < 7 + ($stages * 2))	// Not enough pages to breaking it up
                {
                    for ($counter = 1; $counter <= $lastpage; $counter++)
                    {
                        if ($counter == $page){
                            $paginate.= "<span class='page-numbers page_current'>".$counter."</span>";
                        }else{
                            $paginate.= "<a class='page-numbers' href='".$url."&tfseekpage=".$counter."'>$counter</a>";}
                    }
                }
                elseif($lastpage > 5 + ($stages * 2))	// Enough pages to hide a few?
                {
                    // Beginning only hide later pages
                    if($page < 1 + ($stages * 2))
                    {
                        for ($counter = 1; $counter < 4 + ($stages * 2); $counter++)
                        {
                            if ($counter == $page){
                                $paginate.= "<span class='page-numbers page_current'>".$counter."</span>";
                            }else{
                                $paginate.= "<a class='page-numbers' href='".$url."&tfseekpage=".$counter."'>$counter</a>";}
                        }
                        $paginate.= "...";
                        $paginate.= "<a class='page-numbers' href='".$url."&tfseekpage=".($lastpage-1)."'>".($lastpage-1)."</a>";
                        $paginate.= "<a class='page-numbers' href='".$url."&tfseekpage=".$lastpage."'>".$lastpage."</a>";
                    }
                    // Middle hide some front and some back
                    elseif($lastpage - ($stages * 2) > $page && $page > ($stages * 2))
                    {
                        $paginate.= "<a class='page-numbers' href='".$url."&tfseekpage=1'>1</a>";
                        $paginate.= "...";
                        for ($counter = $page - $stages; $counter <= $page + $stages; $counter++)
                        {
                            if ($counter == $page){
                                $paginate.= "<span class='current'>$counter</span>";
                            }else{
                                $paginate.= "<a class='page-numbers' href='".$url."&tfseekpage=".$counter."'>$counter</a>";}
                        }
                        $paginate.= "...";
                        $paginate.= "<a class='page-numbers' href='".$url."&tfseekpage=".($lastpage-1)."'>".($lastpage-1)."</a>";
                        $paginate.= "<a class='page-numbers' href='".$url."&tfseekpage=".$lastpage."'>".$lastpage."</a>";
                    }
                    // End only hide early pages
                    else
                    {
                        $paginate.= "<a class='page-numbers' href='".$url."&tfseekpage=1'>1</a>";
                        $paginate.= "<a class='page-numbers' href='".$url."&tfseekpage=2'>2</a>";
                        $paginate.= "...";
                        for ($counter = $lastpage - (2 + ($stages * 2)); $counter <= $lastpage; $counter++)
                        {
                            if ($counter == $page){
                                $paginate.= "<span class='page-numbers page_current'>".$counter."</span>";
                            }else{
                                $paginate.= "<a class='page-numbers' href='".$url."&tfseekpage=".$counter."'>$counter</a>";}
                        }
                    }
                }
                echo $paginate; ?>
            </div>
        </div>
        <?php }
    }
}

if(!function_exists('tfuse_get_text_box_vehicles')){
    function tfuse_get_text_box_vehicles(){
        $html = '';
        $text = tfuse_qtranslate(TF_SEEK_HELPER::get_option('seek_property_text_box',''));
        $email_subject = TF_SEEK_HELPER::get_option('seek_property_subject_email','');
        $email_content = TF_SEEK_HELPER::get_option('seek_property_content_email','');
        $text = str_replace('%%email_content%%',$email_content,$text);
        $text = str_replace('%%email_subject%%',$email_subject,$text);
        $link = get_permalink();
        $text = str_replace('%%link%%',$link,$text);
        if($text != ''){
            $html = '<div class="text_box">' .$text. '</div>';
        }
        return $html;
    }
}

if(!function_exists('tfuse_print_theme_color_style')){
    function tfuse_print_theme_color_style(){
        $color1 = tfuse_options('color1','');
        $color2 = tfuse_options('color2','');

        if(isset($_GET['color1']) && $_GET['color1']!='')
            $color1 = '#'.$_GET['color1'];

        if(isset($_GET['color2']) && $_GET['color2']!='')
            $color2 = '#'.$_GET['color2'];

        if($color1 != ''){
            echo '<style>
                h1 span, h2 span, h3 span ,h4 span, h5 span, h6 span,
                .header_title h1 span, .header_title strong span,
                .entry .price_col_foot a,
                .week_offer .link_more a:hover, .special_offers .link_more a:hover,
                .car_types_list .link_more,
                .breadcrumbs p a:hover,
                .postlist .post-item .link_more,
                .postlist .post-title h2 a:hover,
                .entry a:hover,
                .pages_jump .inputSmall:focus,
                .pages .manage_title strong,
                .offer_details .offer_price strong,
                .details_tabs .tabcontent h3,
                .details_form .label_title,
                a:hover, a:focus,
                .btn_white span strong,
                .week_offer .offer_price,
                .special_text h3 a:hover,
                .special_price,
                .service_item h2 a:hover,
                .service_descr h3,
                .brand_list2 li:hover a,
                .content .widget_featured_posts .post-descr a,
                .sidebar .widget-container .current-menu-item a,
                .sidebar .widget-container .current-menu-ancestor a,
                .sidebar .widget-container .current-menu-item li a:hover,
                .sidebar .widget-container .current-menu-ancestor li a:hover,
                .sidebar .widget-container .current-menu-ancestor .current-menu-item a,
                .sidebar .widget_archive li a:hover,
                .widget_featured_posts .post-title a:hover,
                .widget_calendar table a:hover,
                .widget_tag_cloud .tagcloud a,
                .post-item .post-meta a:hover,
                .entry .post-item .post-meta a:hover,
                .post-share,
                a.link-author:hover, .link-reply:hover,
                .offer_list .offer_item h2 a:hover, .offer_list .offer_price,
                .text_box p a:hover,
                .quote-author, .quote-author span, .testimonials .quote-author,
                .sidebar .quote-author, .sidebar .quote-author span,
                .copyright a,
                .faq_q, .faq_question.active,
                .brand_list .link_more:hover,
                .sidebar .widget-container li a:hover,
                .content .widget-container li a:hover,
                .widget-container.widget_nav_menu a:hover,
                .widget-container.widget_nav_menu .current-menu-item li a:hover,
                .widget-container.widget_categories a:hover,
                .widget-container.widget_categories .current-menu-item li a:hover,
                .widget-container.widget_archive a:hover,
                .widget-container.widget_links a:hover,
                .widget-container.widget_meta a:hover,
                .widget-container.widget_pages a:hover,
                .widget_calendar #today,
                .sidebar .contact-address .mail a:hover,
                .post_list li a:hover,
                .widget_recent_posts .post-meta a, .widget_popular_posts .post-meta a,
                .widget_recent_posts .post-meta .link-comments, .widget_popular_posts .post-meta .link-comments,
                .widget_recent_posts .post-meta a:hover, .widget_popular_posts .post-meta a:hover,
                .widget_recent_posts ul li .post-title:hover, .widget_popular_posts ul li .post-title:hover,
                .content .widget_recent_entries .post-meta a, .sidebar .widget_recent_entries .post-meta a,
                .dropdown .mega-nav .widget_featured_posts .post-author,
                .info_price, .featured_caption p span,
                #adv_search_open,
                .sidebar .widget_twitter .tweet_time,
                .author-name, .link-author,
                .widget_login .forget_password a:hover {
                    color:'.$color1.'}

                .dropdown .mega-nav .link-more,
                .dropdown .mega-nav .more-nav a,
                .dropdown .mega-nav .post-more a,
                .tp-caption.subtitle span,
                .btn_save, .btn_send, .rowSubmit .btn_send,
                .tf_pagination .page_prev, .tf_pagination .page_next {
                    color:'.$color1.' !important}

                .widget_tag_cloud .tagcloud a:hover,
                .tf_pagination .page_prev:hover,
                .tf_pagination .page_next:hover,
                .btn_send:hover, .btn_save:hover{
                    background-color:'.$color1.'}

                .btn_default, .btn_search,
                .btn-submit, input.btn-submit, .comment-form .btn-submit,
                .widget_search input.btn-submit,
                .btn_send:hover, .btn_save:hover,
                .tf_pagination .page_prev:hover, .tf_pagination .page_next:hover {
                    background-color:'.$color1.';
                    border-color:'.$color1.';
                    color:#fff !important}
            </style>';
        }
        if($color2 != ''){
            echo '<style>
                a, a:visited,
                .dropdown .menu-level-0.last a,
                .dropdown .menu-level-0.last:hover a,
                .dropdown li:hover a,
                .dropdown li ul li:hover a,
                .dropdown li:hover li ul li:hover a,
                .dropdown .current-menu-ancestor .current-menu-item a,
                .dropdown .current-menu-ancestor .current-menu-ancestor a,
                .dropdown .current-menu-ancestor .current-menu-ancestor .current-menu-item a,
                .dropdown .mega-nav ul li:hover ul li:hover a,
                .dropdown .mega-nav .widget_twitter .tweet_item a,
                .dropdown .mega-nav .widget_recent_entries li a:hover,
                .dropdown .mega-nav .widget_featured_posts li .post-title a:hover,
                .dropdown .mega-nav .widget_twitter .tweet_item a:hover,
                .info_line,
                .footer_address .notice,
                .testimonials .quote-text a,
                .week_offer .offer_text h3 a,
                .special_text h3 a,
                .latest_offers .link_more,
                .brand_list .link_more, .brand_list2 li a,
                .breadcrumbs .link_search, .breadcrumbs .link_back,
                .link-news-rss span,
                .sidebar .widget_archive li a,
                .widget_twitter .tweet_item a,
                .widget_calendar table a,
                .widget_recent_posts ul li .post-title,
                .widget_popular_posts ul li .post-title,
                .content .link-arrow,
                .widget_login .forget_password a,
                .post-item .post-meta .info_row,
                .entry a, .text_box p a,
                .tf_pagination .page-numbers:hover, .tf_pagination .page_current,
                .author-text .author-title,
                .link-add-comment, .link-reset,
                .offer_list .offer_item h2 a,
                .offer_specification li .spec_value,
                form .cusel span:hover, form .cusel .cuselOptHover, form .cusel .cuselActive, form .cusel .cuselActive:hover  {
                    color:'.$color2.'}

                ::-moz-selection {background:'.$color2.'}
                ::selection {background:'.$color2.'}

                .tabs_framed .tabs,
                .car_types_list li .front,
                .car_types_list li .back,
                .details_tabs .tabs li a:hover {background-color:'.$color2.';}

                .car_types_list li.flip a strong {
                    color:'.$color2.'}
                .offer_specification li .spec_value a, .offer_specification li .spec_value a:hover {
                    color:'.$color2.'}

                .car_types_list li a em {
                    color:#333}
            </style>';
        }
    }
}

if (!function_exists('tfuse_user_has_gravatar')){
    function tfuse_user_has_gravatar( $email_address ) {
        // Build the Gravatar URL by hasing the email address
        $url = 'https://www.gravatar.com/avatar/' . md5( strtolower( trim ( $email_address ) ) ) . '?d=404';
        // Now check the headers
        $headers = @get_headers( $url );
        // If 200 is found, the user has a Gravatar; otherwise, they don't.
        return preg_match( '|200|', $headers[0] ) ? true : false;
    }
}


if (!function_exists('tfuse_filter_get_avatar')){
    function tfuse_filter_get_avatar($avatar, $id_or_email, $size, $default, $alt){
        $avatar_src = tfuse_options('default_avatar', false);
        if(empty($avatar_src)) {
            return $avatar;
        }

        $email = '';
        if ( is_numeric($id_or_email) ) {
            $id = (int) $id_or_email;
            $user = get_userdata($id);
            if ( $user )
                $email = $user->user_email;
        } elseif ( is_object($id_or_email) ) {
            // No avatar for pingbacks or trackbacks
            $allowed_comment_types = apply_filters( 'get_avatar_comment_types', array( 'comment' ) );
            if ( ! empty( $id_or_email->comment_type ) && ! in_array( $id_or_email->comment_type, (array) $allowed_comment_types ) )
                return false;

            if ( !empty($id_or_email->user_id) ) {
                $id = (int) $id_or_email->user_id;
                $user = get_userdata($id);
                if ( $user)
                    $email = $user->user_email;
            } elseif ( !empty($id_or_email->comment_author_email) ) {
                $email = $id_or_email->comment_author_email;
            }
        } else {
            $email = $id_or_email;
        }

        if( !tfuse_user_has_gravatar($email) ) {
            $avatar = "<img alt='' src='".TF_GET_IMAGE::get_src_link($avatar_src, $size, $size)."' class='avatar avatar-".$size." photo avatar-default' height='".$size."' width='".$size."' />";
        }

        return $avatar;
    }
    add_filter('get_avatar', 'tfuse_filter_get_avatar',10,5);
}

//Top Ad
if (!function_exists('tfuse_top_adds')) :
    function tfuse_top_adds() {
        global $post,$is_tf_blog_page;
        if($is_tf_blog_page)
        {
            if(tfuse_options('blog_top_ad_space') == 'true')
            {
                if(tfuse_options('blog_top_ad_space')=='true'&&!tfuse_options('blog_top_ad_image')&&!tfuse_options('blog_top_ad_adsense')){
                    echo '
                        <!-- adv before head -->
                            <div class="adv_head">
                                <div class="adv_728"><img src="'.tfuse_get_file_uri('/images/adv_728x90.png').'" width="728" height="90" alt="advert"></div>
                            </div>
                        <!-- adv before head -->';
                }
                elseif(tfuse_options('blog_top_ad_adsense')&&!tfuse_options('blog_top_ad_image')||tfuse_options('blog_top_ad_adsense')&&tfuse_options('blog_top_ad_image'))
                {
                    echo  '<!-- adv before head -->'.tfuse_options('blog_top_ad_adsense').' <!-- adv before head -->';
                }
                elseif(tfuse_options('blog_top_ad_image')&&!tfuse_options('blog_top_ad_adsense'))
                {
                    echo  '
                    <!-- adv before head -->
                    <div class="adv_head">
                        <div class="adv_728"><a href="'.tfuse_options('blog_top_ad_url').'"  target="_blank"><img src="'.tfuse_options('blog_top_ad_image').'" width="728" height="90" alt="advert"></a></div>
                    </div>
                    <!-- adv before head -->
                    ';
                }
                else
                {
                    echo '';
                }
            }
            elseif(!tfuse_options('blog_top_ad_space') && !tfuse_options('top_ads_space'))
            {
                if(!tfuse_options('blog_top_ads_space')&&!tfuse_options('blog_top_ads_image')&&!tfuse_options('blog_top_ads_adsense'))
                {
                    echo '
                        <!-- adv before head -->
                            <div class="adv_head">
                                <div class="adv_728"><img src="'.tfuse_get_file_uri('/images/adv_728x90.png').'" width="728" height="90" alt="advert"></div>
                            </div>
                        <!-- adv before head -->';
                }
                elseif(tfuse_options('blog_top_ads_adsense')&&!tfuse_options('blog_top_ads_image')||tfuse_options('blog_top_ads_adsense')&& tfuse_options('blog_top_ads_image'))
                {
                    echo  '<!-- adv before head -->'.tfuse_options('blog_top_ads_adsense').' <!-- adv before head -->';
                }
                elseif(tfuse_options('blog_top_ads_image')&&!tfuse_options('blog_top_ads_adsense'))
                {
                    echo  '
                    <!-- adv before head -->
                    <div class="adv_head">
                        <div class="adv_728"><a href="'.tfuse_options('blog_top_ads_url').'"  target="_blank"><img src="'.tfuse_options('blog_top_ads_image').'" width="728" height="90" alt="advert"></a></div>
                    </div>
                    <!-- adv before head -->
                    ';
                }
                else
                {
                    echo '';
                }
            }
        }
        elseif(is_front_page())
        {
            if(tfuse_options('use_page_options') && tfuse_options('homepage_category')=='page'){
                $page_id = $post->ID;
                if(tfuse_page_options('top_ad_space','',$page_id) == 'true')
                {
                    if(tfuse_page_options('top_ad_space','',$page_id)=='true'&&!tfuse_page_options('top_ad_image','',$page_id)&&!tfuse_page_options('top_ad_adsense','',$page_id)){
                        echo '
                        <!-- adv before head -->
                            <div class="adv_head">
                                <div class="adv_728"><img src="'.tfuse_get_file_uri('/images/adv_728x90.png').'" width="728" height="90" alt="advert"></div>
                            </div>
                        <!-- adv before head -->';
                    }
                    elseif(tfuse_page_options('top_ad_adsense','',$page_id)&&!tfuse_page_options('top_ad_image','',$page_id)||tfuse_page_options('top_ad_adsense','',$page_id)&&tfuse_page_options('top_ad_image','',$page_id))
                    {
                        echo  '<!-- adv before head -->'.tfuse_page_options('top_ad_adsense').' <!-- adv before head -->';
                    }
                    elseif(tfuse_page_options('top_ad_image','',$page_id)&&!tfuse_page_options('top_ad_adsense','',$page_id))
                    {
                        echo  '
                    <!-- adv before head -->
                    <div class="adv_head">
                        <div class="adv_728"><a href="'.tfuse_page_options('top_ad_url','',$page_id).'"  target="_blank"><img src="'.tfuse_page_options('top_ad_image','',$page_id).'" width="728" height="90" alt="advert"></a></div>
                    </div>
                    <!-- adv before head -->
                    ';
                    }
                    else
                    {
                        echo '';
                    }
                }
            }
            elseif(tfuse_options('home_top_ad_space') == 'true')
            {
                if(tfuse_options('home_top_ad_space')=='true'&&!tfuse_options('home_top_ad_image')&&!tfuse_options('home_top_ad_adsense')){
                    echo '
                        <!-- adv before head -->
                            <div class="adv_head">
                                <div class="adv_728"><img src="'.tfuse_get_file_uri('/images/adv_728x90.png').'" width="728" height="90" alt="advert"></div>
                            </div>
                        <!-- adv before head -->';
                }
                elseif(tfuse_options('home_top_ad_adsense')&&!tfuse_options('home_top_ad_image')||tfuse_options('home_top_ad_adsense')&&tfuse_options('home_top_ad_image'))
                {
                    echo  '<!-- adv before head -->'.tfuse_options('home_top_ad_adsense').' <!-- adv before head -->';
                }
                elseif(tfuse_options('home_top_ad_image')&&!tfuse_options('home_top_ad_adsense'))
                {
                    echo  '
                    <!-- adv before head -->
                    <div class="adv_head">
                        <div class="adv_728"><a href="'.tfuse_options('home_top_ad_url').'"  target="_blank"><img src="'.tfuse_options('home_top_ad_image').'" width="728" height="90" alt="advert"></a></div>
                    </div>
                    <!-- adv before head -->
                    ';
                }
                else
                {
                    echo '';
                }
            }
            elseif(!tfuse_options('home_top_ad_space') && !tfuse_options('top_ads_space'))
            {
                if(!tfuse_options('top_ads_space')&&!tfuse_options('top_ads_image')&&!tfuse_options('top_ads_adsense'))
                {
                    echo '
                        <!-- adv before head -->
                            <div class="adv_head">
                                <div class="adv_728"><img src="'.tfuse_get_file_uri('/images/adv_728x90.png').'" width="728" height="90" alt="advert"></div>
                            </div>
                        <!-- adv before head -->';
                }
                elseif(tfuse_options('top_ads_adsense')&&!tfuse_options('top_ads_image')||tfuse_options('top_ads_adsense')&&tfuse_options('top_ads_image'))
                {
                    echo  '<!-- adv before head -->'.tfuse_options('top_ads_adsense').' <!-- adv before head -->';
                }
                elseif(tfuse_options('top_ads_image')&&!tfuse_options('top_ads_adsense'))
                {
                    echo  '
                    <!-- adv before head -->
                    <div class="adv_head">
                        <div class="adv_728"><a href="'.tfuse_options('top_ads_url').'"  target="_blank"><img src="'.tfuse_options('top_ads_image').'" width="728" height="90" alt="advert"></a></div>
                    </div>
                    <!-- adv before head -->
                    ';
                }
                else
                {
                    echo '';
                }
            }
        }
        elseif(is_page())
        {
            if(tfuse_page_options('top_ad_space') == 'true')
            {
                if(tfuse_page_options('top_ad_space')=='true'&&!tfuse_page_options('top_ad_image')&&!tfuse_page_options('top_ad_adsense')){
                    echo '
                        <!-- adv before head -->
                            <div class="adv_head">
                                <div class="adv_728"><img src="'.tfuse_get_file_uri('/images/adv_728x90.png').'" width="728" height="90" alt="advert"></div>
                            </div><div class="clear"></div>
                        <!-- adv before head -->';
                }
                elseif(tfuse_page_options('top_ad_adsense')&&!tfuse_page_options('top_ad_image')||tfuse_page_options('top_ad_adsense')&&tfuse_page_options('top_ad_image'))
                {
                    echo  '<!-- adv before head -->'.tfuse_page_options('top_ad_adsense').' <!-- adv before head -->';
                }
                elseif(tfuse_page_options('top_ad_image')&&!tfuse_page_options('top_ad_adsense'))
                {
                    echo  '
                    <!-- adv before head -->
                    <div class="adv_head">
                        <div class="adv_728"><a href="'.tfuse_page_options('top_ad_url').'"  target="_blank"><img src="'.tfuse_page_options('top_ad_image').'" width="728" height="90" alt="advert"></a></div>
                    </div>
                    <!-- adv before head -->
                    ';
                }
                else
                {
                    echo '';
                }
            }
            elseif(!tfuse_page_options('top_ad_space') && !tfuse_options('top_ads_space'))
            {
                if(!tfuse_options('top_ads_space')&&!tfuse_options('top_ads_image')&&!tfuse_options('top_ads_adsense'))
                {
                    echo '
                        <!-- adv before head -->
                            <div class="adv_head">
                                <div class="adv_728"><img src="'.tfuse_get_file_uri('/images/adv_728x90.png').'" width="728" height="90" alt="advert"></div>
                            </div>
                        <!-- adv before head -->';
                }
                elseif(tfuse_options('top_ads_adsense')&&!tfuse_options('top_ads_image')||tfuse_options('top_ads_adsense')&&tfuse_options('top_ads_image'))
                {
                    echo  '<!-- adv before head -->'.tfuse_options('top_ads_adsense').' <!-- adv before head -->';
                }
                elseif(tfuse_options('top_ads_image')&&!tfuse_options('top_ads_adsense'))
                {
                    echo  '
                    <!-- adv before head -->
                    <div class="adv_head">
                        <div class="adv_728"><a href="'.tfuse_options('top_ads_url').'"  target="_blank"><img src="'.tfuse_options('top_ads_image').'" width="728" height="90" alt="advert"></a></div>
                    </div>
                    <!-- adv before head -->
                    ';
                }
                else
                {
                    echo '';
                }
            }
        }
        elseif(is_single() && !is_page())
        {
            $cat_name = get_the_category();
            $post = get_post($post->ID);
            $post_type = $post->post_type;
            $taxonomies = get_object_taxonomies($post_type);
            if(!empty($taxonomies))
            {
                if($taxonomies[0] == 'category') {
                    $cat_id = $cat_name[0]->cat_ID;
                }
                elseif($taxonomies[0] == 'group')
                {
                    $terms = wp_get_post_terms($post->ID,'group');
                    $cat_id = $terms[0]->term_id;
                }
            }
            if(!tfuse_page_options('content_ads_post'))
            {
                if(tfuse_page_options('top_ad_space') == 'true')
                {
                    if(tfuse_page_options('top_ad_space')=='true'&&!tfuse_page_options('top_ad_image')&&!tfuse_page_options('top_ad_adsense')){
                        echo '
                            <!-- adv before head -->
                                <div class="adv_head">
                                    <div class="adv_728"><img src="'.tfuse_get_file_uri('/images/adv_728x90.png').'" width="728" height="90" alt="advert"></div>
                                </div>
                            <!-- adv before head -->';
                    }
                    elseif(tfuse_page_options('top_ad_adsense')&&!tfuse_page_options('top_ad_image')||tfuse_page_options('top_ad_adsense')&&tfuse_page_options('top_ad_image'))
                    {
                        echo  '<!-- adv before head -->'.tfuse_page_options('top_ad_adsense').' <!-- adv before head -->';
                    }
                    elseif(tfuse_page_options('top_ad_image')&&!tfuse_page_options('top_ad_adsense'))
                    {
                        echo  '
                        <!-- adv before head -->
                        <div class="adv_head">
                            <div class="adv_728"><a href="'.tfuse_page_options('top_ad_url').'"  target="_blank"><img src="'.tfuse_page_options('top_ad_image').'" width="728" height="90" alt="advert"></a></div>
                        </div>
                        <!-- adv before head -->
                        ';
                    }
                    else
                    {
                        echo '';
                    }
                }
            }
            elseif(tfuse_page_options('content_ads_post') && tfuse_options('top_ad_space',null,$cat_id))
            {
                if(tfuse_options('top_ad_space',null,$cat_id)=='true'&&!tfuse_options('top_ad_image',null,$cat_id)&&!tfuse_options('top_ad_adsense',null,$cat_id))
                {
                    echo '
                        <!-- adv before head -->
                            <div class="adv_head">
                                <div class="adv_728"><img src="'.tfuse_get_file_uri('/images/adv_728x90.png').'" width="728" height="90" alt="advert"></div>
                            </div>
                        <!-- adv before head -->';
                }
                elseif(tfuse_options('top_ad_adsense',null,$cat_id)&&!tfuse_options('top_ad_image',null,$cat_id)||tfuse_options('top_ad_adsense',null,$cat_id)&&tfuse_options('top_ad_image',null,$cat_id))
                {
                    echo  '<!-- adv before head -->'.tfuse_options('top_ad_adsense',null,$cat_id).' <!-- adv before head -->';
                }
                elseif(tfuse_options('top_ad_image',null,$cat_id)&&!tfuse_options('top_ad_adsense',null,$cat_id))
                {
                    echo  '
                    <!-- adv before head -->
                    <div class="adv_head">
                        <div class="adv_728"><a href="'.tfuse_options('top_ad_url',null,$cat_id).'"  target="_blank"><img src="'.tfuse_options('top_ad_image',null,$cat_id).'" width="728" height="90" alt="advert"></a></div>
                    </div>
                    <!-- adv before head -->
                    ';
                }
                else
                {
                    echo '';
                }
            }
            elseif(!tfuse_options('top_ad_space',null,$cat_id) && !tfuse_options('top_ads_space'))
            {
                if(!tfuse_options('top_ads_space')&&!tfuse_options('top_ads_image')&&!tfuse_options('top_ads_adsense'))
                {
                    echo '
                        <!-- adv before head -->
                            <div class="adv_head">
                                <div class="adv_728"><img src="'.tfuse_get_file_uri('/images/adv_728x90.png').'" width="728" height="90" alt="advert"></div>
                            </div>
                        <!-- adv before head -->';
                }
                elseif(tfuse_options('top_ads_adsense')&&!tfuse_options('top_ads_image')||tfuse_options('top_ads_adsense')&&tfuse_options('top_ads_image'))
                {
                    echo  '<!-- adv before head -->'.tfuse_options('top_ads_adsense').' <!-- adv before head -->';
                }
                elseif(tfuse_options('top_ads_image')&&!tfuse_options('top_ads_adsense'))
                {
                    echo  '
                    <!-- adv before head -->
                    <div class="adv_head">
                        <div class="adv_728"><a href="'.tfuse_options('top_ads_url').'"  target="_blank"><img src="'.tfuse_options('top_ads_image').'" width="728" height="90" alt="advert"></a></div>
                    </div>
                    <!-- adv before head -->
                    ';
                }
                else
                {
                    echo '';
                }
            }
        }
        elseif(is_tax())
        {
            $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy') );
            $cat_id = $term->term_id;
            if(tfuse_options('top_ad_space',null,$cat_id))
            {
                if(tfuse_options('top_ad_space',null,$cat_id)=='true'&&!tfuse_options('top_ad_image',null,$cat_id)&&!tfuse_options('top_ad_adsense',null,$cat_id))
                {
                    echo '
                        <!-- adv before head -->
                            <div class="adv_head">
                                <div class="adv_728"><img src="'.tfuse_get_file_uri('/images/adv_728x90.png').'" width="728" height="90" alt="advert"></div>
                            </div>
                        <!-- adv before head -->';
                }
                elseif(tfuse_options('top_ad_adsense',null,$cat_id)&&!tfuse_options('top_ad_image',null,$cat_id)||tfuse_options('top_ad_adsense',null,$cat_id)&&tfuse_options('top_ad_image',null,$cat_id))
                {
                    echo  '<!-- adv before head -->'.tfuse_options('top_ad_adsense',null,$cat_id).' <!-- adv before head -->';
                }
                elseif(tfuse_options('top_ad_image',null,$cat_id)&&!tfuse_options('top_ad_adsense',null,$cat_id))
                {
                    echo  '
                    <!-- adv before head -->
                    <div class="adv_head">
                        <div class="adv_728"><a href="'.tfuse_options('top_ad_url',null,$cat_id).'"  target="_blank"><img src="'.tfuse_options('top_ad_image',null,$cat_id).'" width="728" height="90" alt="advert"></a></div>
                    </div>
                    <!-- adv before head -->
                    ';
                }
                else
                {
                    echo '';
                }
            }
            elseif(!tfuse_options('top_ad_space',null,$cat_id) && !tfuse_options('top_ads_space'))
            {
                if(!tfuse_options('top_ads_space')&&!tfuse_options('top_ads_image')&&!tfuse_options('top_ads_adsense'))
                {
                    echo '
                        <!-- adv before head -->
                            <div class="adv_head">
                                <div class="adv_728"><img src="'.tfuse_get_file_uri('/images/adv_728x90.png').'" width="728" height="90" alt="advert"></div>
                            </div>
                        <!-- adv before head -->';
                }
                elseif(tfuse_options('top_ads_adsense')&&!tfuse_options('top_ads_image')||tfuse_options('top_ads_adsense')&&tfuse_options('top_ads_image'))
                {
                    echo  '<!-- adv before head -->'.tfuse_options('top_ads_adsense').' <!-- adv before head -->';
                }
                elseif(tfuse_options('top_ads_image')&&!tfuse_options('top_ads_adsense'))
                {
                    echo  '
                    <!-- adv before head -->
                    <div class="adv_head">
                        <div class="adv_728"><a href="'.tfuse_options('top_ads_url').'"  target="_blank"><img src="'.tfuse_options('top_ads_image').'" width="728" height="90" alt="advert"></a></div>
                    </div>
                    <!-- adv before head -->
                    ';
                }
                else
                {
                    echo '';
                }
            }
        }
        elseif(is_category())
        {
            $cat_id = get_query_var('cat');
            if(tfuse_options('top_ad_space',null,$cat_id))
            {
                if(tfuse_options('top_ad_space',null,$cat_id)=='true'&&!tfuse_options('top_ad_image',null,$cat_id)&&!tfuse_options('top_ad_adsense',null,$cat_id))
                {
                    echo '
                        <!-- adv before head -->
                            <div class="adv_head">
                                <div class="adv_728"><img src="'.tfuse_get_file_uri('/images/adv_728x90.png').'" width="728" height="90" alt="advert"></div>
                            </div>
                        <!-- adv before head -->';
                }
                elseif(tfuse_options('top_ad_adsense',null,$cat_id)&&!tfuse_options('top_ad_image',null,$cat_id)||tfuse_options('top_ad_adsense',null,$cat_id)&&tfuse_options('top_ad_image',null,$cat_id))
                {
                    echo  '<!-- adv before head -->'.tfuse_options('top_ad_adsense',null,$cat_id).' <!-- adv before head -->';
                }
                elseif(tfuse_options('top_ad_image',null,$cat_id)&&!tfuse_options('top_ad_adsense',null,$cat_id))
                {
                    echo  '
                    <!-- adv before head -->
                    <div class="adv_head">
                        <div class="adv_728"><a href="'.tfuse_options('top_ad_url',null,$cat_id).'"  target="_blank"><img src="'.tfuse_options('top_ad_image',null,$cat_id).'" width="728" height="90" alt="advert"></a></div>
                    </div>
                    <!-- adv before head -->
                    ';
                }
                else
                {
                    echo '';
                }
            }
            elseif(!tfuse_options('top_ad_space',null,$cat_id) && !tfuse_options('top_ads_space'))
            {
                if(!tfuse_options('top_ads_space')&&!tfuse_options('top_ads_image')&&!tfuse_options('top_ads_adsense'))
                {
                    echo '
                        <!-- adv before head -->
                            <div class="adv_head">
                                <div class="adv_728"><img src="'.tfuse_get_file_uri('/images/adv_728x90.png').'" width="728" height="90" alt="advert"></div>
                            </div>
                        <!-- adv before head -->';
                }
                elseif(tfuse_options('top_ads_adsense')&&!tfuse_options('top_ads_image')||tfuse_options('top_ads_adsense')&&tfuse_options('top_ads_image'))
                {
                    echo  '<!-- adv before head -->'.tfuse_options('top_ads_adsense').' <!-- adv before head -->';
                }
                elseif(tfuse_options('top_ads_image')&&!tfuse_options('top_ads_adsense'))
                {
                    echo  '
                    <!-- adv before head -->
                    <div class="adv_head">
                        <div class="adv_728"><a href="'.tfuse_options('top_ads_url').'"  target="_blank"><img src="'.tfuse_options('top_ads_image').'" width="728" height="90" alt="advert"></a></div>
                    </div>
                    <!-- adv before head -->
                    ';
                }
                else
                {
                    echo '';
                }
            }
        }
    }
endif;

//ads for category
if (!function_exists('tfuse_category_ads')) :
    function tfuse_category_ads() {
        global $post,$is_tf_blog_page;
        if($is_tf_blog_page)
        {
            if(tfuse_options('blog_bfcontent_ads_space'))
            {
                if(tfuse_options('blog_bfcontent_number') == 'one' )
                {
                    $adds1 = tfuse_options('blog_bfcontent_ads_image1');
                    if(tfuse_options('blog_bfcontent_ads_space')=='1'&&!$adds1&&!tfuse_options('blog_bfcontent_ads_adsense1')){
                        echo  '
                                <!-- adv before content -->
                                        <div class="adv_before_content">
                                                <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                        </div>
                                <!--/ adv before content -->';
                    }
                    elseif(tfuse_options('blog_bfcontent_ads_adsense1'))
                    {
                        echo '<div class="adv_before_content">
                        <div class="adv_125">'.tfuse_options('blog_bfcontent_ads_adsense1').'</div>
                        </div>';
                    }
                    elseif($adds1)
                    {
                        echo '
                            <!-- adv before content -->
                            <div class="adv_before_content">
                                    <div class="adv_125"><a href="'.tfuse_options('blog_bfcontent_ads_url1').'"  target="_blank"><img src="'.$adds1.'" width="125" height="125" alt="advert"></a></div>
                            </div>
                            <!--/ adv before content -->
                            ';
                    }
                    else
                    {
                        echo '';
                    }
                }
                elseif(tfuse_options('blog_bfcontent_number') == 'two' )
                {
                    $adds1 = tfuse_options('blog_bfcontent_ads_image1');
                    $adds2 = tfuse_options('blog_bfcontent_ads_image2');
                    $adsense1 = tfuse_options('blog_bfcontent_ads_adsense1');
                    $adsense2 = tfuse_options('blog_bfcontent_ads_adsense2');
                    if(tfuse_options('blog_bfcontent_ads_space')=='1'&&!$adds1&&!$adsense1 &&!$adds2&&!$adsense2){
                        echo  '
                                <!-- adv before content -->
                                        <div class="adv_before_content">
                                                <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                                <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                        </div>
                                <!--/ adv before content -->';
                    }
                    elseif($adsense1 || $adsense2)
                    {
                        echo '<div class="adv_before_content">
                        <div class="adv_125">'.$adsense1.'</div>
                        <div class="adv_125">'.$adsense2.'</div>
                        </div>';
                    }
                    elseif($adds1 || $adds2 )
                    {
                        echo '
                            <!-- adv before content -->
                            <div class="adv_before_content">
                                    <div class="adv_125"><a href="'.tfuse_options('blog_bfcontent_ads_url1').'"  target="_blank"><img src="'.$adds1.'" width="125" height="125" alt="advert"></a></div>
                                    <div class="adv_125"><a href="'.tfuse_options('blog_bfcontent_ads_url2').'"  target="_blank"><img src="'.$adds2.'" width="125" height="125" alt="advert"></a></div>
                            </div>
                            <!--/ adv before content -->
                            ';
                    }
                    else
                    {
                        echo '';
                    }
                }
                elseif(tfuse_options('blog_bfcontent_number') == 'three' )
                {
                    $adds1 = tfuse_options('blog_bfcontent_ads_image1');
                    $adds2 = tfuse_options('blog_bfcontent_ads_image2');
                    $adds3 = tfuse_options('blog_bfcontent_ads_image3');
                    $adsense1 = tfuse_options('blog_bfcontent_ads_adsense1');
                    $adsense2 = tfuse_options('blog_bfcontent_ads_adsense2');
                    $adsense3 = tfuse_options('blog_bfcontent_ads_adsense3');
                    if(tfuse_options('blog_bfcontent_ads_space')=='1'&&!$adds1&&!$adsense1 &&!$adds2&&!$adsense2&&!$adds3&&!$adsense3){
                        echo  '
                                <!-- adv before content -->
                                        <div class="adv_before_content">
                                                <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                                <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                                <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                        </div>
                                <!--/ adv before content -->';
                    }
                    elseif($adsense1 || $adsense2 || $adsense3 )
                    {
                        echo '<div class="adv_before_content">
                        <div class="adv_125">'.$adsense1.'</div>
                        <div class="adv_125">'.$adsense2.'</div>
                        <div class="adv_125">'.$adsense3.'</div>
                        </div>';
                    }
                    elseif($adds1 || $adds2 || $adds3 )
                    {
                        echo '
                            <!-- adv before content -->
                            <div class="adv_before_content">
                                    <div class="adv_125"><a href="'.tfuse_options('blog_bfcontent_ads_url1').'"  target="_blank"><img src="'.$adds1.'" width="125" height="125" alt="advert"></a></div>
                                    <div class="adv_125"><a href="'.tfuse_options('blog_bfcontent_ads_url2').'"  target="_blank"><img src="'.$adds2.'" width="125" height="125" alt="advert"></a></div>
                                    <div class="adv_125"><a href="'.tfuse_options('blog_bfcontent_ads_url3').'"  target="_blank"><img src="'.$adds3.'" width="125" height="125" alt="advert"></a></div>
                            </div>
                            <!--/ adv before content -->
                            ';
                    }
                    else
                    {
                        echo '';
                    }
                }
                elseif(tfuse_options('blog_bfcontent_number') == 'four' )
                {
                    $adds1 = tfuse_options('blog_bfcontent_ads_image1');
                    $adds2 = tfuse_options('blog_bfcontent_ads_image2');
                    $adds3 = tfuse_options('blog_bfcontent_ads_image3');
                    $adds4 = tfuse_options('blog_bfcontent_ads_image4');
                    $adsense1 = tfuse_options('blog_bfcontent_ads_adsense1');
                    $adsense2 = tfuse_options('blog_bfcontent_ads_adsense2');
                    $adsense3 = tfuse_options('blog_bfcontent_ads_adsense3');
                    $adsense4 = tfuse_options('blog_bfcontent_ads_adsense4');
                    if(tfuse_options('blog_bfcontent_ads_space')=='1'&&!$adds1&&!$adsense1 &&!$adds2&&!$adsense2&&!$adds3&&!$adsense3&&!tfuse_page_options('bfcontent_ads_image4')&&!tfuse_page_options('bfcontent_ads_adsense4')){
                        echo  '
                                <!-- adv before content -->
                                        <div class="adv_before_content">
                                                <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                                <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                                <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                                <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                        </div>
                                <!--/ adv before content -->';
                    }
                    elseif($adsense1 || $adsense2 || $adsense3 || $adsense4)
                    {
                        echo '<div class="adv_before_content">
                        <div class="adv_125">'.$adsense1.'</div>
                        <div class="adv_125">'.$adsense2.'</div>
                        <div class="adv_125">'.$adsense3.'</div>
                        <div class="adv_125">'.$adsense4.'</div>
                        </div>';
                    }
                    elseif($adds1 || $adds2 || $adds3 || $adds4)
                    {
                        echo '
                            <!-- adv before content -->
                            <div class="adv_before_content">
                                    <div class="adv_125"><a href="'.tfuse_options('blog_bfcontent_ads_url1').'"  target="_blank"><img src="'.$adds1.'" width="125" height="125" alt="advert"></a></div>
                                    <div class="adv_125"><a href="'.tfuse_options('blog_bfcontent_ads_url2').'"  target="_blank"><img src="'.$adds2.'" width="125" height="125" alt="advert"></a></div>
                                    <div class="adv_125"><a href="'.tfuse_options('blog_bfcontent_ads_url3').'"  target="_blank"><img src="'.$adds3.'" width="125" height="125" alt="advert"></a></div>
                                    <div class="adv_125"><a href="'.tfuse_options('blog_bfcontent_ads_url4').'"  target="_blank"><img src="'.$adds4.'" width="125" height="125" alt="advert"></a></div>
                            </div>
                            <!--/ adv before content -->
                            ';
                    }
                    else
                    {
                        echo '';
                    }
                }
                elseif(tfuse_options('blog_bfcontent_number') == 'five' )
                {
                    $adds1 = tfuse_options('blog_bfcontent_ads_image1');
                    $adds2 = tfuse_options('blog_bfcontent_ads_image2');
                    $adds3 = tfuse_options('blog_bfcontent_ads_image3');
                    $adds4 = tfuse_options('blog_bfcontent_ads_image4');
                    $adds5 = tfuse_options('blog_bfcontent_ads_image5');
                    $adsense1 = tfuse_options('blog_bfcontent_ads_adsense1');
                    $adsense2 = tfuse_options('blog_bfcontent_ads_adsense2');
                    $adsense3 = tfuse_options('blog_bfcontent_ads_adsense3');
                    $adsense4 = tfuse_options('blog_bfcontent_ads_adsense4');
                    $adsense5 = tfuse_options('blog_bfcontent_ads_adsense5');
                    if(tfuse_options('blog_bfcontent_ads_space')=='1'&&!$adds1&&!$adsense1 &&!$adds2&&!$adsense2&&!$adds3&&!$adsense3&&!$adds4&&!$adsense4&&!$adds5&&!$adsense5){
                        echo  '
                                <!-- adv before content -->
                                        <div class="adv_before_content">
                                                <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                                <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                                <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                                <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                                <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                        </div>
                                <!--/ adv before content -->';
                    }
                    elseif($adsense1 || $adsense2 || $adsense3 || $adsense4 || $adsense5)
                    {
                        echo '<div class="adv_before_content">
                        <div class="adv_125">'.$adsense1.'</div>
                        <div class="adv_125">'.$adsense2.'</div>
                        <div class="adv_125">'.$adsense3.'</div>
                        <div class="adv_125">'.$adsense4.'</div>
                        <div class="adv_125">'.$adsense5.'</div>
                        </div>';
                    }
                    elseif($adds1 || $adds2 || $adds3 || $adds4 || $adds5)
                    {
                        echo '
                            <!-- adv before content -->
                            <div class="adv_before_content">
                                    <div class="adv_125"><a href="'.tfuse_options('blog_bfcontent_ads_url1').'"  target="_blank"><img src="'.$adds1.'" width="125" height="125" alt="advert"></a></div>
                                    <div class="adv_125"><a href="'.tfuse_options('blog_bfcontent_ads_url2').'"  target="_blank"><img src="'.$adds2.'" width="125" height="125" alt="advert"></a></div>
                                    <div class="adv_125"><a href="'.tfuse_options('blog_bfcontent_ads_url3').'"  target="_blank"><img src="'.$adds3.'" width="125" height="125" alt="advert"></a></div>
                                    <div class="adv_125"><a href="'.tfuse_options('blog_bfcontent_ads_url4').'"  target="_blank"><img src="'.$adds4.'" width="125" height="125" alt="advert"></a></div>
                                    <div class="adv_125"><a href="'.tfuse_options('blog_bfcontent_ads_url5').'"  target="_blank"><img src="'.$adds5.'" width="125" height="125" alt="advert"></a></div>
                            </div>
                            <!--/ adv before content -->
                            ';
                    }
                    else
                    {
                        echo '';
                    }
                }
                elseif(tfuse_options('blog_bfcontent_number') == 'six' )
                {
                    $adds1 = tfuse_options('blog_bfcontent_ads_image1');
                    $adds2 = tfuse_options('blog_bfcontent_ads_image2');
                    $adds3 = tfuse_options('blog_bfcontent_ads_image3');
                    $adds4 = tfuse_options('blog_bfcontent_ads_image4');
                    $adds5 = tfuse_options('blog_bfcontent_ads_image5');
                    $adds6 = tfuse_options('blog_bfcontent_ads_image6');
                    $adsense1 = tfuse_options('blog_bfcontent_ads_adsense1');
                    $adsense2 = tfuse_options('blog_bfcontent_ads_adsense2');
                    $adsense3 = tfuse_options('blog_bfcontent_ads_adsense3');
                    $adsense4 = tfuse_options('blog_bfcontent_ads_adsense4');
                    $adsense5 = tfuse_options('blog_bfcontent_ads_adsense5');
                    $adsense6 = tfuse_options('blog_bfcontent_ads_adsense6');
                    if(tfuse_options('blog_bfcontent_ads_space')=='1'&&!$adds1&&!$adsense1 &&!$adds2&&!$adsense2&&!$adds3&&!$adsense3&&!$adds4&&!$adsense4&&!$adds5&&!$adsense5&&!$adds6&&!$adsense6){
                        echo  '
                            <!-- adv before content -->
                                    <div class="adv_before_content">
                                            <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                            <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                            <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                            <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                            <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                            <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                    </div>
                            <!--/ adv before content -->';
                    }
                    elseif($adsense1 || $adsense2 || $adsense3 || $adsense4 || $adsense5 || $adsense6 )
                    {
                        echo '<div class="adv_before_content">
                        <div class="adv_125">'.$adsense1.'</div>
                        <div class="adv_125">'.$adsense2.'</div>
                        <div class="adv_125">'.$adsense3.'</div>
                        <div class="adv_125">'.$adsense4.'</div>
                        <div class="adv_125">'.$adsense5.'</div>
                        <div class="adv_125">'.$adsense6.'</div>
                        </div>';
                    }
                    elseif($adds1 || $adds2 || $adds3 || $adds4 || $adds5 || $adds6 )
                    {
                        echo '
                            <!-- adv before content -->
                            <div class="adv_before_content">
                                    <div class="adv_125"><a href="'.tfuse_options('blog_bfcontent_ads_url1').'"  target="_blank"><img src="'.$adds1.'" width="125" height="125" alt="advert"></a></div>
                                    <div class="adv_125"><a href="'.tfuse_options('blog_bfcontent_ads_url2').'"  target="_blank"><img src="'.$adds2.'" width="125" height="125" alt="advert"></a></div>
                                    <div class="adv_125"><a href="'.tfuse_options('blog_bfcontent_ads_url3').'"  target="_blank"><img src="'.$adds3.'" width="125" height="125" alt="advert"></a></div>
                                    <div class="adv_125"><a href="'.tfuse_options('blog_bfcontent_ads_url4').'"  target="_blank"><img src="'.$adds4.'" width="125" height="125" alt="advert"></a></div>
                                    <div class="adv_125"><a href="'.tfuse_options('blog_bfcontent_ads_url5').'"  target="_blank"><img src="'.$adds5.'" width="125" height="125" alt="advert"></a></div>
                                    <div class="adv_125"><a href="'.tfuse_options('blog_bfcontent_ads_url6').'"  target="_blank"><img src="'.$adds6.'" width="125" height="125" alt="advert"></a></div>
                            </div>
                            <!--/ adv before content -->
                            ';
                    }
                    else
                    {
                        echo '';
                    }
                }
                elseif(tfuse_options('blog_bfcontent_number') == 'seven' )
                {
                    $adds1 = tfuse_options('blog_bfcontent_ads_image1');
                    $adds2 = tfuse_options('blog_bfcontent_ads_image2');
                    $adds3 = tfuse_options('blog_bfcontent_ads_image3');
                    $adds4 = tfuse_options('blog_bfcontent_ads_image4');
                    $adds5 = tfuse_options('blog_bfcontent_ads_image5');
                    $adds6 = tfuse_options('blog_bfcontent_ads_image6');
                    $adds7 = tfuse_options('blog_bfcontent_ads_image7');
                    $adsense1 = tfuse_options('blog_bfcontent_ads_adsense1');
                    $adsense2 = tfuse_options('blog_bfcontent_ads_adsense2');
                    $adsense3 = tfuse_options('blog_bfcontent_ads_adsense3');
                    $adsense4 = tfuse_options('blog_bfcontent_ads_adsense4');
                    $adsense5 = tfuse_options('blog_bfcontent_ads_adsense5');
                    $adsense6 = tfuse_options('blog_bfcontent_ads_adsense6');
                    $adsense7 = tfuse_options('blog_bfcontent_ads_adsense7');
                    if(tfuse_options('blog_bfcontent_ads_space')=='1'&&!$adds1&&!$adsense1 &&!$adds2&&!$adsense2&&!$adds3&&!$adsense3&&!$adds4&&!$adsense4&&!$adds5&&!$adsense5&&!$adds6&&!$adsense6&&!$adds7&&!$adsense7){
                        echo  '
                            <!-- adv before content -->
                                    <div class="adv_before_content">
                                            <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                            <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                            <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                            <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                            <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                            <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                            <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                    </div>
                            <!--/ adv before content -->';
                    }
                    elseif($adsense1 || $adsense2 || $adsense3 || $adsense4 || $adsense5 || $adsense6 || $adsense7)
                    {
                        echo '<div class="adv_before_content">
                        <div class="adv_125">'.$adsense1.'</div>
                        <div class="adv_125">'.$adsense2.'</div>
                        <div class="adv_125">'.$adsense3.'</div>
                        <div class="adv_125">'.$adsense4.'</div>
                        <div class="adv_125">'.$adsense5.'</div>
                        <div class="adv_125">'.$adsense6.'</div>
                        <div class="adv_125">'.$adsense7.'</div>
                        </div>';
                    }
                    elseif($adds1 || $adds2 || $adds3 || $adds4 || $adds5 || $adds6 || $adds7)
                    {
                        echo '
                            <!-- adv before content -->
                            <div class="adv_before_content">
                                <div class="adv_125"><a href="'.tfuse_options('blog_bfcontent_ads_url1').'"  target="_blank"><img src="'.$adds1.'" width="125" height="125" alt="advert"></a></div>
                                <div class="adv_125"><a href="'.tfuse_options('blog_bfcontent_ads_url2').'"  target="_blank"><img src="'.$adds2.'" width="125" height="125" alt="advert"></a></div>
                                <div class="adv_125"><a href="'.tfuse_options('blog_bfcontent_ads_url3').'"  target="_blank"><img src="'.$adds3.'" width="125" height="125" alt="advert"></a></div>
                                <div class="adv_125"><a href="'.tfuse_options('blog_bfcontent_ads_url4').'"  target="_blank"><img src="'.$adds4.'" width="125" height="125" alt="advert"></a></div>
                                <div class="adv_125"><a href="'.tfuse_options('blog_bfcontent_ads_url5').'"  target="_blank"><img src="'.$adds5.'" width="125" height="125" alt="advert"></a></div>
                                <div class="adv_125"><a href="'.tfuse_options('blog_bfcontent_ads_url6').'"  target="_blank"><img src="'.$adds6.'" width="125" height="125" alt="advert"></a></div>
                                <div class="adv_125"><a href="'.tfuse_options('blog_bfcontent_ads_url7').'"  target="_blank"><img src="'.$adds7.'" width="125" height="125" alt="advert"></a></div>
                            </div>
                            <!--/ adv before content -->
                            ';
                    }
                    else
                    {
                        echo '';
                    }
                }
            }
            elseif(!tfuse_options('blog_bfcontent_ads_space') && !tfuse_options('bfc_ads_space'))
            {
                tfuse_bfc_ads_admin();
            }
        }
        elseif(is_front_page())
        {
            if(tfuse_options('use_page_options') && tfuse_options('homepage_category')=='page'){
                $page_id = $post->ID;
                if(tfuse_page_options('bfcontent_ads_space','',$page_id))
                {
                    if(tfuse_page_options('bfcontent_number','',$page_id) == 'one' )
                    {
                        $adds1 = tfuse_page_options('bfcontent_ads_image1','',$page_id);
                        if(tfuse_page_options('bfcontent_ads_space','',$page_id)=='1'&&!$adds1&&!tfuse_page_options('bfcontent_ads_adsense1','',$page_id)){
                            echo  '
                                    <!-- adv before content -->
                                            <div class="adv_before_content">
                                                    <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                            </div>
                                    <!--/ adv before content -->';
                        }
                        elseif(tfuse_page_options('bfcontent_ads_adsense1'))
                        {
                            echo '<div class="adv_before_content">
                            <div class="adv_125">'.tfuse_page_options('bfcontent_ads_adsense1','',$page_id).'</div>
                            </div>';
                        }
                        elseif($adds1)
                        {
                            echo '
                                <!-- adv before content -->
                                <div class="adv_before_content">
                                        <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url1','',$page_id).'"  target="_blank"><img src="'.$adds1.'" width="125" height="125" alt="advert"></a></div>
                                </div>
                                <!--/ adv before content -->
                                ';
                        }
                        else
                        {
                            echo '';
                        }
                    }
                    elseif(tfuse_page_options('bfcontent_number','',$page_id) == 'two' )
                    {
                        $adds1 = tfuse_page_options('bfcontent_ads_image1','',$page_id);
                        $adds2 = tfuse_page_options('bfcontent_ads_image2','',$page_id);
                        $adsense1 = tfuse_page_options('bfcontent_ads_adsense1','',$page_id);
                        $adsense2 = tfuse_page_options('bfcontent_ads_adsense2','',$page_id);
                        if(tfuse_page_options('bfcontent_ads_space','',$page_id)=='1'&&!$adds1&&!$adsense1 &&!$adds2&&!$adsense2){
                            echo  '
                                    <!-- adv before content -->
                                            <div class="adv_before_content">
                                                    <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                                    <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                            </div>
                                    <!--/ adv before content -->';
                        }
                        elseif($adsense1 || $adsense2)
                        {
                            echo '<div class="adv_before_content">
                            <div class="adv_125">'.$adsense1.'</div>
                            <div class="adv_125">'.$adsense2.'</div>
                            </div>';
                        }
                        elseif($adds1 || $adds2 )
                        {
                            echo '
                                <!-- adv before content -->
                                <div class="adv_before_content">
                                        <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url1','',$page_id).'"  target="_blank"><img src="'.$adds1.'" width="125" height="125" alt="advert"></a></div>
                                        <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url2','',$page_id).'"  target="_blank"><img src="'.$adds2.'" width="125" height="125" alt="advert"></a></div>
                                </div>
                                <!--/ adv before content -->
                                ';
                        }
                        else
                        {
                            echo '';
                        }
                    }
                    elseif(tfuse_page_options('bfcontent_number','',$page_id) == 'three' )
                    {
                        $adds1 = tfuse_page_options('bfcontent_ads_image1','',$page_id);
                        $adds2 = tfuse_page_options('bfcontent_ads_image2','',$page_id);
                        $adds3 = tfuse_page_options('bfcontent_ads_image3','',$page_id);
                        $adsense1 = tfuse_page_options('bfcontent_ads_adsense1','',$page_id);
                        $adsense2 = tfuse_page_options('bfcontent_ads_adsense2','',$page_id);
                        $adsense3 = tfuse_page_options('bfcontent_ads_adsense3','',$page_id);
                        if(tfuse_page_options('bfcontent_ads_space','',$page_id)=='1'&&!$adds1&&!$adsense1 &&!$adds2&&!$adsense2&&!$adds3&&!$adsense3){
                            echo  '
                                    <!-- adv before content -->
                                            <div class="adv_before_content">
                                                    <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                                    <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                                    <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                            </div>
                                    <!--/ adv before content -->';
                        }
                        elseif($adsense1 || $adsense2 || $adsense3 )
                        {
                            echo '<div class="adv_before_content">
                            <div class="adv_125">'.$adsense1.'</div>
                            <div class="adv_125">'.$adsense2.'</div>
                            <div class="adv_125">'.$adsense3.'</div>
                            </div>';
                        }
                        elseif($adds1 || $adds2 || $adds3 )
                        {
                            echo '
                                <!-- adv before content -->
                                <div class="adv_before_content">
                                        <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url1','',$page_id).'"  target="_blank"><img src="'.$adds1.'" width="125" height="125" alt="advert"></a></div>
                                        <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url2','',$page_id).'"  target="_blank"><img src="'.$adds2.'" width="125" height="125" alt="advert"></a></div>
                                        <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url3','',$page_id).'"  target="_blank"><img src="'.$adds3.'" width="125" height="125" alt="advert"></a></div>
                                </div>
                                <!--/ adv before content -->
                                ';
                        }
                        else
                        {
                            echo '';
                        }
                    }
                    elseif(tfuse_page_options('bfcontent_number','',$page_id) == 'four' )
                    {
                        $adds1 = tfuse_page_options('bfcontent_ads_image1','',$page_id);
                        $adds2 = tfuse_page_options('bfcontent_ads_image2','',$page_id);
                        $adds3 = tfuse_page_options('bfcontent_ads_image3','',$page_id);
                        $adds4 = tfuse_page_options('bfcontent_ads_image4','',$page_id);
                        $adsense1 = tfuse_page_options('bfcontent_ads_adsense1','',$page_id);
                        $adsense2 = tfuse_page_options('bfcontent_ads_adsense2','',$page_id);
                        $adsense3 = tfuse_page_options('bfcontent_ads_adsense3','',$page_id);
                        $adsense4 = tfuse_page_options('bfcontent_ads_adsense4','',$page_id);
                        if(tfuse_page_options('bfcontent_ads_space','',$page_id)=='1'&&!$adds1&&!$adsense1 &&!$adds2&&!$adsense2&&!$adds3&&!$adsense3&&!tfuse_page_options('bfcontent_ads_image4','',$page_id)&&!tfuse_page_options('bfcontent_ads_adsense4','',$page_id)){
                            echo  '
                                    <!-- adv before content -->
                                            <div class="adv_before_content">
                                                    <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                                    <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                                    <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                                    <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                            </div>
                                    <!--/ adv before content -->';
                        }
                        elseif($adsense1 || $adsense2 || $adsense3 || $adsense4)
                        {
                            echo '<div class="adv_before_content">
                            <div class="adv_125">'.$adsense1.'</div>
                            <div class="adv_125">'.$adsense2.'</div>
                            <div class="adv_125">'.$adsense3.'</div>
                            <div class="adv_125">'.$adsense4.'</div>
                            </div>';
                        }
                        elseif($adds1 || $adds2 || $adds3 || $adds4)
                        {
                            echo '
                                <!-- adv before content -->
                                <div class="adv_before_content">
                                        <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url1','',$page_id).'"  target="_blank"><img src="'.$adds1.'" width="125" height="125" alt="advert"></a></div>
                                        <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url2','',$page_id).'"  target="_blank"><img src="'.$adds2.'" width="125" height="125" alt="advert"></a></div>
                                        <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url3','',$page_id).'"  target="_blank"><img src="'.$adds3.'" width="125" height="125" alt="advert"></a></div>
                                        <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url4','',$page_id).'"  target="_blank"><img src="'.$adds4.'" width="125" height="125" alt="advert"></a></div>
                                </div>
                                <!--/ adv before content -->
                                ';
                        }
                        else
                        {
                            echo '';
                        }
                    }
                    elseif(tfuse_page_options('bfcontent_number','',$page_id) == 'five' )
                    {
                        $adds1 = tfuse_page_options('bfcontent_ads_image1','',$page_id);
                        $adds2 = tfuse_page_options('bfcontent_ads_image2','',$page_id);
                        $adds3 = tfuse_page_options('bfcontent_ads_image3','',$page_id);
                        $adds4 = tfuse_page_options('bfcontent_ads_image4','',$page_id);
                        $adds5 = tfuse_page_options('bfcontent_ads_image5','',$page_id);
                        $adsense1 = tfuse_page_options('bfcontent_ads_adsense1','',$page_id);
                        $adsense2 = tfuse_page_options('bfcontent_ads_adsense2','',$page_id);
                        $adsense3 = tfuse_page_options('bfcontent_ads_adsense3','',$page_id);
                        $adsense4 = tfuse_page_options('bfcontent_ads_adsense4','',$page_id);
                        $adsense5 = tfuse_page_options('bfcontent_ads_adsense5','',$page_id);
                        if(tfuse_page_options('bfcontent_ads_space','',$page_id)=='1'&&!$adds1&&!$adsense1 &&!$adds2&&!$adsense2&&!$adds3&&!$adsense3&&!$adds4&&!$adsense4&&!$adds5&&!$adsense5){
                            echo  '
                                    <!-- adv before content -->
                                            <div class="adv_before_content">
                                                    <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                                    <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                                    <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                                    <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                                    <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                            </div>
                                    <!--/ adv before content -->';
                        }
                        elseif($adsense1 || $adsense2 || $adsense3 || $adsense4 || $adsense5)
                        {
                            echo '<div class="adv_before_content">
                            <div class="adv_125">'.$adsense1.'</div>
                            <div class="adv_125">'.$adsense2.'</div>
                            <div class="adv_125">'.$adsense3.'</div>
                            <div class="adv_125">'.$adsense4.'</div>
                            <div class="adv_125">'.$adsense5.'</div>
                            </div>';
                        }
                        elseif($adds1 || $adds2 || $adds3 || $adds4 || $adds5)
                        {
                            echo '
                                <!-- adv before content -->
                                <div class="adv_before_content">
                                        <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url1','',$page_id).'"  target="_blank"><img src="'.$adds1.'" width="125" height="125" alt="advert"></a></div>
                                        <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url2','',$page_id).'"  target="_blank"><img src="'.$adds2.'" width="125" height="125" alt="advert"></a></div>
                                        <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url3','',$page_id).'"  target="_blank"><img src="'.$adds3.'" width="125" height="125" alt="advert"></a></div>
                                        <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url4','',$page_id).'"  target="_blank"><img src="'.$adds4.'" width="125" height="125" alt="advert"></a></div>
                                        <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url5','',$page_id).'"  target="_blank"><img src="'.$adds5.'" width="125" height="125" alt="advert"></a></div>
                                </div>
                                <!--/ adv before content -->
                                ';
                        }
                        else
                        {
                            echo '';
                        }
                    }
                    elseif(tfuse_page_options('bfcontent_number','',$page_id) == 'six' )
                    {
                        $adds1 = tfuse_page_options('bfcontent_ads_image1','',$page_id);
                        $adds2 = tfuse_page_options('bfcontent_ads_image2','',$page_id);
                        $adds3 = tfuse_page_options('bfcontent_ads_image3','',$page_id);
                        $adds4 = tfuse_page_options('bfcontent_ads_image4','',$page_id);
                        $adds5 = tfuse_page_options('bfcontent_ads_image5','',$page_id);
                        $adds6 = tfuse_page_options('bfcontent_ads_image6','',$page_id);
                        $adsense1 = tfuse_page_options('bfcontent_ads_adsense1','',$page_id);
                        $adsense2 = tfuse_page_options('bfcontent_ads_adsense2','',$page_id);
                        $adsense3 = tfuse_page_options('bfcontent_ads_adsense3','',$page_id);
                        $adsense4 = tfuse_page_options('bfcontent_ads_adsense4','',$page_id);
                        $adsense5 = tfuse_page_options('bfcontent_ads_adsense5','',$page_id);
                        $adsense6 = tfuse_page_options('bfcontent_ads_adsense6','',$page_id);
                        if(tfuse_page_options('bfcontent_ads_space','',$page_id)=='1'&&!$adds1&&!$adsense1 &&!$adds2&&!$adsense2&&!$adds3&&!$adsense3&&!$adds4&&!$adsense4&&!$adds5&&!$adsense5&&!$adds6&&!$adsense6){
                            echo  '
                                <!-- adv before content -->
                                        <div class="adv_before_content">
                                                <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                                <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                                <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                                <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                                <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                                <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                        </div>
                                <!--/ adv before content -->';
                        }
                        elseif($adsense1 || $adsense2 || $adsense3 || $adsense4 || $adsense5 || $adsense6 )
                        {
                            echo '<div class="adv_before_content">
                            <div class="adv_125">'.$adsense1.'</div>
                            <div class="adv_125">'.$adsense2.'</div>
                            <div class="adv_125">'.$adsense3.'</div>
                            <div class="adv_125">'.$adsense4.'</div>
                            <div class="adv_125">'.$adsense5.'</div>
                            <div class="adv_125">'.$adsense6.'</div>
                            </div>';
                        }
                        elseif($adds1 || $adds2 || $adds3 || $adds4 || $adds5 || $adds6 )
                        {
                            echo '
                                <!-- adv before content -->
                                <div class="adv_before_content">
                                        <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url1','',$page_id).'"  target="_blank"><img src="'.$adds1.'" width="125" height="125" alt="advert"></a></div>
                                        <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url2','',$page_id).'"  target="_blank"><img src="'.$adds2.'" width="125" height="125" alt="advert"></a></div>
                                        <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url3','',$page_id).'"  target="_blank"><img src="'.$adds3.'" width="125" height="125" alt="advert"></a></div>
                                        <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url4','',$page_id).'"  target="_blank"><img src="'.$adds4.'" width="125" height="125" alt="advert"></a></div>
                                        <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url5','',$page_id).'"  target="_blank"><img src="'.$adds5.'" width="125" height="125" alt="advert"></a></div>
                                        <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url6','',$page_id).'"  target="_blank"><img src="'.$adds6.'" width="125" height="125" alt="advert"></a></div>
                                </div>
                                <!--/ adv before content -->
                                ';
                        }
                        else
                        {
                            echo '';
                        }
                    }
                    elseif(tfuse_page_options('bfcontent_number','',$page_id) == 'seven' )
                    {
                        $adds1 = tfuse_page_options('bfcontent_ads_image1','',$page_id);
                        $adds2 = tfuse_page_options('bfcontent_ads_image2','',$page_id);
                        $adds3 = tfuse_page_options('bfcontent_ads_image3','',$page_id);
                        $adds4 = tfuse_page_options('bfcontent_ads_image4','',$page_id);
                        $adds5 = tfuse_page_options('bfcontent_ads_image5','',$page_id);
                        $adds6 = tfuse_page_options('bfcontent_ads_image6','',$page_id);
                        $adds7 = tfuse_page_options('bfcontent_ads_image7','',$page_id);
                        $adsense1 = tfuse_page_options('bfcontent_ads_adsense1','',$page_id);
                        $adsense2 = tfuse_page_options('bfcontent_ads_adsense2','',$page_id);
                        $adsense3 = tfuse_page_options('bfcontent_ads_adsense3','',$page_id);
                        $adsense4 = tfuse_page_options('bfcontent_ads_adsense4','',$page_id);
                        $adsense5 = tfuse_page_options('bfcontent_ads_adsense5','',$page_id);
                        $adsense6 = tfuse_page_options('bfcontent_ads_adsense6','',$page_id);
                        $adsense7 = tfuse_page_options('bfcontent_ads_adsense7','',$page_id);
                        if(tfuse_page_options('bfcontent_ads_space','',$page_id)=='1'&&!$adds1&&!$adsense1 &&!$adds2&&!$adsense2&&!$adds3&&!$adsense3&&!$adds4&&!$adsense4&&!$adds5&&!$adsense5&&!$adds6&&!$adsense6&&!$adds7&&!$adsense7){
                            echo  '
                                <!-- adv before content -->
                                        <div class="adv_before_content">
                                                <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                                <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                                <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                                <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                                <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                                <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                                <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                        </div>
                                <!--/ adv before content -->';
                        }
                        elseif($adsense1 || $adsense2 || $adsense3 || $adsense4 || $adsense5 || $adsense6 || $adsense7)
                        {
                            echo '<div class="adv_before_content">
                            <div class="adv_125">'.$adsense1.'</div>
                            <div class="adv_125">'.$adsense2.'</div>
                            <div class="adv_125">'.$adsense3.'</div>
                            <div class="adv_125">'.$adsense4.'</div>
                            <div class="adv_125">'.$adsense5.'</div>
                            <div class="adv_125">'.$adsense6.'</div>
                            <div class="adv_125">'.$adsense7.'</div>
                            </div>';
                        }
                        elseif($adds1 || $adds2 || $adds3 || $adds4 || $adds5 || $adds6 || $adds7)
                        {
                            echo '
                                <!-- adv before content -->
                                <div class="adv_before_content">
                                    <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url1','',$page_id).'"  target="_blank"><img src="'.$adds1.'" width="125" height="125" alt="advert"></a></div>
                                    <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url2','',$page_id).'"  target="_blank"><img src="'.$adds2.'" width="125" height="125" alt="advert"></a></div>
                                    <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url3','',$page_id).'"  target="_blank"><img src="'.$adds3.'" width="125" height="125" alt="advert"></a></div>
                                    <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url4','',$page_id).'"  target="_blank"><img src="'.$adds4.'" width="125" height="125" alt="advert"></a></div>
                                    <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url5','',$page_id).'"  target="_blank"><img src="'.$adds5.'" width="125" height="125" alt="advert"></a></div>
                                    <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url6','',$page_id).'"  target="_blank"><img src="'.$adds6.'" width="125" height="125" alt="advert"></a></div>
                                    <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url7','',$page_id).'"  target="_blank"><img src="'.$adds7.'" width="125" height="125" alt="advert"></a></div>
                                </div>
                                <!--/ adv before content -->
                                ';
                        }
                        else
                        {
                            echo '';
                        }
                    }
                }
            }
            elseif(tfuse_options('home_bfcontent_ads_space'))
            {
                if(tfuse_options('home_bfcontent_number') == 'one' )
                {
                    $adds1 = tfuse_options('home_bfcontent_ads_image1');
                    if(tfuse_options('home_bfcontent_ads_space')=='1'&&!$adds1&&!tfuse_options('home_bfcontent_ads_adsense1')){
                        echo  '
                                <!-- adv before content -->
                                        <div class="adv_before_content">
                                                <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                        </div>
                                <!--/ adv before content -->';
                    }
                    elseif(tfuse_options('home_bfcontent_ads_adsense1'))
                    {
                        echo '<div class="adv_before_content">
                        <div class="adv_125">'.tfuse_options('home_bfcontent_ads_adsense1').'</div>
                        </div>';
                    }
                    elseif($adds1)
                    {
                        echo '
                            <!-- adv before content -->
                            <div class="adv_before_content">
                                    <div class="adv_125"><a href="'.tfuse_options('home_bfcontent_ads_url1').'"  target="_blank"><img src="'.$adds1.'" width="125" height="125" alt="advert"></a></div>
                            </div>
                            <!--/ adv before content -->
                            ';
                    }
                    else
                    {
                        echo '';
                    }
                }
                elseif(tfuse_options('home_bfcontent_number') == 'two' )
                {
                    $adds1 = tfuse_options('home_bfcontent_ads_image1');
                    $adds2 = tfuse_options('home_bfcontent_ads_image2');
                    $adsense1 = tfuse_options('home_bfcontent_ads_adsense1');
                    $adsense2 = tfuse_options('home_bfcontent_ads_adsense2');
                    if(tfuse_options('home_bfcontent_ads_space')=='1'&&!$adds1&&!$adsense1 &&!$adds2&&!$adsense2){
                        echo  '
                                <!-- adv before content -->
                                        <div class="adv_before_content">
                                                <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                                <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                        </div>
                                <!--/ adv before content -->';
                    }
                    elseif($adsense1 || $adsense2)
                    {
                        echo '<div class="adv_before_content">
                        <div class="adv_125">'.$adsense1.'</div>
                        <div class="adv_125">'.$adsense2.'</div>
                        </div>';
                    }
                    elseif($adds1 || $adds2 )
                    {
                        echo '
                            <!-- adv before content -->
                            <div class="adv_before_content">
                                    <div class="adv_125"><a href="'.tfuse_options('home_bfcontent_ads_url1').'"  target="_blank"><img src="'.$adds1.'" width="125" height="125" alt="advert"></a></div>
                                    <div class="adv_125"><a href="'.tfuse_options('home_bfcontent_ads_url2').'"  target="_blank"><img src="'.$adds2.'" width="125" height="125" alt="advert"></a></div>
                            </div>
                            <!--/ adv before content -->
                            ';
                    }
                    else
                    {
                        echo '';
                    }
                }
                elseif(tfuse_options('home_bfcontent_number') == 'three' )
                {
                    $adds1 = tfuse_options('home_bfcontent_ads_image1');
                    $adds2 = tfuse_options('home_bfcontent_ads_image2');
                    $adds3 = tfuse_options('home_bfcontent_ads_image3');
                    $adsense1 = tfuse_options('home_bfcontent_ads_adsense1');
                    $adsense2 = tfuse_options('home_bfcontent_ads_adsense2');
                    $adsense3 = tfuse_options('home_bfcontent_ads_adsense3');
                    if(tfuse_options('home_bfcontent_ads_space')=='1'&&!$adds1&&!$adsense1 &&!$adds2&&!$adsense2&&!$adds3&&!$adsense3){
                        echo  '
                                <!-- adv before content -->
                                        <div class="adv_before_content">
                                                <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                                <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                                <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                        </div>
                                <!--/ adv before content -->';
                    }
                    elseif($adsense1 || $adsense2 || $adsense3 )
                    {
                        echo '<div class="adv_before_content">
                        <div class="adv_125">'.$adsense1.'</div>
                        <div class="adv_125">'.$adsense2.'</div>
                        <div class="adv_125">'.$adsense3.'</div>
                        </div>';
                    }
                    elseif($adds1 || $adds2 || $adds3 )
                    {
                        echo '
                            <!-- adv before content -->
                            <div class="adv_before_content">
                                    <div class="adv_125"><a href="'.tfuse_options('home_bfcontent_ads_url1').'"  target="_blank"><img src="'.$adds1.'" width="125" height="125" alt="advert"></a></div>
                                    <div class="adv_125"><a href="'.tfuse_options('home_bfcontent_ads_url2').'"  target="_blank"><img src="'.$adds2.'" width="125" height="125" alt="advert"></a></div>
                                    <div class="adv_125"><a href="'.tfuse_options('home_bfcontent_ads_url3').'"  target="_blank"><img src="'.$adds3.'" width="125" height="125" alt="advert"></a></div>
                            </div>
                            <!--/ adv before content -->
                            ';
                    }
                    else
                    {
                        echo '';
                    }
                }
                elseif(tfuse_options('home_bfcontent_number') == 'four' )
                {
                    $adds1 = tfuse_options('home_bfcontent_ads_image1');
                    $adds2 = tfuse_options('home_bfcontent_ads_image2');
                    $adds3 = tfuse_options('home_bfcontent_ads_image3');
                    $adds4 = tfuse_options('home_bfcontent_ads_image4');
                    $adsense1 = tfuse_options('home_bfcontent_ads_adsense1');
                    $adsense2 = tfuse_options('home_bfcontent_ads_adsense2');
                    $adsense3 = tfuse_options('home_bfcontent_ads_adsense3');
                    $adsense4 = tfuse_options('home_bfcontent_ads_adsense4');
                    if(tfuse_options('home_bfcontent_ads_space')=='1'&&!$adds1&&!$adsense1 &&!$adds2&&!$adsense2&&!$adds3&&!$adsense3&&!tfuse_page_options('bfcontent_ads_image4')&&!tfuse_page_options('bfcontent_ads_adsense4')){
                        echo  '
                                <!-- adv before content -->
                                        <div class="adv_before_content">
                                                <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                                <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                                <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                                <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                        </div>
                                <!--/ adv before content -->';
                    }
                    elseif($adsense1 || $adsense2 || $adsense3 || $adsense4)
                    {
                        echo '<div class="adv_before_content">
                        <div class="adv_125">'.$adsense1.'</div>
                        <div class="adv_125">'.$adsense2.'</div>
                        <div class="adv_125">'.$adsense3.'</div>
                        <div class="adv_125">'.$adsense4.'</div>
                        </div>';
                    }
                    elseif($adds1 || $adds2 || $adds3 || $adds4)
                    {
                        echo '
                            <!-- adv before content -->
                            <div class="adv_before_content">
                                    <div class="adv_125"><a href="'.tfuse_options('home_bfcontent_ads_url1').'"  target="_blank"><img src="'.$adds1.'" width="125" height="125" alt="advert"></a></div>
                                    <div class="adv_125"><a href="'.tfuse_options('home_bfcontent_ads_url2').'"  target="_blank"><img src="'.$adds2.'" width="125" height="125" alt="advert"></a></div>
                                    <div class="adv_125"><a href="'.tfuse_options('home_bfcontent_ads_url3').'"  target="_blank"><img src="'.$adds3.'" width="125" height="125" alt="advert"></a></div>
                                    <div class="adv_125"><a href="'.tfuse_options('home_bfcontent_ads_url4').'"  target="_blank"><img src="'.$adds4.'" width="125" height="125" alt="advert"></a></div>
                            </div>
                            <!--/ adv before content -->
                            ';
                    }
                    else
                    {
                        echo '';
                    }
                }
                elseif(tfuse_options('home_bfcontent_number') == 'five' )
                {
                    $adds1 = tfuse_options('home_bfcontent_ads_image1');
                    $adds2 = tfuse_options('home_bfcontent_ads_image2');
                    $adds3 = tfuse_options('home_bfcontent_ads_image3');
                    $adds4 = tfuse_options('home_bfcontent_ads_image4');
                    $adds5 = tfuse_options('home_bfcontent_ads_image5');
                    $adsense1 = tfuse_options('home_bfcontent_ads_adsense1');
                    $adsense2 = tfuse_options('home_bfcontent_ads_adsense2');
                    $adsense3 = tfuse_options('home_bfcontent_ads_adsense3');
                    $adsense4 = tfuse_options('home_bfcontent_ads_adsense4');
                    $adsense5 = tfuse_options('home_bfcontent_ads_adsense5');
                    if(tfuse_options('home_bfcontent_ads_space')=='1'&&!$adds1&&!$adsense1 &&!$adds2&&!$adsense2&&!$adds3&&!$adsense3&&!$adds4&&!$adsense4&&!$adds5&&!$adsense5){
                        echo  '
                                <!-- adv before content -->
                                        <div class="adv_before_content">
                                                <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                                <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                                <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                                <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                                <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                        </div>
                                <!--/ adv before content -->';
                    }
                    elseif($adsense1 || $adsense2 || $adsense3 || $adsense4 || $adsense5)
                    {
                        echo '<div class="adv_before_content">
                        <div class="adv_125">'.$adsense1.'</div>
                        <div class="adv_125">'.$adsense2.'</div>
                        <div class="adv_125">'.$adsense3.'</div>
                        <div class="adv_125">'.$adsense4.'</div>
                        <div class="adv_125">'.$adsense5.'</div>
                        </div>';
                    }
                    elseif($adds1 || $adds2 || $adds3 || $adds4 || $adds5)
                    {
                        echo '
                            <!-- adv before content -->
                            <div class="adv_before_content">
                                    <div class="adv_125"><a href="'.tfuse_options('home_bfcontent_ads_url1').'"  target="_blank"><img src="'.$adds1.'" width="125" height="125" alt="advert"></a></div>
                                    <div class="adv_125"><a href="'.tfuse_options('home_bfcontent_ads_url2').'"  target="_blank"><img src="'.$adds2.'" width="125" height="125" alt="advert"></a></div>
                                    <div class="adv_125"><a href="'.tfuse_options('home_bfcontent_ads_url3').'"  target="_blank"><img src="'.$adds3.'" width="125" height="125" alt="advert"></a></div>
                                    <div class="adv_125"><a href="'.tfuse_options('home_bfcontent_ads_url4').'"  target="_blank"><img src="'.$adds4.'" width="125" height="125" alt="advert"></a></div>
                                    <div class="adv_125"><a href="'.tfuse_options('home_bfcontent_ads_url5').'"  target="_blank"><img src="'.$adds5.'" width="125" height="125" alt="advert"></a></div>
                            </div>
                            <!--/ adv before content -->
                            ';
                    }
                    else
                    {
                        echo '';
                    }
                }
                elseif(tfuse_options('home_bfcontent_number') == 'six' )
                {
                    $adds1 = tfuse_options('home_bfcontent_ads_image1');
                    $adds2 = tfuse_options('home_bfcontent_ads_image2');
                    $adds3 = tfuse_options('home_bfcontent_ads_image3');
                    $adds4 = tfuse_options('home_bfcontent_ads_image4');
                    $adds5 = tfuse_options('home_bfcontent_ads_image5');
                    $adds6 = tfuse_options('home_bfcontent_ads_image6');
                    $adsense1 = tfuse_options('home_bfcontent_ads_adsense1');
                    $adsense2 = tfuse_options('home_bfcontent_ads_adsense2');
                    $adsense3 = tfuse_options('home_bfcontent_ads_adsense3');
                    $adsense4 = tfuse_options('home_bfcontent_ads_adsense4');
                    $adsense5 = tfuse_options('home_bfcontent_ads_adsense5');
                    $adsense6 = tfuse_options('home_bfcontent_ads_adsense6');
                    if(tfuse_options('home_bfcontent_ads_space')=='1'&&!$adds1&&!$adsense1 &&!$adds2&&!$adsense2&&!$adds3&&!$adsense3&&!$adds4&&!$adsense4&&!$adds5&&!$adsense5&&!$adds6&&!$adsense6){
                        echo  '
                            <!-- adv before content -->
                                    <div class="adv_before_content">
                                            <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                            <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                            <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                            <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                            <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                            <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                    </div>
                            <!--/ adv before content -->';
                    }
                    elseif($adsense1 || $adsense2 || $adsense3 || $adsense4 || $adsense5 || $adsense6 )
                    {
                        echo '<div class="adv_before_content">
                        <div class="adv_125">'.$adsense1.'</div>
                        <div class="adv_125">'.$adsense2.'</div>
                        <div class="adv_125">'.$adsense3.'</div>
                        <div class="adv_125">'.$adsense4.'</div>
                        <div class="adv_125">'.$adsense5.'</div>
                        <div class="adv_125">'.$adsense6.'</div>
                        </div>';
                    }
                    elseif($adds1 || $adds2 || $adds3 || $adds4 || $adds5 || $adds6 )
                    {
                        echo '
                            <!-- adv before content -->
                            <div class="adv_before_content">
                                    <div class="adv_125"><a href="'.tfuse_options('home_bfcontent_ads_url1').'"  target="_blank"><img src="'.$adds1.'" width="125" height="125" alt="advert"></a></div>
                                    <div class="adv_125"><a href="'.tfuse_options('home_bfcontent_ads_url2').'"  target="_blank"><img src="'.$adds2.'" width="125" height="125" alt="advert"></a></div>
                                    <div class="adv_125"><a href="'.tfuse_options('home_bfcontent_ads_url3').'"  target="_blank"><img src="'.$adds3.'" width="125" height="125" alt="advert"></a></div>
                                    <div class="adv_125"><a href="'.tfuse_options('home_bfcontent_ads_url4').'"  target="_blank"><img src="'.$adds4.'" width="125" height="125" alt="advert"></a></div>
                                    <div class="adv_125"><a href="'.tfuse_options('home_bfcontent_ads_url5').'"  target="_blank"><img src="'.$adds5.'" width="125" height="125" alt="advert"></a></div>
                                    <div class="adv_125"><a href="'.tfuse_options('home_bfcontent_ads_url6').'"  target="_blank"><img src="'.$adds6.'" width="125" height="125" alt="advert"></a></div>
                            </div>
                            <!--/ adv before content -->
                            ';
                    }
                    else
                    {
                        echo '';
                    }
                }
                elseif(tfuse_options('home_bfcontent_number') == 'seven' )
                {
                    $adds1 = tfuse_options('home_bfcontent_ads_image1');
                    $adds2 = tfuse_options('home_bfcontent_ads_image2');
                    $adds3 = tfuse_options('home_bfcontent_ads_image3');
                    $adds4 = tfuse_options('home_bfcontent_ads_image4');
                    $adds5 = tfuse_options('home_bfcontent_ads_image5');
                    $adds6 = tfuse_options('home_bfcontent_ads_image6');
                    $adds7 = tfuse_options('home_bfcontent_ads_image7');
                    $adsense1 = tfuse_options('home_bfcontent_ads_adsense1');
                    $adsense2 = tfuse_options('home_bfcontent_ads_adsense2');
                    $adsense3 = tfuse_options('home_bfcontent_ads_adsense3');
                    $adsense4 = tfuse_options('home_bfcontent_ads_adsense4');
                    $adsense5 = tfuse_options('home_bfcontent_ads_adsense5');
                    $adsense6 = tfuse_options('home_bfcontent_ads_adsense6');
                    $adsense7 = tfuse_options('home_bfcontent_ads_adsense7');
                    if(tfuse_options('home_bfcontent_ads_space')=='1'&&!$adds1&&!$adsense1 &&!$adds2&&!$adsense2&&!$adds3&&!$adsense3&&!$adds4&&!$adsense4&&!$adds5&&!$adsense5&&!$adds6&&!$adsense6&&!$adds7&&!$adsense7){
                        echo  '
                            <!-- adv before content -->
                                    <div class="adv_before_content">
                                            <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                            <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                            <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                            <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                            <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                            <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                            <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                    </div>
                            <!--/ adv before content -->';
                    }
                    elseif($adsense1 || $adsense2 || $adsense3 || $adsense4 || $adsense5 || $adsense6 || $adsense7)
                    {
                        echo '<div class="adv_before_content">
                        <div class="adv_125">'.$adsense1.'</div>
                        <div class="adv_125">'.$adsense2.'</div>
                        <div class="adv_125">'.$adsense3.'</div>
                        <div class="adv_125">'.$adsense4.'</div>
                        <div class="adv_125">'.$adsense5.'</div>
                        <div class="adv_125">'.$adsense6.'</div>
                        <div class="adv_125">'.$adsense7.'</div>
                        </div>';
                    }
                    elseif($adds1 || $adds2 || $adds3 || $adds4 || $adds5 || $adds6 || $adds7)
                    {
                        echo '
                            <!-- adv before content -->
                            <div class="adv_before_content">
                                <div class="adv_125"><a href="'.tfuse_options('home_bfcontent_ads_url1').'"  target="_blank"><img src="'.$adds1.'" width="125" height="125" alt="advert"></a></div>
                                <div class="adv_125"><a href="'.tfuse_options('home_bfcontent_ads_url2').'"  target="_blank"><img src="'.$adds2.'" width="125" height="125" alt="advert"></a></div>
                                <div class="adv_125"><a href="'.tfuse_options('home_bfcontent_ads_url3').'"  target="_blank"><img src="'.$adds3.'" width="125" height="125" alt="advert"></a></div>
                                <div class="adv_125"><a href="'.tfuse_options('home_bfcontent_ads_url4').'"  target="_blank"><img src="'.$adds4.'" width="125" height="125" alt="advert"></a></div>
                                <div class="adv_125"><a href="'.tfuse_options('home_bfcontent_ads_url5').'"  target="_blank"><img src="'.$adds5.'" width="125" height="125" alt="advert"></a></div>
                                <div class="adv_125"><a href="'.tfuse_options('home_bfcontent_ads_url6').'"  target="_blank"><img src="'.$adds6.'" width="125" height="125" alt="advert"></a></div>
                                <div class="adv_125"><a href="'.tfuse_options('home_bfcontent_ads_url7').'"  target="_blank"><img src="'.$adds7.'" width="125" height="125" alt="advert"></a></div>
                            </div>
                            <!--/ adv before content -->
                            ';
                    }
                    else
                    {
                        echo '';
                    }
                }
            }
            elseif(!tfuse_options('home_bfcontent_ads_space') && !tfuse_options('bfc_ads_space'))
            {
                tfuse_bfc_ads_admin();
            }
        }
        elseif(is_page())
        {
            if(tfuse_page_options('bfcontent_ads_space'))
            {
                if(tfuse_page_options('bfcontent_number') == 'one' )
                {
                    $adds1 = tfuse_page_options('bfcontent_ads_image1');
                    if(tfuse_page_options('bfcontent_ads_space')=='1'&&!$adds1&&!tfuse_page_options('bfcontent_ads_adsense1')){
                        echo  '
                                <!-- adv before content -->
                                        <div class="adv_before_content">
                                                <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                        </div>
                                <!--/ adv before content -->';
                    }
                    elseif(tfuse_page_options('bfcontent_ads_adsense1'))
                    {
                        echo '<div class="adv_before_content">
                        <div class="adv_125">'.tfuse_page_options('bfcontent_ads_adsense1').'</div>
                        </div>';
                    }
                    elseif($adds1)
                    {
                        echo '
                            <!-- adv before content -->
                            <div class="adv_before_content">
                                    <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url1').'"  target="_blank"><img src="'.$adds1.'" width="125" height="125" alt="advert"></a></div>
                            </div>
                            <!--/ adv before content -->
                            ';
                    }
                    else
                    {
                        echo '';
                    }
                }
                elseif(tfuse_page_options('bfcontent_number') == 'two' )
                {
                    $adds1 = tfuse_page_options('bfcontent_ads_image1');
                    $adds2 = tfuse_page_options('bfcontent_ads_image2');
                    $adsense1 = tfuse_page_options('bfcontent_ads_adsense1');
                    $adsense2 = tfuse_page_options('bfcontent_ads_adsense2');
                    if(tfuse_page_options('bfcontent_ads_space')=='1'&&!$adds1&&!$adsense1 &&!$adds2&&!$adsense2){
                        echo  '
                                <!-- adv before content -->
                                        <div class="adv_before_content">
                                                <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                                <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                        </div>
                                <!--/ adv before content -->';
                    }
                    elseif($adsense1 || $adsense2)
                    {
                        echo '<div class="adv_before_content">
                        <div class="adv_125">'.$adsense1.'</div>
                        <div class="adv_125">'.$adsense2.'</div>
                        </div>';
                    }
                    elseif($adds1 || $adds2 )
                    {
                        echo '
                            <!-- adv before content -->
                            <div class="adv_before_content">
                                    <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url1').'"  target="_blank"><img src="'.$adds1.'" width="125" height="125" alt="advert"></a></div>
                                    <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url2').'"  target="_blank"><img src="'.$adds2.'" width="125" height="125" alt="advert"></a></div>
                            </div>
                            <!--/ adv before content -->
                            ';
                    }
                    else
                    {
                        echo '';
                    }
                }
                elseif(tfuse_page_options('bfcontent_number') == 'three' )
                {
                    $adds1 = tfuse_page_options('bfcontent_ads_image1');
                    $adds2 = tfuse_page_options('bfcontent_ads_image2');
                    $adds3 = tfuse_page_options('bfcontent_ads_image3');
                    $adsense1 = tfuse_page_options('bfcontent_ads_adsense1');
                    $adsense2 = tfuse_page_options('bfcontent_ads_adsense2');
                    $adsense3 = tfuse_page_options('bfcontent_ads_adsense3');
                    if(tfuse_page_options('bfcontent_ads_space')=='1'&&!$adds1&&!$adsense1 &&!$adds2&&!$adsense2&&!$adds3&&!$adsense3){
                        echo  '
                                <!-- adv before content -->
                                        <div class="adv_before_content">
                                                <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                                <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                                <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                        </div>
                                <!--/ adv before content -->';
                    }
                    elseif($adsense1 || $adsense2 || $adsense3 )
                    {
                        echo '<div class="adv_before_content">
                        <div class="adv_125">'.$adsense1.'</div>
                        <div class="adv_125">'.$adsense2.'</div>
                        <div class="adv_125">'.$adsense3.'</div>
                        </div>';
                    }
                    elseif($adds1 || $adds2 || $adds3 )
                    {
                        echo '
                            <!-- adv before content -->
                            <div class="adv_before_content">
                                    <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url1').'"  target="_blank"><img src="'.$adds1.'" width="125" height="125" alt="advert"></a></div>
                                    <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url2').'"  target="_blank"><img src="'.$adds2.'" width="125" height="125" alt="advert"></a></div>
                                    <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url3').'"  target="_blank"><img src="'.$adds3.'" width="125" height="125" alt="advert"></a></div>
                            </div>
                            <!--/ adv before content -->
                            ';
                    }
                    else
                    {
                        echo '';
                    }
                }
                elseif(tfuse_page_options('bfcontent_number') == 'four' )
                {
                    $adds1 = tfuse_page_options('bfcontent_ads_image1');
                    $adds2 = tfuse_page_options('bfcontent_ads_image2');
                    $adds3 = tfuse_page_options('bfcontent_ads_image3');
                    $adds4 = tfuse_page_options('bfcontent_ads_image4');
                    $adsense1 = tfuse_page_options('bfcontent_ads_adsense1');
                    $adsense2 = tfuse_page_options('bfcontent_ads_adsense2');
                    $adsense3 = tfuse_page_options('bfcontent_ads_adsense3');
                    $adsense4 = tfuse_page_options('bfcontent_ads_adsense4');
                    if(tfuse_page_options('bfcontent_ads_space')=='1'&&!$adds1&&!$adsense1 &&!$adds2&&!$adsense2&&!$adds3&&!$adsense3&&!tfuse_page_options('bfcontent_ads_image4')&&!tfuse_page_options('bfcontent_ads_adsense4')){
                        echo  '
                                <!-- adv before content -->
                                        <div class="adv_before_content">
                                                <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                                <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                                <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                                <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                        </div>
                                <!--/ adv before content -->';
                    }
                    elseif($adsense1 || $adsense2 || $adsense3 || $adsense4)
                    {
                        echo '<div class="adv_before_content">
                        <div class="adv_125">'.$adsense1.'</div>
                        <div class="adv_125">'.$adsense2.'</div>
                        <div class="adv_125">'.$adsense3.'</div>
                        <div class="adv_125">'.$adsense4.'</div>
                        </div>';
                    }
                    elseif($adds1 || $adds2 || $adds3 || $adds4)
                    {
                        echo '
                            <!-- adv before content -->
                            <div class="adv_before_content">
                                    <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url1').'"  target="_blank"><img src="'.$adds1.'" width="125" height="125" alt="advert"></a></div>
                                    <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url2').'"  target="_blank"><img src="'.$adds2.'" width="125" height="125" alt="advert"></a></div>
                                    <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url3').'"  target="_blank"><img src="'.$adds3.'" width="125" height="125" alt="advert"></a></div>
                                    <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url4').'"  target="_blank"><img src="'.$adds4.'" width="125" height="125" alt="advert"></a></div>
                            </div>
                            <!--/ adv before content -->
                            ';
                    }
                    else
                    {
                        echo '';
                    }
                }
                elseif(tfuse_page_options('bfcontent_number') == 'five' )
                {
                    $adds1 = tfuse_page_options('bfcontent_ads_image1');
                    $adds2 = tfuse_page_options('bfcontent_ads_image2');
                    $adds3 = tfuse_page_options('bfcontent_ads_image3');
                    $adds4 = tfuse_page_options('bfcontent_ads_image4');
                    $adds5 = tfuse_page_options('bfcontent_ads_image5');
                    $adsense1 = tfuse_page_options('bfcontent_ads_adsense1');
                    $adsense2 = tfuse_page_options('bfcontent_ads_adsense2');
                    $adsense3 = tfuse_page_options('bfcontent_ads_adsense3');
                    $adsense4 = tfuse_page_options('bfcontent_ads_adsense4');
                    $adsense5 = tfuse_page_options('bfcontent_ads_adsense5');
                    if(tfuse_page_options('bfcontent_ads_space')=='1'&&!$adds1&&!$adsense1 &&!$adds2&&!$adsense2&&!$adds3&&!$adsense3&&!$adds4&&!$adsense4&&!$adds5&&!$adsense5){
                        echo  '
                                <!-- adv before content -->
                                        <div class="adv_before_content">
                                                <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                                <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                                <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                                <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                                <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                        </div>
                                <!--/ adv before content -->';
                    }
                    elseif($adsense1 || $adsense2 || $adsense3 || $adsense4 || $adsense5)
                    {
                        echo '<div class="adv_before_content">
                        <div class="adv_125">'.$adsense1.'</div>
                        <div class="adv_125">'.$adsense2.'</div>
                        <div class="adv_125">'.$adsense3.'</div>
                        <div class="adv_125">'.$adsense4.'</div>
                        <div class="adv_125">'.$adsense5.'</div>
                        </div>';
                    }
                    elseif($adds1 || $adds2 || $adds3 || $adds4 || $adds5)
                    {
                        echo '
                            <!-- adv before content -->
                            <div class="adv_before_content">
                                    <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url1').'"  target="_blank"><img src="'.$adds1.'" width="125" height="125" alt="advert"></a></div>
                                    <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url2').'"  target="_blank"><img src="'.$adds2.'" width="125" height="125" alt="advert"></a></div>
                                    <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url3').'"  target="_blank"><img src="'.$adds3.'" width="125" height="125" alt="advert"></a></div>
                                    <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url4').'"  target="_blank"><img src="'.$adds4.'" width="125" height="125" alt="advert"></a></div>
                                    <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url5').'"  target="_blank"><img src="'.$adds5.'" width="125" height="125" alt="advert"></a></div>
                            </div>
                            <!--/ adv before content -->
                            ';
                    }
                    else
                    {
                        echo '';
                    }
                }
                elseif(tfuse_page_options('bfcontent_number') == 'six' )
                {
                    $adds1 = tfuse_page_options('bfcontent_ads_image1');
                    $adds2 = tfuse_page_options('bfcontent_ads_image2');
                    $adds3 = tfuse_page_options('bfcontent_ads_image3');
                    $adds4 = tfuse_page_options('bfcontent_ads_image4');
                    $adds5 = tfuse_page_options('bfcontent_ads_image5');
                    $adds6 = tfuse_page_options('bfcontent_ads_image6');
                    $adsense1 = tfuse_page_options('bfcontent_ads_adsense1');
                    $adsense2 = tfuse_page_options('bfcontent_ads_adsense2');
                    $adsense3 = tfuse_page_options('bfcontent_ads_adsense3');
                    $adsense4 = tfuse_page_options('bfcontent_ads_adsense4');
                    $adsense5 = tfuse_page_options('bfcontent_ads_adsense5');
                    $adsense6 = tfuse_page_options('bfcontent_ads_adsense6');
                    if(tfuse_page_options('bfcontent_ads_space')=='1'&&!$adds1&&!$adsense1 &&!$adds2&&!$adsense2&&!$adds3&&!$adsense3&&!$adds4&&!$adsense4&&!$adds5&&!$adsense5&&!$adds6&&!$adsense6){
                        echo  '
                            <!-- adv before content -->
                                    <div class="adv_before_content">
                                            <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                            <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                            <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                            <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                            <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                            <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                    </div>
                            <!--/ adv before content -->';
                    }
                    elseif($adsense1 || $adsense2 || $adsense3 || $adsense4 || $adsense5 || $adsense6 )
                    {
                        echo '<div class="adv_before_content">
                        <div class="adv_125">'.$adsense1.'</div>
                        <div class="adv_125">'.$adsense2.'</div>
                        <div class="adv_125">'.$adsense3.'</div>
                        <div class="adv_125">'.$adsense4.'</div>
                        <div class="adv_125">'.$adsense5.'</div>
                        <div class="adv_125">'.$adsense6.'</div>
                        </div>';
                    }
                    elseif($adds1 || $adds2 || $adds3 || $adds4 || $adds5 || $adds6 )
                    {
                        echo '
                            <!-- adv before content -->
                            <div class="adv_before_content">
                                    <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url1').'"  target="_blank"><img src="'.$adds1.'" width="125" height="125" alt="advert"></a></div>
                                    <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url2').'"  target="_blank"><img src="'.$adds2.'" width="125" height="125" alt="advert"></a></div>
                                    <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url3').'"  target="_blank"><img src="'.$adds3.'" width="125" height="125" alt="advert"></a></div>
                                    <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url4').'"  target="_blank"><img src="'.$adds4.'" width="125" height="125" alt="advert"></a></div>
                                    <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url5').'"  target="_blank"><img src="'.$adds5.'" width="125" height="125" alt="advert"></a></div>
                                    <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url6').'"  target="_blank"><img src="'.$adds6.'" width="125" height="125" alt="advert"></a></div>
                            </div>
                            <!--/ adv before content -->
                            ';
                    }
                    else
                    {
                        echo '';
                    }
                }
                elseif(tfuse_page_options('bfcontent_number') == 'seven' )
                {
                    $adds1 = tfuse_page_options('bfcontent_ads_image1');
                    $adds2 = tfuse_page_options('bfcontent_ads_image2');
                    $adds3 = tfuse_page_options('bfcontent_ads_image3');
                    $adds4 = tfuse_page_options('bfcontent_ads_image4');
                    $adds5 = tfuse_page_options('bfcontent_ads_image5');
                    $adds6 = tfuse_page_options('bfcontent_ads_image6');
                    $adds7 = tfuse_page_options('bfcontent_ads_image7');
                    $adsense1 = tfuse_page_options('bfcontent_ads_adsense1');
                    $adsense2 = tfuse_page_options('bfcontent_ads_adsense2');
                    $adsense3 = tfuse_page_options('bfcontent_ads_adsense3');
                    $adsense4 = tfuse_page_options('bfcontent_ads_adsense4');
                    $adsense5 = tfuse_page_options('bfcontent_ads_adsense5');
                    $adsense6 = tfuse_page_options('bfcontent_ads_adsense6');
                    $adsense7 = tfuse_page_options('bfcontent_ads_adsense7');
                    if(tfuse_page_options('bfcontent_ads_space')=='1'&&!$adds1&&!$adsense1 &&!$adds2&&!$adsense2&&!$adds3&&!$adsense3&&!$adds4&&!$adsense4&&!$adds5&&!$adsense5&&!$adds6&&!$adsense6&&!$adds7&&!$adsense7){
                        echo  '
                            <!-- adv before content -->
                                    <div class="adv_before_content">
                                            <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                            <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                            <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                            <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                            <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                            <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                            <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                    </div>
                            <!--/ adv before content -->';
                    }
                    elseif($adsense1 || $adsense2 || $adsense3 || $adsense4 || $adsense5 || $adsense6 || $adsense7)
                    {
                        echo '<div class="adv_before_content">
                        <div class="adv_125">'.$adsense1.'</div>
                        <div class="adv_125">'.$adsense2.'</div>
                        <div class="adv_125">'.$adsense3.'</div>
                        <div class="adv_125">'.$adsense4.'</div>
                        <div class="adv_125">'.$adsense5.'</div>
                        <div class="adv_125">'.$adsense6.'</div>
                        <div class="adv_125">'.$adsense7.'</div>
                        </div>';
                    }
                    elseif($adds1 || $adds2 || $adds3 || $adds4 || $adds5 || $adds6 || $adds7)
                    {
                        echo '
                            <!-- adv before content -->
                            <div class="adv_before_content">
                                <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url1').'"  target="_blank"><img src="'.$adds1.'" width="125" height="125" alt="advert"></a></div>
                                <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url2').'"  target="_blank"><img src="'.$adds2.'" width="125" height="125" alt="advert"></a></div>
                                <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url3').'"  target="_blank"><img src="'.$adds3.'" width="125" height="125" alt="advert"></a></div>
                                <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url4').'"  target="_blank"><img src="'.$adds4.'" width="125" height="125" alt="advert"></a></div>
                                <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url5').'"  target="_blank"><img src="'.$adds5.'" width="125" height="125" alt="advert"></a></div>
                                <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url6').'"  target="_blank"><img src="'.$adds6.'" width="125" height="125" alt="advert"></a></div>
                                <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url7').'"  target="_blank"><img src="'.$adds7.'" width="125" height="125" alt="advert"></a></div>
                            </div>
                            <!--/ adv before content -->
                            ';
                    }
                    else
                    {
                        echo '';
                    }
                }
            }
            elseif(!tfuse_page_options('bfcontent_ads_space') && !tfuse_options('bfc_ads_space'))
            {
                tfuse_bfc_ads_admin();
            }
        }
        elseif(is_single() && !is_page())
        {
            $cat_name = get_the_category();
            $post = get_post($post->ID);
            $post_type = $post->post_type;
            $taxonomies = get_object_taxonomies($post_type);
            if(!empty($taxonomies))
            {
                if($taxonomies[0] == 'category') {
                    $cat_id = $cat_name[0]->cat_ID;
                }
                elseif($taxonomies[0] == 'group')
                {
                    $terms = wp_get_post_terms($post->ID,'group');
                    $cat_id = $terms[0]->term_id;
                }
            }
            if(!tfuse_page_options('content_ads_post'))
            {
                tfuse_bfc_ads_post();
            }
            elseif(tfuse_page_options('content_ads_post') && tfuse_options('bfcontent_ads_space',null,$cat_id))
            {
                tfuse_bfc_ads_cat($cat_id);
            }
            elseif(!tfuse_options('bfcontent_ads_space',null,$cat_id) && !tfuse_options('bfc_ads_space'))
            {
                tfuse_bfc_ads_admin();
            }
        }
        elseif(is_category())
        {
            $cat_id = get_query_var('cat');
            if(tfuse_options('bfcontent_ads_space',null,$cat_id))
            {
                tfuse_bfc_ads_cat($cat_id);
            }
            elseif(!tfuse_options('bfcontent_ads_space',null,$cat_id) && !tfuse_options('bfc_ads_space'))
            {
                tfuse_bfc_ads_admin();
            }
        }
        elseif(is_tax())
        {
            $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy') );
            $cat_id = $term->term_id;
            if(tfuse_options('bfcontent_ads_space',null,$cat_id))
            {
                tfuse_bfc_ads_cat($cat_id);
            }
            elseif(!tfuse_options('bfcontent_ads_space',null,$cat_id) && !tfuse_options('bfc_ads_space'))
            {
                tfuse_bfc_ads_admin();
            }
        }
    }
endif;

//468x60 ad
if (!function_exists('tfuse_hook')) :
    function tfuse_hook() {
        $id = 0;
        global $post,$is_tf_front_page,$is_tf_blog_page;
        $ID = '';
        if($is_tf_blog_page)
        {
            if (tfuse_options('blog_hook_space')=='true')
            {
                if(tfuse_options('blog_hook_space')&&!tfuse_options('blog_hook_image')&&!tfuse_options('blog_hook_adsense')){
                    echo '
                        <!-- adv: 468x60 center -->
                        <div class="adv_content">
                                        <div class="adv_468"><img src="'.tfuse_get_file_uri('/images/adv_468x60.png').'" width="460" height="60" alt="advert"></div>
                                </div>
                        <!--/ adv: 468x60 center -->';
                }
                elseif(tfuse_options('blog_hook_adsense')&&!tfuse_options('blog_hook_image')||tfuse_options('blog_hook_adsense')&&tfuse_options('blog_hook_image'))
                {
                    echo  ' <!-- adv: 468x60 center --><div class="adv_content"><div class="adv_468">'.tfuse_options('blog_hook_adsense').'</div></div><!--/ adv: 468x60 center -->';
                }
                elseif(tfuse_options('blog_hook_image')&&!tfuse_options('blog_hook_adsense'))
                {
                    echo '
                    <!-- adv: 468x60 center -->
                    <div class="adv_content">
                            <div class="adv_468"><a href="'.tfuse_options('blog_hook_url').'"  target="_blank"><img src="'.tfuse_options('blog_hook_image').'" width="460" height="60" alt="advert"></a></div>
                    </div>
                    <!--/ adv: 468x60 center -->
                    ';
                }
                else
                {
                    echo '';
                }
            }
            elseif(!tfuse_options('blog_hook_space') && !tfuse_options('content_ads_space'))
            {
                if(!tfuse_options('content_ads_space')&&!tfuse_options('hook_image_admin')&&!tfuse_options('hook_adsense_admin')){
                    echo '
                        <!-- adv: 468x60 center -->
                        <div class="adv_content">
                                        <div class="adv_468"><img src="'.tfuse_get_file_uri('/images/adv_468x60.png').'" width="460" height="60" alt="advert"></div>
                                </div>
                        <!--/ adv: 468x60 center -->';
                }
                elseif(tfuse_options('hook_adsense_admin')&&!tfuse_options('hook_image_admin')||tfuse_options('hook_adsense_admin')&&tfuse_options('hook_image_admin'))
                {
                    echo  ' <!-- adv: 468x60 center --><div class="adv_content"><div class="adv_468">'.tfuse_options('hook_adsense_admin').'</div></div><!--/ adv: 468x60 center -->';
                }
                elseif(tfuse_options('hook_image_admin')&&!tfuse_options('hook_adsense_admin'))
                {
                    echo '
                    <!-- adv: 468x60 center -->
                    <div class="adv_content">
                            <div class="adv_468"><a href="'.tfuse_options('hook_url_admin').'"  target="_blank"><img src="'.tfuse_options('hook_image_admin').'" width="460" height="60" alt="advert"></a></div>
                    </div>
                    <!--/ adv: 468x60 center -->
                    ';
                }
                else
                {
                    echo '';
                }
            }
        }
        elseif($is_tf_front_page)
        {
            if(tfuse_options('use_page_options') && tfuse_options('homepage_category')=='page'){
                $page_id = $post->ID;
                if(tfuse_page_options('hook_space','',$page_id)&&!tfuse_page_options('hook_image','',$page_id)&&!tfuse_page_options('hook_adsense','',$page_id)){
                    echo '
                        <!-- adv: 468x60 center -->
                        <div class="adv_content">
                                        <div class="adv_468"><img src="'.tfuse_get_file_uri('/images/adv_468x60.png').'" width="460" height="60" alt="advert"></div>
                                </div>
                        <!--/ adv: 468x60 center -->';
                }
                elseif(tfuse_page_options('hook_adsense','',$page_id)&&!tfuse_page_options('hook_image','',$page_id)||tfuse_page_options('hook_adsense','',$page_id)&&tfuse_page_options('hook_image','',$page_id))
                {
                    echo  ' <!-- adv: 468x60 center --><div class="adv_content"><div class="adv_468">'.tfuse_page_options('hook_adsense','',$page_id).'</div></div><!--/ adv: 468x60 center -->';
                }
                elseif(tfuse_page_options('hook_image','',$page_id)&&!tfuse_page_options('hook_adsense','',$page_id))
                {
                    echo '
                    <!-- adv: 468x60 center -->
                    <div class="adv_content">
                            <div class="adv_468"><a href="'.tfuse_page_options('hook_url','',$page_id).'"  target="_blank"><img src="'.tfuse_page_options('hook_image','',$page_id).'" width="460" height="60" alt="advert"></a></div>
                    </div>
                    <!--/ adv: 468x60 center -->
                    ';
                }
                else
                {
                    echo '';
                }
            }
            elseif (tfuse_options('home_hook_space')=='true')
            {
                if(tfuse_options('home_hook_space')&&!tfuse_options('home_hook_image')&&!tfuse_options('home_hook_adsense')){
                    echo '
                        <!-- adv: 468x60 center -->
                        <div class="adv_content">
                                        <div class="adv_468"><img src="'.tfuse_get_file_uri('/images/adv_468x60.png').'" width="460" height="60" alt="advert"></div>
                                </div>
                        <!--/ adv: 468x60 center -->';
                }
                elseif(tfuse_options('home_hook_adsense')&&!tfuse_options('home_hook_image')||tfuse_options('home_hook_adsense')&&tfuse_options('home_hook_image'))
                {
                    echo  ' <!-- adv: 468x60 center --><div class="adv_content"><div class="adv_468">'.tfuse_options('home_hook_adsense').'</div></div><!--/ adv: 468x60 center -->';
                }
                elseif(tfuse_options('home_hook_image')&&!tfuse_options('home_hook_adsense'))
                {
                    echo '
                    <!-- adv: 468x60 center -->
                    <div class="adv_content">
                            <div class="adv_468"><a href="'.tfuse_options('home_hook_url').'"  target="_blank"><img src="'.tfuse_options('home_hook_image').'" width="460" height="60" alt="advert"></a></div>
                    </div>
                    <!--/ adv: 468x60 center -->
                    ';
                }
                else
                {
                    echo '';
                }
            }
            elseif(!tfuse_options('home_hook_space') && !tfuse_options('content_ads_space'))
            {
                if(!tfuse_options('content_ads_space')&&!tfuse_options('hook_image_admin')&&!tfuse_options('hook_adsense_admin')){
                    echo '
                        <!-- adv: 468x60 center -->
                        <div class="adv_content">
                                        <div class="adv_468"><img src="'.tfuse_get_file_uri('/images/adv_468x60.png').'" width="460" height="60" alt="advert"></div>
                                </div>
                        <!--/ adv: 468x60 center -->';
                }
                elseif(tfuse_options('hook_adsense_admin')&&!tfuse_options('hook_image_admin')||tfuse_options('hook_adsense_admin')&&tfuse_options('hook_image_admin'))
                {
                    echo  ' <!-- adv: 468x60 center --><div class="adv_content"><div class="adv_468">'.tfuse_options('hook_adsense_admin').'</div></div><!--/ adv: 468x60 center -->';
                }
                elseif(tfuse_options('hook_image_admin')&&!tfuse_options('hook_adsense_admin'))
                {
                    echo '
                    <!-- adv: 468x60 center -->
                    <div class="adv_content">
                            <div class="adv_468"><a href="'.tfuse_options('hook_url_admin').'"  target="_blank"><img src="'.tfuse_options('hook_image_admin').'" width="460" height="60" alt="advert"></a></div>
                    </div>
                    <!--/ adv: 468x60 center -->
                    ';
                }
                else
                {
                    echo '';
                }
            }
        }
        elseif(is_page())
        {
            if (tfuse_page_options('hook_space')=='true')
            {
                if(tfuse_page_options('hook_space')&&!tfuse_page_options('hook_image')&&!tfuse_page_options('hook_adsense')){
                    echo '
                        <!-- adv: 468x60 center -->
                        <div class="adv_content">
                                        <div class="adv_468"><img src="'.tfuse_get_file_uri('/images/adv_468x60.png').'" width="460" height="60" alt="advert"></div>
                                </div>
                        <!--/ adv: 468x60 center -->';
                }
                elseif(tfuse_page_options('hook_adsense')&&!tfuse_page_options('hook_image')||tfuse_page_options('hook_adsense')&&tfuse_page_options('hook_image'))
                {
                    echo  ' <!-- adv: 468x60 center --><div class="adv_content"><div class="adv_468">'.tfuse_page_options('hook_adsense').'</div></div><!--/ adv: 468x60 center -->';
                }
                elseif(tfuse_page_options('hook_image')&&!tfuse_page_options('hook_adsense'))
                {
                    echo '
                    <!-- adv: 468x60 center -->
                    <div class="adv_content">
                            <div class="adv_468"><a href="'.tfuse_page_options('hook_url').'"  target="_blank"><img src="'.tfuse_page_options('hook_image').'" width="460" height="60" alt="advert"></a></div>
                    </div>
                    <!--/ adv: 468x60 center -->
                    ';
                }
                else
                {
                    echo '';
                }
            }
            elseif(!tfuse_page_options('hook_space') && !tfuse_options('content_ads_space'))
            {
                if(!tfuse_options('content_ads_space')&&!tfuse_options('hook_image_admin')&&!tfuse_options('hook_adsense_admin')){
                    echo '
                        <!-- adv: 468x60 center -->
                        <div class="adv_content">
                                        <div class="adv_468"><img src="'.tfuse_get_file_uri('/images/adv_468x60.png').'" width="460" height="60" alt="advert"></div>
                                </div>
                        <!--/ adv: 468x60 center -->';
                }
                elseif(tfuse_options('hook_adsense_admin')&&!tfuse_options('hook_image_admin')||tfuse_options('hook_adsense_admin')&&tfuse_options('hook_image_admin'))
                {
                    echo  ' <!-- adv: 468x60 center --><div class="adv_content"><div class="adv_468">'.tfuse_options('hook_adsense_admin').'</div></div><!--/ adv: 468x60 center -->';
                }
                elseif(tfuse_options('hook_image_admin')&&!tfuse_options('hook_adsense_admin'))
                {
                    echo '
                    <!-- adv: 468x60 center -->
                    <div class="adv_content">
                            <div class="adv_468"><a href="'.tfuse_options('hook_url_admin').'"  target="_blank"><img src="'.tfuse_options('hook_image_admin').'" width="460" height="60" alt="advert"></a></div>
                    </div>
                    <!--/ adv: 468x60 center -->
                    ';
                }
                else
                {
                    echo '';
                }
            }
        }
        elseif(is_single() && !is_page())
        {
            $cat_name = get_the_category();
            $post = get_post($post->ID);
            $post_type = $post->post_type;
            $taxonomies = get_object_taxonomies($post_type);
            if(!empty($taxonomies))
            {
                if($taxonomies[0] == 'category') {
                    $cat_id = $cat_name[0]->cat_ID;
                }
                elseif($taxonomies[0] == 'group')
                {
                    $terms = wp_get_post_terms($post->ID,'group');
                    $cat_id = $terms[0]->term_id;
                }
            }
            if(!tfuse_page_options('content_ads_post'))
            {
                if (tfuse_page_options('hook_space')=='true')
                {
                    if(tfuse_page_options('hook_space')&&!tfuse_page_options('hook_image')&&!tfuse_page_options('hook_adsense')){
                        echo '
                            <!-- adv: 468x60 center -->
                            <div class="adv_content">
                                            <div class="adv_468"><img src="'.tfuse_get_file_uri('/images/adv_468x60.png').'" width="460" height="60" alt="advert"></div>
                                    </div>
                            <!--/ adv: 468x60 center -->';
                    }
                    elseif(tfuse_page_options('hook_adsense')&&!tfuse_page_options('hook_image')||tfuse_page_options('hook_adsense')&&tfuse_page_options('hook_image'))
                    {
                        echo  ' <!-- adv: 468x60 center --><div class="adv_content"><div class="adv_468">'.tfuse_page_options('hook_adsense').'</div></div><!--/ adv: 468x60 center -->';
                    }
                    elseif(tfuse_page_options('hook_image')&&!tfuse_page_options('hook_adsense'))
                    {
                        echo '
                        <!-- adv: 468x60 center -->
                        <div class="adv_content">
                                <div class="adv_468"><a href="'.tfuse_page_options('hook_url').'"  target="_blank"><img src="'.tfuse_page_options('hook_image').'" width="460" height="60" alt="advert"></a></div>
                        </div>
                        <!--/ adv: 468x60 center -->
                        ';
                    }
                    else
                    {
                        echo '';
                    }
                }
            }
            elseif(tfuse_page_options('content_ads_post') && tfuse_options('hook_space',null,$cat_id))
            {
                if(tfuse_options('hook_space',null,$cat_id)&&!tfuse_options('hook_image',null,$cat_id)&&!tfuse_options('hook_adsense',null,$cat_id)){
                    echo '
                        <!-- adv: 468x60 center -->
                        <div class="adv_content">
                                        <div class="adv_468"><img src="'.tfuse_get_file_uri('/images/adv_468x60.png').'" width="460" height="60" alt="advert"></div>
                                </div>
                        <!--/ adv: 468x60 center -->';
                }
                elseif(tfuse_options('hook_adsense',null,$cat_id)&&!tfuse_options('hook_image',null,$cat_id)||tfuse_options('hook_adsense',null,$cat_id)&&tfuse_options('hook_image',null,$cat_id))
                {
                    echo  ' <!-- adv: 468x60 center --><div class="adv_content"><div class="adv_468">'.tfuse_options('hook_adsense',null,$cat_id).'</div></div><!--/ adv: 468x60 center -->';
                }
                elseif(tfuse_options('hook_image',null,$cat_id)&&!tfuse_options('hook_adsense',null,$cat_id))
                {
                    echo '
                    <!-- adv: 468x60 center -->
                    <div class="adv_content">
                            <div class="adv_468"><a href="'.tfuse_options('hook_url',null,$cat_id).'"  target="_blank"><img src="'.tfuse_options('hook_image',null,$cat_id).'" width="460" height="60" alt="advert"></a></div>
                    </div>
                    <!--/ adv: 468x60 center -->
                    ';
                }
                else
                {
                    echo '';
                }
            }
            elseif(!tfuse_options('hook_space',null,$cat_id) && !tfuse_options('content_ads_space'))
            {
                if(!tfuse_options('content_ads_space')&&!tfuse_options('hook_image_admin')&&!tfuse_options('hook_adsense_admin')){
                    echo '
                        <!-- adv: 468x60 center -->
                        <div class="adv_content">
                                        <div class="adv_468"><img src="'.tfuse_get_file_uri('/images/adv_468x60.png').'" width="460" height="60" alt="advert"></div>
                                </div>
                        <!--/ adv: 468x60 center -->';
                }
                elseif(tfuse_options('hook_adsense_admin')&&!tfuse_options('hook_image_admin')||tfuse_options('hook_adsense_admin')&&tfuse_options('hook_image_admin'))
                {
                    echo  ' <!-- adv: 468x60 center --><div class="adv_content"><div class="adv_468">'.tfuse_options('hook_adsense_admin').'</div></div><!--/ adv: 468x60 center -->';
                }
                elseif(tfuse_options('hook_image_admin')&&!tfuse_options('hook_adsense_admin'))
                {
                    echo '
                    <!-- adv: 468x60 center -->
                    <div class="adv_content">
                            <div class="adv_468"><a href="'.tfuse_options('hook_url_admin').'"  target="_blank"><img src="'.tfuse_options('hook_image_admin').'" width="460" height="60" alt="advert"></a></div>
                    </div>
                    <!--/ adv: 468x60 center -->
                    ';
                }
                else
                {
                    echo '';
                }
            }
        }
        elseif(is_category())
        {
            $id = get_query_var('cat');
            if (tfuse_options('hook_space',null,$id))
            {
                if(tfuse_options('hook_space',null,$id)&&!tfuse_options('hook_image',null,$id)&&!tfuse_options('hook_adsense',null,$id)){
                    echo '
                        <!-- adv: 468x60 center -->
                        <div class="adv_content">
                                        <div class="adv_468"><img src="'.tfuse_get_file_uri('/images/adv_468x60.png').'" width="460" height="60" alt="advert"></div>
                                </div>
                        <!--/ adv: 468x60 center -->';
                }
                elseif(tfuse_options('hook_adsense',null,$id)&&!tfuse_options('hook_image',null,$id)||tfuse_options('hook_adsense',null,$id)&&tfuse_options('hook_image',null,$id))
                {
                    echo  ' <!-- adv: 468x60 center --><div class="adv_content"><div class="adv_468">'.tfuse_options('hook_adsense',null,$id).'</div></div><!--/ adv: 468x60 center -->';
                }
                elseif(tfuse_options('hook_image',null,$id)&&!tfuse_options('hook_adsense',null,$id))
                {
                    echo '
                    <!-- adv: 468x60 center -->
                    <div class="adv_content">
                            <div class="adv_468"><a href="'.tfuse_options('hook_url',null,$id).'"  target="_blank"><img src="'.tfuse_options('hook_image',null,$id).'" width="460" height="60" alt="advert"></a></div>
                    </div>
                    <!--/ adv: 468x60 center -->
                    ';
                }
                else
                {
                    echo '';
                }
            }
            elseif(!tfuse_options('hook_space',null,$id) && !tfuse_options('content_ads_space'))
            {
                if(!tfuse_options('content_ads_space')&&!tfuse_options('hook_image_admin')&&!tfuse_options('hook_adsense_admin')){
                    echo '
                        <!-- adv: 468x60 center -->
                        <div class="adv_content">
                                        <div class="adv_468"><img src="'.tfuse_get_file_uri('/images/adv_468x60.png').'" width="460" height="60" alt="advert"></div>
                                </div>
                        <!--/ adv: 468x60 center -->';
                }
                elseif(tfuse_options('hook_adsense_admin')&&!tfuse_options('hook_image_admin')||tfuse_options('hook_adsense_admin')&&tfuse_options('hook_image_admin'))
                {
                    echo  ' <!-- adv: 468x60 center --><div class="adv_content"><div class="adv_468">'.tfuse_options('hook_adsense_admin').'</div></div><!--/ adv: 468x60 center -->';
                }
                elseif(tfuse_options('hook_image_admin')&&!tfuse_options('hook_adsense_admin'))
                {
                    echo '
                    <!-- adv: 468x60 center -->
                    <div class="adv_content">
                            <div class="adv_468"><a href="'.tfuse_options('hook_url_admin').'"  target="_blank"><img src="'.tfuse_options('hook_image_admin').'" width="460" height="60" alt="advert"></a></div>
                    </div>
                    <!--/ adv: 468x60 center -->
                    ';
                }
                else
                {
                    echo '';
                }
            }
        }
        elseif(is_tax())
        {
            $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy') );
            $id = $term->term_id;
            if (tfuse_options('hook_space',null,$id))
            {
                if(tfuse_options('hook_space',null,$id)&&!tfuse_options('hook_image',null,$id)&&!tfuse_options('hook_adsense',null,$id)){
                    echo '
                        <!-- adv: 468x60 center -->
                        <div class="adv_content">
                                        <div class="adv_468"><img src="'.tfuse_get_file_uri('/images/adv_468x60.png').'" width="460" height="60" alt="advert"></div>
                                </div>
                        <!--/ adv: 468x60 center -->';
                }
                elseif(tfuse_options('hook_adsense',null,$id)&&!tfuse_options('hook_image',null,$id)||tfuse_options('hook_adsense',null,$id)&&tfuse_options('hook_image',null,$id))
                {
                    echo  ' <!-- adv: 468x60 center --><div class="adv_content"><div class="adv_468">'.tfuse_options('hook_adsense',null,$id).'</div></div><!--/ adv: 468x60 center -->';
                }
                elseif(tfuse_options('hook_image',null,$id)&&!tfuse_options('hook_adsense',null,$id))
                {
                    echo '
                    <!-- adv: 468x60 center -->
                    <div class="adv_content">
                            <div class="adv_468"><a href="'.tfuse_options('hook_url',null,$id).'"  target="_blank"><img src="'.tfuse_options('hook_image',null,$id).'" width="460" height="60" alt="advert"></a></div>
                    </div>
                    <!--/ adv: 468x60 center -->
                    ';
                }
                else
                {
                    echo '';
                }
            }
            elseif(!tfuse_options('hook_space',null,$id) && !tfuse_options('content_ads_space'))
            {
                if(!tfuse_options('content_ads_space')&&!tfuse_options('hook_image_admin')&&!tfuse_options('hook_adsense_admin')){
                    echo '
                        <!-- adv: 468x60 center -->
                        <div class="adv_content">
                                        <div class="adv_468"><img src="'.tfuse_get_file_uri('/images/adv_468x60.png').'" width="460" height="60" alt="advert"></div>
                                </div>
                        <!--/ adv: 468x60 center -->';
                }
                elseif(tfuse_options('hook_adsense_admin')&&!tfuse_options('hook_image_admin')||tfuse_options('hook_adsense_admin')&&tfuse_options('hook_image_admin'))
                {
                    echo  ' <!-- adv: 468x60 center --><div class="adv_content"><div class="adv_468">'.tfuse_options('hook_adsense_admin').'</div></div><!--/ adv: 468x60 center -->';
                }
                elseif(tfuse_options('hook_image_admin')&&!tfuse_options('hook_adsense_admin'))
                {
                    echo '
                    <!-- adv: 468x60 center -->
                    <div class="adv_content">
                            <div class="adv_468"><a href="'.tfuse_options('hook_url_admin').'"  target="_blank"><img src="'.tfuse_options('hook_image_admin').'" width="460" height="60" alt="advert"></a></div>
                    </div>
                    <!--/ adv: 468x60 center -->
                    ';
                }
                else
                {
                    echo '';
                }
            }
        }
    }
endif;

//before content 125x125 ads from frame
if (!function_exists('tfuse_bfc_ads_admin')) :
    function tfuse_bfc_ads_admin()
    {
        if(!tfuse_options('bfc_ads_space'))
        {
            if(tfuse_options('bfcontent_number') == 'one' )
            {
                $adds1 = tfuse_options('bfcontent_ads_image1');
                if(!tfuse_options('bfcontent_ads_space')&&!$adds1&&!tfuse_options('bfcontent_ads_adsense1')){
                    echo  '
                        <!-- adv before content -->
                                <div class="adv_before_content">
                                        <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                </div>
                        <!--/ adv before content -->';
                }
                elseif(tfuse_options('bfcontent_ads_adsense1'))
                {
                    echo '<div class="adv_before_content">
                <div class="adv_125">'.tfuse_options('bfcontent_ads_adsense1').'</div>
                </div>';
                }
                elseif($adds1)
                {
                    echo '
                    <!-- adv before content -->
                    <div class="adv_before_content">
                            <div class="adv_125"><a href="'.tfuse_options('bfcontent_ads_url1').'"  target="_blank"><img src="'.$adds1.'" width="125" height="125" alt="advert"></a></div>
                    </div>
                    <!--/ adv before content -->
                    ';
                }
                else
                {
                    echo '';
                }
            }
            elseif(tfuse_options('bfcontent_number') == 'two' )
            {
                $adds1 = tfuse_options('bfcontent_ads_image1');
                $adds2 = tfuse_options('bfcontent_ads_image2');
                $adsense1 = tfuse_options('bfcontent_ads_adsense1');
                $adsense2 = tfuse_options('bfcontent_ads_adsense2');
                if(!tfuse_options('bfcontent_ads_space')&&!$adds1&&!$adsense1 &&!$adds2&&!$adsense2){
                    echo  '
                        <!-- adv before content -->
                                <div class="adv_before_content">
                                        <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                        <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                </div>
                        <!--/ adv before content -->';
                }
                elseif($adsense1 || $adsense2)
                {
                    echo '<div class="adv_before_content">
                <div class="adv_125">'.$adsense1.'</div>
                <div class="adv_125">'.$adsense2.'</div>
                </div>';
                }
                elseif($adds1 || $adds2 )
                {
                    echo '
                    <!-- adv before content -->
                    <div class="adv_before_content">
                            <div class="adv_125"><a href="'.tfuse_options('bfcontent_ads_url1').'"  target="_blank"><img src="'.$adds1.'" width="125" height="125" alt="advert"></a></div>
                            <div class="adv_125"><a href="'.tfuse_options('bfcontent_ads_url2').'"  target="_blank"><img src="'.$adds2.'" width="125" height="125" alt="advert"></a></div>
                    </div>
                    <!--/ adv before content -->
                    ';
                }
                else
                {
                    echo '';
                }
            }
            elseif(tfuse_options('bfcontent_number') == 'three' )
            {
                $adds1 = tfuse_options('bfcontent_ads_image1');
                $adds2 = tfuse_options('bfcontent_ads_image2');
                $adds3 = tfuse_options('bfcontent_ads_image3');
                $adsense1 = tfuse_options('bfcontent_ads_adsense1');
                $adsense2 = tfuse_options('bfcontent_ads_adsense2');
                $adsense3 = tfuse_options('bfcontent_ads_adsense3');
                if(!tfuse_options('bfcontent_ads_space')&&!$adds1&&!$adsense1 &&!$adds2&&!$adsense2&&!$adds3&&!$adsense3){
                    echo  '
                        <!-- adv before content -->
                                <div class="adv_before_content">
                                        <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                        <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                        <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                </div>
                        <!--/ adv before content -->';
                }
                elseif($adsense1 || $adsense2 || $adsense3 )
                {
                    echo '<div class="adv_before_content">
                <div class="adv_125">'.$adsense1.'</div>
                <div class="adv_125">'.$adsense2.'</div>
                <div class="adv_125">'.$adsense3.'</div>
                </div>';
                }
                elseif($adds1 || $adds2 || $adds3 )
                {
                    echo '
                    <!-- adv before content -->
                    <div class="adv_before_content">
                            <div class="adv_125"><a href="'.tfuse_options('bfcontent_ads_url1').'"  target="_blank"><img src="'.$adds1.'" width="125" height="125" alt="advert"></a></div>
                            <div class="adv_125"><a href="'.tfuse_options('bfcontent_ads_url2').'"  target="_blank"><img src="'.$adds2.'" width="125" height="125" alt="advert"></a></div>
                            <div class="adv_125"><a href="'.tfuse_options('bfcontent_ads_url3').'"  target="_blank"><img src="'.$adds3.'" width="125" height="125" alt="advert"></a></div>
                    </div>
                    <!--/ adv before content -->
                    ';
                }
                else
                {
                    echo '';
                }
            }
            elseif(tfuse_options('bfcontent_number') == 'four' )
            {
                $adds1 = tfuse_options('bfcontent_ads_image1');
                $adds2 = tfuse_options('bfcontent_ads_image2');
                $adds3 = tfuse_options('bfcontent_ads_image3');
                $adds4 = tfuse_options('bfcontent_ads_image4');
                $adsense1 = tfuse_options('bfcontent_ads_adsense1');
                $adsense2 = tfuse_options('bfcontent_ads_adsense2');
                $adsense3 = tfuse_options('bfcontent_ads_adsense3');
                $adsense4 = tfuse_options('bfcontent_ads_adsense4');
                if(!tfuse_options('bfcontent_ads_space')&&!$adds1&&!$adsense1 &&!$adds2&&!$adsense2&&!$adds3&&!$adsense3&&!tfuse_page_options('bfcontent_ads_image4')&&!tfuse_page_options('bfcontent_ads_adsense4')){
                    echo  '
                        <!-- adv before content -->
                                <div class="adv_before_content">
                                        <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                        <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                        <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                        <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                </div>
                        <!--/ adv before content -->';
                }
                elseif($adsense1 || $adsense2 || $adsense3 || $adsense4)
                {
                    echo '<div class="adv_before_content">
                <div class="adv_125">'.$adsense1.'</div>
                <div class="adv_125">'.$adsense2.'</div>
                <div class="adv_125">'.$adsense3.'</div>
                <div class="adv_125">'.$adsense4.'</div>
                </div>';
                }
                elseif($adds1 || $adds2 || $adds3 || $adds4)
                {
                    echo '
                    <!-- adv before content -->
                    <div class="adv_before_content">
                            <div class="adv_125"><a href="'.tfuse_options('bfcontent_ads_url1').'"  target="_blank"><img src="'.$adds1.'" width="125" height="125" alt="advert"></a></div>
                            <div class="adv_125"><a href="'.tfuse_options('bfcontent_ads_url2').'"  target="_blank"><img src="'.$adds2.'" width="125" height="125" alt="advert"></a></div>
                            <div class="adv_125"><a href="'.tfuse_options('bfcontent_ads_url3').'"  target="_blank"><img src="'.$adds3.'" width="125" height="125" alt="advert"></a></div>
                            <div class="adv_125"><a href="'.tfuse_options('bfcontent_ads_url4').'"  target="_blank"><img src="'.$adds4.'" width="125" height="125" alt="advert"></a></div>
                    </div>
                    <!--/ adv before content -->
                    ';
                }
                else
                {
                    echo '';
                }
            }
            elseif(tfuse_options('bfcontent_number') == 'five' )
            {
                $adds1 = tfuse_options('bfcontent_ads_image1');
                $adds2 = tfuse_options('bfcontent_ads_image2');
                $adds3 = tfuse_options('bfcontent_ads_image3');
                $adds4 = tfuse_options('bfcontent_ads_image4');
                $adds5 = tfuse_options('bfcontent_ads_image5');
                $adsense1 = tfuse_options('bfcontent_ads_adsense1');
                $adsense2 = tfuse_options('bfcontent_ads_adsense2');
                $adsense3 = tfuse_options('bfcontent_ads_adsense3');
                $adsense4 = tfuse_options('bfcontent_ads_adsense4');
                $adsense5 = tfuse_options('bfcontent_ads_adsense5');
                if(!tfuse_options('bfcontent_ads_space')&&!$adds1&&!$adsense1 &&!$adds2&&!$adsense2&&!$adds3&&!$adsense3&&!$adds4&&!$adsense4&&!$adds5&&!$adsense5){
                    echo  '
                        <!-- adv before content -->
                                <div class="adv_before_content">
                                        <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                        <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                        <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                        <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                        <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                </div>
                        <!--/ adv before content -->';
                }
                elseif($adsense1 || $adsense2 || $adsense3 || $adsense4 || $adsense5)
                {
                    echo '<div class="adv_before_content">
                <div class="adv_125">'.$adsense1.'</div>
                <div class="adv_125">'.$adsense2.'</div>
                <div class="adv_125">'.$adsense3.'</div>
                <div class="adv_125">'.$adsense4.'</div>
                <div class="adv_125">'.$adsense5.'</div>
                </div>';
                }
                elseif($adds1 || $adds2 || $adds3 || $adds4 || $adds5)
                {
                    echo '
                    <!-- adv before content -->
                    <div class="adv_before_content">
                            <div class="adv_125"><a href="'.tfuse_options('bfcontent_ads_url1').'"  target="_blank"><img src="'.$adds1.'" width="125" height="125" alt="advert"></a></div>
                            <div class="adv_125"><a href="'.tfuse_options('bfcontent_ads_url2').'"  target="_blank"><img src="'.$adds2.'" width="125" height="125" alt="advert"></a></div>
                            <div class="adv_125"><a href="'.tfuse_options('bfcontent_ads_url3').'"  target="_blank"><img src="'.$adds3.'" width="125" height="125" alt="advert"></a></div>
                            <div class="adv_125"><a href="'.tfuse_options('bfcontent_ads_url4').'"  target="_blank"><img src="'.$adds4.'" width="125" height="125" alt="advert"></a></div>
                            <div class="adv_125"><a href="'.tfuse_options('bfcontent_ads_url5').'"  target="_blank"><img src="'.$adds5.'" width="125" height="125" alt="advert"></a></div>
                    </div>
                    <!--/ adv before content -->
                    ';
                }
                else
                {
                    echo '';
                }
            }
            elseif(tfuse_options('bfcontent_number') == 'six' )
            {
                $adds1 = tfuse_options('bfcontent_ads_image1');
                $adds2 = tfuse_options('bfcontent_ads_image2');
                $adds3 = tfuse_options('bfcontent_ads_image3');
                $adds4 = tfuse_options('bfcontent_ads_image4');
                $adds5 = tfuse_options('bfcontent_ads_image5');
                $adds6 = tfuse_options('bfcontent_ads_image6');
                $adsense1 = tfuse_options('bfcontent_ads_adsense1');
                $adsense2 = tfuse_options('bfcontent_ads_adsense2');
                $adsense3 = tfuse_options('bfcontent_ads_adsense3');
                $adsense4 = tfuse_options('bfcontent_ads_adsense4');
                $adsense5 = tfuse_options('bfcontent_ads_adsense5');
                $adsense6 = tfuse_options('bfcontent_ads_adsense6');
                if(!tfuse_options('bfcontent_ads_space')&&!$adds1&&!$adsense1 &&!$adds2&&!$adsense2&&!$adds3&&!$adsense3&&!$adds4&&!$adsense4&&!$adds5&&!$adsense5&&!$adds6&&!$adsense6){
                    echo  '
                    <!-- adv before content -->
                            <div class="adv_before_content">
                                    <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                    <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                    <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                    <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                    <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                    <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                            </div>
                    <!--/ adv before content -->';
                }
                elseif($adsense1 || $adsense2 || $adsense3 || $adsense4 || $adsense5 || $adsense6 )
                {
                    echo '<div class="adv_before_content">
                <div class="adv_125">'.$adsense1.'</div>
                <div class="adv_125">'.$adsense2.'</div>
                <div class="adv_125">'.$adsense3.'</div>
                <div class="adv_125">'.$adsense4.'</div>
                <div class="adv_125">'.$adsense5.'</div>
                <div class="adv_125">'.$adsense6.'</div>
                </div>';
                }
                elseif($adds1 || $adds2 || $adds3 || $adds4 || $adds5 || $adds6 )
                {
                    echo '
                    <!-- adv before content -->
                    <div class="adv_before_content">
                            <div class="adv_125"><a href="'.tfuse_options('bfcontent_ads_url1').'"  target="_blank"><img src="'.$adds1.'" width="125" height="125" alt="advert"></a></div>
                            <div class="adv_125"><a href="'.tfuse_options('bfcontent_ads_url2').'"  target="_blank"><img src="'.$adds2.'" width="125" height="125" alt="advert"></a></div>
                            <div class="adv_125"><a href="'.tfuse_options('bfcontent_ads_url3').'"  target="_blank"><img src="'.$adds3.'" width="125" height="125" alt="advert"></a></div>
                            <div class="adv_125"><a href="'.tfuse_options('bfcontent_ads_url4').'"  target="_blank"><img src="'.$adds4.'" width="125" height="125" alt="advert"></a></div>
                            <div class="adv_125"><a href="'.tfuse_options('bfcontent_ads_url5').'"  target="_blank"><img src="'.$adds5.'" width="125" height="125" alt="advert"></a></div>
                            <div class="adv_125"><a href="'.tfuse_options('bfcontent_ads_url6').'"  target="_blank"><img src="'.$adds6.'" width="125" height="125" alt="advert"></a></div>
                    </div>
                    <!--/ adv before content -->
                    ';
                }
                else
                {
                    echo '';
                }
            }
            elseif(tfuse_options('bfcontent_number') == 'seven' )
            {
                $adds1 = tfuse_options('bfcontent_ads_image1');
                $adds2 = tfuse_options('bfcontent_ads_image2');
                $adds3 = tfuse_options('bfcontent_ads_image3');
                $adds4 = tfuse_options('bfcontent_ads_image4');
                $adds5 = tfuse_options('bfcontent_ads_image5');
                $adds6 = tfuse_options('bfcontent_ads_image6');
                $adds7 = tfuse_options('bfcontent_ads_image7');
                $adsense1 = tfuse_options('bfcontent_ads_adsense1');
                $adsense2 = tfuse_options('bfcontent_ads_adsense2');
                $adsense3 = tfuse_options('bfcontent_ads_adsense3');
                $adsense4 = tfuse_options('bfcontent_ads_adsense4');
                $adsense5 = tfuse_options('bfcontent_ads_adsense5');
                $adsense6 = tfuse_options('bfcontent_ads_adsense6');
                $adsense7 = tfuse_options('bfcontent_ads_adsense7');
                if(!tfuse_options('bfcontent_ads_space')&&!$adds1&&!$adsense1 &&!$adds2&&!$adsense2&&!$adds3&&!$adsense3&&!$adds4&&!$adsense4&&!$adds5&&!$adsense5&&!$adds6&&!$adsense6&&!$adds7&&!$adsense7){
                    echo  '
                    <!-- adv before content -->
                            <div class="adv_before_content">
                                    <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                    <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                    <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                    <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                    <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                    <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                    <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                            </div>
                    <!--/ adv before content -->';
                }
                elseif($adsense1 || $adsense2 || $adsense3 || $adsense4 || $adsense5 || $adsense6 || $adsense7)
                {
                    echo '<div class="adv_before_content">
                <div class="adv_125">'.$adsense1.'</div>
                <div class="adv_125">'.$adsense2.'</div>
                <div class="adv_125">'.$adsense3.'</div>
                <div class="adv_125">'.$adsense4.'</div>
                <div class="adv_125">'.$adsense5.'</div>
                <div class="adv_125">'.$adsense6.'</div>
                <div class="adv_125">'.$adsense7.'</div>
                </div>';
                }
                elseif($adds1 || $adds2 || $adds3 || $adds4 || $adds5 || $adds6 || $adds7)
                {
                    echo '
                    <!-- adv before content -->
                    <div class="adv_before_content">
                        <div class="adv_125"><a href="'.tfuse_options('bfcontent_ads_url1').'"  target="_blank"><img src="'.$adds1.'" width="125" height="125" alt="advert"></a></div>
                        <div class="adv_125"><a href="'.tfuse_options('bfcontent_ads_url2').'"  target="_blank"><img src="'.$adds2.'" width="125" height="125" alt="advert"></a></div>
                        <div class="adv_125"><a href="'.tfuse_options('bfcontent_ads_url3').'"  target="_blank"><img src="'.$adds3.'" width="125" height="125" alt="advert"></a></div>
                        <div class="adv_125"><a href="'.tfuse_options('bfcontent_ads_url4').'"  target="_blank"><img src="'.$adds4.'" width="125" height="125" alt="advert"></a></div>
                        <div class="adv_125"><a href="'.tfuse_options('bfcontent_ads_url5').'"  target="_blank"><img src="'.$adds5.'" width="125" height="125" alt="advert"></a></div>
                        <div class="adv_125"><a href="'.tfuse_options('bfcontent_ads_url6').'"  target="_blank"><img src="'.$adds6.'" width="125" height="125" alt="advert"></a></div>
                        <div class="adv_125"><a href="'.tfuse_options('bfcontent_ads_url7').'"  target="_blank"><img src="'.$adds7.'" width="125" height="125" alt="advert"></a></div>
                    </div>
                    <!--/ adv before content -->
                    ';
                }
                else
                {
                    echo '';
                }
            }
        }
    }
endif;

//before content 125x125 ads from category
if (!function_exists('tfuse_bfc_ads_cat')) :
    function tfuse_bfc_ads_cat($cat_id)
    {
        if(tfuse_options('bfcontent_ads_space',null,$cat_id))
        {
            if(tfuse_options('bfcontent_number',null,$cat_id) == 'one' )
            {
                $adds1 = tfuse_options('bfcontent_ads_image1',null,$cat_id);
                if(tfuse_options('bfcontent_ads_space',null,$cat_id)=='1'&&!$adds1&&!tfuse_options('bfcontent_ads_adsense1',null,$cat_id)){
                    echo  '
                        <!-- adv before content -->
                                <div class="adv_before_content">
                                        <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                </div>
                        <!--/ adv before content -->';
                }
                elseif(tfuse_options('bfcontent_ads_adsense1',null,$cat_id))
                {
                    echo '<div class="adv_before_content">
                <div class="adv_125">'.tfuse_options('bfcontent_ads_adsense1',null,$cat_id).'</div>
                </div>';
                }
                elseif($adds1)
                {
                    echo '
                    <!-- adv before content -->
                    <div class="adv_before_content">
                            <div class="adv_125"><a href="'.tfuse_options('bfcontent_ads_url1',null,$cat_id).'"  target="_blank"><img src="'.$adds1.'" width="125" height="125" alt="advert"></a></div>
                    </div>
                    <!--/ adv before content -->
                    ';
                }
                else
                {
                    echo '';
                }
            }
            elseif(tfuse_options('bfcontent_number',null,$cat_id) == 'two' )
            {
                $adds1 = tfuse_options('bfcontent_ads_image1',null,$cat_id);
                $adds2 = tfuse_options('bfcontent_ads_image2',null,$cat_id);
                $adsense1 = tfuse_options('bfcontent_ads_adsense1',null,$cat_id);
                $adsense2 = tfuse_options('bfcontent_ads_adsense2',null,$cat_id);
                if(tfuse_options('bfcontent_ads_space',null,$cat_id)=='1'&&!$adds1&&!$adsense1 &&!$adds2&&!$adsense2){
                    echo  '
                        <!-- adv before content -->
                                <div class="adv_before_content">
                                        <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                        <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                </div>
                        <!--/ adv before content -->';
                }
                elseif($adsense1 || $adsense2)
                {
                    echo '<div class="adv_before_content">
                <div class="adv_125">'.$adsense1.'</div>
                <div class="adv_125">'.$adsense2.'</div>
                </div>';
                }
                elseif($adds1 || $adds2 )
                {
                    echo '
                    <!-- adv before content -->
                    <div class="adv_before_content">
                            <div class="adv_125"><a href="'.tfuse_options('bfcontent_ads_url1',null,$cat_id).'"  target="_blank"><img src="'.$adds1.'" width="125" height="125" alt="advert"></a></div>
                            <div class="adv_125"><a href="'.tfuse_options('bfcontent_ads_url2',null,$cat_id).'"  target="_blank"><img src="'.$adds2.'" width="125" height="125" alt="advert"></a></div>
                    </div>
                    <!--/ adv before content -->
                    ';
                }
                else
                {
                    echo '';
                }
            }
            elseif(tfuse_options('bfcontent_number',null,$cat_id) == 'three' )
            {
                $adds1 = tfuse_options('bfcontent_ads_image1',null,$cat_id);
                $adds2 = tfuse_options('bfcontent_ads_image2',null,$cat_id);
                $adds3 = tfuse_options('bfcontent_ads_image3',null,$cat_id);
                $adsense1 = tfuse_options('bfcontent_ads_adsense1',null,$cat_id);
                $adsense2 = tfuse_options('bfcontent_ads_adsense2',null,$cat_id);
                $adsense3 = tfuse_options('bfcontent_ads_adsense3',null,$cat_id);
                if(tfuse_options('bfcontent_ads_space',null,$cat_id)=='1'&&!$adds1&&!$adsense1 &&!$adds2&&!$adsense2&&!$adds3&&!$adsense3){
                    echo  '
                        <!-- adv before content -->
                                <div class="adv_before_content">
                                        <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                        <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                        <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                </div>
                        <!--/ adv before content -->';
                }
                elseif($adsense1 || $adsense2 || $adsense3 )
                {
                    echo '<div class="adv_before_content">
                <div class="adv_125">'.$adsense1.'</div>
                <div class="adv_125">'.$adsense2.'</div>
                <div class="adv_125">'.$adsense3.'</div>
                </div>';
                }
                elseif($adds1 || $adds2 || $adds3 )
                {
                    echo '
                    <!-- adv before content -->
                    <div class="adv_before_content">
                            <div class="adv_125"><a href="'.tfuse_options('bfcontent_ads_url1',null,$cat_id).'"  target="_blank"><img src="'.$adds1.'" width="125" height="125" alt="advert"></a></div>
                            <div class="adv_125"><a href="'.tfuse_options('bfcontent_ads_url2',null,$cat_id).'"  target="_blank"><img src="'.$adds2.'" width="125" height="125" alt="advert"></a></div>
                            <div class="adv_125"><a href="'.tfuse_options('bfcontent_ads_url3',null,$cat_id).'"  target="_blank"><img src="'.$adds3.'" width="125" height="125" alt="advert"></a></div>
                    </div>
                    <!--/ adv before content -->
                    ';
                }
                else
                {
                    echo '';
                }
            }
            elseif(tfuse_options('bfcontent_number',null,$cat_id) == 'four' )
            {
                $adds1 = tfuse_options('bfcontent_ads_image1',null,$cat_id);
                $adds2 = tfuse_options('bfcontent_ads_image2',null,$cat_id);
                $adds3 = tfuse_options('bfcontent_ads_image3',null,$cat_id);
                $adds4 = tfuse_options('bfcontent_ads_image4',null,$cat_id);
                $adsense1 = tfuse_options('bfcontent_ads_adsense1',null,$cat_id);
                $adsense2 = tfuse_options('bfcontent_ads_adsense2',null,$cat_id);
                $adsense3 = tfuse_options('bfcontent_ads_adsense3',null,$cat_id);
                $adsense4 = tfuse_options('bfcontent_ads_adsense4',null,$cat_id);
                if(tfuse_options('bfcontent_ads_space',null,$cat_id)=='1'&&!$adds1&&!$adsense1 &&!$adds2&&!$adsense2&&!$adds3&&!$adsense3&&!tfuse_page_options('bfcontent_ads_image4')&&!tfuse_page_options('bfcontent_ads_adsense4')){
                    echo  '
                        <!-- adv before content -->
                                <div class="adv_before_content">
                                        <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                        <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                        <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                        <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                </div>
                        <!--/ adv before content -->';
                }
                elseif($adsense1 || $adsense2 || $adsense3 || $adsense4)
                {
                    echo '<div class="adv_before_content">
                <div class="adv_125">'.$adsense1.'</div>
                <div class="adv_125">'.$adsense2.'</div>
                <div class="adv_125">'.$adsense3.'</div>
                <div class="adv_125">'.$adsense4.'</div>
                </div>';
                }
                elseif($adds1 || $adds2 || $adds3 || $adds4)
                {
                    echo '
                    <!-- adv before content -->
                    <div class="adv_before_content">
                            <div class="adv_125"><a href="'.tfuse_options('bfcontent_ads_url1',null,$cat_id).'"  target="_blank"><img src="'.$adds1.'" width="125" height="125" alt="advert"></a></div>
                            <div class="adv_125"><a href="'.tfuse_options('bfcontent_ads_url2',null,$cat_id).'"  target="_blank"><img src="'.$adds2.'" width="125" height="125" alt="advert"></a></div>
                            <div class="adv_125"><a href="'.tfuse_options('bfcontent_ads_url3',null,$cat_id).'"  target="_blank"><img src="'.$adds3.'" width="125" height="125" alt="advert"></a></div>
                            <div class="adv_125"><a href="'.tfuse_options('bfcontent_ads_url4',null,$cat_id).'"  target="_blank"><img src="'.$adds4.'" width="125" height="125" alt="advert"></a></div>
                    </div>
                    <!--/ adv before content -->
                    ';
                }
                else
                {
                    echo '';
                }
            }
            elseif(tfuse_options('bfcontent_number',null,$cat_id) == 'five' )
            {
                $adds1 = tfuse_options('bfcontent_ads_image1',null,$cat_id);
                $adds2 = tfuse_options('bfcontent_ads_image2',null,$cat_id);
                $adds3 = tfuse_options('bfcontent_ads_image3',null,$cat_id);
                $adds4 = tfuse_options('bfcontent_ads_image4',null,$cat_id);
                $adds5 = tfuse_options('bfcontent_ads_image5',null,$cat_id);
                $adsense1 = tfuse_options('bfcontent_ads_adsense1',null,$cat_id);
                $adsense2 = tfuse_options('bfcontent_ads_adsense2',null,$cat_id);
                $adsense3 = tfuse_options('bfcontent_ads_adsense3',null,$cat_id);
                $adsense4 = tfuse_options('bfcontent_ads_adsense4',null,$cat_id);
                $adsense5 = tfuse_options('bfcontent_ads_adsense5',null,$cat_id);
                if(tfuse_options('bfcontent_ads_space',null,$cat_id)=='1'&&!$adds1&&!$adsense1 &&!$adds2&&!$adsense2&&!$adds3&&!$adsense3&&!$adds4&&!$adsense4&&!$adds5&&!$adsense5){
                    echo  '
                        <!-- adv before content -->
                                <div class="adv_before_content">
                                        <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                        <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                        <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                        <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                        <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                </div>
                        <!--/ adv before content -->';
                }
                elseif($adsense1 || $adsense2 || $adsense3 || $adsense4 || $adsense5)
                {
                    echo '<div class="adv_before_content">
                <div class="adv_125">'.$adsense1.'</div>
                <div class="adv_125">'.$adsense2.'</div>
                <div class="adv_125">'.$adsense3.'</div>
                <div class="adv_125">'.$adsense4.'</div>
                <div class="adv_125">'.$adsense5.'</div>
                </div>';
                }
                elseif($adds1 || $adds2 || $adds3 || $adds4 || $adds5)
                {
                    echo '
                    <!-- adv before content -->
                    <div class="adv_before_content">
                            <div class="adv_125"><a href="'.tfuse_options('bfcontent_ads_url1',null,$cat_id).'"  target="_blank"><img src="'.$adds1.'" width="125" height="125" alt="advert"></a></div>
                            <div class="adv_125"><a href="'.tfuse_options('bfcontent_ads_url2',null,$cat_id).'"  target="_blank"><img src="'.$adds2.'" width="125" height="125" alt="advert"></a></div>
                            <div class="adv_125"><a href="'.tfuse_options('bfcontent_ads_url3',null,$cat_id).'"  target="_blank"><img src="'.$adds3.'" width="125" height="125" alt="advert"></a></div>
                            <div class="adv_125"><a href="'.tfuse_options('bfcontent_ads_url4',null,$cat_id).'"  target="_blank"><img src="'.$adds4.'" width="125" height="125" alt="advert"></a></div>
                            <div class="adv_125"><a href="'.tfuse_options('bfcontent_ads_url5',null,$cat_id).'"  target="_blank"><img src="'.$adds5.'" width="125" height="125" alt="advert"></a></div>
                    </div>
                    <!--/ adv before content -->
                    ';
                }
                else
                {
                    echo '';
                }
            }
            elseif(tfuse_options('bfcontent_number',null,$cat_id) == 'six' )
            {
                $adds1 = tfuse_options('bfcontent_ads_image1',null,$cat_id);
                $adds2 = tfuse_options('bfcontent_ads_image2',null,$cat_id);
                $adds3 = tfuse_options('bfcontent_ads_image3',null,$cat_id);
                $adds4 = tfuse_options('bfcontent_ads_image4',null,$cat_id);
                $adds5 = tfuse_options('bfcontent_ads_image5',null,$cat_id);
                $adds6 = tfuse_options('bfcontent_ads_image6',null,$cat_id);
                $adsense1 = tfuse_options('bfcontent_ads_adsense1',null,$cat_id);
                $adsense2 = tfuse_options('bfcontent_ads_adsense2',null,$cat_id);
                $adsense3 = tfuse_options('bfcontent_ads_adsense3',null,$cat_id);
                $adsense4 = tfuse_options('bfcontent_ads_adsense4',null,$cat_id);
                $adsense5 = tfuse_options('bfcontent_ads_adsense5',null,$cat_id);
                $adsense6 = tfuse_options('bfcontent_ads_adsense6',null,$cat_id);
                if(tfuse_options('bfcontent_ads_space',null,$cat_id)=='1'&&!$adds1&&!$adsense1 &&!$adds2&&!$adsense2&&!$adds3&&!$adsense3&&!$adds4&&!$adsense4&&!$adds5&&!$adsense5&&!$adds6&&!$adsense6){
                    echo  '
                    <!-- adv before content -->
                            <div class="adv_before_content">
                                    <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                    <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                    <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                    <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                    <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                    <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                            </div>
                    <!--/ adv before content -->';
                }
                elseif($adsense1 || $adsense2 || $adsense3 || $adsense4 || $adsense5 || $adsense6 )
                {
                    echo '<div class="adv_before_content">
                <div class="adv_125">'.$adsense1.'</div>
                <div class="adv_125">'.$adsense2.'</div>
                <div class="adv_125">'.$adsense3.'</div>
                <div class="adv_125">'.$adsense4.'</div>
                <div class="adv_125">'.$adsense5.'</div>
                <div class="adv_125">'.$adsense6.'</div>
                </div>';
                }
                elseif($adds1 || $adds2 || $adds3 || $adds4 || $adds5 || $adds6 )
                {
                    echo '
                    <!-- adv before content -->
                    <div class="adv_before_content">
                            <div class="adv_125"><a href="'.tfuse_options('bfcontent_ads_url1',null,$cat_id).'"  target="_blank"><img src="'.$adds1.'" width="125" height="125" alt="advert"></a></div>
                            <div class="adv_125"><a href="'.tfuse_options('bfcontent_ads_url2',null,$cat_id).'"  target="_blank"><img src="'.$adds2.'" width="125" height="125" alt="advert"></a></div>
                            <div class="adv_125"><a href="'.tfuse_options('bfcontent_ads_url3',null,$cat_id).'"  target="_blank"><img src="'.$adds3.'" width="125" height="125" alt="advert"></a></div>
                            <div class="adv_125"><a href="'.tfuse_options('bfcontent_ads_url4',null,$cat_id).'"  target="_blank"><img src="'.$adds4.'" width="125" height="125" alt="advert"></a></div>
                            <div class="adv_125"><a href="'.tfuse_options('bfcontent_ads_url5',null,$cat_id).'"  target="_blank"><img src="'.$adds5.'" width="125" height="125" alt="advert"></a></div>
                            <div class="adv_125"><a href="'.tfuse_options('bfcontent_ads_url6',null,$cat_id).'"  target="_blank"><img src="'.$adds6.'" width="125" height="125" alt="advert"></a></div>
                    </div>
                    <!--/ adv before content -->
                    ';
                }
                else
                {
                    echo '';
                }
            }
            elseif(tfuse_options('bfcontent_number',null,$cat_id) == 'seven' )
            {
                $adds1 = tfuse_options('bfcontent_ads_image1',null,$cat_id);
                $adds2 = tfuse_options('bfcontent_ads_image2',null,$cat_id);
                $adds3 = tfuse_options('bfcontent_ads_image3',null,$cat_id);
                $adds4 = tfuse_options('bfcontent_ads_image4',null,$cat_id);
                $adds5 = tfuse_options('bfcontent_ads_image5',null,$cat_id);
                $adds6 = tfuse_options('bfcontent_ads_image6',null,$cat_id);
                $adds7 = tfuse_options('bfcontent_ads_image7',null,$cat_id);
                $adsense1 = tfuse_options('bfcontent_ads_adsense1',null,$cat_id);
                $adsense2 = tfuse_options('bfcontent_ads_adsense2',null,$cat_id);
                $adsense3 = tfuse_options('bfcontent_ads_adsense3',null,$cat_id);
                $adsense4 = tfuse_options('bfcontent_ads_adsense4',null,$cat_id);
                $adsense5 = tfuse_options('bfcontent_ads_adsense5',null,$cat_id);
                $adsense6 = tfuse_options('bfcontent_ads_adsense6',null,$cat_id);
                $adsense7 = tfuse_options('bfcontent_ads_adsense7',null,$cat_id);
                if(tfuse_options('bfcontent_ads_space',null,$cat_id)=='1'&&!$adds1&&!$adsense1 &&!$adds2&&!$adsense2&&!$adds3&&!$adsense3&&!$adds4&&!$adsense4&&!$adds5&&!$adsense5&&!$adds6&&!$adsense6&&!$adds7&&!$adsense7){
                    echo  '
                    <!-- adv before content -->
                            <div class="adv_before_content">
                                    <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                    <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                    <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                    <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                    <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                    <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                    <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                            </div>
                    <!--/ adv before content -->';
                }
                elseif($adsense1 || $adsense2 || $adsense3 || $adsense4 || $adsense5 || $adsense6 || $adsense7)
                {
                    echo '<div class="adv_before_content">
                <div class="adv_125">'.$adsense1.'</div>
                <div class="adv_125">'.$adsense2.'</div>
                <div class="adv_125">'.$adsense3.'</div>
                <div class="adv_125">'.$adsense4.'</div>
                <div class="adv_125">'.$adsense5.'</div>
                <div class="adv_125">'.$adsense6.'</div>
                <div class="adv_125">'.$adsense7.'</div>
                </div>';
                }
                elseif($adds1 || $adds2 || $adds3 || $adds4 || $adds5 || $adds6 || $adds7)
                {
                    echo '
                    <!-- adv before content -->
                    <div class="adv_before_content">
                        <div class="adv_125"><a href="'.tfuse_options('bfcontent_ads_url1',null,$cat_id).'"  target="_blank"><img src="'.$adds1.'" width="125" height="125" alt="advert"></a></div>
                        <div class="adv_125"><a href="'.tfuse_options('bfcontent_ads_url2',null,$cat_id).'"  target="_blank"><img src="'.$adds2.'" width="125" height="125" alt="advert"></a></div>
                        <div class="adv_125"><a href="'.tfuse_options('bfcontent_ads_url3',null,$cat_id).'"  target="_blank"><img src="'.$adds3.'" width="125" height="125" alt="advert"></a></div>
                        <div class="adv_125"><a href="'.tfuse_options('bfcontent_ads_url4',null,$cat_id).'"  target="_blank"><img src="'.$adds4.'" width="125" height="125" alt="advert"></a></div>
                        <div class="adv_125"><a href="'.tfuse_options('bfcontent_ads_url5',null,$cat_id).'"  target="_blank"><img src="'.$adds5.'" width="125" height="125" alt="advert"></a></div>
                        <div class="adv_125"><a href="'.tfuse_options('bfcontent_ads_url6',null,$cat_id).'"  target="_blank"><img src="'.$adds6.'" width="125" height="125" alt="advert"></a></div>
                        <div class="adv_125"><a href="'.tfuse_options('bfcontent_ads_url7',null,$cat_id).'"  target="_blank"><img src="'.$adds7.'" width="125" height="125" alt="advert"></a></div>
                    </div>
                    <!--/ adv before content -->
                    ';
                }
                else
                {
                    echo '';
                }
            }
        }
    }
endif;

//before content 125x125 ads in post
if (!function_exists('tfuse_bfc_ads_post')) :
    function tfuse_bfc_ads_post()
    {
        if(tfuse_page_options('bfcontent_ads_space'))
        {
            if(tfuse_page_options('bfcontent_number') == 'one' )
            {
                $adds1 = tfuse_page_options('bfcontent_ads_image1');
                if(tfuse_page_options('bfcontent_ads_space')=='1'&&!$adds1&&!tfuse_page_options('bfcontent_ads_adsense1')){
                    echo  '
                        <!-- adv before content -->
                                <div class="adv_before_content">
                                        <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                </div>
                        <!--/ adv before content -->';
                }
                elseif(tfuse_page_options('bfcontent_ads_adsense1'))
                {
                    echo '<div class="adv_before_content">
                <div class="adv_125">'.tfuse_page_options('bfcontent_ads_adsense1').'</div>
                </div>';
                }
                elseif($adds1)
                {
                    echo '
                    <!-- adv before content -->
                    <div class="adv_before_content">
                            <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url1').'"  target="_blank"><img src="'.$adds1.'" width="125" height="125" alt="advert"></a></div>
                    </div>
                    <!--/ adv before content -->
                    ';
                }
                else
                {
                    echo '';
                }
            }
            elseif(tfuse_page_options('bfcontent_number') == 'two' )
            {
                $adds1 = tfuse_page_options('bfcontent_ads_image1');
                $adds2 = tfuse_page_options('bfcontent_ads_image2');
                $adsense1 = tfuse_page_options('bfcontent_ads_adsense1');
                $adsense2 = tfuse_page_options('bfcontent_ads_adsense2');
                if(tfuse_page_options('bfcontent_ads_space')=='1'&&!$adds1&&!$adsense1 &&!$adds2&&!$adsense2){
                    echo  '
                        <!-- adv before content -->
                                <div class="adv_before_content">
                                        <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                        <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                </div>
                        <!--/ adv before content -->';
                }
                elseif($adsense1 || $adsense2)
                {
                    echo '<div class="adv_before_content">
                <div class="adv_125">'.$adsense1.'</div>
                <div class="adv_125">'.$adsense2.'</div>
                </div>';
                }
                elseif($adds1 || $adds2 )
                {
                    echo '
                    <!-- adv before content -->
                    <div class="adv_before_content">
                            <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url1').'"  target="_blank"><img src="'.$adds1.'" width="125" height="125" alt="advert"></a></div>
                            <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url2').'"  target="_blank"><img src="'.$adds2.'" width="125" height="125" alt="advert"></a></div>
                    </div>
                    <!--/ adv before content -->
                    ';
                }
                else
                {
                    echo '';
                }
            }
            elseif(tfuse_page_options('bfcontent_number') == 'three' )
            {
                $adds1 = tfuse_page_options('bfcontent_ads_image1');
                $adds2 = tfuse_page_options('bfcontent_ads_image2');
                $adds3 = tfuse_page_options('bfcontent_ads_image3');
                $adsense1 = tfuse_page_options('bfcontent_ads_adsense1');
                $adsense2 = tfuse_page_options('bfcontent_ads_adsense2');
                $adsense3 = tfuse_page_options('bfcontent_ads_adsense3');
                if(tfuse_page_options('bfcontent_ads_space')=='1'&&!$adds1&&!$adsense1 &&!$adds2&&!$adsense2&&!$adds3&&!$adsense3){
                    echo  '
                        <!-- adv before content -->
                                <div class="adv_before_content">
                                        <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                        <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                        <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                </div>
                        <!--/ adv before content -->';
                }
                elseif($adsense1 || $adsense2 || $adsense3 )
                {
                    echo '<div class="adv_before_content">
                <div class="adv_125">'.$adsense1.'</div>
                <div class="adv_125">'.$adsense2.'</div>
                <div class="adv_125">'.$adsense3.'</div>
                </div>';
                }
                elseif($adds1 || $adds2 || $adds3 )
                {
                    echo '
                    <!-- adv before content -->
                    <div class="adv_before_content">
                            <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url1').'"  target="_blank"><img src="'.$adds1.'" width="125" height="125" alt="advert"></a></div>
                            <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url2').'"  target="_blank"><img src="'.$adds2.'" width="125" height="125" alt="advert"></a></div>
                            <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url3').'"  target="_blank"><img src="'.$adds3.'" width="125" height="125" alt="advert"></a></div>
                    </div>
                    <!--/ adv before content -->
                    ';
                }
                else
                {
                    echo '';
                }
            }
            elseif(tfuse_page_options('bfcontent_number') == 'four' )
            {
                $adds1 = tfuse_page_options('bfcontent_ads_image1');
                $adds2 = tfuse_page_options('bfcontent_ads_image2');
                $adds3 = tfuse_page_options('bfcontent_ads_image3');
                $adds4 = tfuse_page_options('bfcontent_ads_image4');
                $adsense1 = tfuse_page_options('bfcontent_ads_adsense1');
                $adsense2 = tfuse_page_options('bfcontent_ads_adsense2');
                $adsense3 = tfuse_page_options('bfcontent_ads_adsense3');
                $adsense4 = tfuse_page_options('bfcontent_ads_adsense4');
                if(tfuse_page_options('bfcontent_ads_space')=='1'&&!$adds1&&!$adsense1 &&!$adds2&&!$adsense2&&!$adds3&&!$adsense3&&!tfuse_page_options('bfcontent_ads_image4')&&!tfuse_page_options('bfcontent_ads_adsense4')){
                    echo  '
                        <!-- adv before content -->
                                <div class="adv_before_content">
                                        <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                        <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                        <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                        <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                </div>
                        <!--/ adv before content -->';
                }
                elseif($adsense1 || $adsense2 || $adsense3 || $adsense4)
                {
                    echo '<div class="adv_before_content">
                <div class="adv_125">'.$adsense1.'</div>
                <div class="adv_125">'.$adsense2.'</div>
                <div class="adv_125">'.$adsense3.'</div>
                <div class="adv_125">'.$adsense4.'</div>
                </div>';
                }
                elseif($adds1 || $adds2 || $adds3 || $adds4)
                {
                    echo '
                    <!-- adv before content -->
                    <div class="adv_before_content">
                            <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url1').'"  target="_blank"><img src="'.$adds1.'" width="125" height="125" alt="advert"></a></div>
                            <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url2').'"  target="_blank"><img src="'.$adds2.'" width="125" height="125" alt="advert"></a></div>
                            <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url3').'"  target="_blank"><img src="'.$adds3.'" width="125" height="125" alt="advert"></a></div>
                            <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url4').'"  target="_blank"><img src="'.$adds4.'" width="125" height="125" alt="advert"></a></div>
                    </div>
                    <!--/ adv before content -->
                    ';
                }
                else
                {
                    echo '';
                }
            }
            elseif(tfuse_page_options('bfcontent_number') == 'five' )
            {
                $adds1 = tfuse_page_options('bfcontent_ads_image1');
                $adds2 = tfuse_page_options('bfcontent_ads_image2');
                $adds3 = tfuse_page_options('bfcontent_ads_image3');
                $adds4 = tfuse_page_options('bfcontent_ads_image4');
                $adds5 = tfuse_page_options('bfcontent_ads_image5');
                $adsense1 = tfuse_page_options('bfcontent_ads_adsense1');
                $adsense2 = tfuse_page_options('bfcontent_ads_adsense2');
                $adsense3 = tfuse_page_options('bfcontent_ads_adsense3');
                $adsense4 = tfuse_page_options('bfcontent_ads_adsense4');
                $adsense5 = tfuse_page_options('bfcontent_ads_adsense5');
                if(tfuse_page_options('bfcontent_ads_space')=='1'&&!$adds1&&!$adsense1 &&!$adds2&&!$adsense2&&!$adds3&&!$adsense3&&!$adds4&&!$adsense4&&!$adds5&&!$adsense5){
                    echo  '
                        <!-- adv before content -->
                                <div class="adv_before_content">
                                        <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                        <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                        <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                        <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                        <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                </div>
                        <!--/ adv before content -->';
                }
                elseif($adsense1 || $adsense2 || $adsense3 || $adsense4 || $adsense5)
                {
                    echo '<div class="adv_before_content">
                <div class="adv_125">'.$adsense1.'</div>
                <div class="adv_125">'.$adsense2.'</div>
                <div class="adv_125">'.$adsense3.'</div>
                <div class="adv_125">'.$adsense4.'</div>
                <div class="adv_125">'.$adsense5.'</div>
                </div>';
                }
                elseif($adds1 || $adds2 || $adds3 || $adds4 || $adds5)
                {
                    echo '
                    <!-- adv before content -->
                    <div class="adv_before_content">
                            <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url1').'"  target="_blank"><img src="'.$adds1.'" width="125" height="125" alt="advert"></a></div>
                            <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url2').'"  target="_blank"><img src="'.$adds2.'" width="125" height="125" alt="advert"></a></div>
                            <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url3').'"  target="_blank"><img src="'.$adds3.'" width="125" height="125" alt="advert"></a></div>
                            <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url4').'"  target="_blank"><img src="'.$adds4.'" width="125" height="125" alt="advert"></a></div>
                            <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url5').'"  target="_blank"><img src="'.$adds5.'" width="125" height="125" alt="advert"></a></div>
                    </div>
                    <!--/ adv before content -->
                    ';
                }
                else
                {
                    echo '';
                }
            }
            elseif(tfuse_page_options('bfcontent_number') == 'six' )
            {
                $adds1 = tfuse_page_options('bfcontent_ads_image1');
                $adds2 = tfuse_page_options('bfcontent_ads_image2');
                $adds3 = tfuse_page_options('bfcontent_ads_image3');
                $adds4 = tfuse_page_options('bfcontent_ads_image4');
                $adds5 = tfuse_page_options('bfcontent_ads_image5');
                $adds6 = tfuse_page_options('bfcontent_ads_image6');
                $adsense1 = tfuse_page_options('bfcontent_ads_adsense1');
                $adsense2 = tfuse_page_options('bfcontent_ads_adsense2');
                $adsense3 = tfuse_page_options('bfcontent_ads_adsense3');
                $adsense4 = tfuse_page_options('bfcontent_ads_adsense4');
                $adsense5 = tfuse_page_options('bfcontent_ads_adsense5');
                $adsense6 = tfuse_page_options('bfcontent_ads_adsense6');
                if(tfuse_page_options('bfcontent_ads_space')=='1'&&!$adds1&&!$adsense1 &&!$adds2&&!$adsense2&&!$adds3&&!$adsense3&&!$adds4&&!$adsense4&&!$adds5&&!$adsense5&&!$adds6&&!$adsense6){
                    echo  '
                    <!-- adv before content -->
                            <div class="adv_before_content">
                                    <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                    <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                    <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                    <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                    <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                    <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                            </div>
                    <!--/ adv before content -->';
                }
                elseif($adsense1 || $adsense2 || $adsense3 || $adsense4 || $adsense5 || $adsense6 )
                {
                    echo '<div class="adv_before_content">
                <div class="adv_125">'.$adsense1.'</div>
                <div class="adv_125">'.$adsense2.'</div>
                <div class="adv_125">'.$adsense3.'</div>
                <div class="adv_125">'.$adsense4.'</div>
                <div class="adv_125">'.$adsense5.'</div>
                <div class="adv_125">'.$adsense6.'</div>
                </div>';
                }
                elseif($adds1 || $adds2 || $adds3 || $adds4 || $adds5 || $adds6 )
                {
                    echo '
                    <!-- adv before content -->
                    <div class="adv_before_content">
                            <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url1').'"  target="_blank"><img src="'.$adds1.'" width="125" height="125" alt="advert"></a></div>
                            <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url2').'"  target="_blank"><img src="'.$adds2.'" width="125" height="125" alt="advert"></a></div>
                            <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url3').'"  target="_blank"><img src="'.$adds3.'" width="125" height="125" alt="advert"></a></div>
                            <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url4').'"  target="_blank"><img src="'.$adds4.'" width="125" height="125" alt="advert"></a></div>
                            <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url5').'"  target="_blank"><img src="'.$adds5.'" width="125" height="125" alt="advert"></a></div>
                            <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url6').'"  target="_blank"><img src="'.$adds6.'" width="125" height="125" alt="advert"></a></div>
                    </div>
                    <!--/ adv before content -->
                    ';
                }
                else
                {
                    echo '';
                }
            }
            elseif(tfuse_page_options('bfcontent_number') == 'seven' )
            {
                $adds1 = tfuse_page_options('bfcontent_ads_image1');
                $adds2 = tfuse_page_options('bfcontent_ads_image2');
                $adds3 = tfuse_page_options('bfcontent_ads_image3');
                $adds4 = tfuse_page_options('bfcontent_ads_image4');
                $adds5 = tfuse_page_options('bfcontent_ads_image5');
                $adds6 = tfuse_page_options('bfcontent_ads_image6');
                $adds7 = tfuse_page_options('bfcontent_ads_image7');
                $adsense1 = tfuse_page_options('bfcontent_ads_adsense1');
                $adsense2 = tfuse_page_options('bfcontent_ads_adsense2');
                $adsense3 = tfuse_page_options('bfcontent_ads_adsense3');
                $adsense4 = tfuse_page_options('bfcontent_ads_adsense4');
                $adsense5 = tfuse_page_options('bfcontent_ads_adsense5');
                $adsense6 = tfuse_page_options('bfcontent_ads_adsense6');
                $adsense7 = tfuse_page_options('bfcontent_ads_adsense7');
                if(tfuse_page_options('bfcontent_ads_space')=='1'&&!$adds1&&!$adsense1 &&!$adds2&&!$adsense2&&!$adds3&&!$adsense3&&!$adds4&&!$adsense4&&!$adds5&&!$adsense5&&!$adds6&&!$adsense6&&!$adds7&&!$adsense7){
                    echo  '
                    <!-- adv before content -->
                            <div class="adv_before_content">
                                    <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                    <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                    <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                    <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                    <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                    <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                                    <div class="adv_125"><img src="'.tfuse_get_file_uri('/images/adv_125x125.png').'" width="125" height="125" alt="advert"></div>
                            </div>
                    <!--/ adv before content -->';
                }
                elseif($adsense1 || $adsense2 || $adsense3 || $adsense4 || $adsense5 || $adsense6 || $adsense7)
                {
                    echo '<div class="adv_before_content">
                <div class="adv_125">'.$adsense1.'</div>
                <div class="adv_125">'.$adsense2.'</div>
                <div class="adv_125">'.$adsense3.'</div>
                <div class="adv_125">'.$adsense4.'</div>
                <div class="adv_125">'.$adsense5.'</div>
                <div class="adv_125">'.$adsense6.'</div>
                <div class="adv_125">'.$adsense7.'</div>
                </div>';
                }
                elseif($adds1 || $adds2 || $adds3 || $adds4 || $adds5 || $adds6 || $adds7)
                {
                    echo '
                    <!-- adv before content -->
                    <div class="adv_before_content">
                        <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url1').'"  target="_blank"><img src="'.$adds1.'" width="125" height="125" alt="advert"></a></div>
                        <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url2').'"  target="_blank"><img src="'.$adds2.'" width="125" height="125" alt="advert"></a></div>
                        <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url3').'"  target="_blank"><img src="'.$adds3.'" width="125" height="125" alt="advert"></a></div>
                        <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url4').'"  target="_blank"><img src="'.$adds4.'" width="125" height="125" alt="advert"></a></div>
                        <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url5').'"  target="_blank"><img src="'.$adds5.'" width="125" height="125" alt="advert"></a></div>
                        <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url6').'"  target="_blank"><img src="'.$adds6.'" width="125" height="125" alt="advert"></a></div>
                        <div class="adv_125"><a href="'.tfuse_page_options('bfcontent_ads_url7').'"  target="_blank"><img src="'.$adds7.'" width="125" height="125" alt="advert"></a></div>
                    </div>
                    <!--/ adv before content -->
                    ';
                }
                else
                {
                    echo '';
                }
            }
        }
    }
endif;

if(!function_exists('tfuse_print_items_pos'))
{
    function tfuse_symbol_position($main_item, $dynamic_item, $position = 0, $divider = '', $return = false)
    {
        if($position == 0)
        {
            $out = $dynamic_item . $divider . $main_item;
        }
        else
        {
            $out = $main_item . $divider . $dynamic_item;
        }

        $out = apply_filters('tfuse_symbol_position_formatter', $out, $main_item, $dynamic_item);

        if($return)
        {
            return $out;
        }

        echo $out;
        return '';
    }
}

add_filter("comment_id_fields","tfuse_my_submit_comment_message");
function tfuse_my_submit_comment_message($result){
    return $result.'<a onclick="document.getElementById(&#39;addcomments&#39;).reset();return false" href="#" class="link-reset">'. __('Reset all fields', 'tfuse').'</a>';
}

if(!function_exists('tfuse_update_reservation_forms'))
{
    function tfuse_update_reservation_forms()
    {
        $forms = get_terms( 'reservations', array(
            'orderby'    => 'count',
            'hide_empty' => 0
        ) );

        $args = array(
            '0' =>  'text',
            '1' =>  'textarea',
            '2' =>  'radio',
            '3' =>  'checkbox',
            '4' =>  'select',
            '5' =>  'email',
            '6' =>  'captcha',
            '7' =>  'date_in',
            '8' =>  'date_out',
            '9' =>  'res_email',
        );

        foreach($forms as $form)
        {
            $check_option = get_option( 'tfuse_update_reservation_forms', 'none' );
            if($check_option == 'set')
            {
                return;
            }
            $description = unserialize($form->description);
            if(isset($description['version']) AND $description['version'] == '1.1')
                continue;

            foreach($description['input'] as $key => $input)
            {
                if(isset($args[$input['type']]))
                    $input['type'] = $args[$input['type']];
                $description['input'][$key]['type'] = $input['type'];
            }
            $description['version'] = '1.1';
            wp_update_term($form->term_id, 'reservations', array('description' => serialize($description)));
            add_option('tfuse_update_reservation_forms', 'set');
        }
    }
    add_action('wp_head', 'tfuse_update_reservation_forms');
}

if( !function_exists('tfuse_is_date') )
{
    /**
     * The function is_date() validates the date and returns true or false
     * @param $str sting expected valid date format
     * @return bool returns true if the supplied parameter is a valid date
     * otherwise false
     */
    function tfuse_is_date( $str ) {
        try {
            $dt = new DateTime( trim($str) );
        }
        catch( Exception $e ) {
            return false;
        }
        $month = $dt->format('m');
        $day = $dt->format('d');
        $year = $dt->format('Y');
        if( checkdate($month, $day, $year) ) {
            return true;
        }
        else {
            return false;
        }
    }
}


if( !function_exists('tfuse_vehicle_title') ) {
	/**
	 * Show vehicle title when header element is not a image
	 */
	function tfuse_vehicle_title() {
		$header_element = tfuse_page_options('header_element','none');
		if($header_element != 'image') : ?>
			<div class="header_title title_before_vehicle">
				<h1><?php the_title(); ?></h1>
			</div>
		<?php endif;
	}
}

if ( ! function_exists( 'tfuse_total_cat_post_count' ) ) :

    function tfuse_total_cat_post_count( $term_id, $taxonomy = 'categpry', $post_type = 'post' ) {
        $q = new WP_Query( array(
            'nopaging' => true,
            'post_type' => $post_type,
            'tax_query' => array(
                array(
                    'taxonomy' => $taxonomy,
                    'field' => 'id',
                    'terms' => $term_id,
                    'include_children' => true,
                ),
            ),
            'fields' => 'ids',
        ) );
        return $q->post_count;
    }

endif;


if( !function_exists('tf_lang_object_ids') ) :
    function tf_lang_object_ids($ids_array, $type) {
        if(function_exists('icl_object_id')) {
            $res = array();
            foreach ($ids_array as $id) {
                $xlat = icl_object_id($id, $type, false);
                if(!is_null($xlat)) $res[] = $xlat;
            }
            return $res;
        } else {
            return $ids_array;
        }
    }
endif;


if ( ! function_exists( 'tfuse_get_the_excerpt' ) ) :
	function tfuse_get_the_excerpt( $post ) {
		// if isset excerpt setted
		if ( ! empty( $post->post_excerpt ) ) {
			return $post->post_excerpt;
		}

		$the_excerpt    = $post->post_content;
		$excerpt_length = 50; // Sets excerpt length by word count, default in WP is 55
		$the_excerpt    = strip_tags( strip_shortcodes( $the_excerpt ) ); // Strips tags and images
		$words          = explode( ' ', $the_excerpt, $excerpt_length + 1 );

		if ( count( $words ) > $excerpt_length ) :
			array_pop( $words );
			//array_push( $words, '…' );
			$the_excerpt = implode( ' ', $words );
		endif;

		return $the_excerpt;
	}
endif;


add_action( 'brizy_shortcode_before_sidebar', 'tfuse_brizy_shortcode_before_sidebar' );
function tfuse_brizy_shortcode_before_sidebar() {
	echo '<div class="sidebar">';
}


add_action( 'brizy_shortcode_after_sidebar', 'tfuse_brizy_shortcode_after_sidebar' );
function tfuse_brizy_shortcode_after_sidebar() {
	echo '</div>';
}


/* Enable shortcodes in text widgets */
add_filter( 'widget_text', 'do_shortcode' );


add_filter( 'comment_form_submit_field', 'tfuse_filter_comment_form_submit_field', 999 );
function tfuse_filter_comment_form_submit_field( $submit ) {
	$enable_comments_gdpr = tfuse_options( 'comments_gdpr', false );
	if ( ! $enable_comments_gdpr ) {
		return $submit;
	}

	$tfuse_comments_checkbox = apply_filters(
		'tfuse_wordpress_comments_checkbox',
		'<p class="tfuse-checkbox"><label><input type="checkbox" name="tfuse_gdpr" id="tfuse_gdpr" value="" />'.tfuse_options( 'comments_gdpr_text', '' ).'</label></p>'
	);

	return $tfuse_comments_checkbox . $submit;
}


add_action( 'pre_comment_on_post', 'tfuse_action_pre_comment_on_post' );
function tfuse_action_pre_comment_on_post() {
	$enable_comments_gdpr = tfuse_options( 'comments_gdpr', false );
	if ( ! $enable_comments_gdpr ) {
		return;
	}

	if ( ! isset( $_POST['tfuse_gdpr'] ) ) {
		wp_die(
			'<p>' . sprintf(
				__( '<strong>ERROR</strong>: %s', 'tfuse' ),
				__( 'Please check the privacy checkbox before submit', 'tfuse' )
			) . '</p>',
			__( 'Comment Submission Failure' ),
			array( 'back_link' => true )
		);
	}
}


