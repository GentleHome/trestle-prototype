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

    // Ispagetting pataas, pataas ng pataas
    $recurring_fields = [
        $_POST["recurring_sun"]     ? 0 : false,
        $_POST["recurring_mon"]     ? 1 : false,
        $_POST["recurring_tues"]    ? 2 : false,
        $_POST["recurring_wed"]     ? 3 : false,
        $_POST["recurring_thurs"]   ? 4 : false,
        $_POST["recurring_fri"]     ? 5 : false,
        $_POST["recurring_sat"]     ? 6 : false
    ];

    $recurring_days = '';

    foreach($recurring_fields as $field){
        if ($field){
            $recurring_days = $recurring_days . (string)$field;
        }
    }

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

    if ($type == TYPE_REMINDER && $recurring_days != '') {

        $reminder->set_is_recurring((int)$recurring_days);

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
