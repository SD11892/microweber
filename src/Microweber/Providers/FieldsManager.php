<?php

namespace Microweber\Providers;

api_expose_admin('fields/reorder');
api_expose_admin('fields/delete');
api_expose_admin('fields/make');
api_expose_admin('fields/save');
$_mw_made_default_fields_register = array();

class FieldsManager
{
    public $app;
    public $tables = array();
    public $table = 'custom_fields';
    public $table_values = 'custom_fields_values';
    private $skip_cache = false;

    public function __construct($app = null)
    {
        if (!is_object($this->app)) {
            if (is_object($app)) {
                $this->app = $app;
            } else {
                $this->app = mw();
            }
        }
        $this->tables = $this->app->content_manager->tables;
    }

    public function get_by_id($field_id)
    {
        if ($field_id != 0) {
            $params = array();
            $params['id'] = $field_id;
            $params['return_full'] = true;

            $data = $this->get($params);

            if (isset($data[0])) {
                return $data[0];
            }
        }
    }

    public function make_default($rel, $rel_id, $fields_csv_str)
    {
        global $_mw_made_default_fields_register;
        if (!defined('SKIP_CF_ADMIN_CHECK')) {
            define('SKIP_CF_ADMIN_CHECK', 1);
        }

        // return false;
        $id = $this->app->user_manager->is_admin();
        if ($id == false) {
            //return false;
        }

        $function_cache_id = false;

        $args = func_get_args();

        foreach ($args as $k => $v) {
            $function_cache_id = $function_cache_id.serialize($k).serialize($v);
        }

        $function_cache_id = 'fields_'.__FUNCTION__.crc32($function_cache_id);

        //$is_made = $this->app->option_manager->get($function_cache_id, 'make_default_custom_fields');

        $make_field = array();

        $make_field['rel_type'] = $rel;
        $make_field['rel_id'] = $rel_id;
        $is_made = $this->get_all($make_field);

        if (isset($_mw_made_default_fields_register[ $function_cache_id ])) {
            return;
        }

        if (is_array($is_made) and !empty($is_made)) {
            return;
        }
        $_mw_made_default_fields_register[ $function_cache_id ] = true;

        $table_custom_field = $this->table;

        if (isset($rel)) {
            $rel = $this->app->database_manager->escape_string($rel);

            $rel = $this->app->database_manager->assoc_table_name($rel);
            $rel_id = $this->app->database_manager->escape_string($rel_id);

            if (strstr($fields_csv_str, ',')) {
                $fields_csv_str = explode(',', $fields_csv_str);
                $fields_csv_str = array_trim($fields_csv_str);
            } else {
                $fields_csv_str = array($fields_csv_str);
            }

            $pos = 0;
            if (is_array($fields_csv_str)) {
                foreach ($fields_csv_str as $field_type) {
                    $ex = array();
                    $ex['type'] = $field_type;
                    $ex['rel_type'] = $rel;
                    $ex['rel_id'] = $rel_id;
                    $ex = $this->get_all($ex);
                    if ($ex == false or is_array($ex) == false) {
                        $make_field = array();

                        $make_field['rel_type'] = $rel;
                        $make_field['rel_id'] = $rel_id;
                        $make_field['position'] = $pos;
                        $make_field['name'] = ucfirst($field_type);
                        $make_field['value'] = '';
                        $make_field['type'] = $field_type;
                        $this->save($make_field);
                        ++$pos;
                    }
                }
                if ($pos > 0) {
                    $this->app->cache_manager->delete('custom_fields/global');
                }
            }
        }
    }

    public function save($data)
    {
        if (is_string($data)) {
            $data = parse_params($data);
        }

        $table_custom_field = $this->table;
        $table_values = $this->table_values;
        if (isset($data['cf_id']) and !isset($data['id'])) {
            $data['id'] = $data['cf_id'];
        }
        if (isset($data['field_type']) and !isset($data['type'])) {
            $data['type'] = $data['field_type'];
        }
        if (isset($data['custom_field_type']) and !isset($data['type'])) {
            $data['type'] = $data['custom_field_type'];
        }

        if (isset($params['field_value'])) {
            $params['value'] = $params['field_value'];
        }
        if (isset($data['field_name']) and !isset($data['name'])) {
            $data['name'] = $data['field_name'];
        }

        if (isset($data['for']) and !isset($data['rel_type'])) {
            $data['rel_type'] = $data['for'];
        }

        if (isset($data['type']) and !isset($data['name'])) {
            $data['name'] = $data['type'];
        }

        if (isset($data['rel']) and !isset($data['rel_type'])) {
            $data['rel_type'] = $data['rel'];
        }

        $data_to_save = ($data);
        $data_to_save = $this->unify_params($data_to_save);

        if (isset($data_to_save['for_id'])) {
            $data_to_save['rel_id'] = $data_to_save['for_id'];
        }
        if (isset($data_to_save['id'])) {
            $data_to_save['cf_id'] = $data_to_save['id'];
        }
        if (isset($data_to_save['cf_id'])) {
            $data_to_save['id'] = intval($data_to_save['cf_id']);
            $table_custom_field = $this->table;
            $form_data_from_id = $this->app->database_manager->get_by_id($table_custom_field, $data_to_save['id'], $is_this_field = false);
            if (isset($form_data_from_id['id'])) {
                if (!isset($data_to_save['rel_type'])) {
                    $data_to_save['rel_type'] = $form_data_from_id['rel_type'];
                }
                if (!isset($data_to_save['rel_id'])) {
                    $data_to_save['rel_id'] = $form_data_from_id['rel_id'];
                }
                if (isset($form_data_from_id['type']) and $form_data_from_id['type'] != '' and (!isset($data_to_save['type']) or ($data_to_save['type']) == '')) {
                    $data_to_save['type'] = $form_data_from_id['type'];
                }
                if (isset($form_data_from_id['name']) and $form_data_from_id['name'] != '' and (!isset($data_to_save['name']) or ($data_to_save['name']) == '')) {
                    $data_to_save['name'] = $form_data_from_id['name'];
                }
            }

            if (isset($data_to_save['copy_rel_id'])) {
                $cp = $this->app->database_manager->copy_row_by_id($table_custom_field, $data_to_save['cf_id']);
                $data_to_save['id'] = $cp;
                $data_to_save['rel_id'] = $data_to_save['copy_rel_id'];
            }
        }

        if (!isset($data_to_save['rel_type'])) {
            $data_to_save['rel_type'] = 'content';
        }
        $data_to_save['rel_type'] = $this->app->database_manager->assoc_table_name($data_to_save['rel_type']);
        if (!isset($data_to_save['rel_id'])) {
            $data_to_save['rel_id'] = '0';
        }
        if (isset($data['options'])) {
            $data_to_save['options'] = $this->_encode_options($data['options']);
        }

        $data_to_save['session_id'] = mw()->user_manager->session_id();

        if (!isset($data_to_save['value']) and isset($data_to_save['field_value'])) {
            $data_to_save['value'] = $data_to_save['field_value'];
        } elseif (isset($data_to_save['values'])) {
            $data_to_save['value'] = $data_to_save['values'];
        }

        if ((!isset($data_to_save['id']) or ($data_to_save['id']) == 0) and !isset($data_to_save['is_active'])) {
            $data_to_save['is_active'] = 1;
        }

        if (!isset($data_to_save['type']) or trim($data_to_save['type']) == '') {
            return array('error' => 'You must set type');
        } else {
            if (!isset($data_to_save['name'])) {
                return array('error' => 'You must set name');
            }
            $cf_k = $data_to_save['name'];
            if ($cf_k != false and !isset($data_to_save['name_key'])) {
                $data_to_save['name_key'] = $this->app->url_manager->slug(strtolower($cf_k));
            }

            $data_to_save['allow_html'] = true;
            if (!isset($data_to_save['id'])) {
                $data_to_save['id'] = 0;
            }

            // $this->skip_cache = true;
            $data_to_save['table'] = $table_custom_field;
            $data_to_save['allow_html'] = false;
            $data_to_save_parent = $data_to_save;
            if (isset($data_to_save_parent['value'])) {
                unset($data_to_save_parent['value']);
            }

            $save = $this->app->database_manager->save($data_to_save_parent);

            if (isset($data_to_save['value'])) {
                $custom_field_id = $save;
                $values_to_save = array();
                if (!is_array($data_to_save['value'])) {
                    $values_to_save = array($data_to_save['value']);
                } elseif (is_array($data_to_save['value'])) {
                    $values_to_save = ($data_to_save['value']);
                }

                if (!empty($values_to_save)) {
                    $check_existing = array();
                    $check_existing['table'] = $table_values;
                    $check_existing['custom_field_id'] = $custom_field_id;
                    $check_old = $this->app->database_manager->get($check_existing);
                    $i = 0;
                    foreach ($values_to_save as $value_to_save) {
                        $save_value = array();
                        if (isset($check_old[ $i ]) and isset($check_old[ $i ]['id'])) {
                            $save_value['id'] = $check_old[ $i ]['id'];
                            unset($check_old[ $i ]);
                        }
                        $save_value['custom_field_id'] = $custom_field_id;
                        $save_value['value'] = $value_to_save;
                        if (is_array($value_to_save)) {
                            $save_value['value'] = implode(',', array_values($value_to_save));
                        }
                        $save_value['position'] = $i;
                        $save_value['allow_html'] = false;
                        $save_value = $this->app->database_manager->save($table_values, $save_value);
                        ++$i;
                    }
                    if (!empty($check_old)) {
                        $remove_old_ids = array();
                        foreach ($check_old as $remove) {
                            $remove_old_ids[] = $remove['id'];
                        }
                        if (!empty($remove_old_ids)) {
                            $remove_old = $this->app->database_manager->delete_by_id($table_values, $remove_old_ids);
                        }
                    }
                }
            }

            $this->app->cache_manager->delete('custom_fields/'.$save);
            $this->app->cache_manager->delete('custom_fields');

            return $save;
        }
    }

    public function get_values($custom_field_id)
    {
        $id = $custom_field_id;
        if (is_array($custom_field_id)) {
            $id = implode(',', $custom_field_id);
        }
        $table = $this->table_values;
        $params = array();
        $params['table'] = $table;
        $params['limit'] = 99999;
        $params['custom_field_id'] = '[in]'.$id;
        $data = $this->app->database_manager->get($params);

        return $data;
    }

    public function get_value($content_id, $field_name, $return_full = false, $table = 'content')
    {
        $val = false;
        $data = $this->get($table, $id = $content_id, $return_full, $field_for = false, $debug = false, $field_type = false, $for_session = false);
        foreach ($data as $item) {
            if (isset($item['name']) and
                ((strtolower($item['name']) == strtolower($field_name))
                 or (strtolower($item['type']) == strtolower($item['type'])))
            ) {
                $val = $item['value'];
            }
        }

        return $val;
    }

    public function get_all($params)
    {
        if (!is_array($params)) {
            $params = parse_params($params);
        }
        $table_custom_field = $this->table;
        $params['table'] = $table_custom_field;

        return $this->app->database_manager->get($params);
    }

    public function get($table, $id = 0, $return_full = false, $field_for = false, $debug = false, $field_type = false, $for_session = false)
    {
        $params = array();
        $no_cache = false;
        $table_assoc_name = false;
        // $id = intval ( $id );
        if (is_string($table)) {
            $params = $params2 = parse_params($table);
            if (!is_array($params2) or (is_array($params2) and count($params2) < 2)) {
                $id = trim($id);
                $table = $this->app->database_manager->escape_string($table);
                if ($table != false) {
                    $table_assoc_name = $this->app->database_manager->assoc_table_name($table);
                } else {
                    $table_assoc_name = 'MW_ANY_TABLE';
                }
                if ($table_assoc_name == false) {
                    $table_assoc_name = $this->app->database_manager->assoc_table_name($table_assoc_name);
                }
                $params['rel_type'] = $table_assoc_name;
            } else {
                $params = $params2;
            }
        } elseif (is_array($table)) {
            $params = $table;
        }

        $params = $this->unify_params($params);

        if (!isset($table_assoc_name)) {
            if (isset($params['for'])) {
                $params['rel_type'] = $table_assoc_name = $this->app->database_manager->assoc_table_name($params['for']);
            }
        } else {
            // $params['rel_type'] = $table_assoc_name;
        }

        if (isset($params['debug'])) {
            $debug = $params['debug'];
        }
        if (isset($params['for'])) {
            if (!isset($params['rel_type']) or $params['rel_type'] == false) {
                $params['for'] = $params['rel_type'];
            }
        }
        if (isset($params['for_id'])) {
            $params['rel_id'] = $id = $this->app->database_manager->escape_string($params['for_id']);
        }

        if (isset($params['field_type'])) {
            $params['type'] = $params['field_type'];
        }
        if (isset($params['field_value'])) {
            $params['value'] = $params['field_value'];
        }

        if (isset($params['no_cache'])) {
            $no_cache = $params['no_cache'];
        }

        if (isset($params['return_full'])) {
            $return_full = $params['return_full'];
        }

        if (isset($params['is_active']) and strtolower(trim($params['is_active'])) == 'any') {
        } elseif (isset($params['is_active']) and $params['is_active'] == 0) {
            $custom_field_is_active = 0;
        } else {
            $custom_field_is_active = 1;
        }

        $table_custom_field = $this->table;
        $params['table'] = $table_custom_field;
        if (isset($custom_field_is_active)) {
            $params['is_active'] = $custom_field_is_active;
        }
        if (strval($table_assoc_name) != '') {
            if ($field_for != false) {
                $field_for = trim($field_for);
                $field_for = $this->app->database_manager->escape_string($field_for);
                $params['name'] = $field_for;
            }

            if (isset($params['rel_type']) and $params['rel_type'] == 'MW_ANY_TABLE') {
                unset($params['rel_type']);
            }

            $sidq = '';
            if (intval($id) == 0 and $for_session != false) {
                if (is_admin() != false) {
                    $sid = mw()->user_manager->session_id();
                    $params['session_id'] = $sid;
                }
            }

            if ($id != 'all' and $id != 'any') {
                $id = $this->app->database_manager->escape_string($id);

                $params['rel_id'] = $id;

            }
        }
        if (isset($params['content'])) {
            unset($params['content']);
        }
        if (!isset($params['order_by']) and !isset($params['orderby'])) {
            $params['order_by'] = 'position asc';
        }

        if (empty($params)) {
            return false;
        }

        $q = $this->app->database_manager->get($params);

        if (!empty($q)) {
            $get_values = array();
            $fields = array();
            foreach ($q as $k => $v) {
                $get_values[] = $v['id'];
            }

            $vals = $this->get_values($get_values);

            foreach ($q as $k => $v) {
                if (isset($v['options']) and is_string($v['options'])) {
                    $v['options'] = $this->_decode_options($v['options']);
                }
                $default_values = $v;
                $default_values['values_plain'] = '';
                $default_values['value'] = array();
                $default_values['values'] = array();

                if (!empty($vals)) {
                    foreach ($vals as $val) {
                        if ($val['custom_field_id'] == $v['id']) {
                            $default_values['value'][] = $val['value'];
                            $default_values['values'][] = $val['value'];
                        }
                    }
                }
                if (!empty($default_values['value'])) {
                    $default_values['value_plain'] = implode(',', $default_values['value']);

                    if (count($default_values['value']) == 1) {
                        $default_values['value'] = reset($default_values['value']);
                    }
                } else {
                    $default_values['value'] = false;
                }

                $fields[ $k ] = $default_values;
            }

            $q = $fields;
        }

        if (isset($params['fields'])) {
            return $q;
        }
        if (!empty($q)) {
            $the_data_with_custom_field__stuff = array();

            if ($return_full == true) {
                $to_ret = array();
                $i = 1;
                foreach ($q as $it) {
                    $it = $this->decode_array_vals($it);
                    //  $it['type'] = $it['type'];
                    $it['position'] = $i;
                    if (isset($it['options']) and is_string($it['options'])) {
                       // $it['options'] = $this->_decode_options($it['options']);
                    }

                    $it['title'] = $it['name'];
                    $to_ret[] = $it;
                    ++$i;
                }

                return $to_ret;
            }

            $append_this = array();
            if (is_array($q) and !empty($q)) {
                foreach ($q as $q2) {
                    $i = 0;

                    $the_name = false;

                    $the_val = false;

                    foreach ($q2 as $cfk => $cfv) {
                        if ($cfk == 'name') {
                            $the_name = $cfv;
                        }

                        if ($cfk == 'value') {
                            $the_val = $cfv;
                        }

                        ++$i;
                    }

                    if ($the_name != false and $the_val !== null) {
                        if ($return_full == false) {
                            $the_name = strtolower($the_name);

                            $the_data_with_custom_field__stuff[ $the_name ] = $the_val;
                        } else {
                            $the_data_with_custom_field__stuff[ $the_name ] = $q2;
                        }
                    }
                }
            }

            $result = $the_data_with_custom_field__stuff;
            //$result = (array_change_key_case($result, CASE_LOWER));
            $result = $this->app->url_manager->replace_site_url_back($result);

            //

            return $result;
        }

        return $q;
    }

    public function unify_params($data)
    {
        if (isset($data['rel_type'])) {
            if ($data['rel_type'] == 'content' or $data['rel_type'] == 'page' or $data['rel_type'] == 'post') {
                $data['rel_type'] = 'content';
            }
            $data['rel_type'] = $data['rel_type'];
        }

        if (isset($params['content_id'])) {
            $params['for'] = 'content';
            $params['for_id'] = $params['content_id'];
        }
        if (isset($data['content_id'])) {
            $data['rel_type'] = 'content';
            $data['rel_id'] = intval($data['content_id']);
        }

        if (!isset($data['type']) and isset($data['field_type']) and $data['field_type'] != '') {
            $data['type'] = $data['field_type'];
        }

        if (isset($data['field_type']) and (!isset($data['type']))) {
            $data['type'] = $data['field_type'];
        }
        if (isset($data['field_name']) and (!isset($data['name']))) {
            $data['name'] = $data['field_name'];
        }
        if (isset($data['field_value'])) {
            $data['value'] = $data['value'] = $data['field_value'];
        }

        if (!isset($data['name']) and isset($data['field_name']) and $data['field_type'] != '') {
            $data['name'] = $data['field_name'];
        }

        if (!isset($data['value']) and isset($data['field_value']) and $data['field_value'] != '') {
            $data['value'] = $data['field_value'];
        }
        if (!isset($data['rel_type']) and isset($data['for'])) {
            $data['rel_type'] = $this->app->database_manager->assoc_table_name($data['for']);
        }

        if (!isset($data['cf_id']) and isset($data['id'])) {
            //   $data['cf_id'] = $data['id'];
        }
        if (!isset($data['rel_id'])) {
            if (isset($data['data-id'])) {
                $data['rel_id'] = $data['data-id'];
            }
        }
        if (!isset($data['is_active']) and isset($data['cf_id']) and $data['cf_id'] == 0) {
            $data['is_active'] = 1;
        }

        if (!isset($data['rel_type']) and isset($data['for'])) {
            $data['rel_type'] = $data['for'];
        }
        if (isset($data['for_id'])) {
            $data['rel_id'] = $data['for_id'];
        }

        return $data;
    }

    public function decode_array_vals($it)
    {
        if (isset($it['value'])) {
            if (isset($it['value']) and is_string($it['value']) and strtolower($it['value']) == 'array') {
                if (isset($it['values']) and is_string($it['values'])) {
                    $try = base64_decode($it['values']);
                    if ($try != false and strlen($try) > 5) {
                        $it['values'] = unserialize($try);
                    }
                    if (isset($it['values']['value'])) {
                        $temp = $it['values']['value'];
                        if (is_array($it['values']['value'])) {
                            $temp = array();
                            foreach ($it['values']['value'] as $item1) {
                                if ($item1 != false) {
                                    $item1 = explode(',', $item1);
                                    $temp = array_merge($temp, $item1);
                                }
                            }
                        }
                        $it['values'] = $temp;
                    }
                }
            }
        }

        if (isset($it['options']) and is_string($it['options'])) {
            $it['options'] = $this->_decode_options($it['options']);
        }

        return $it;
    }

    public function reorder($data)
    {
        $adm = $this->app->user_manager->is_admin();
        if ($adm == false) {
            $this->app->error('Error: not logged in as admin.'.__FILE__.__LINE__);
        }

        $table = $this->table;

        foreach ($data as $value) {
            if (is_array($value)) {
                $indx = array();
                $i = 0;
                foreach ($value as $value2) {
                    $indx[ $i ] = $value2;
                    ++$i;
                }

                $this->app->database_manager->update_position_field($table, $indx);

                return true;
            }
        }
    }

    public function delete($id)
    {
        $uid = $this->app->user_manager->is_admin();
        if (defined('MW_API_CALL') and $uid == false) {
            exit('Error: not logged in as admin.'.__FILE__.__LINE__);
        }
        if (is_array($id)) {
            extract($id);
        }

        $id = intval($id);
        if (isset($cf_id)) {
            $id = intval($cf_id);
        }

        if ($id == 0) {
            return false;
        }

        $custom_field_table = $this->table;
        $custom_field_table_values = $this->table_values;
        $this->app->database_manager->delete_by_id($custom_field_table, $id);
        $this->app->database_manager->delete_by_id($custom_field_table_values, $id, 'custom_field_id');
        $this->app->cache_manager->delete('custom_fields');

        return $id;
    }

    public function make_field($field_id = 0, $field_type = 'text', $settings = false)
    {
        $data = false;
        $form_data = array();
        if (is_array($field_id)) {
            if (!empty($field_id)) {
                $data = $field_id;

                return $this->make($field_id, false, 'y');
            }
        } else {
            if ($field_id != 0) {
                return $this->make($field_id);

                //
                // $this->app->error('no permission to get data');
                //  $form_data = $this->app->database_manager->get_by_id('custom_fields', $id = $field_id, $is_this_field = false);
            }
        }
    }

    /**
     * make_field.
     *
     * @desc        make_field
     *
     * @category    forms
     *
     * @author      Microweber
     *
     * @link        http://microweber.com
     *
     * @param string $field_type
     * @param string $field_id
     * @param array  $settings
     */
    public function make($field_id = 0, $field_type = 'text', $settings = false)
    {
        if (is_array($field_id)) {
            if (!empty($field_id)) {
                $data = $field_id;
            }
        } else {
            if ($field_id != 0) {
                $data = $this->get_by_id($id = $field_id);
            }
        }
        if (isset($data['settings']) or (isset($_REQUEST['settings']) and trim($_REQUEST['settings']) == 'y')) {
            $settings = true;
        }

        if (isset($data['copy_from'])) {
            $copy_from = intval($data['copy_from']);
            if (is_admin() == true) {
                $table_custom_field = $this->table;
                $form_data = $this->app->database_manager->get_by_id($table_custom_field, $id = $copy_from);
                if (is_array($form_data)) {
                    $field_type = $form_data['type'];
                    $data['id'] = 0;
                    if (isset($data['save_on_copy'])) {
                        $cp = $form_data;
                        $cp['id'] = 0;
                        $cp['copy_of_field'] = $copy_from;
                        if (isset($data['rel_type'])) {
                            $cp['rel_type'] = ($data['rel_type']);
                        }
                        if (isset($data['rel_id'])) {
                            $cp['rel_id'] = ($data['rel_id']);
                        }
                        $this->save($cp);
                        $data = $cp;
                    } else {
                        $data = $form_data;
                    }
                }
            }
        } elseif (isset($data['field_id'])) {
            $data = $this->get_by_id($id = $data['field_id']);
        }

        if (isset($data['type'])) {
            $field_type = $data['type'];
        }

        if (!isset($data['custom_field_required'])) {
            $data['custom_field_required'] = 'n';
        }

        if (isset($data['type'])) {
            $field_type = $data['type'];
        }

        if (isset($data['field_type'])) {
            $field_type = $data['field_type'];
        }

        if (isset($data['field_values']) and !isset($data['value'])) {
            $data['values'] = $data['field_values'];
        }


        if (isset($data['value']) and is_array($data['value'])) {
            $data['value'] = implode(',',$data['value']);
        }



        $data['type'] = $field_type;

        if (isset($data['options']) and is_string($data['options'])) {
            $data['options'] = $this->_decode_options($data['options']);
        }

        $data = $this->app->url_manager->replace_site_url_back($data);

        $dir = mw_includes_path();
        $dir = $dir.DS.'custom_fields'.DS;
        $field_type = str_replace('..', '', $field_type);
        $load_from_theme = false;
        if (defined('ACTIVE_TEMPLATE_DIR')) {
            $custom_fields_from_theme = ACTIVE_TEMPLATE_DIR.'modules'.DS.'custom_fields'.DS;
            if (is_dir($custom_fields_from_theme)) {
                if ($settings == true or isset($data['settings'])) {
                    $file = $custom_fields_from_theme.$field_type.'_settings.php';
                } else {
                    $file = $custom_fields_from_theme.$field_type.'.php';
                }
                if (is_file($file)) {
                    $load_from_theme = true;
                }
            }
        }

        if ($load_from_theme == false) {
            if ($settings == true or isset($data['settings'])) {
                $file = $dir.$field_type.'_settings.php';
            } else {
                $file = $dir.$field_type.'.php';
            }
        }
        if (!is_file($file)) {
            $field_type = 'text';
            if ($settings == true or isset($data['settings'])) {
                $file = $dir.$field_type.'_settings.php';
            } else {
                $file = $dir.$field_type.'.php';
            }
        }
        $file = normalize_path($file, false);

        if (is_file($file)) {
            $l = new \Microweber\View($file);

            //

            $l->assign('settings', $settings);
            if (isset($data['params'])) {
                $l->assign('params', $data['params']);
            } else {
                $l->assign('params', false);
            }
            //  $l->settings = $settings;

            if (isset($data) and !empty($data)) {
                $l->data = $data;
            } else {
                $l->data = array();
            }

            $l->assign('data', $data);

            $layout = $l->__toString();

            return $layout;
        }
    }

    /**
     * names_for_table.
     *
     * @desc        names_for_table
     *
     * @category    forms
     *
     * @author      Microweber
     *
     * @link        http://microweber.com
     *
     * @param string $table
     */
    public function names_for_table($table)
    {
        $table = $this->app->database_manager->escape_string($table);
        $table1 = $this->app->database_manager->assoc_table_name($table);

        $table = $this->table;
        $q = false;
        $results = false;

        $q = "SELECT *, count(id) AS qty FROM $table WHERE   type IS NOT NULL AND rel_type='{$table1}' AND name!='' GROUP BY name, type ORDER BY qty DESC LIMIT 100";
        $crc = (crc32($q));

        $cache_id = __FUNCTION__.'_'.$crc;

        $results = $this->app->database_manager->query($q, $cache_id, 'custom_fields/global');

        if (is_array($results)) {
            return $results;
        }
    }

    private function _encode_options($data){
        return json_encode($data);
    }
    private function _decode_options($data){
        return @json_decode($data,true);
    }
}
