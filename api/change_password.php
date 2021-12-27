<?php
require_once dirname(__FILE__) . "/../bootstrap.php";
require_once dirname(__FILE__) . '/./helpers/db_utils.php';
require_once dirname(__FILE__) . '/helpers/constants.php';


$user = get_logged_in_user($entityManager);

if (!isset($_POST['current-password'])) {
    array_push($errors, ERROR_MISSING_VALUE . ": Current Password");
}

if (!isset($_POST['new-password1'])) {
    array_push($errors, ERROR_MISSING_VALUE . ": New Password");
}

if (!isset($_POST['new-password2'])) {
    array_push($errors, ERROR_MISSING_VALUE . ": Confirm New Password");
}

if (!count($errors) === 0) {
    echo json_encode(array("errors" => $errors));
    exit();
}

$current_password = $_POST['current-password'];
$password1 = $_POST['new-password1'];
$password2 = $_POST['new-password2'];

if ($user->authenticate($current_password)) {
    if ($password1 == $password2) {
        $user->set_password($password1);
        array_push($messages, "Password Change Success!");
        echo json_encode(array("messages" => $messages));
    } else {
        array_push($errors, ERROR_INVALID_VALUE . ": Confirm New Password");
        echo json_encode(array("errors" => $errors));
    }
} else {
    array_push($errors, ERROR_INVALID_VALUE . ": Current Password");
    echo json_encode(array("errors" => $errors));
}
