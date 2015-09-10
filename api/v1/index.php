<?php

require_once 'AppUtil.php';
// require_once '../config.php';
require_once 'DBUtil.php';
require_once 'PasswordHash.php';

require '.././libs/Slim/Slim.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

// User id from db - Global Variable
$user_id = NULL;

require_once 'authentication.php';


$app->post('/events/register', function() use ($app) {
    // Select and validate ----------------------------------------------
	$postData = json_decode($app->request->getBody());
    $db = new DBUtil();
    $session = AppUtil::getSession();
    if (isset($session['uid'])) {
        $tableName = "customer_event";
        $insertData = array(
                'uid'      => $session['uid'],
                'event_id' => $postData->event->event_id
            );
        $result = $db->insert($tableName, $insertData);

        $response['status'] = "success";
        $response['message'] = 'Registering for ' . $postData->event->name;
        jsonWrite(200, $response);
    } else {
        $response['status'] = "error";
        $response['message'] = 'Cannot register for event...';
        jsonWrite(200, $response);
    }
    $db = NULL;
});

$app->post('/events/cancel', function() use ($app) {
    // Select and validate ----------------------------------------------
	$postData = json_decode($app->request->getBody());
    $db = new DBUtil();
    $session = AppUtil::getSession();
    if (isset($session['uid'])) {
        $tableName = "customer_event";
        $deleteData = array(
                'uid'      => $session['uid'],
                'event_id' => $postData->event->event_id
            );
        $result = $db->delete($tableName, $deleteData);

        $response['status'] = "success";
        $response['message'] = 'Cancelled registration for ' . $postData->event->name;
        jsonWrite(200, $response);
    } else {
        $response['status'] = "error";
        $response['message'] = 'Cannot cancel registration...';
        jsonWrite(200, $response);
    }
    $db = NULL;
});



$app->get('/events', function() {
    // Select and validate ----------------------------------------------
    $db = new DBUtil();
    $session = AppUtil::getSession();
    if (isset($session['uid'])) {
        // $sqlStr = "SELECT * FROM event";
        $sqlStr = "SELECT event.*, ce.uid FROM event
                   LEFT JOIN customer_event ce
                   ON event.event_id = ce.event_id AND ce.uid = :uid
                   ORDER BY event.date ASC";
        $events = $db->select($sqlStr, array(':uid' => $session['uid']), PDO::FETCH_OBJ);
    } else {
        $sqlStr = "SELECT event.*, ce.uid FROM event
                   ORDER BY event.date ASC";
        $events = $db->select($sqlStr, array(':uid' => $session['uid']), PDO::FETCH_OBJ);
    }
    // $db->select()

    jsonWrite(200, $events);
});
$app->get('/events/past', function() {
    // Select and validate ----------------------------------------------
    $db = new DBUtil();
    $session = AppUtil::getSession();
    if (isset($session['uid'])) {
        // $sqlStr = "SELECT * FROM event";
        $sqlStr = "SELECT event.*, ce.uid FROM event
                   LEFT JOIN customer_event ce
                   ON event.event_id = ce.event_id AND ce.uid = :uid
                   WHERE event.date < CURDATE()
                   ORDER BY event.date ASC";
        $events = $db->select($sqlStr, array(':uid' => $session['uid']), PDO::FETCH_OBJ);
    } else {
        $sqlStr = "SELECT event.*, ce.uid FROM event
                   WHERE event.date < CURDATE()
                   ORDER BY event.date ASC";
        $events = $db->select($sqlStr, array(':uid' => $session['uid']), PDO::FETCH_OBJ);
    }
    // $db->select()

    jsonWrite(200, $events);
});
$app->get('/events/future', function() {
    // Select and validate ----------------------------------------------
    $db = new DBUtil();
    $session = AppUtil::getSession();
    if (isset($session['uid'])) {
        // $sqlStr = "SELECT * FROM event";
        $sqlStr = "SELECT event.*, ce.uid FROM event
                   LEFT JOIN customer_event ce
                   ON event.event_id = ce.event_id AND ce.uid = :uid
                   WHERE event.date >= CURDATE()
                   ORDER BY event.date ASC";
        $events = $db->select($sqlStr, array(':uid' => $session['uid']), PDO::FETCH_OBJ);
    } else {
        $sqlStr = "SELECT event.*, ce.uid FROM event
                   WHERE event.date >= CURDATE()
                   ORDER BY event.date ASC";
        $events = $db->select($sqlStr, array(':uid' => $session['uid']), PDO::FETCH_OBJ);
    }
    // $db->select()

    jsonWrite(200, $events);
});


$app->run();


// TODO: Move to a Helper class with namespace
function validate($param, $type) {}

function jsonWrite($status_code, $response) {
    $app = \Slim\Slim::getInstance();
    // Http response code
    $app->status($status_code);

    // setting response content type to json
    $app->contentType('application/json');

    echo json_encode($response);
}
