<?php
require_once dirname(__FILE__) . "/../bootstrap.php";
require_once dirname(__FILE__) . "/./helpers/parsers.php";

$errors = array("errors" => []);

if (!isset($_SESSION[USER_ID]) && !TEST_MODE) {

    array_push($errors["errors"], ERROR_MISSING_LOGGED_IN_USER);
}

if (!isset($_GET['reminder_id'])) {

    array_push($errors["errors"], ERROR_MISSING_VALUE . ": Reminder ID");
}

if (empty($errors['errors'])) {
    $user_id = !TEST_MODE ? $_SESSION[USER_ID] : 2;
    $reminder_id = $_GET['reminder_id'];
    
    $user = $entityManager->find("User", $user_id);
    $reminder = $entityManager->find("Reminder", $reminder_id);

    if($reminder->get_user() == $user){
        echo json_encode(parse_reminder($reminder));
    } else {
        array_push($errors["errors"], ERROR_INVALID_ACCESS);
        echo json_encode($errors);
    }

} else {
    echo json_encode($errors);
}
