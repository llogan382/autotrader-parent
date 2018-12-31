<?php

if (!defined('TFUSE'))
    exit('Direct access forbidden.');


/**
 * Object with static Funtions that return pieces of sql (for WHERE) with data from search inputs for main seek search sql
 */

/**
 * Function Structure:
 * public static function <Function-Name>($item_id, $sql_options, $settings, $template_vars, $all_item_options)
 *
 * return: array(
 *      'sql' => '...',  // Please, create safe sql!
 * )
 */
class TF_SEEK_SQL_GENERATORS {

    public static function numeric_interval($item_id, $parameter_name, $sql_options, $settings, $template_vars, $all_item_options)
    {
        $result = array(
            'sql'           => '',
            'in_taxonomy'   => ''
        );

        $values = TF_SEEK_HELPER::get_input_value($parameter_name);
        $delimiter = '-';
        if(strpos($values, ';'))
            $delimiter = ';';
        $values = explode($delimiter, $values);

        if((!is_numeric($values[0])) || (!is_numeric($values[1])))
            return $result;

        $result['sql'] = '(' . $sql_options['search_on'] . '.' . $sql_options['search_on_id'] . ' >= ' .  min($values) . ') AND (' .  $sql_options['search_on'] . '.' . $sql_options['search_on_id'] . ' <= ' .  max($values) . ')';

        return $result;
    }

    public static function year($item_id, $parameter_name, $sql_options, $settings, $template_vars, $all_item_options)
    {
        $result = array(
            'sql'           => '',
            'in_taxonomy'   => ''
        );

        if(!in_array($sql_options['relation'], array('>=', '=', '<=')))
            return $result;

        $searched_time = ($sql_options['relation'] != '<=') ? strtotime('01/01/' . TF_SEEK_HELPER::get_input_value($parameter_name)) : strtotime('12/31/' . TF_SEEK_HELPER::get_input_value($parameter_name));

        if( (!$searched_time) || ($searched_time == -1))
            return $result;

        if($sql_options['relation'] == '=')
        {
            $result['sql']  = 'options.' . $sql_options['search_on'] . ' LIKE \'' . date('Y', $searched_time) . '-%\'';
            return $result;
        }

        $result['sql'] = 'options.' . $sql_options['search_on'] . ' ' . $sql_options['relation'] . '\'' . date('Y-m-d\TH:i:sO', $searched_time) . '\'';

        return $result;
    }
    // Simple int option
    public static function option_int($item_id, $parameter_name, $sql_options, $settings, $template_vars, $all_item_options){
        global $wpdb;

        $result = array(
            'sql'           => '',
            'in_taxonomy'   => ''
        );

        $value  = TF_SEEK_HELPER::get_input_value($parameter_name);
        if(!is_numeric($value))
            return $result;
        do{
            $value = intval($value);

            $relation = $sql_options['relation'];


            $sql = " {$sql_options['search_on']}.{$sql_options['search_on_id']} {$relation} {$value} ";

            $result['sql'] = $sql;
        }while(false);

        return $result;
    }

    // Simple int options (ex:'1;4;7')
    public static function options_int($item_id, $parameter_name, $sql_options, $settings, $template_vars, $all_item_options){
        global $wpdb;

        $result = array(
            'sql'           => '',
            'in_taxonomy'   => ''
        );

        $values = explode(';', TF_SEEK_HELPER::get_input_value($parameter_name));
        $sqls   = array();
        if(sizeof($values)){
            foreach($values as $value){
                $value = intval($value);

                if($value < $settings['min'] || $value > $settings['max']){
                    continue;
                }

                $relation = $sql_options['relation'];
                if( $value >= $settings['max'] && isset($sql_options['relation_max']) ){
                    $relation = $sql_options['relation_max'];
                } elseif( $value <= $settings['min'] && isset($sql_options['relation_min']) ){
                    $relation = $sql_options['relation_min'];
                }

                $sqls[] = " {$sql_options['search_on']}.{$sql_options['search_on_id']} {$relation} {$value} ";
            }
        }

        if(sizeof($sqls)){
            $result['sql'] = implode(' OR ', $sqls);
        } else {
            $result['sql'] = '';
        }

        return $result;
    }

    // Ex: 1;4 => ... => ( (option >= 1000 AND option <=1500) OR (option >= 2500) )
    public static function intervals_options_int($item_id, $parameter_name, $sql_options, $settings, $template_vars, $all_item_options){
        global $wpdb;

        $result = array(
            'sql'           => '',
            'in_taxonomy'   => ''
        );

        $values = explode(';', TF_SEEK_HELPER::get_input_value($parameter_name));
        $sqls   = array();
        if(sizeof($values)){
            foreach($values as $value){
                $value = intval($value);

                if($value < 1 || $value > $settings['max_steps']){
                    continue;
                }

                $interval = array(
                    'from'  => $settings['start'] + ( $settings['step'] * ($value-1) ),
                    'to'    => $settings['start'] + ( $settings['step'] * ($value) ) - 1
                );

                if($value>=$settings['max_steps'] && @$settings['max_unlimited']){
                    $sqls[] = " {$sql_options['search_on']}.{$sql_options['search_on_id']} >= {$interval['from']} ";
                } else {
                    $sqls[] = " ( {$sql_options['search_on']}.{$sql_options['search_on_id']} >= {$interval['from']} AND {$sql_options['search_on']}.{$sql_options['search_on_id']} <= {$interval['to']} ) ";
                }
            }
        }

        if(sizeof($sqls)){
            $result['sql'] = implode(' OR ', $sqls);
        } else {
            $result['sql'] = '';
        }

        return $result;
    }



    // taxonomy %taxonomy%
    public static function taxonomy_univalue($item_id, $parameter_name, $sql_options, $settings, $template_vars, $all_item_options){
        global $wpdb;

        $result = array(
            'sql'           => '',
            'in_taxonomy'   => ''
        );

        $value  = TF_SEEK_HELPER::get_input_value($parameter_name);

        do {
            $sql = "";

            $search_item = trim($value);

            if(!$search_item) continue;

            if(mb_strlen($search_item, 'UTF-8') < 2) continue; // minimum length

            // Replace multiple spaces (no regexp because of utf8)
            while (strpos('  ', $search_item)) $search_item = str_replace('  ', ' ', $search_item);

            // safe
            $search_item = TF_SEEK_HELPER::safe_sql_like($search_item);

            // Replace spaces with %
            $search_item = str_replace(' ', '%', $search_item);

            $sql_like  = "{$sql_options['search_on']}_terms.name LIKE N'%$search_item%'";

            $sql .= $sql_like;

            $result['in_taxonomy']  = $sql_options['search_on_id'];
            $result['sql']          = $sql;
        }while(false);

        return $result;
    }

    // search by taxonomy parent_id and his childs
    public static function taxonomy_parent($item_id, $parameter_name, $sql_options, $settings, $template_vars, $all_item_options){
        global $wpdb;

        $result = array(
            'sql'           => '',
            'in_taxonomy'   => ''
        );

        do {
            $sql = "";

            $is_child = false;
            if(isset($settings['parent_item_id'])){

                $all_items      = TF_SEEK_HELPER::get_items_options();
                if(!isset($all_items[ @$settings['parent_item_id'] ])) break;

                $parent_item    = $all_items[ $settings['parent_item_id'] ];

                $is_child = true;

                $child__parameter_name  = $parameter_name;

                $parameter_name         = $parent_item['parameter_name'];
                $settings               = $parent_item['settings'];
                $sql_options            = $parent_item['sql_generator_options'];
            }

            $value  = TF_SEEK_HELPER::get_input_value($parameter_name);

            if(!is_numeric($value)) break;

            $value = intval( $value );

            // Check if this taxonomy exists
            //if(!term_exists($value, $sql_options['search_on_id'], $settings['select_parent'])) break;

            $search_terms = array($value);

            $list = get_terms($sql_options['search_on_id'], 'hide_empty=0&fields=ids&child_of='.$value);

            if(sizeof($list)){

                if($is_child){
                    $child_input_value    = TF_SEEK_HELPER::get_input_value($child__parameter_name,'');
                    $child_input_values   = explode(';', $child_input_value);

                    // Check if input values has at least one valid id, else ignore it as it is wrong
                    $is_valid_child_values= false;
                    foreach($list as $term){
                        if(in_array($term, $child_input_values)){
                            $is_valid_child_values = true;
                            $search_terms = array(); // remove parent from search
                            break;
                        }
                    }
                }

                foreach($list as $term){
                    if($is_child && $is_valid_child_values){
                        if(!in_array($term, $child_input_values)){
                            continue;
                        }
                    }

                    $search_terms[] = $term;
                }
            }

            $result['in_taxonomy_ids'] = implode(',', $search_terms);

            $result['sql']          = $sql;
        }while(false);

        return $result;
    }

    // search by taxonomy parent_id and his childs
    public static function multi_taxonomy_parent($item_id, $parameter_name, $sql_options, $settings, $template_vars, $all_item_options){
        global $wpdb;

        $result = array(
            'sql'           => '',
            'in_taxonomy'   => ''
        );

        do {
            $sql = "";

            $is_child = false;
            if(isset($settings['parent_item_id'])){

                $all_items      = TF_SEEK_HELPER::get_items_options();
                if(!isset($all_items[ @$settings['parent_item_id'] ])) break;

                $parent_item    = $all_items[ $settings['parent_item_id'] ];

                $is_child = true;

                $child__parameter_name  = $parameter_name;

                $parameter_name         = $parent_item['parameter_name'];
                $settings               = $parent_item['settings'];
                $sql_options            = $parent_item['sql_generator_options'];
            }

            $values  = explode(';', TF_SEEK_HELPER::get_input_value($parameter_name));

            $list = array();
            if(is_array($values)){
                foreach($values as $value){
                    if(!is_numeric($value)) continue;
                    $list = array_merge($list, get_terms($sql_options['search_on_id'], 'hide_empty=0&fields=ids&child_of='.$value));
                }

            }else{
                break;
            }

            $search_terms = $values;
            if(sizeof($list)){

                if($is_child){
                    $child_input_value    = TF_SEEK_HELPER::get_input_value($child__parameter_name,'');
                    $child_input_values   = explode(';', $child_input_value);

                    // Check if input values has at least one valid id, else ignore it as it is wrong
                    $is_valid_child_values= false;
                    foreach($list as $term){
                        if(in_array($term, $child_input_values)){
                            $is_valid_child_values = true;
                            $search_terms = array(); // remove parent from search
                            break;
                        }
                    }
                }

                foreach($list as $term){
                    if($is_child && $is_valid_child_values){
                        if(!in_array($term, $child_input_values)){
                            continue;
                        }
                    }

                    $search_terms[] = $term;
                }
            }

            $result['in_taxonomy_ids'] = implode(',', $search_terms);

            $result['sql']          = $sql;
        }while(false);

        return $result;
    }

    public static function taxonomy_multi_ids($item_id, $parameter_name, $sql_options, $settings, $template_vars, $all_item_options){
        global $wpdb;

        $result = array(
            'sql'           => '',
            'in_taxonomy'   => ''
        );

        do{
            $sql = '';

            $value       = TF_SEEK_HELPER::get_input_value($parameter_name);
            if(!$value) break;

            $values      = explode(';', (string)$value );

            // cleanup input
            $old_values  = $values;
            $values      = array();
            if(sizeof($old_values)){
                foreach($old_values as $id){
                    if(1 > ( $id = intval($id)) ) continue;

                    $values[ $id ] = '~';
                }
            }
            $values = array_keys($values);
            unset($old_values);

            if(!sizeof($values)) break;

            $terms = get_terms($sql_options['search_on_id'], 'hide_empty=0' . (@$template_vars['get_terms_args']) . '&fields=ids' . '&include='.implode(',', $values) );
            if(!sizeof($terms)) break;

            $result['in_taxonomy_ids'] = implode(',', $terms);

            //$result['in_taxonomy']  = $sql_options['search_on_id'];
            $result['sql']          = $sql;

        }while(false);

        return $result;
    }

    public static function single_term($item_id, $parameter_name, $sql_options, $settings, $template_vars, $all_item_options){

        $result = array(
            'sql'           => '',
            'in_taxonomy'   => ''
        );

        $sql = '';
        $value       = intval(TF_SEEK_HELPER::get_input_value($parameter_name));
        if(!$value) return $result;

        $sql .= "options." . $sql_options['search_on_field'] . " REGEXP '((^|,)+" . strval($value) . ",)'";

        $result['sql'] = $sql;
        return $result;
    }

    public static function multi_terms($item_id, $parameter_name, $sql_options, $settings, $template_vars, $all_item_options){

        $result = array(
            'sql'           => '',
            'in_taxonomy'   => ''
        );

        $sql = '';
        $value       = TF_SEEK_HELPER::get_input_value($parameter_name);
        if(!$value) return $result;

        $values      = explode(';', strval($value) );

        // cleanup input
        $old_values  = $values;
        $values      = array();

        if(sizeof($old_values)){
            foreach($old_values as $id){
                if(1 > ( $id = intval($id)) ) continue;

                $values[] = $id;
            }
        }
        unset($old_values);


        if(sizeof($values)){
            $sql .= "options." . $sql_options['search_on_field'] . " REGEXP '(";
            foreach($values as $term){
                $sql .= "(^|,)+" . strval($term) . ",|";
            }
            $sql = substr($sql, 0, -1);
            $sql .=")'";
        }

        $result['sql'] = $sql;
        return $result;
    }

    public static function multi_terms_custom_relation($item_id, $parameter_name, $sql_options, $settings, $template_vars, $all_item_options){

        $result = array(
            'sql'           => '',
            'in_taxonomy'   => ''
        );
        if(!empty($sql_options['intern_relation']) && ($sql_options['intern_relation']=='AND')) $intern_relation = 'AND'; else $intern_relation = 'OR';
        do{
            $sql = '';

            $value       = TF_SEEK_HELPER::get_input_value($parameter_name);
            if(!$value) break;

            $values      = explode(';', (string)$value );

            // cleanup input
            $old_values  = $values;
            $values      = array();

            if(sizeof($old_values)){
                foreach($old_values as $id){
                    if(1 > ( $id = intval($id)) ) continue;

                    $values[] = $id;
                }
            }
            unset($old_values);

            $len = count($values);
            if(sizeof($values)){
                foreach($values as $key => $term){
                    if($key != $len-1){
                        $sql .= " options." . $sql_options['search_on_field'] . " REGEXP '((^|,)+" . $term . ",)' " . $intern_relation;
                    }else{
                        $sql .= " options." . $sql_options['search_on_field'] . " REGEXP '((^|,)+" . $term . ",)' ";
                    }

                }
            }
            $result['sql']          = $sql;

        }while(false);

        return $result;
    }

    public static function favorites($item_id, $parameter_name, $sql_options, $settings, $template_vars, $all_item_options){
    global $wpdb, $TFUSE;

    $result = array(
        'sql'           => '',
        'in_taxonomy'   => ''
    );

    do{
        $value  = TF_SEEK_HELPER::get_input_value($parameter_name);
        if($value === NULL) break;

        $cookieFavorites    = (!$TFUSE->request->empty_COOKIE('favorite_posts')) ? $TFUSE->request->COOKIE('favorite_posts') : '';
        $cookieValues       = explode(',', $cookieFavorites);
        $validValues        = array();
        foreach($cookieValues as $val){
            $val = intval(trim($val));

            if($val<1) continue;

            $validValues[ $val ] = $val;
        }

        if(!sizeof($validValues)) $validValues[0] = 0;

        $stringValidValues = implode(',', array_keys($validValues));

        $result['sql'] = 'p.ID IN (' . $stringValidValues . ')';

        /// $validValues contains only valid/clean/unique values of properties ids
        /// so replace this clean values in cookie
        /// uncoment an correct this:
        // set_cookie('favorite_posts', $stringValidValues);
    }while(false);

    return $result;
}

    public static function promos($item_id, $parameter_name, $sql_options, $settings, $template_vars, $all_item_options){
        global $wpdb, $TFUSE;

        $result = array(
            'sql'           => '',
            'in_taxonomy'   => ''
        );

        if ($TFUSE->request->isset_GET('promos'))
        {
            $sql = 'options.seek_property_reduction <> 0';
            $result['sql'] = $sql;
        }

        return $result;
    }
}
