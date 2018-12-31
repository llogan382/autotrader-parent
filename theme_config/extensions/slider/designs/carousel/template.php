<?php
    $interval = 'false';
    if(isset($slider['general']['slider_interval']) && $slider['general']['slider_interval']>0)
        $interval = $slider['general']['slider_interval'];
?>
<div class="middle_row latest_offers">
    <div class="container clearfix">
        <h2><?php echo $slider['general']['slider_info_text']; ?></h2>
        <a href="<?php echo $slider['general']['slider_more_link']; ?>" class="link_more"><?php echo $slider['general']['slider_more_text']; ?></a>
    </div>

    <div id="latest_offers">
        <?php foreach ($slider['slides'] as $slide){ ?>
            <div class="latest_item">
                <a href="<?php echo $slide['slide_link_title']; ?>"><img src="<?php echo $slide['slide_src']; ?>" alt=""></a>
                <a href="<?php echo $slide['slide_link_title']; ?>"><?php echo $slide['slide_title']; ?></a>
            </div>
        <?php } ?>
    </div><!-- /#latest_offers -->

    <a class="prev" id="latest_offers_prev" href="#"></a>
    <a class="next" id="latest_offers_next" href="#"></a>
    <script>
        jQuery(document).ready(function() {
            var screenRes = jQuery(window).width();
            jQuery('#latest_offers').carouFredSel({
                prev : "#latest_offers_prev",
                next : "#latest_offers_next",
                infinite: false,
                circular: true,
                auto: <?php echo $interval; ?>,
                width: '100%',
                scroll: {
                    items : 1,
                    onBefore: function (data) {
                        if (screenRes > 900) {
                           if(data.scroll.direction == "prev"){
                                data.items.visible.eq(0).animate({opacity: 0.15},0);
                                data.items.visible.eq(-1).animate({opacity: 0.15},300);
                                data.items.old.eq(0).animate({opacity: 1},100);
                            }else{
                                data.items.visible.eq(-1).animate({opacity: 0.15},0);
                                data.items.visible.eq(0).animate({opacity: 0.15},300);
                                data.items.old.eq(-1).animate({opacity: 1},100);
                            }
                        }
                    }
                }
            });
            if (screenRes > 900) {
                var vis_items = jQuery("#latest_offers").triggerHandler("currentVisible");
                vis_items.eq(-1).animate({opacity: 0.15},100);
                vis_items.eq(0).animate({opacity: 0.15},100);
            }
        });
    </script>
</div><!-- /.latest_offers -->