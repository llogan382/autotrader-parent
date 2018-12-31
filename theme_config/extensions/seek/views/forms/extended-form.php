<?php

if (!defined('TFUSE'))
    exit('Direct access forbidden.');

    //TF_SEEK_HELPER::print_form_item('extended_maker');

    //TF_SEEK_HELPER::print_form_item('extended_model');

    TF_SEEK_HELPER::print_form_item('extended_multi_maker');

    TF_SEEK_HELPER::print_form_item('extended_multi_model');

    TF_SEEK_HELPER::print_form_item('extended_registration_year');

    TF_SEEK_HELPER::print_form_item('extended_price_until');

    TF_SEEK_HELPER::print_form_item('extended_mileage_to');

    TF_SEEK_HELPER::print_form_item('extended_fuel_type');

    TF_SEEK_HELPER::print_form_item('extended_vehicle_type');

    TF_SEEK_HELPER::print_form_item('extended_location');

    TF_SEEK_HELPER::print_form_item('extended_engine_power_kw');

    TF_SEEK_HELPER::print_form_item('extended_engine_size');

    TF_SEEK_HELPER::print_form_item('extended_gearbox');

    TF_SEEK_HELPER::print_form_item('extended_status');



?>
<div class="clear"></div>


<div class="row rowSubmit">
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