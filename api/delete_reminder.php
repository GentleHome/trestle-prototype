<?php
require_once dirname(__FILE__) . "/../bootstrap.php";
require_once dirname(__FILE__) . "/./helpers/parsers.php";

$errors = array("errors" => []);

if (!isset($_SESSION[USER_ID]) && !TEST_MODE) {

    array_push($errors["errors"], ERROR_MISSING_LOGGED_IN_USER);
}

if (!isset($_POST['reminder_id'])) {

    array_push($errors["errors"], ERROR_MISSING_VALUE . ": Reminder");
}

if (empty($errors['errors'])) {

    $lookups = array();
    $user_id = !TEST_MODE ? $_SESSION[USER_ID] : 1;
    $reminder_id = $_POST['reminder_id'];

    $user = $entityManager->find("User", $user_id);

    $lookups["id"] = $id;
    $lookups["user"] = $user;

    $reminder = $entityManager->getRepository("Reminder")->findOneBy($lookups);

    echo json_encode(array("success" => "Reminder deleted for " . $remind_date->format("M-d-Y h:i"), "reminder" => parse_reminder($reminder)));

    $entityManager->remove($reminder);
    $entityManager->flush();

} else {

    echo json_encode($errors);

}
