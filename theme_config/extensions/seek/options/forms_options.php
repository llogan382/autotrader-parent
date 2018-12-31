<?php

if (!defined('TFUSE'))
    exit('Direct access forbidden.');


/**
 * Define forms
 */

/*- Help -*/
array(
    // Optins structure
    '<id>'   => array(
        'items'         => array( '<item-id>', '<item-id>', '...' ),
        'template'      => '<template-name>', // Located in views/forms/ (without '.php')
        'template_vars' => array('foo'=>'bar'), // Some $vars accesibile from template (ex: $vars['foo'])
        'attributes'    => array(
            'class'     => 'foo',
            'onsubmit'  => 'bar',
        ), // <form class="foo" onsubmit="bar" >
    ),
);
/*---*/
global $search;
$options = array(

    'main_search'   => array(
        'items'         => array(
            //'main_maker',
            //'main_model',
            'main_multi_maker',
            'main_multi_model',
            'main_registration_year',
            'main_price_until',
            'main_mileage_to',
            'main_fuel_type',
            'main_vehicle_type',
            'main_location',
            'main_engine_power_kw',
            'main_engine_size',
            'main_gearbox',
            'main_status'
        ),
        'template'      => 'main-form',
        'template_vars' => array(),
        'attributes'    => array(
            'class' =>'search_form advsearch_hide clearfix'
        )
    ),

    'extended_search' => array(
        'items'         => array(

            //'extended_maker',
            //'extended_model',
            'extended_multi_maker',
            'extended_multi_model',
            'extended_registration_year',
            'extended_price_until',
            'extended_mileage_to',
            'extended_fuel_type',
            'extended_vehicle_type',
            'extended_location',
            'extended_engine_power_kw',
            'extended_engine_size',
            'extended_gearbox',
            'extended_status'
        ),
        'template'      => 'extended-form',
        'template_vars' => array(),
        'attributes'    => array(
            'class'     => 'search_form clearfix'
        ),
    ),

    'filter_search' => array(
        'items'         => array(
            //'filter_maker',
            //'filter_model',
            'filter_multi_maker',
            'filter_multi_model',
            'filter_registration_year',
            'filter_price',
            'filter_mileage',
            'filter_vehicle_type',
            'filter_fuel_type',
            'filter_color',
            'filter_location',
            'filter_engine_power_kw',
            'filter_engine_size',
            'filter_gearbox',
            'filter_status',
            'favorites',
            'promos'
        ),
        'template'      => 'filter-form',
        'template_vars' => array(),
        'attributes'    => array(
            'class'     => 'side_form'
        ),
    )
);