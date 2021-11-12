<?php
require_once "bootstrap.php";
require_once "./helpers/parsers.php";

$errors = array("errors" => []);

if (!isset($_SESSION[USER_ID])) {

    array_push($errors["errors"], ERROR_MISSING_LOGGED_IN_USER);
}

if (!isset($_POST['type'])) {

    array_push($errors["errors"], ERROR_MISSING_VALUE . ": Type");
}

if (empty($errors['errors'])) {
    if ($type != TYPE_TASK && $type != TYPE_REMINDER) {

        array_push($errors["errors"], ERROR_INVALID_VALUE . ": Type");
        exit;

    }

    $user = $entityManager->find("User", $user_id);


    $reminders = $entityManager->getRepository("User")->findBy(array('user' => $user, "type" => $type));

    $response = [];
    foreach ($reminders as $reminder) {
        array_push($response, parse_reminder($reminder));
    }

    echo json_encode($response);

} else {
    echo json_encode($errors);
}
