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

if (!isset($_POST['title'])) {
    array_push($errors["errors"], ERROR_MISSING_VALUE . ": Title");
}

if (!isset($_POST['remind-date'])) {
    array_push($errors["errors"], ERROR_MISSING_VALUE . ": Remind Date");
}

if (empty($errors['errors'])) {

    $lookups = array();
    $user_id = !TEST_MODE ? $_SESSION[USER_ID] : 1;
    $reminder_id = $_POST['reminder-id'];
    $title = $_POST['title'];
    $type = $_POST['type'];
    $remind_date = new DateTime($_POST['remind-date'], new DateTimeZone('Asia/Manila'));

    $user = $entityManager->find("User", $user_id);
    $reminder = $entityManager->find("Reminder", $reminder_id);

    if ($type != TYPE_TASK && $type != TYPE_REMINDER) {

        array_push($errors["errors"], ERROR_INVALID_VALUE . ": TYPE");
        echo json_encode($errors);
        exit;

    }

    if (!$reminder->get_user()->get_id() == $user->get_id()){
        array_push($errors["errors"], ERROR_INVALID_ACCESS);
        echo json_encode($errors);
        exit;
    }

    $reminder->set_type($type);
    $reminder->set_user($user);
    $reminder->set_title($title);

    if (isset($_POST['message'])) {
        $message = $_POST['message'];
        $reminder->set_message(empty($message) ? null : $message);
    } else {
        $reminder->set_remind_date($remind_date);
    }

    $entityManager->persist($reminder);
    $entityManager->flush();

    echo json_encode(array("success" => "Reminder edited for ". $remind_date->format("M-d-Y h:i"), "reminder" => parse_reminder($reminder)));


} else {

    echo json_encode($errors);

}
