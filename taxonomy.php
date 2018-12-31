<?php get_header();
tfuse_shortcode_content('top');
global $wp_query,$TFUSE;
$default = $wp_query;
$sidebar_position = tfuse_sidebar_position();
if (!$TFUSE->request->empty_GET('order_by')) {
    $order_by = $TFUSE->request->GET('order_by');
} else {
    $order_by = 'date';
}
if (!$TFUSE->request->empty_GET('order')) {
    $order = $TFUSE->request->GET('order');
} else {
    $order = 'DESC';
}
if (!$TFUSE->request->empty_GET('page')) {
    $page = $TFUSE->request->GET('page');
} else {
    $page = 0;
}
$sel = 1;
if ($order_by == 'date' && $order == 'desc') {
    $sel = 1;
} elseif ($order_by == 'price' && $order == 'DESC') {
    $sel = 2;
} elseif ($order_by == 'price' && $order == 'ASC') {
    $sel = 3;
} elseif ($order_by == 'title' && $order == 'ASC') {
    $sel = 4;
} elseif ($order_by == 'title' && $order == 'DESC') {
    $sel = 5;
}

$get_order_by = $order_by;
$posts_per_page = $wp_query->query_vars['posts_per_page'];
if ($get_order_by == 'price') {
    $posts_per_page = -1;
}
if ($order_by != 'date' && $order_by != 'title') {
    $order_by = 'date';
}

$args = array(
    'orderby' => $order_by,
    'order' => $order,
    'paged' => $page,
    'posts_per_page' => $posts_per_page
);
$args = array_merge($wp_query->query, $args);
$posts = query_posts($args);
$tag = $wp_query->get_queried_object();
$num_pages = $wp_query->max_num_pages;
$vehicles = $posts;
foreach ($posts as $key => $value) :
    $vehicles [$key] = get_object_vars($value);
    $vehicles [$key]['price'] = TF_SEEK_HELPER::get_post_option('property_price', null, $value->ID);
    $vehicles [$key]['mileage'] = TF_SEEK_HELPER::get_post_option('property_mileage', 1, $value->ID);
    $vehicles [$key]['reduction'] = TF_SEEK_HELPER::get_post_option('property_reduction', 0, $value->ID);
    $vehicles [$key]['reg_year'] = TF_SEEK_HELPER::get_post_option('property_year', '', $value->ID);
endforeach;
if ($get_order_by == 'price') {
    if (!is_numeric($page)) {
        $page = 0;
    }
    $count = tfuse_get_count_properties_by_taxonomy_id($tag->term_id);
    $num_pages = (int)($count[0]['count'] / $default->query_vars['posts_per_page']);
    if ((($count[0]['count']) % $default->query_vars['posts_per_page']) != 0) {
        $num_pages++;
    }
    $page = intval($page);
    $start = $default->query_vars['posts_per_page'] * ($page - 1);
    if ($page == 1 || $page == 0) {
        $start = 0;
    }
    $final = $default->query_vars['posts_per_page'];
    if ($order == 'DESC') {
        $obj = tfuse_get_properties_by_taxonomy_id($tag->term_id, true, $start, $final);
    } else {
        $obj = tfuse_get_properties_by_taxonomy_id($tag->term_id, false, $start, $final);
    }
    $vehicles = array();
    if(sizeof($obj)) :
        foreach ($obj as $key => $prop) {
            $vehicles [$key]['ID'] = $prop['post_id'];
            $vehicles [$key]['price'] = $prop['seek_property_price'];
            $vehicles [$key]['mileage'] = $prop['seek_property_mileage'];
            $vehicles [$key]['post_excerpt'] = tfuse_qtranslate($prop['post_excerpt']);
            $vehicles [$key]['post_title'] = tfuse_qtranslate($prop['post_title']);
            $vehicles [$key]['reduction'] = $prop['seek_property_reduction'];
            $vehicles [$key]['reg_year'] = $prop['seek_property_year'];
        }
    endif;
} ?>
<input type="hidden" id="tax_permalink" value="<?php echo get_term_link($tag->slug, $tag->taxonomy);?>">
<input type="hidden" id="tax_results" page="<?php print $page ?>" num_pages="<?php print $num_pages; ?>" get_order="<?php print $order; ?>" get_order_by="<?php print $get_order_by; ?>">

<div <?php tfuse_class('middle'); ?>>
    <div class="container clearfix">
        <?php tfuse_category_ads(); tfuse_hook(); ?>
        <!-- content -->
        <div class="content">
            <!-- sorting, pages -->
            <div class="list_manage">
                <div class="inner clearfix">
                    <form action="#" method="post" class="form_sort">
                        <span class="manage_title"><?php _e('Sort by', 'tfuse'); ?>:</span>
                        <select class="select_styled white_select" name="sort_list" id="sort_list">
                            <option value="1"<?php if ($sel == 1) {
                                echo ' selected';
                            }?>><?php _e('Latest Added', 'tfuse'); ?></option>
                            <option value="2"<?php if ($sel == 2) { echo ' selected'; }?>><?php _e('Price High - Low', 'tfuse'); ?></option>
                            <option value="3"<?php if ($sel == 3) { echo ' selected'; }?>><?php _e('Price Low - Hight', 'tfuse'); ?></option>
                            <option value="4"<?php if ($sel == 4) { echo ' selected'; }?>><?php _e('Names A-Z', 'tfuse'); ?></option>
                            <option value="5"<?php if ($sel == 5) { echo ' selected'; }?>><?php _e('Names Z-A', 'tfuse'); ?></option>
                        </select>
                    </form>

                    <div class="pages_jump">
                        <span class="manage_title"><?php _e('Jump to page', 'tfuse'); ?>:</span>
                        <form action="#" method="post">
                            <input type="text" name="jumptopage" value="<?php print $num_pages ?>" class="inputSmall" id="jumptopage">
                            <input id="jumptopage_submit" type="submit" class="btn-arrow" value="Go">
                        </form>
                    </div>

                    <div class="pages">
                        <span class="manage_title"><?php _e('Page', 'tfuse'); ?> : &nbsp;<strong><?php if ($page == 0) {
                                echo $page + 1 . ' ';
                            } else {
                                echo $page . ' ';
                            } _e('of', 'tfuse');  echo ' ' . $num_pages; ?></strong></span> <a href="#" <?php if ($page == 0 || $page == 1) { echo 'rel="first" style="opacity:0.4;"'; } ?>
                            class="link_prev"><?php _e('Previous', 'tfuse'); ?></a><a href="#" <?php if ($page == $num_pages) { echo 'rel="last" style="opacity:0.4;"'; } ?>
                            class="link_next"><?php _e('Next', 'tfuse'); ?></a>
                    </div>
                </div><!--/ .inner clearfix -->
            </div><!--/ .list_menage -->

            <!-- offers list -->
            <div class="offer_list clearfix">
                <?php if (count($vehicles)):
                    $price_symbol = TF_SEEK_HELPER::get_option('seek_property_currency_symbol','$');
                    $price_symbol_pos = TF_SEEK_HELPER::get_option('seek_property_currency_symbol_pos',0);
                    $mileage_symbol = TF_SEEK_HELPER::get_option('seek_property_mileage_symbol','Km');
                    foreach ($vehicles as $vehicle):
                        $link = get_permalink($vehicle['ID']); ?>
                        <div class="offer_item clearfix">
                            <div class="offer_image">
                                <a href="<?php echo $link; ?>"><?php tfuse_media($vehicle['ID']);?></a>
                                <?php if($vehicle['reduction'] != 0) { ?><span class="sale"><?php echo $vehicle['reduction']; _e('% OFF','tfuse'); ?></span> <?php } ?>
                            </div>
                            <div class="offer_aside">
                                <h2><a href="<?php echo $link; ?>"><?php print(esc_attr($vehicle['post_title'])); ?></a></h2>
                                <div class="offer_descr"><?php echo $vehicle['post_excerpt']; ?></div>
                                <div class="offer_data">
                                    <?php
                                        $price_symbol_str = '<span class="symbol_price">' . $price_symbol . '</span>';
                                        $price_number_str = apply_filters( 'tfuse_price_number_format', number_format($vehicle['price'],0,'', ','), $vehicle['price'] );
                                    ?>
                                    <div class="offer_price"><?php tfuse_symbol_position($price_number_str, $price_symbol_str, $price_symbol_pos) ?></div>
                                    <?php
                                        $mileage_number = apply_filters('tfuse_mileage_number_format', number_format($vehicle['mileage']), $vehicle['mileage']);
                                        $out = tfuse_symbol_position($mileage_number, $mileage_symbol, 1, ' ', true);
                                    ?>
                                    <span class="offer_miliage"><?php echo apply_filters('tfuse_mileage_symbol_position', $out, $mileage_number, $mileage_symbol); ?></span>
                                    <?php if( $vehicle['reg_year'] != '' ) : ?>
                                        <span class="offer_regist"><?php echo apply_filters('tfuse_vehicle_year', $vehicle['reg_year']); ?></span>
                                    <?php endif ?>
                                </div>
                            </div><!-- /.offer_aside -->
                        </div><!-- /.offer_item clearfix -->
                    <?php endforeach; ?>
                <?php endif; ?>

                <?php if (($page > $num_pages) || (!count($vehicles))) :
                    echo '<p>' . __('Page not found.', 'tfuse') . '</p>';
                    echo '<p>' . __('The page you were looking for doesn&rsquo;t seem to exist.', 'tfuse') . '</p>';
                endif; ?>
            </div><!-- offers list -->
            <?php tfuse_seek_pagination($num_pages); ?>
        </div><!--/ .content -->

        <?php if ($sidebar_position == 'left' || $sidebar_position == 'right') : ?>
            <div class="sidebar">
                <?php get_sidebar(); ?>
            </div>
        <?php endif; ?>
    </div><!--/ .container -->
</div><!--/ .middle -->

<?php tfuse_shortcode_content('bottom1'); ?>
<?php tfuse_header_content('content'); ?>
<?php tfuse_shortcode_content('bottom'); ?>
<?php tfuse_shortcode_content('bottom2'); ?>
<?php get_footer(); ?>