<?php
require_once dirname(__FILE__) . "/../bootstrap.php";
require_once dirname(__FILE__) . "/./helpers/parsers.php";

$errors = array("errors" => []);

if (!isset($_SESSION[USER_ID]) && !TEST_MODE) {

    array_push($errors["errors"], ERROR_MISSING_LOGGED_IN_USER);
}

if (empty($errors['errors'])) {
    $type = null;
    $user_id = !TEST_MODE ? $_SESSION[USER_ID] : 1;

    $user = $entityManager->find("User", $user_id);

    if(isset($_GET['type'])){

        $type = $_GET['type'];

    }

    if ($type == TYPE_TASK || $type == TYPE_REMINDER) {

        $reminders = $entityManager->getRepository("Reminder")->findBy(array('user' => $user, "type" => $type));
    } else {

        $reminders = $entityManager->getRepository("Reminder")->findBy(array('user' => $user));
    }

    $response = [];
    foreach ($reminders as $reminder) {
        array_push($response, parse_reminder($reminder));
    }

    echo json_encode($response);

} else {
    echo json_encode($errors);
}
