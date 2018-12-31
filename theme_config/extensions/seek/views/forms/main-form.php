<?php

if (!defined('TFUSE'))
    exit('Direct access forbidden.');

?>

            <?php //TF_SEEK_HELPER::print_form_item('main_maker'); ?>

            <?php //TF_SEEK_HELPER::print_form_item('main_model'); ?>

            <?php TF_SEEK_HELPER::print_form_item('main_multi_maker'); ?>

            <?php TF_SEEK_HELPER::print_form_item('main_multi_model'); ?>


            <?php TF_SEEK_HELPER::print_form_item('main_registration_year'); ?>

            <?php TF_SEEK_HELPER::print_form_item('main_price_until'); ?>



            <div class="adv_search_hidden clearfix">
                <?php TF_SEEK_HELPER::print_form_item('main_mileage_to'); ?>

                <?php TF_SEEK_HELPER::print_form_item('main_fuel_type'); ?>

                <?php TF_SEEK_HELPER::print_form_item('main_vehicle_type'); ?>

                <?php TF_SEEK_HELPER::print_form_item('main_location'); ?>
            </div>
            <div class="adv_search_hidden clearfix">
                <?php TF_SEEK_HELPER::print_form_item('main_engine_power_kw'); ?>

                <?php TF_SEEK_HELPER::print_form_item('main_engine_size'); ?>
                <?php TF_SEEK_HELPER::print_form_item('main_gearbox'); ?>
                <?php TF_SEEK_HELPER::print_form_item('main_status'); ?>
            </div>

            <div class="row rowSubmit">
                <label class="label_title" id="adv_search_open"><?php _e('Advanced Search', 'tfuse'); ?></label>
                <span class="btn btn_search"><input type="submit" value="<?php _e('SEARCH', 'tfuse'); ?>"></span>
            </div>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        var deviceAgent = navigator.userAgent.toLowerCase();
        var agentID = deviceAgent.match(/(iphone|ipod|ipad)/);
        if (agentID) {
            cuSel({changedEl: "select.select_styled:not(.cusel)", visRows: 8, scrollArrows: true});	 // Add arrows Up/Down for iPad/iPhone
        } else {
            cuSel({changedEl: "select.select_styled:not(.cusel)", visRows: 8, scrollArrows: true});
        }
    });
</script>