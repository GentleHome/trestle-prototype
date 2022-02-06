<?php
require_once dirname(__FILE__) . "/./helpers/constants.php";
require_once dirname(__FILE__) . "/../bootstrap.php";
require_once dirname(__FILE__) . "/./helpers/parsers.php";

session_start();

$errors = array("errors" => []);

if (!isset($_SESSION[USER_ID]) && !TEST_MODE) {
    array_push($errors["errors"], ERROR_MISSING_LOGGED_IN_USER);
}

if (!isset($_POST['reminder-id'])) {
    array_push($errors["errors"], ERROR_MISSING_VALUE . ": Reminder");
}

if (empty($errors['errors'])) {

    $lookups = array();
    $user_id = !TEST_MODE ? $_SESSION[USER_ID] : 1;
    $reminder_id = $_POST['reminder-id'];

    $user = $entityManager->find("User", $user_id);
    $reminder = $entityManager->find("Reminder", $reminder_id);

    if (is_null($reminder)) {
        array_push($errors["errors"], ERROR_MISSING_VALUE . ": Reminder");
        echo json_encode($errors);
        exit;
    }

    if (!$reminder->get_user()->get_id() == $user->get_id()) {
        array_push($errors["errors"], ERROR_INVALID_ACCESS);
        echo json_encode($errors);
        exit;
    }

    echo json_encode(array("success" => $reminder->get_type() . " deleted", "reminder" => parse_reminder($reminder)));

    $entityManager->remove($reminder);
    $entityManager->flush();
} else {

    echo json_encode($errors);
}
