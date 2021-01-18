<?php

$btn_id = 'btn-' . $params['id'];

$btn_options = [];
$btn_options['button_style'] = '';
$btn_options['button_size'] = '';
$btn_options['button_action'] = '';
$btn_options['popupcontent'] = '';
$btn_options['url'] = '';
$btn_options['url_blank'] = '';
$btn_options['text'] = '';
$btn_options['icon'] = '';
$btn_options['button_id'] = '';

$get_btn_options = get_module_options($params['id']);
if (!empty($get_btn_options)) {
    foreach ($get_btn_options as $get_btn_option) {
        $btn_options[$get_btn_option['option_key']] = $get_btn_option['option_value'];
    }
}


$style = $btn_options['button_style'];
$size = $btn_options['button_size'];
$action = $btn_options['button_action'];
$action_content = $btn_options['popupcontent'];
$url = $btn_options['url'];
$blank = $btn_options['url_blank'] == 'y';
$text = $btn_options['text'];
if ($btn_options['icon']) {
    $icon = $btn_options['icon'];
} elseif (isset($params['icon'])) {
    $icon = $params['icon'];
} else {
    $icon = '';
}

if (isset($params['button_id'])) {
    $btn_id = $params['button_id'];
}

$attributes = '';
if (isset($params['button_onclick'])) {
    $attributes .= 'onclick="'.$params['button_onclick'].'"';
}

if (isset($params['button_text']) && !empty($params['button_text']) && empty($text)) {
	$text = $params['button_text'];
}

$popup_function_id = 'btn_popup' . uniqid();
if ($text == false and isset($params['text'])) {
    $text = $params['text'];
} elseif ($text == '') {
    $text = lang('Button', 'templates/dream/modules/btn');
}
if($icon){
    $text = $icon . '&nbsp;' . $text;
}

if ($url == false and isset($params['url'])) {
    $url = $params['url'];
} elseif ($url == '') {
    $url = '#';
}



$link_to_content_by_id = 'content:';
$link_to_category_by_id = 'category:';


$url_display = false;
if (substr($url, 0, strlen($link_to_content_by_id)) === $link_to_content_by_id) {
    $link_to_content_by_id = substr($url, strlen($link_to_content_by_id));
    if ($link_to_content_by_id) {
        $url_display = content_link($link_to_content_by_id);
    }
} else if (substr($url, 0, strlen($link_to_category_by_id)) === $link_to_category_by_id) {
    $link_to_category_by_id = substr($url, strlen($link_to_category_by_id));

    if ($link_to_category_by_id) {
        $url_display = category_link($link_to_category_by_id);
    }
}

if($url_display){
    $url = $url_display;
}




if ($style == false and isset($params['button_style'])) {
    $style = $params['button_style'];
}
if ($style == '') {
    $style = 'btn-default';
}

if ($action == false and isset($params['button_action'])) {
    $action = $params['button_action'];
}

if ($size == false and isset($params['button_size'])) {
    $size = $params['button_size'];
}


if ($action == 'popup') {
    $url = 'javascript:' . $popup_function_id . '()';
}


$module_template = get_option('data-template', $params['id']);
if ($module_template == false and isset($params['template'])) {
    $module_template = $params['template'];
}
if ($module_template != false) {
    $template_file = module_templates($config['module'], $module_template);
} else {
    $template_file = module_templates($config['module'], 'default');
}


if (is_file($template_file) != false) {
    include($template_file);
} else {
    print lnotif("No template found. Please choose template.");
}


 if ($action == 'popup') { ?>

    <script type="text/microweber" id="area<?php print $btn_id; ?>">
        <?php print $action_content; ?>
    </script>

    <script>
        function <?php print $popup_function_id ?>() {
            mw.dialog({
                name: 'frame<?php print $btn_id; ?>',
                content: $(document.getElementById('area<?php print $btn_id; ?>')).html(),
                template: 'basic',
                title: "<?php print addslashes ($text); ?>"
            });
        }
    </script>
<?php }