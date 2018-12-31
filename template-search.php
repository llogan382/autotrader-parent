<?php
/**
 * The template for displaying Search Results pages.
 *
 * @since AutoTrader 1.0
 */
global $TFUSE;
// Hack search <title>
$s_backup = get_query_var('s');
set_query_var('s', __(TF_SEEK_HELPER::get_option('seek_property_name_plural', 'AutoTrader'), 'tfuse') );

get_header();

set_query_var('s', $s_backup);
unset($s_backup);
/// ^end-back
$sidebar_position   = tfuse_sidebar_position();

// Seek search
$orderby_options    = array(
    'latest'        => array(
        'label'     => __('Latest Added', 'tfuse'),
        'sql'       => 'p.post_date DESC',
    ),
    'price-high-low'    => array(
        'label'     => __('Price High - Low', 'tfuse'),
        'sql'       => 'options.seek_property_price DESC',
    ),
    'price-low-high'    => array(
        'label'     => __('Price Low - High', 'tfuse'),
        'sql'       => 'options.seek_property_price ASC',
    ),
    'names-a-z'    => array(
        'label'     => __('Names A - Z', 'tfuse'),
        'sql'       => 'p.post_title ASC',
    ),
    'names-z-a'    => array(
        'label'     => __('Names Z - A', 'tfuse'),
        'sql'       => 'p.post_title DESC',
    ),
);

$search_params      = array(
    'return_type'       => ARRAY_A,
    'posts_per_page'    => get_option('posts_per_page',5),
    'orderby_options'   => $orderby_options,
    'debug'             => false,
);
$search_results = TF_SEEK_HELPER::get_search_results($search_params);
$results_number = $search_results['total'];
tfuse_header_content('header',$results_number);
tfuse_breadcrumbs();
tfuse_header_search();
tfuse_shortcode_content('top');
?>

<div <?php tfuse_class('middle'); ?>>
    <div class="container clearfix">
        <div class="title2">
            <?php if($TFUSE->request->isset_GET('promos')) { ?>
                <h2><?php _e('CARS WITH SPECIAL PRICES', 'tfuse'); ?></h2>
            <?php } elseif($TFUSE->request->isset_GET('favorites')) { ?>
                <h2><?php _e('Favorite Cars', 'tfuse'); ?></h2>
            <?php } else { ?>
                <h2><?php _e('Search Results:', 'tfuse'); echo ' '.$search_results['total']; ?></h2>
            <?php } ?>
        </div>
        <!-- content -->
        <div class="content">
            <!-- sorting, pages -->
            <div class="list_manage">
                <div class="inner clearfix">
                    <?php
                        TF_SEEK_CUSTOM_FUNCTIONS::orderby( $orderby_options, array('select_id'=>'sort_list') );

                        TF_SEEK_CUSTOM_FUNCTIONS::html_jump_to_page(array(
                            'curr_page' => $search_results['curr_page'],
                            'max_pages' => $search_results['max_pages'],
                        ));

                        TF_SEEK_CUSTOM_FUNCTIONS::html_paging(array(
                            'curr_page' => $search_results['curr_page'],
                            'max_pages' => $search_results['max_pages'],
                        ));
                    ?>
                </div><!--/ inner -->
            </div><!--/ sorting, pages -->

            <!-- offers list -->
            <div class="offer_list clearfix">
                <?php $slist = $search_results['rows'];
                if(sizeof($slist)):
                    $price_symbol = TF_SEEK_HELPER::get_option('seek_property_currency_symbol','$');
                    $price_symbol_pos = TF_SEEK_HELPER::get_option('seek_property_currency_symbol_pos',0);
                    $mileage_symbol = TF_SEEK_HELPER::get_option('seek_property_mileage_symbol','Km');
                    foreach($slist as $spost):
                        $link = get_permalink($spost['ID']); ?>
                        <div class="offer_item clearfix">
                            <div class="offer_image">
                                <a href="<?php echo $link; ?>"><?php tfuse_media($spost['ID']);?></a>
								<?php if($spost['seek_property_reduction'] != 0) { ?><span class="sale"><?php echo $spost['seek_property_reduction']; _e('% OFF','tfuse'); ?></span> <?php } ?>
                            </div>
                            <div class="offer_aside">
                                <h2><a href="<?php echo $link; ?>"><?php echo esc_attr(tfuse_qtranslate($spost['post_title'])); ?></a></h2>
                                <div class="offer_descr"><?php echo $spost['post_excerpt']; ?></div>
                                <div class="offer_data">
                                    <?php
                                    $price_symbol_str = '<span class="symbol_price">' . $price_symbol . '</span>';
                                    $price_number_str = apply_filters( 'tfuse_price_number_format',
                                        number_format( $spost['seek_property_price'], 0, ','),
                                        $spost['seek_property_price'] );
                                    ?>
                                    <div class="offer_price"><?php tfuse_symbol_position($price_number_str, $price_symbol_str, $price_symbol_pos) ?></div>
                                    <?php
                                    $mileage_number = apply_filters( 'tfuse_mileage_number_format', number_format($spost['seek_property_mileage'], 0, '.', ','), $spost['seek_property_mileage']);
                                    $out = tfuse_symbol_position($mileage_number, $mileage_symbol, 1, ' ', true);
                                    ?>
                                    <span class="offer_miliage"><?php echo apply_filters('tfuse_mileage_symbol_position', $out, $mileage_number, $mileage_symbol); ?></span>
                                    <?php if( tfuse_is_date( $spost['seek_property_year'] ) ) : ?>
                                        <span class="offer_regist"><?php echo apply_filters('tfuse_vehicle_year', date("m/Y", strtotime($spost['seek_property_year']) ) ); ?></span>
                                    <?php endif ?>
                                </div><!-- /.offer_data -->
                            </div><!-- /.offer_aside -->
                        </div><!-- /.offer_item -->
                    <?php endforeach;
                else:
                    if($search_results['total']<1){
                        if(!$TFUSE->request->isset_GET('favorites')) _e( 'Sorry, but nothing matched your search criteria.', 'tfuse' );
                        else _e( 'Sorry, no favorites added yet', 'tfuse');
                    } else {
                        // Wrong page
                    }
                endif; ?>
            </div><!--/ offers list -->

            <?php tfuse_search_number_pagination(array(
                'curr_page' => $search_results['curr_page'],
                'max_pages' => $search_results['max_pages'])
            ); ?>
        </div><!--/ content -->

        <?php if ($sidebar_position == 'left' || $sidebar_position == 'right') : ?>
            <div class="sidebar">

                    <?php get_sidebar(); ?>

            </div><!--/ .sidebar -->
        <?php endif; ?>

    </div><!--/ .container -->
</div><!--/ middle -->

<?php tfuse_shortcode_content('bottom1'); ?>
<?php tfuse_header_content('content'); ?>
<?php tfuse_shortcode_content('bottom'); ?>
<?php tfuse_shortcode_content('bottom2'); ?>
<?php get_footer(); ?>