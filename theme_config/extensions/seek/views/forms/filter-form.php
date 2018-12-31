<?php

if (!defined('TFUSE'))
    exit('Direct access forbidden.');

    //TF_SEEK_HELPER::print_form_item('filter_maker');

    //TF_SEEK_HELPER::print_form_item('filter_model');

    TF_SEEK_HELPER::print_form_item('filter_multi_maker');

    TF_SEEK_HELPER::print_form_item('filter_multi_model');

    TF_SEEK_HELPER::print_form_item('filter_registration_year');

    TF_SEEK_HELPER::print_form_item('filter_price');

    TF_SEEK_HELPER::print_form_item('filter_mileage');

    TF_SEEK_HELPER::print_form_item('filter_vehicle_type');

    TF_SEEK_HELPER::print_form_item('filter_fuel_type');

    TF_SEEK_HELPER::print_form_item('filter_color');

    TF_SEEK_HELPER::print_form_item('filter_location');

    TF_SEEK_HELPER::print_form_item('filter_engine_power_kw');

    TF_SEEK_HELPER::print_form_item('filter_engine_size');

    TF_SEEK_HELPER::print_form_item('filter_gearbox');

    TF_SEEK_HELPER::print_form_item('filter_status');

    TF_SEEK_HELPER::print_form_item('favorites');

    TF_SEEK_HELPER::print_form_item('promos');
?>

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