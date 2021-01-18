<?php
api_expose_admin('save_option');

function get_module_options($optionGroup) {
    return \MicroweberPackages\Option\Models\ModuleOption::where('option_group', $optionGroup)->get()->toArray();
}

function get_module_option($optionKey, $optionGroup = false, $returnFull = false) {

    $option = \MicroweberPackages\Option\Models\ModuleOption::query();
    $option->where('option_key', $optionKey);

    if ($optionGroup) {
        $option->where('option_group', $optionGroup);
    }

    $data = $option->first();
    if ($data == null) {
        return false;
    }

    if ($returnFull) {
        return $data->toArray();
    }

    return $data->toArray()['option_value'];
}

/**
 * Getting options from the database.
 *
 * @param $key array|string - if array it will replace the db params
 * @param $option_group string - your option group
 * @param $return_full bool - if true it will return the whole db row as array rather then just the value
 * @param $module string - if set it will store option for module
 * Example usage:
 * get_option('my_key', 'my_group');
 */
function get_option($key, $option_group = false, $return_full = false, $orderby = false, $module = false)
{
    return app()->option_manager->get($key, $option_group, $return_full, $orderby, $module);
}

/*
 *
 * You can use this function to store options in the database.
 *
 * @param $data array|string
 * Example usage:
 *
 * $option = array();
 * $option['option_value'] = 'my value';
 * $option['option_key'] = 'my_option';
 * $option['option_group'] = 'my_option_group';
 * save_option($option);
 *
 * Or Eexample:
 * save_option($key, $value, $group);
 *
 */
function save_option($dataOrKey, $value = false, $group = false)
{
    if ($dataOrKey && $value && $group) {

        $option = array();
        $option['option_value'] = $value;
        $option['option_key'] = $dataOrKey;
        $option['option_group'] = $group;

        return app()->option_manager->save($option);
    } else {
        return app()->option_manager->save($dataOrKey);
    }
}

function delete_option($key, $group = false, $module_id = false) {

    return app()->option_manager->delete($key, $group, $module_id);
}