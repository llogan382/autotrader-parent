<!doctype html>
<!--[if lt IE 7 ]><html <?php language_attributes(); ?> class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]><html <?php language_attributes(); ?> class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]><html <?php language_attributes(); ?> class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]><html <?php language_attributes(); ?> class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html <?php language_attributes(); ?> class="no-js"> <!--<![endif]-->
<head>
    <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title><?php
        if(tfuse_options('disable_tfuse_seo_tab')) {
            wp_title( '|', true, 'right' );
            bloginfo( 'name' );
            $site_description = get_bloginfo( 'description', 'display' );
            if ( $site_description && ( is_home() || is_front_page() ) ) echo " | $site_description";
        } else wp_title(''); ?>
    </title>
    <?php tfuse_meta(); ?>
    <link rel="profile" href="//gmpg.org/xfn/11" />
    <link href='//fonts.googleapis.com/css?family=Cabin:400,400italic,500,600,700|PT+Serif+Caption:400,400italic' rel='stylesheet' type='text/css'>
    <link rel="shortcut icon" href="favicon.ico">
    <!-- Mobile viewport optimized: h5bp.com/viewport -->
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php echo tfuse_options('feedburner_url', get_bloginfo_rss('rss2_url')); ?>" />
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
    <?php
        tfuse_head();
        wp_head();
        TF_SEEK_HELPER::register_search_parameters(array(
            'form_id'   => 'tfseekfid',
            'page'      => 'tfseekpage',
            'orderby'   => 'tfseekorderby'
        ));
        tfuse_print_theme_color_style();
        global $is_tf_blog_page,$TFUSE;
    ?>
</head>
<body <?php body_class(); ?> >
<?php tfuse_top_adds(); ?>
<div class="body_wrap <?php if(is_home() || is_front_page()) echo 'homepage'; ?>">
    <div class="header_top">
        <div class="container">
            <?php $favoritesCount = (!$TFUSE->request->empty_COOKIE('favorite_posts')) ? sizeof(explode(',',$TFUSE->request->COOKIE('favorite_posts'))) : 0;?>
            <div class="bookmarker"<?php echo(!$favoritesCount) ? ' style="display:none;"' : ''; ?>>
                <a href="<?php echo home_url('?s=~&tfseekfid=filter_search&favorites'); ?>"><span><?php echo $favoritesCount; ?></span> <?php printf( __('Saved %s', 'tfuse'), ($favoritesCount != 1) ? TF_SEEK_HELPER::get_option('seek_property_name_plural','Vehicles') : TF_SEEK_HELPER::get_option('seek_property_name_singular','Vehicle') ); ?></a>
            </div>
            <div class="logo">
                <a href="<?php echo home_url(); ?>"><img src="<?php echo tfuse_logo(); ?>" alt="<?php bloginfo('name'); ?>"></a>
            </div>
            <?php tfuse_menu('default'); ?>
        </div><!-- .container-->
    </div><!--/ .header_top -->

    <?php
        if (!is_search() && !$TFUSE->request->isset_GET(TF_SEEK_HELPER::get_search_parameter('form_id'))){
            tfuse_header_content('header');
            tfuse_breadcrumbs();
            tfuse_header_search();
            if($is_tf_blog_page) tfuse_category_on_blog_page();
        }
    ?>