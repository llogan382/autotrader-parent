<?php

if (!defined('TFUSE'))
    exit('Direct access forbidden.');

global $TFUSE;

/**
 * Define form items
 */

array(
/**
 * Option Structure:
 */
'<id>' => array( // id used as form item name in html and for TF_SEEK_HELPER::print_form_item('<id>'), please use only safe characters [a-z0-9_]
    'parameter_name'        => '<some-name>',
        // $_GET parameter name. Accessible from template and sql_generator as $parameter_name
        // make sure if the value of this parameter is unique within one form items
        // if you want to change that, make sure and search in all files if this value is not used hardcoded somewhere else
    'template'              => '<template-file-name>',
        // without .php, located in ../views/items/
        // if empty ('') , print function filters and actions within it will be executed but no template will be included
    'template_vars'         => array('foo'=>'bar'),
        // accessible from template as $vars['foo']
    'settings'              => array('foo'=>'bar'),
        // item settings accessible from template as $settings['foo'] and from <sql_generator> as third parameter
    'sql_generator'         => '<function-name>',
        // public static function from object located in ../includes/sql_generators.php
    'sql_generator_options' => array(
        // second parameter for sql_generator function
        'search_on'         => '<options>/<taxonomy>',
            // search in options or taxonomy
        'search_on_id'      => 'seek_property_price',
            // if 'search_on'='option': need to match id from ./<post_type>_options.php where 'searchable'=> TRUE,
            // if 'search_on'='taxonomy': need to match taxonomy name registered in ../register_custom_taxonomies.php
         '...something...'  => '...else...',
        ),
    )
);
$engine_power_kw_symbol = __('KW', 'tfuse');

$options = array(

    'main_maker'  => array(
        'parameter_name'        => 'maker',
        'template'              => 'taxonomy-select',
        'template_vars'         => array(
            'label'             => __('Select Maker', 'tfuse'),
            'default_option'    => __('All Makers', 'tfuse'),
        ),
        'settings'              => array(
            'taxonomy'          => TF_SEEK_HELPER::get_post_type() . '_models',
            'have_child'        => true,
            'get_terms_args'    => array(
                'hide_empty'    => false,
                'order_by'      => 'name',
                'order'         => 'ASC',
                'parent'        => 0
            ),
        ),
        'sql_generator'         => 'taxonomy_parent',
        'sql_generator_options' => array(
            'search_on'         => 'taxonomy',
            'search_on_id'      => TF_SEEK_HELPER::get_post_type().'_models',
        ),
    ),

    'main_model'  => array(
        'parameter_name'        => 'model',
        'template'              => 'sensitive-taxonomy-select',
        'template_vars'         => array(
            'label'             => __('Select Model', 'tfuse'),
            'default_option'    => __('All Models', 'tfuse'),
        ),
        'settings'              => array(
            'parent_name'       => 'maker',
            'taxonomy'          => TF_SEEK_HELPER::get_post_type() . '_models',
            'no_children_text'  => __('All Models', 'tfuse')
        ),
        'sql_generator'         => 'taxonomy_parent',
        'sql_generator_options' => array(
            'search_on'         => 'taxonomy',
            'search_on_id'      => TF_SEEK_HELPER::get_post_type().'_models',
        ),
    ),

    'main_multi_maker'  => array(
        'parameter_name'        => 'maker',
        'template'              => 'multi-taxonomy-select',
        'template_vars'         => array(
            'label'             => __('Select Maker', 'tfuse'),
            'default_option'    => __('Select Car maker', 'tfuse'),
        ),
        'settings'              => array(
            'taxonomy'          => TF_SEEK_HELPER::get_post_type() . '_models',
            'have_child'        => true,
            'get_terms_args'    => array(
                'hide_empty'    => false,
                'order_by'      => 'name',
                'order'         => 'ASC',
                'parent'        => 0
            ),
        ),
        'sql_generator'         => 'multi_taxonomy_parent',
        'sql_generator_options' => array(
            'search_on'         => 'taxonomy',
            'search_on_id'      => TF_SEEK_HELPER::get_post_type().'_models',
        ),
    ),

    'main_multi_model'  => array(
        'parameter_name'        => 'model',
        'template'              => 'sensitive-multi-taxonomy-select',
        'template_vars'         => array(
            'label'             => __('Select Model', 'tfuse'),
            'default_option'    => __('All Models', 'tfuse')
        ),
        'settings'              => array(
            'taxonomy'          => TF_SEEK_HELPER::get_post_type() . '_models',
            'parent_name'       => 'maker',
            'parent_id'         => 'main_multi_maker'
        ),
        'sql_generator'         => 'multi_taxonomy_parent',
        'sql_generator_options' => array(
            'search_on'         => 'taxonomy',
            'search_on_id'      => TF_SEEK_HELPER::get_post_type().'_models',
        ),
    ),

    'main_registration_year'  => array(
        'parameter_name'        => 'registration_year',
        'template'              => 'custom-select',
        'template_vars'         => array(
            'label'             => __('1st Registration from', 'tfuse'),
            'default_option'    => __('All years', 'tfuse'),
            'select_class'      => '',
        ),
        'settings'              => array(
            //int options
            'from'              => 2001,
            'to'                => date("Y"),
            'step'              => 1,
            // or custom options
            /*'options'           => array(
                                    2001    => '2001',
                                    2002    => '2002',
                                    2003    => '2003',
                                    2004    => '2004',
                                    2005    => '2005',
                                    2006    => '2006',
                                    2007    => '2007',
                                    2008    => '2008',
                                    2009    => '2009',
                                    2010    => '2010',
                                    2011    => '2011',
                                    2012    => '2012'
            )*/

        ),
        'sql_generator'         => 'year',
        'sql_generator_options' => array(
            'search_on'         => 'seek_property_year',
            'relation'          => '>=', // allowed '=', '<=', '>='

        ),
    ),

    'main_price_until'  => array(
        'parameter_name'        => 'price_until',
        'template'              => 'custom-select',
        'template_vars'         => array(
            'label'             => __('Price until', 'tfuse'),
            'default_option'    => __('All', 'tfuse'),
            'select_class'      => ''
        ),
        'settings'              => array(
            //int options
            'from'              => 20000,
            'to'                => 100000,
            'step'              => 20000,
            // or custom options
           /* 'options'           => array(
                                    3000    => '3000',
                                    5000    => '5000',
                                    7000    => '7000',
                                    10000    => '10000',
                                    20000    => '20000',
            ),*/
            'options_suffix'    => __( ' ' . TF_SEEK_HELPER::get_option('seek_property_currency_symbol','$'), 'tfuse'),
            'suffix_pos'        => TF_SEEK_HELPER::get_option('seek_property_currency_symbol_pos', 0)

        ),
        'sql_generator'         => 'option_int',
        'sql_generator_options' => array(
            'search_on'         => 'options',
            'search_on_id'      => 'seek_property_price',
            'relation'          => '<=',
        ),
    ),

    'main_mileage_to'  => array(
        'parameter_name'        => 'mileage_to',
        'template'              => 'custom-select',
        'template_vars'         => array(
            'label'             => __('Mileage up to', 'tfuse'),
            'default_option'    => __('All', 'tfuse'),
            'select_class'      => '',
        ),
        'settings'              => array(
            //int options
            /*'from'              => 50000,
            'to'                => 300000,
            'step'              => 50000,*/
            // or custom options
            'options'           => array(
                50000       => '50.000',
                100000      => '100.000',
                150000      => '150.000',
                200000      => '200.000',
                300000      => '300.000',
                1000000      => 'Unlimited',
            )

        ),
        'sql_generator'         => 'option_int',
        'sql_generator_options' => array(
            'search_on'         => 'options',
            'search_on_id'      => 'seek_property_mileage',
            'relation'          => '<=',
        ),
    ),

    'main_fuel_type'  => array(
        'parameter_name'        => 'fuel_type',
        'template'              => 'taxonomy-select',
        'template_vars'         => array(
            'label'             => __('Fuel Type', 'tfuse'),
            'default_option'    => __('All fuel types', 'tfuse')
        ),
        'settings'              => array(
            'taxonomy'          => TF_SEEK_HELPER::get_post_type() . '_fuel_type',
            'get_terms_args'    => array(
                'hide_empty'    => false,
                'order_by'      => 'name',
                'order'         => 'ASC',
            ),
        ),
        'sql_generator'         => 'single_term',
        'sql_generator_options' => array(
            'search_on_field'      =>TF_SEEK_HELPER::get_post_type() . '_fuel_type',
        ),
    ),

    'main_vehicle_type'  => array(
        'parameter_name'        => 'type',
        'template'              => 'taxonomy-select',
        'template_vars'         => array(
            'label'             => __('Vehicle Type', 'tfuse'),
            'default_option'    => __('All types', 'tfuse')
        ),
        'settings'              => array(
            'taxonomy'          => TF_SEEK_HELPER::get_post_type() . '_type',
            'get_terms_args'    => array(
                'hide_empty'    => false,
                'order_by'      => 'name',
                'order'         => 'ASC',
            ),
        ),
        'sql_generator'         => 'single_term',
        'sql_generator_options' => array(
            'search_on_field'      => TF_SEEK_HELPER::get_post_type().'_type',
        ),
    ),

    'main_location'  => array(
        'parameter_name'        => 'location',
        'template'              => 'taxonomy-select',
        'template_vars'         => array(
            'label'             => __('Country / State', 'tfuse'),
            'default_option'    => __('All locations', 'tfuse'),
            'select_class'      => '',
        ),
        'settings'              => array(
            'taxonomy'          => TF_SEEK_HELPER::get_post_type() . '_locations',
            'get_terms_args'    => array(
                'hide_empty'    => false,
                'order_by'      => 'name',
                'order'         => 'ASC',
                'parent'        => 0
            ),
        ),
        'sql_generator'         => 'taxonomy_parent',
        'sql_generator_options' => array(
            'search_on'         => 'taxonomy',
            'search_on_id'      => TF_SEEK_HELPER::get_post_type().'_locations',
        ),
    ),

    'main_engine_power_kw'  => array(
        'parameter_name'        => 'engine_power_kw',
        'template'              => 'interval-select',
        'template_vars'         => array(
            'label'             => __('Motor Power', 'tfuse'),
            'default_option'    => __('All', 'tfuse'),
            'select_class'      => '',
        ),
        'settings'              => array(
            'complete_from'     =>'seek_property_engine_power_kw',
            'custom_options'    => array(
                '110-146'   => '110 ' . $engine_power_kw_symbol . ' - 146 ' . $engine_power_kw_symbol,
                '147-184'   => '147 ' . $engine_power_kw_symbol . ' - 184 ' . $engine_power_kw_symbol,
                '185-222'   => '185 ' . $engine_power_kw_symbol . ' - 222 ' . $engine_power_kw_symbol,
                '223-262'   => '223 ' . $engine_power_kw_symbol . ' - 262 ' . $engine_power_kw_symbol,
                '263-295'   => '263 ' . $engine_power_kw_symbol . ' - 295 ' . $engine_power_kw_symbol,
            ),
            'custom_labels'         => true,
            'auto_options'          => true,    // false if it completes, it will take into consideration custom_options(boolean default: true)
            'auto_steps'            => 5,       // (int, default: 5)
            'auto_round_precision'  => -1,       //  (number of digits after the decimal point). precision can also be negative or zero (default).
            'auto_options_suffix'   => $engine_power_kw_symbol, // when auto_options is true
            'auto_label_format'     => '%%value1%%  %%suffix%% - %%value2%% %%suffix%%'
        ),
        'sql_generator'         => 'numeric_interval',
        'sql_generator_options' => array(
            'search_on'         => 'options',
            'search_on_id'      => 'seek_property_engine_power_kw',
        ),
    ),
    'main_engine_size'  => array(
        'parameter_name'        => 'engine_size',
        'template'              => 'interval-select',
        'template_vars'         => array(
            'label'             => __('Engine Size', 'tfuse'),
            'default_option'    => __('All', 'tfuse'),
            'select_class'      => '',
        ),
        'settings'              => array(
            'complete_from'     =>'seek_property_engine_size',
            'custom_options'    => array(
                '1100-1599'  => '1.1 - 1.5',
                '1600-2000'  => '1.6 - 2.0',
                '2001-3000'  => '2.1 - 3.0',
                '3001-4000'  => '3.1 - 4.0',
                '4001-10000' => '4.1+'
            ),
            'custom_labels' => true,
            'auto_options'      => true,    // false if it completes, it will take into consideration custom_options(boolean default: true)
            'auto_steps'        => 5,       // (int, default: 5)
            'auto_round_precision'  => -2,  //  (number of digits after the decimal point). precision can also be negative or zero (default).
            'auto_options_suffix'    => __( ' cm<sup>3</sup>', 'tfuse'), // when auto_options is true
            'auto_label_format' => '%%value1%% - %%value2%% %%suffix%%'
        ),
        'sql_generator'         => 'numeric_interval',
        'sql_generator_options' => array(
            'search_on'         => 'options',
            'search_on_id'      => 'seek_property_engine_size',
        ),
    ),
    'main_gearbox'  => array(
        'parameter_name'        => 'gearbox',
        'template'              => 'taxonomy-select',
        'template_vars'         => array(
            'label'             => __('Gearbox', 'tfuse'),
            'select_class'      => '',
            'default_option'    => __('All', 'tfuse'),
        ),
        'settings'              => array(
            'taxonomy'          => TF_SEEK_HELPER::get_post_type() . '_gearboxes',
            'get_terms_args'    => array(
                'hide_empty'    => false,
                'order_by'      => 'name',
                'order'         => 'ASC',
                'parent'        => 0
            ),
        ),
        'sql_generator'         => 'single_term',
        'sql_generator_options' => array(
            'search_on_field'      => TF_SEEK_HELPER::get_post_type().'_gearboxes',
        ),
    ),
    'main_status'  => array(
        'parameter_name'        => 'status',
        'template'              => 'taxonomy-select',
        'template_vars'         => array(
            'label'             => __('Status', 'tfuse'),
            'default_option'    => __('All', 'tfuse')
        ),
        'settings'              => array(
            'taxonomy'          => TF_SEEK_HELPER::get_post_type() . '_statuses',
            'get_terms_args'    => array(
                'hide_empty'    => false,
                'order_by'      => 'name',
                'order'         => 'DESC',
            ),
        ),
        'sql_generator'         => 'single_term',
        'sql_generator_options' => array(
            'search_on_field'      => TF_SEEK_HELPER::get_post_type().'_statuses',
        ),
    ),
    'extended_maker'  => array(
        'parameter_name'        => 'maker',
        'template'              => 'taxonomy-select',
        'template_vars'         => array(
            'label'             => __('Select Maker', 'tfuse'),
            'default_option'    => __('All Makers', 'tfuse'),
        ),
        'settings'              => array(
            'taxonomy'          => TF_SEEK_HELPER::get_post_type() . '_models',
            'have_child'        => true,
            'get_terms_args'    => array(
                'hide_empty'    => false,
                'order_by'      => 'name',
                'order'         => 'ASC',
                'parent'        => 0
            ),
        ),
        'sql_generator'         => 'taxonomy_parent',
        'sql_generator_options' => array(
            'search_on'         => 'taxonomy',
            'search_on_id'      => TF_SEEK_HELPER::get_post_type().'_models',
        ),
    ),

    'extended_model'  => array(
        'parameter_name'        => 'model',
        'template'              => 'sensitive-taxonomy-select',
        'template_vars'         => array(
            'label'             => __('Select Model', 'tfuse'),
            'default_option'    => __('All Models', 'tfuse'),
        ),
        'settings'              => array(
            'parent_name'       => 'maker',
            'taxonomy'          => TF_SEEK_HELPER::get_post_type() . '_models',
            'no_children_text'  => __('All Models', 'tfuse')
        ),
        'sql_generator'         => 'taxonomy_parent',
        'sql_generator_options' => array(
            'search_on'         => 'taxonomy',
            'search_on_id'      => TF_SEEK_HELPER::get_post_type().'_models',
        ),
    ),

    'extended_multi_maker'  => array(
        'parameter_name'        => 'maker',
        'template'              => 'multi-taxonomy-select',
        'template_vars'         => array(
            'label'             => __('Select Maker', 'tfuse'),
            'default_option'    => __('Select Car maker', 'tfuse'),
        ),
        'settings'              => array(
            'taxonomy'          => TF_SEEK_HELPER::get_post_type() . '_models',
            'have_child'        => true,
            'get_terms_args'    => array(
                'hide_empty'    => false,
                'order_by'      => 'name',
                'order'         => 'ASC',
                'parent'        => 0
            ),
        ),
        'sql_generator'         => 'multi_taxonomy_parent',
        'sql_generator_options' => array(
            'search_on'         => 'taxonomy',
            'search_on_id'      => TF_SEEK_HELPER::get_post_type().'_models',
        ),
    ),

    'extended_multi_model'  => array(
        'parameter_name'        => 'model',
        'template'              => 'sensitive-multi-taxonomy-select',
        'template_vars'         => array(
            'label'             => __('Select Model', 'tfuse'),
            'default_option'    => __('All Models', 'tfuse')
        ),
        'settings'              => array(
            'taxonomy'          => TF_SEEK_HELPER::get_post_type() . '_models',
            'parent_name'       => 'maker',
            'parent_id'         => 'extended_multi_maker'
        ),
        'sql_generator'         => 'multi_taxonomy_parent',
        'sql_generator_options' => array(
            'search_on'         => 'taxonomy',
            'search_on_id'      => TF_SEEK_HELPER::get_post_type().'_models',
        ),
    ),

    'extended_registration_year'  => array(
        'parameter_name'        => 'registration_year',
        'template'              => 'custom-select',
        'template_vars'         => array(
            'label'             => __('1st Registration from', 'tfuse'),
            'default_option'    => __('All years', 'tfuse'),
            'select_class'      => '',
        ),
        'settings'              => array(
            //int options
            'from'              => 2001,
            'to'                => date("Y"),
            'step'              => 1,
            // or custom options
            /*'options'           => array(
                                    2001    => '2001',
                                    2002    => '2002',
                                    2003    => '2003',
                                    2004    => '2004',
                                    2005    => '2005',
                                    2006    => '2006',
                                    2007    => '2007',
                                    2008    => '2008',
                                    2009    => '2009',
                                    2010    => '2010',
                                    2011    => '2011',
                                    2012    => '2012'
            )*/

        ),
        'sql_generator'         => 'year',
        'sql_generator_options' => array(
            'search_on'         => 'seek_property_year',
            'relation'          => '>=',

        ),
    ),

    'extended_price_until'  => array(
        'parameter_name'        => 'price_until',
        'template'              => 'custom-select',
        'template_vars'         => array(
            'label'             => __('Price until', 'tfuse'),
            'default_option'    => __('All', 'tfuse'),
            'select_class'      => '',
        ),
        'settings'              => array(
            //int options
            'from'              => 20000,
            'to'                => 100000,
            'step'              => 20000,
            // or custom options
           /* 'options'           => array(
                                    3000    => '3000',
                                    5000    => '5000',
                                    7000    => '7000',
                                    10000    => '10000',
                                    20000    => '20000',
            ),*/
            'options_suffix'    => __( ' ' . TF_SEEK_HELPER::get_option('seek_property_currency_symbol','$'), 'tfuse')

        ),
        'sql_generator'         => 'option_int',
        'sql_generator_options' => array(
            'search_on'         => 'options',
            'search_on_id'      => 'seek_property_price',
            'relation'          => '<=',
        ),
    ),

    'extended_mileage_to'  => array(
        'parameter_name'        => 'mileage_to',
        'template'              => 'custom-select',
        'template_vars'         => array(
            'label'             => __('Mileage up to', 'tfuse'),
            'default_option'    => __('All', 'tfuse'),
            'select_class'      => '',
        ),
        'settings'              => array(
            //int options
            /*'from'              => 50000,
            'to'                => 300000,
            'step'              => 50000,*/
            // or custom options
            'options'           => array(
                50000       => '50.000',
                100000      => '100.000',
                150000      => '150.000',
                200000      => '200.000',
                300000      => '300.000',
                1000000      => 'Unlimited',
            )

        ),
        'sql_generator'         => 'option_int',
        'sql_generator_options' => array(
            'search_on'         => 'options',
            'search_on_id'      => 'seek_property_mileage',
            'relation'          => '<=',
        ),
    ),

    'extended_fuel_type'  => array(
        'parameter_name'        => 'fuel_type',
        'template'              => 'taxonomy-select',
        'template_vars'         => array(
            'label'             => __('Fuel Type', 'tfuse'),
            'default_option'    => __('All fuel types', 'tfuse'),
        ),
        'settings'              => array(
            'taxonomy'          => TF_SEEK_HELPER::get_post_type() . '_fuel_type',
            'get_terms_args'    => array(
                'hide_empty'    => false,
                'order_by'      => 'name',
                'order'         => 'ASC',
            ),
        ),
        'sql_generator'         => 'single_term',
        'sql_generator_options' => array(
            'search_on'         => '_fuel_type',
            'search_on_field'      =>TF_SEEK_HELPER::get_post_type() . '_fuel_type',
        ),
    ),

    'extended_vehicle_type'  => array(
        'parameter_name'        => 'type',
        'template'              => 'taxonomy-select',
        'template_vars'         => array(
            'label'             => __('Vehicle Type', 'tfuse'),
            'default_option'    => __('All types', 'tfuse'),
        ),
        'settings'              => array(
            'taxonomy'          => TF_SEEK_HELPER::get_post_type() . '_type',
            'get_terms_args'    => array(
                'hide_empty'    => false,
                'order_by'      => 'name',
                'order'         => 'ASC',
            ),
        ),
        'sql_generator'         => 'single_term',
        'sql_generator_options' => array(
            'search_on_field'      => TF_SEEK_HELPER::get_post_type().'_type',
        ),
    ),

    'extended_location'  => array(
        'parameter_name'        => 'location',
        'template'              => 'taxonomy-select',
        'template_vars'         => array(
            'label'             => __('Country / State', 'tfuse'),
            'default_option'    => __('All locations', 'tfuse'),
            'select_class'      => '',
        ),
        'settings'              => array(
            'taxonomy'          => TF_SEEK_HELPER::get_post_type() . '_locations',
            'get_terms_args'    => array(
                'hide_empty'    => false,
                'order_by'      => 'name',
                'order'         => 'ASC',
                'parent'        => 0
            ),
        ),
        'sql_generator'         => 'taxonomy_parent',
        'sql_generator_options' => array(
            'search_on'         => 'taxonomy',
            'search_on_id'      => TF_SEEK_HELPER::get_post_type().'_locations',
        ),
    ),

    'extended_engine_power_kw'  => array(
        'parameter_name'        => 'engine_power_kw',
        'template'              => 'interval-select',
        'template_vars'         => array(
            'label'             => __('Motor Power', 'tfuse'),
            'default_option'    => __('All', 'tfuse'),
            'select_class'      => '',
        ),
        'settings'              => array(
            'complete_from'     =>'seek_property_engine_power_kw',
            'custom_options'    => array(
                '110-146'   => '110 ' . $engine_power_kw_symbol . ' - 146 ' . $engine_power_kw_symbol,
                '147-184'   => '147 ' . $engine_power_kw_symbol . ' - 184 ' . $engine_power_kw_symbol,
                '185-222'   => '185 ' . $engine_power_kw_symbol . ' - 222 ' . $engine_power_kw_symbol,
                '223-262'   => '223 ' . $engine_power_kw_symbol . ' - 262 ' . $engine_power_kw_symbol,
                '263-295'   => '263 ' . $engine_power_kw_symbol . ' - 295 ' . $engine_power_kw_symbol,

            ),
            'custom_labels'         => true,
            'auto_options'          => true,    // false if it completes, it will take into consideration custom_options(boolean default: true)
            'auto_steps'            => 5,       // (int, default: 5)
            'auto_round_precision'  => -1,       //  (number of digits after the decimal point). precision can also be negative or zero (default).
            'auto_options_suffix'   => $engine_power_kw_symbol, // when auto_options is true
            'auto_label_format'     => '%%value1%%  %%suffix%% - %%value2%% %%suffix%%'
        ),
        'sql_generator'         => 'numeric_interval',
        'sql_generator_options' => array(
            'search_on'         => 'options',
            'search_on_id'      => 'seek_property_engine_power_kw',
        ),
    ),

    'extended_engine_size'  => array(
        'parameter_name'        => 'engine_size',
        'template'              => 'interval-select',
        'template_vars'         => array(
            'label'             => __('Engine Size', 'tfuse'),
            'default_option'    => __('All', 'tfuse'),
            'select_class'      => '',
        ),
        'settings'              => array(
            'complete_from'     =>'seek_property_engine_size',
            'custom_options'    => array(
                '1100-1599'  => '1.1 - 1.5',
                '1600-2000'  => '1.6 - 2.0',
                '2001-3000'  => '2.1 - 3.0',
                '3001-4000'  => '3.1 - 4.0',
                '4001-10000' => '4.1+'

            ),
            'custom_labels' => true,
            'auto_options'      => true,    // false if it completes, it will take into consideration custom_options(boolean default: true)
            'auto_steps'        => 5,       // (int, default: 5)
            'auto_round_precision'  => -2,  //  (number of digits after the decimal point). precision can also be negative or zero (default).
            'auto_options_suffix'    => __( ' cm<sup>3</sup>', 'tfuse'), // when auto_options is true
            'auto_label_format' => '%%value1%% - %%value2%% %%suffix%%'

        ),
        'sql_generator'         => 'numeric_interval',
        'sql_generator_options' => array(
            'search_on'         => 'options',
            'search_on_id'      => 'seek_property_engine_size',
        ),
    ),

    'extended_gearbox'  => array(
        'parameter_name'        => 'gearbox',
        'template'              => 'taxonomy-select',
        'template_vars'         => array(
            'label'             => __('Gearbox', 'tfuse'),
            'select_class'      => '',
            'default_option'    => __('All', 'tfuse'),
        ),
        'settings'              => array(
            'taxonomy'          => TF_SEEK_HELPER::get_post_type() . '_gearboxes',
            'get_terms_args'    => array(
                'hide_empty'    => false,
                'order_by'      => 'name',
                'order'         => 'ASC',
                'parent'        => 0
            ),
        ),
        'sql_generator'         => 'single_term',
        'sql_generator_options' => array(
            'search_on_field'      => TF_SEEK_HELPER::get_post_type().'_gearboxes',
        ),
    ),

    'extended_status'  => array(
        'parameter_name'        => 'status',
        'template'              => 'taxonomy-select',
        'template_vars'         => array(
            'label'             => __('Status', 'tfuse'),
            'default_option'    => __('All', 'tfuse')
        ),
        'settings'              => array(
            'taxonomy'          => TF_SEEK_HELPER::get_post_type() . '_statuses',
            'get_terms_args'    => array(
                'hide_empty'    => false,
                'order_by'      => 'name',
                'order'         => 'DESC',
            ),
        ),
        'sql_generator'         => 'single_term',
        'sql_generator_options' => array(
            'search_on_field'      => TF_SEEK_HELPER::get_post_type().'_statuses',
        ),
    ),

    'filter_maker'  => array(
        'parameter_name'        => 'maker',
        'template'              => 'taxonomy-select',
        'template_vars'         => array(
            'label'             => __('Car Maker', 'tfuse'),
            'default_option'    => __('All Makers', 'tfuse'),
            'select_class'      => 'white_select'
        ),
        'settings'              => array(
            'taxonomy'          => TF_SEEK_HELPER::get_post_type() . '_models',
            'have_child'        => true,
            'get_terms_args'    => array(
                'hide_empty'    => false,
                'order_by'      => 'name',
                'order'         => 'ASC',
                'parent'        => 0
            ),
        ),
        'sql_generator'         => 'taxonomy_parent',
        'sql_generator_options' => array(
            'search_on'         => 'taxonomy',
            'search_on_id'      => TF_SEEK_HELPER::get_post_type().'_models',
        ),
    ),

    'filter_model'  => array(
        'parameter_name'        => 'model',
        'template'              => 'sensitive-taxonomy-select',
        'template_vars'         => array(
            'label'             => __('Model', 'tfuse'),
            'default_option'    => __('All Models', 'tfuse'),
            'select_class'      => 'white_select'
        ),
        'settings'              => array(
            'parent_name'       => 'maker',
            'taxonomy'          => TF_SEEK_HELPER::get_post_type() . '_models',
            'no_children_text'  => __('All Models', 'tfuse')
        ),
        'sql_generator'         => 'taxonomy_parent',
        'sql_generator_options' => array(
            'search_on'         => 'taxonomy',
            'search_on_id'      => TF_SEEK_HELPER::get_post_type().'_models',
        ),
    ),

    'filter_multi_maker'  => array(
        'parameter_name'        => 'maker',
        'template'              => 'multi-taxonomy-select',
        'template_vars'         => array(
            'label'             => __('Car Maker', 'tfuse'),
            'default_option'    => __('Select Car maker', 'tfuse'),
        ),
        'settings'              => array(
            'taxonomy'          => TF_SEEK_HELPER::get_post_type() . '_models',
            'have_child'        => true,
            'get_terms_args'    => array(
                'hide_empty'    => false,
                'order_by'      => 'name',
                'order'         => 'ASC',
                'parent'        => 0
            ),
        ),
        'sql_generator'         => 'multi_taxonomy_parent',
        'sql_generator_options' => array(
            'search_on'         => 'taxonomy',
            'search_on_id'      => TF_SEEK_HELPER::get_post_type().'_models',
        ),
    ),

    'filter_multi_model'  => array(
        'parameter_name'        => 'model',
        'template'              => 'sensitive-multi-taxonomy-select',
        'template_vars'         => array(
            'label'             => __('Model', 'tfuse'),
            'default_option'    => __('All Models', 'tfuse')
        ),
        'settings'              => array(
            'taxonomy'          => TF_SEEK_HELPER::get_post_type() . '_models',
            'parent_name'       => 'maker',
            'parent_id'         => 'filter_multi_maker'
        ),
        'sql_generator'         => 'multi_taxonomy_parent',
        'sql_generator_options' => array(
            'search_on'         => 'taxonomy',
            'search_on_id'      => TF_SEEK_HELPER::get_post_type().'_models',
        ),
    ),

    'filter_registration_year'  => array(
        'parameter_name'        => 'registration_year',
        'template'              => 'custom-select',
        'template_vars'         => array(
            'label'             => __('Regist. from', 'tfuse'),
            'default_option'    => __('All years', 'tfuse'),
            'select_class'      => 'white_select',
        ),
        'settings'              => array(
            //int options
            'from'              => 2001,
            'to'                => date("Y"),
            'step'              => 1,
            // or custom options
            /*'options'           => array(
                                    2001    => '2001',
                                    2002    => '2002',
                                    2003    => '2003',
                                    2004    => '2004',
                                    2005    => '2005',
                                    2006    => '2006',
                                    2007    => '2007',
                                    2008    => '2008',
                                    2009    => '2009',
                                    2010    => '2010',
                                    2011    => '2011',
                                    2012    => '2012'
            )*/

        ),
        'sql_generator'         => 'year',
        'sql_generator_options' => array(
            'search_on'         => 'seek_property_year',
            'relation'          => '>=',

        ),
    ),

    'filter_price'     => array(
        'parameter_name'        => 'price',
        'template'              => 'slider',
        'template_vars'         => array(
            'label'             => __('Price Range', 'tfuse'),
            'label_class'       => 'label_title2',
            'symbol'            => TF_SEEK_HELPER::get_option('seek_property_currency_symbol','$')
        ),
        'settings'              => array(
            'from'              => 100,
            'to'                => 100000,
            'selected_values'   => array(5000, 60000), //two default selected values
            'scale'             => array( TF_SEEK_HELPER::get_option('seek_property_currency_symbol','$') . '100', TF_SEEK_HELPER::get_option('seek_property_currency_symbol','$') . '100k'),
            'limits'            => FALSE,
            'step'              => 100,
            'smooth'            => TRUE,
            'dimension'         => TF_SEEK_HELPER::get_option('seek_property_currency_symbol','$'),
            'skin'              => "round_green",

            'auto_options'      => true,
            'auto_step'         => 100,

            'listen_items'       =>array(
                'min'           => false,
                'max'           => 'price_until'
            )
        ),
        'sql_generator'         => 'numeric_interval',
        'sql_generator_options' => array(
            'search_on'         => 'options',
            'search_on_id'      => 'seek_property_price',
        ),
    ),

    'filter_mileage'     => array(
        'parameter_name'        => 'mileage',
        'template'              => 'slider',
        'template_vars'         => array(
            'label'             => __('Kilometers', 'tfuse'),
            'label_class'       => 'label_title2'

        ),
        'settings'              => array(
            'from'              => 100,
            'to'                => 200000,
            'selected_values'   => array(5000, 60000), //two default selected values
            'scale'             => array( '100', '200k'),
            'limits'            => FALSE,
            'step'              => 100,
            'smooth'            => TRUE,
            //'dimension'         => TF_SEEK_HELPER::get_option('seek_property_mileage_symbol','KM'),
            'skin'              => "round_green",

            'auto_options'      => true,
            'auto_step'         => 100,

            'listen_items'       =>array(
                'min'           => false,
                'max'           => 'mileage_to'
            )
        ),
        'sql_generator'         => 'numeric_interval',
        'sql_generator_options' => array(
            'search_on'         => 'options',
            'search_on_id'      => 'seek_property_mileage',
        ),
    ),

    'filter_vehicle_type'       => array(
        'parameter_name'        => 'type',
        'template'              => 'taxonomy-checkboxes',
        'template_vars'         => array(
            'label'             => __('Vehicle Type', 'tfuse'),
            'label_class'       => 'label_title2',
            'show_counts'           => true,
            'counts_label_singular' => '',
            'counts_label_plural'   => '',
        ),
        'settings'              => array(
            'taxonomy'          => TF_SEEK_HELPER::get_post_type() . '_type',
            'get_terms_args'    => array(
                'hide_empty'    => false,
                'order_by'      => 'name',
                'order'         => 'ASC',
            ),
        ),
        'sql_generator'         => 'multi_terms_custom_relation',
        'sql_generator_options' => array(
            'search_on'         => 'taxonomy',
            'intern_relation'   => 'OR',//allowed: AND,OR(OR default)
            'search_on_field'      => TF_SEEK_HELPER::get_post_type().'_type',
        ),
    ),

    'filter_fuel_type'       => array(
        'parameter_name'        => 'fuel_type',
        'template'              => 'taxonomy-checkboxes',
        'template_vars'         => array(
            'label'             => __('Fuel Type', 'tfuse'),
            'label_class'       => 'label_title2',
            'show_counts'           => true,
            'counts_label_singular' => '',
            'counts_label_plural'   => '',
        ),
        'settings'              => array(
            'taxonomy'          => TF_SEEK_HELPER::get_post_type() . '_fuel_type',
            'get_terms_args'    => array(
                'hide_empty'    => false,
                'order_by'      => 'name',
                'order'         => 'ASC',
            ),
        ),
        'sql_generator'         => 'multi_terms',
        'sql_generator_options' => array(
            'search_on_field'      => TF_SEEK_HELPER::get_post_type().'_fuel_type',
        ),
    ),

    'filter_color'       => array(
        'parameter_name'        => 'color',
        'template'              => 'taxonomy-checkboxes',
        'template_vars'         => array(
            'label'             => __('Car Colour', 'tfuse'),
            'label_class'       => 'label_title2',
            'show_counts'           => true,
            'counts_label_singular' => '',
            'counts_label_plural'   => '',
        ),
        'settings'              => array(
            'taxonomy'          => TF_SEEK_HELPER::get_post_type() . '_colors',
            'get_terms_args'    => array(
                'hide_empty'    => false,
                'order_by'      => 'count',
                'order'         => 'DESC',
            ),
        ),
        'sql_generator'         => 'multi_terms',
        'sql_generator_options' => array(
            'search_on_field'      => TF_SEEK_HELPER::get_post_type().'_colors',
        ),
    ),

    'filter_location'  => array(
        'parameter_name'        => 'location',
        'template'              => 'hidden-input',
        'template_vars'         => array(
            'label'             => __('Country / State', 'tfuse'),
            'default_option'    => __('All locations', 'tfuse'),
            'select_class'      => '',
        ),
        'settings'              => array(
            'taxonomy'          => TF_SEEK_HELPER::get_post_type() . '_locations',
            'get_terms_args'    => array(
                'hide_empty'    => false,
                'order_by'      => 'name',
                'order'         => 'ASC',
                'parent'        => 0
            ),
        ),
        'sql_generator'         => 'taxonomy_parent',
        'sql_generator_options' => array(
            'search_on'         => 'taxonomy',
            'search_on_id'      => TF_SEEK_HELPER::get_post_type().'_locations',
        ),
    ),

    'filter_engine_power_kw'  => array(
        'parameter_name'        => 'engine_power_kw',
        'template'              => 'hidden-input',
        'template_vars'         => array(
            'label'             => __('Motor Power', 'tfuse'),
            'default_option'    => __('All', 'tfuse'),
            'select_class'      => '',
        ),
        'settings'              => array(
            'complete_from'     =>'seek_property_engine_power_kw',
            'custom_options'    => array(
                '110-146'   => '110 ' . $engine_power_kw_symbol . ' - 146 ' . $engine_power_kw_symbol,
                '147-184'   => '147 ' . $engine_power_kw_symbol . ' - 184 ' . $engine_power_kw_symbol,
                '185-222'   => '185 ' . $engine_power_kw_symbol . ' - 222 ' . $engine_power_kw_symbol,
                '223-262'   => '223 ' . $engine_power_kw_symbol . ' - 262 ' . $engine_power_kw_symbol,
                '263-295'   => '263 ' . $engine_power_kw_symbol . ' - 295 ' . $engine_power_kw_symbol,

            ),
            'custom_labels'         => true,
            'auto_options'          => true,    // false if it completes, it will take into consideration custom_options(boolean default: true)
            'auto_steps'            => 5,       // (int, default: 5)
            'auto_round_precision'  => -1,       //  (number of digits after the decimal point). precision can also be negative or zero (default).
            'auto_options_suffix'   => $engine_power_kw_symbol, // when auto_options is true
            'auto_label_format'     => '%%value1%%  %%suffix%% - %%value2%% %%suffix%%'
        ),
        'sql_generator'         => 'numeric_interval',
        'sql_generator_options' => array(
            'search_on'         => 'options',
            'search_on_id'      => 'seek_property_engine_power_kw',
        ),
    ),

    'filter_engine_size'  => array(
        'parameter_name'        => 'engine_size',
        'template'              => 'hidden-input',
        'template_vars'         => array(
            'label'             => __('Engine Size', 'tfuse'),
            'default_option'    => __('All', 'tfuse'),
            'select_class'      => '',
        ),
        'settings'              => array(
            'complete_from'     =>'seek_property_engine_size',
            'custom_options'    => array(
                '1100-1599'  => '1.1 - 1.5',
                '1600-2000'  => '1.6 - 2.0',
                '2001-3000'  => '2.1 - 3.0',
                '3001-4000'  => '3.1 - 4.0',
                '4001-10000' => '4.1+'

            ),
            'custom_labels'     => true,
            'auto_options'      => true,    // false if it completes, it will take into consideration custom_options(boolean default: true)
            'auto_steps'        => 5,       // (int, default: 5)
            'auto_round_precision'  => -2,  //  (number of digits after the decimal point). precision can also be negative or zero (default).
            'auto_options_suffix'    => __( ' cm<sup>3</sup>', 'tfuse'), // when auto_options is true
            'auto_label_format' => '%%value1%% - %%value2%% %%suffix%%'

        ),
        'sql_generator'         => 'numeric_interval',
        'sql_generator_options' => array(
            'search_on'         => 'options',
            'search_on_id'      => 'seek_property_engine_size',
        ),
    ),

    'filter_gearbox'  => array(
        'parameter_name'        => 'gearbox',
        'template'              => 'hidden-input',
        'template_vars'         => array(
            'label'             => __('Gearbox', 'tfuse'),
            'select_class'      => '',
        ),
        'settings'              => array(
            'taxonomy'          => TF_SEEK_HELPER::get_post_type() . '_gearboxes',
            'get_terms_args'    => array(
                'hide_empty'    => false,
                'order_by'      => 'name',
                'order'         => 'ASC',
                'parent'        => 0
            ),
        ),
        'sql_generator'         => 'single_term',
        'sql_generator_options' => array(
            'search_on_field'      => TF_SEEK_HELPER::get_post_type().'_gearboxes',
        ),
    ),

    'filter_status'  => array(
        'parameter_name'        => 'status',
        'template'              => 'hidden-input',
        'template_vars'         => array(
            'label'             => __('Status', 'tfuse'),
        ),
        'settings'              => array(
            'taxonomy'          => TF_SEEK_HELPER::get_post_type() . '_statuses',
            'get_terms_args'    => array(
                'hide_empty'    => false,
                'order_by'      => 'name',
                'order'         => 'DESC',
            ),
        ),
        'sql_generator'         => 'single_term',
        'sql_generator_options' => array(
            'search_on_field'      => TF_SEEK_HELPER::get_post_type().'_statuses',
        ),
    ),

    'favorites'     => array(
        'parameter_name'        => 'favorites',
        'template'              => 'hidden-input',
        'template_vars'         => array(
            'no_output'         => !$TFUSE->request->isset_GET('favorites')
        ),
        'settings'              => array(),
        'sql_generator'         => 'favorites',
        'sql_generator_options' => array(
            'search_on'         => '',
            'search_on_id'      => '',
        ),
    ),

    'promos'     => array(
        'parameter_name'        => 'promos',
        'template'              => 'hidden-input',
        'template_vars'         => array(
            'no_output'         => !$TFUSE->request->isset_GET('promos')
        ),
        'settings'              => array(),
        'sql_generator'         => 'promos',
        'sql_generator_options' => array(
            'search_on'         => '',
            'search_on_id'      => '',
        ),
    ),

);