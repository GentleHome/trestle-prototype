<?php
require_once "bootstrap.php";

$errors = array("errors" => []);

if (!isset($_SESSION[USER_ID])) {

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

    $user_id = $_SESSION[USER_ID];
    $title = $_POST['title'];
    $message = isset($_POST['message']) ? $_POST['message'] : null;
    $type = $_POST['type'];
    $remind_date = $_POST['remind-date'];

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
    $reminder->set_message($message);

    // I'm stupid and I have no idea what kind of data the form input of type date will give.
    $reminder->set_remind_date(new DateTime($remind_date));

    $entityManager->persist($reminder);
    $entityManager->flush();

    echo json_encode(array("success" => ["Reminder Created"]));

} else {

    echo json_encode($errors);

}
