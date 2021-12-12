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

    $user_id = !TEST_MODE ? $_SESSION[USER_ID] : 1;
    $reminder_id = $_POST['reminder-id'];
    $is_checked = isset($_POST['is-checked']);

    $user = $entityManager->find("User", $user_id);
    $reminder = $entityManager->find("Reminder", $reminder_id);

    $type = $reminder->get_type();

    if ($type != TYPE_TASK) {
        array_push($errors["errors"], ERROR_INVALID_VALUE . ": TYPE");
        echo json_encode($errors);
        exit;
    }

    if ($reminder->get_user()->get_id() != $user->get_id()) {
        array_push($errors["errors"], ERROR_INVALID_ACCESS);
        echo json_encode($errors);
        exit;
    }

    $reminder->set_is_checked($is_checked);

    $entityManager->persist($reminder);
    $entityManager->flush();

    echo json_encode(array("success" => $type . " edited", "reminder" => parse_reminder($reminder)));

} else {
    
    echo json_encode($errors);
}
