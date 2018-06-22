<?php
$config = array();
$config['name'] = "Calendar";
$config['author'] = "nick@easy-host.uk";
$config['ui'] = true; //if set to true, module will be visible in the toolbar
$config['ui_admin'] = false; //if set to true, module will be visible in the admin panel
$config['categories'] = "content";
$config['position'] = 99;
$config['version'] = 0.2;

$config['tables'] = array(
    "calendar" => array(
        '$id' => "integer",
        'content_id' => "integer",
        'title' => "string",
		'startdate' => "date",
		'enddate' => "date",
		'description' => "text",
		'allDay' => "integer"
    )
);