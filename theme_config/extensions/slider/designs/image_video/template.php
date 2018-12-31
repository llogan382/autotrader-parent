<?php
if($slider['general']['slider_text_position']=='left'){$class='img_right';$c=0;}
else if($slider['general']['slider_text_position']=='right'){$class='';$c=0;}
else {$class=''; $c=1;}
$count=0;
$interval = 4000;
if(isset($slider['general']['slider_interval']) && $slider['general']['slider_interval']>0)
    $interval = $slider['general']['slider_interval'];
?>
<div class="header slider_imv" style="background:url(<?php echo $slider['general']['slider_image']; ?>)">
    <div class="container">
        <div class="offers_slider">
            <div id="offers_slider">
                <?php foreach ($slider['slides'] as $slide){ $count++;
                    if($c==1){
                        if($count%2==0)$class='img_right';
                        else $class='';
                    } ?>
                    <div class="slide_item <?php echo $class; ?>">
                        <div class="slide_img">
                            <?php if($slide['slide_type_slide']=='image'){ ?>
                                <a href="<?php echo $slide['slide_link_title']; ?>"><img src="<?php echo $slide['slide_src']; ?>" alt=""></a>
                            <?php }
                            else echo $slide['slide_video_frame']; ?>
                        </div>
                        <div class="slide_text">
                            <h2><a href="<?php echo $slide['slide_link_title']; ?>"><?php echo $slide['slide_title']; ?></a></h2>
                            <?php if ( isset($slide['slide_list']) && !empty($slide['slide_list']) ){
                                $list = explode("\n",$slide['slide_list']);
                                foreach($list as $item) echo '<div class="info_line">'.$item.'</div>';
                            } ?>
                            <div class="info_price"><?php echo $slide['slide_subtitle']; ?></a> <a href="<?php echo $slide['slide_link_title']; ?>" class="save-item">Save</a></div>
                        </div>
                    </div>
                <?php } ?>

            </div><!-- /#offers_slider -->
            <div class="slider_pag" id="offers_slider_pag"></div>
        </div><!-- /.offers_slider -->
        <script>
            jQuery(document).ready(function() {
                var $ = jQuery;
                function carOffersInit() {
                    $('#offers_slider').carouFredSel({
                        pagination : "#offers_slider_pag",
                        responsive : true,
                        infinite: false,
                        circular: true,
                        auto: {
                            play: true,
                            timeoutDuration: <?php echo $interval; ?>
                        },
                        width: '100%',
                        scroll: {
                            items : 1,
                            fx : "fade",
                            pauseOnHover: true
                        }
                    });
                }
                carOffersInit();
                var resizeTimer;
                $(window).resize(function() {
                    clearTimeout(resizeTimer);
                    resizeTimer = setTimeout(carOffersInit, 100);
                });
            });
        </script>
    </div><!-- /.container -->
</div><!-- /.header -->