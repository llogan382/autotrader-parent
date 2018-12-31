<?php
    $interval = 5000;
    if(isset($slider['general']['slider_interval']) && $slider['general']['slider_interval']>0)
        $interval = $slider['general']['slider_interval'];
?>
<div class="header slider_fwb" style="background:#000">
    <div class="fullwidthbanner-container">
        <div class="fullwidthbanner">
            <ul>
                <?php foreach($slider['slides'] as $slide){ ?>
                    <li data-transition="fade" data-slotamount="7" data-masterspeed="500">
                        <img src="<?php echo $slide['slide_src']; ?>" data-fullwidthcentering="on">
                        <?php if($slide['slide_text_position']=='left'){ ?>
                            <div class="caption sft text_line" data-x="10" data-y="190" data-speed="900" data-start="800" data-easing="easeOutExpo"></div>
                            <div class="caption sfb text_line" data-x="10" data-y="300" data-speed="900" data-start="800" data-easing="easeOutExpo"></div>
                            <div class="caption sfl <?php echo $slide['slide_text_color']; ?>_text big_title" data-x="10" data-y="220" data-speed="900" data-start="500" data-easing="easeOutExpo">
                                <a href="<?php echo $slide['slide_link_title']; ?>"><strong><?php echo $slide['slide_title']; ?></strong></a>
                            </div>
                            <div class="caption sfr <?php echo $slide['slide_text_color']; ?>_text subtitle" data-x="10" data-y="257" data-speed="900" data-start="700" data-easing="easeOutExpo">
                                <?php echo $slide['slide_subtitle']; ?>
                            </div>
                        <?php }
                        else { ?>
                            <div class="caption sft text_line" data-x="550" data-y="250" data-speed="900" data-start="800" data-easing="easeOutExpo"></div>
                            <div class="caption sfb text_line" data-x="550" data-y="360" data-speed="900" data-start="800" data-easing="easeOutExpo"></div>
                            <div class="caption sft <?php echo $slide['slide_text_color']; ?>_text big_title" data-x="550" data-y="280" data-speed="900" data-start="500" data-easing="easeOutExpo">
                                <a href="<?php echo $slide['slide_link_title']; ?>"><strong><?php echo $slide['slide_title']; ?></strong></a>
                            </div>
                            <div class="caption sfb <?php echo $slide['slide_text_color']; ?>_text subtitle" data-x="550" data-y="317" data-speed="900" data-start="700" data-easing="easeOutExpo">
                                <?php echo $slide['slide_subtitle']; ?>
                            </div>
                        <?php } ?>
                    </li>
                <?php } ?>
            </ul>
        </div><!-- /.fullwidthbanner -->
    </div><!-- /.fullwidthbanner_container -->

    <script>
        jQuery(document).ready(function() {
            var $ = jQuery;
            if ($.fn.cssOriginal!=undefined)
                $.fn.css = $.fn.cssOriginal;

            $('.fullwidthbanner').revolution({
                delay:<?php echo $interval; ?>,
                startwidth:950,
                startheight:617,
                onHoverStop:"off",						// Stop Banner Timet at Hover on Slide on/off
                hideThumbs:0,
                navigationType:"bullet",				// bullet, thumb, none
                navigationArrows:"none",				// nexttobullets, solo (old name verticalcentered), none
                navigationStyle:"round",				// round,square,navbar,round-old,square-old,navbar-old, or any from the list in the docu (choose between 50+ different item), custom
                navigationHAlign:"center",				// Vertical Align top,center,bottom
                navigationVAlign:"bottom",				// Horizontal Align left,center,right
                navigationHOffset:0,
                navigationVOffset:23,
                touchenabled:"on",						// Enable Swipe Function : on/off
                stopAtSlide:-1,							// Stop Timer if Slide "x" has been Reached. If stopAfterLoops set to 0, then it stops already in the first Loop at slide X which defined. -1 means do not stop at any slide. stopAfterLoops has no sinn in this case.
                stopAfterLoops:-1,						// Stop Timer if All slides has been played "x" times. IT will stop at THe slide which is defined via stopAtSlide:x, if set to -1 slide never stop automatic
                hideCaptionAtLimit:0,					// It Defines if a caption should be shown under a Screen Resolution ( Basod on The Width of Browser)
                hideAllCaptionAtLilmit:0,				// Hide all The Captions if Width of Browser is less then this value
                hideSliderAtLimit:0,					// Hide the whole slider, and stop also functions if Width of Browser is less than this value
                fullWidth:"on",
                shadow:0								//0 = no Shadow, 1,2,3 = 3 Different Art of Shadows -  (No Shadow in Fullwidth Version !)
            });
        });
    </script>
</div><!--/ .header -->