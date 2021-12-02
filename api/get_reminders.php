<?php
require_once dirname(__FILE__) . "/./helpers/constants.php";
require_once dirname(__FILE__) . "/../bootstrap.php";
require_once dirname(__FILE__) . "/./helpers/parsers.php";
session_start();

$errors = array("errors" => []);

if (!isset($_SESSION[USER_ID]) && !TEST_MODE) {

    array_push($errors["errors"], ERROR_MISSING_LOGGED_IN_USER);
    
}

if (empty($errors['errors'])) {

    $type = null;
    $is_recurring = null;
    $lookups = array();
    $user_id = !TEST_MODE ? $_SESSION[USER_ID] : 1;

    $user = $entityManager->find("User", $user_id);
    $lookups['user'] = $user;

    if (isset($_GET['type'])) {
        $lookups['type'] = $_GET['type'];
    }

    if (isset($_GET['is_recurring'])) {
        $lookups['is_recurring'] = $_GET['is_recurring'];
    }

    $reminders = $entityManager->getRepository("Reminder")->findBy($lookups);

    $response = [];
    foreach ($reminders as $reminder) {
        array_push($response, parse_reminder($reminder));
    }

    echo json_encode($response);

} else {

    echo json_encode($errors);

}
