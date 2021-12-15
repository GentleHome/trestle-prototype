<?php
require_once dirname(__FILE__) . "/./helpers/constants.php";
require_once dirname(__FILE__) . "/../bootstrap.php";
require_once dirname(__FILE__) . "/./helpers/parsers.php";

session_start();

$errors = array("errors" => []);

if (!isset($_SESSION[USER_ID]) && !TEST_MODE) {
    array_push($errors["errors"], ERROR_MISSING_LOGGED_IN_USER);
}

if (!isset($_GET['reminder-id'])) {
    array_push($errors["errors"], ERROR_MISSING_VALUE . ": reminder-id");
}
if (!isset($_GET['is-checked'])) {
    array_push($errors["errors"], ERROR_MISSING_VALUE . ": is-checked");
}

if (empty($errors['errors'])) {

    $user_id = !TEST_MODE ? $_SESSION[USER_ID] : 1;
    $reminder_id = $_GET['reminder-id'];
    $is_checked = $_GET['is-checked'];
    $user = $entityManager->find("User", $user_id);
    $reminder = $entityManager->find("Reminder", $reminder_id);

    $type = $reminder->get_type();

    if (!$reminder) {
        array_push($errors["errors"], ERROR_INVALID_VALUE . ": Reminder ID");
        echo json_encode($errors);
        exit;
    }

    if ($type != TYPE_TASK) {
        array_push($errors["errors"], ERROR_INVALID_VALUE . ": Type");
        echo json_encode($errors);
        exit;
    }

    if ($reminder->get_user()->get_id() != $user->get_id()) {
        array_push($errors["errors"], ERROR_INVALID_ACCESS);
        echo json_encode($errors);
        exit;
    }

    if ($is_checked === 1 || $is_checked === 0) { $is_checked = $is_checked == 1 ? true : false; } 
    else {
        array_push($errors["errors"], ERROR_INVALID_VALUE . ": is_checked");
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
