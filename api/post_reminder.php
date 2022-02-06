<?php
require_once dirname(__FILE__) . "/./helpers/constants.php";
require_once dirname(__FILE__) . "/../bootstrap.php";
require_once dirname(__FILE__) . "/./helpers/parsers.php";
session_start();

$errors = array("errors" => []);

if (!isset($_SESSION[USER_ID]) && !TEST_MODE) {

    array_push($errors["errors"], ERROR_MISSING_LOGGED_IN_USER);
}

if (!isset($_POST['type'])) {

    array_push($errors["errors"], ERROR_MISSING_VALUE . ": Type");

}

if (!isset($_POST['title'])) {

    array_push($errors["errors"], ERROR_MISSING_VALUE . ": Title");

}

if (!isset($_POST['remind-date'])) {

    array_push($errors["errors"], ERROR_MISSING_VALUE . ": Remind Date");

}

if (empty($errors['errors'])) {
    
    $user_id = !TEST_MODE ? $_SESSION[USER_ID] : 1;
    $title = $_POST['title'];
    $type = $_POST['type'];
    $remind_date = new DateTime($_POST['remind-date'], new DateTimeZone('Asia/Manila'));

    $user = $entityManager->find("User", $user_id);

    if ($type != TYPE_TASK && $type != TYPE_REMINDER) {

        array_push($errors["errors"], ERROR_INVALID_VALUE . ": TYPE");
        echo json_encode($errors);
        exit;
    }

    $reminder = new Reminder();

    $reminder->set_type($type);
    $reminder->set_user($user);
    $reminder->set_title($title);

    if (isset($_POST['message'])){ 

        $message = $_POST['message'];
        if (!empty($message)) { $reminder->set_message($message); }
    }

    if ($type == TYPE_REMINDER && isset($_POST['is-recurring'])) {

        $reminder->set_is_recurring(date('w', strtotime($_POST['remind-date'])));

    } else {

        $reminder->set_remind_date($remind_date);
    }

    if($type == TYPE_TASK){

        $reminder->set_is_checked(false);
    }

    $entityManager->persist($reminder);
    $entityManager->flush();

    echo json_encode(array("success" => $type . " created", "reminder" => parse_reminder($reminder)));

} else {

    echo json_encode($errors);
}
