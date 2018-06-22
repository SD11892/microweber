<?php
api_expose('calendar_get_events_api');
function calendar_get_events_api($params = array())
{
    if (is_string($params)) {
        $params = parse_params($params);
    }

    if (isset($params['yearmonth'])) {
        $yearmonth = $params['yearmonth'];
    } elseif (isset($_POST['yearmonth'])) {
        $yearmonth = $_POST['yearmonth'];
    } else {
        $yearmonth = date("Y-m");
    }

    $params['table'] = "calendar";
    $params ['no_cache'] = true; // disable cache whilst testing

    $events = array();

    if ($data = DB::table($params['table'])->select('id', 'title', 'description', 'startdate', 'enddate', 'allDay', 'content_id')->where('startdate', 'like', $yearmonth . '%')->get()) {
        foreach ($data as $event) {
            if (!empty($event->id) && !empty($event->title) && !empty($event->startdate)) {
                $e = array();
                $e['id'] = $event->id;
                $e['title'] = $event->title;
                $e['description'] = $event->description;
                $e['start'] = $event->startdate;
                $e['end'] = $event->enddate;
                $e['allDay'] = ((isset($event->allDay) and ($event->allDay)) == 1 ? true : false);
                $e['content_id'] = ($event->content_id);
                array_push($events, $e);
            } else {
                // blank data
            }
        }

        print json_encode($events);

    } else {
        // no event data
        return print lnotif(_e('Click here to edit Calendar', true));
    }
}

api_expose('calendar_new_event');
function calendar_new_event()
{

    if (!is_admin()) return;

    $table = "calendar";
    $startdate = mw()->database_manager->escape_string(trim($_POST['startdate']) . '+' . trim($_POST['zone']));
    $title = mw()->database_manager->escape_string(trim($_POST['title']));

    $data = array('title' => $title,
        'startdate' => $startdate,
        'enddate' => $startdate,
        'allDay' => 0
    );

    $lastid = db_save($table, $data);

    if ($lastid)
        return (array('status' => 'success', 'eventid' => $lastid));
    else
        return (array('status' => 'failed'));
}

api_expose('calendar_change_title');
function calendar_change_title()
{

    if (!is_admin()) return;


    if (!isset($_POST['zone'])) {
        $_POST['zone'] = '00:00';
    }
    if (!isset($_POST['eventid']) and isset($_POST['id'])) {
        $_POST['eventid'] = $_POST['id'];
    }

    $table = "calendar";
    $eventid = $_POST['eventid'];
    $title = mw()->database_manager->escape_string(trim($_POST['title']));
    $description = mw()->database_manager->escape_string(trim($_POST['description']));

    if (isset($_POST['startdate'])) {
        $startdate = $_POST['startdate'];
    } else {
        $startdate = mw()->database_manager->escape_string(trim($_POST['start'] . '+' . trim($_POST['zone'])));

    }
    if (isset($_POST['enddate'])) {
        $enddate = $_POST['enddate'];
    } else{
        $enddate = mw()->database_manager->escape_string(trim($_POST['end'] . '+' . trim($_POST['zone'])));

    }


    $data = array('id' => $eventid, 'title' => $title, 'description' => $description, 'startdate' => $startdate, 'enddate' => $enddate);

    $update = db_save($table, $data);

    if ($update)
        return array('status' => 'success');
    else
        return array('status' => 'failed');
}

api_expose('calendar_reset_date');
function calendar_reset_date($params)
{

    if (!is_admin()) return;

// INTERNAL SERVER ERROR 500
    if (!isset($params['zone'])) {
        $params['zone'] = '00:00';
    }

    if (isset($params['event_id'])) {
        $params['eventid'] = $params['event_id'];
    }


    $table = "calendar";
    $title = mw()->database_manager->escape_string(trim($params['title']));
    $startdate = mw()->database_manager->escape_string(trim($params['start'] . '+' . trim($params['zone'])));
    $enddate = mw()->database_manager->escape_string(trim($params['end'] . '+' . trim($params['zone'])));
    $eventid = $params['eventid'];

    $data = array('id' => $eventid, 'title' => $title, 'startdate' => $startdate, 'enddate' => $enddate);

    $update = db_save($table, $data);

    if ($update)
        return (array('status' => 'success'));
    else
        return (array('status' => 'failed'));
}

api_expose('calendar_remove_event');
function calendar_remove_event()
{

    if (!is_admin()) return;

    $table = "calendar";
    $eventid = $_POST['eventid'];

    $delete = db_delete($table, $eventid);

    if ($delete)
        return (array('status' => 'success'));
    else
        return (array('status' => 'failed'));
}


function calendar_get_event_by_id($event_id)
{
    $data = array('id' => $event_id, 'single' => true);
    $table = "calendar";

    return db_get($table, $data);
}