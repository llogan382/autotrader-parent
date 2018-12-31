<?php
    $price = TF_SEEK_HELPER::get_post_option('property_price', 0);
    global $TFUSE, $post;
    $vehicle_id = $post->ID;
?>
<!-- offer right -->
<div class="offer_aside">
    <div class="offer_price">
        <?php
        $price_number_str   = apply_filters( 'tfuse_price_number_format', number_format($price,0,'', ','), $price );
        $price_symbol_str   = ' <span class="symbol_price_right">' .  TF_SEEK_HELPER::get_option('seek_property_currency_symbol','$') . '</span>';
        $price_symbol_pos   = TF_SEEK_HELPER::get_option('seek_property_currency_symbol_pos', 0);

        $price_string =  tfuse_symbol_position($price_number_str, $price_symbol_str, $price_symbol_pos, '', true) ;
        ?>
        <strong> <?php echo $price_number_str  ? $price_string :__('-', 'tfuse'); ?></strong><br>
        <?php
            $vat_rate = TF_SEEK_HELPER::get_option('seek_property_vat_rate',0);
            if($vat_rate!=0){
                $vat_format = TF_SEEK_HELPER::get_option('seek_property_vat_show','');
                $vat_price = number_format(intval(TF_SEEK_HELPER::get_post_option('property_vat_price', 0)));
                if($vat_price!=0){
                    echo '<style>.btn_save{top:9px;}</style>';
                    if($vat_format!=''){
                        $text = tfuse_qtranslate($vat_format);
                        $text = str_replace('%%price%%',$vat_price,$text);
                        $text = str_replace('%%VAT%%',$vat_rate,$text);
                        echo '<em>'.$text.'</em>';
                    }
                    else echo '<em>'.$vat_price.' '.TF_SEEK_HELPER::get_option('seek_property_currency_plural','EUR').' '.__( '(Net)','tfuse').' '.$vat_rate.__('% VAT','tfuse').'</em>';
                }
            }
        ?>
        <?php
            $fav_saved = array();
            if (!$TFUSE->request->empty_COOKIE('favorite_posts')) $fav_saved = explode(',', $TFUSE->request->COOKIE('favorite_posts'));
            $this_saved = in_array($vehicle_id,$fav_saved);
        ?>
        <?php if ($this_saved) { ?>
            <a href="#" rel="<?php echo $vehicle_id; ?>" class="btn_save link-saved"><?php _e('REMOVE VEHICLE','tfuse'); ?></a>
        <?php } else { ?>
            <a href="#" rel="<?php echo $vehicle_id; ?>" class="btn_save link-save"><?php _e('SAVE VEHICLE','tfuse'); ?></a>
        <?php } ?>
    </div>

    <?php print( TF_SEEK_HELPER::print_zone('header') ); ?>

    <div class="offer_descr"><?php the_excerpt(); ?></div>

    <?php print( TF_SEEK_HELPER::print_zone('specifications') ); ?>
</div><!-- /.offer right -->