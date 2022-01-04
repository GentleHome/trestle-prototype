<?php
require_once dirname(__FILE__) . "/../bootstrap.php";
require_once dirname(__FILE__) . '/./helpers/db_utils.php';
require_once dirname(__FILE__) . '/helpers/constants.php';

session_start();
$user = get_logged_in_user($entityManager);
if (!isset($_POST['currentPassword']) || $_POST['currentPassword']==null || !isset($_POST['newPassword1']) || $_POST['newPassword1']==null || !isset($_POST['newPassword2']) || $_POST['newPassword2']==null) {
    $errors = "All fields are required.";
    echo json_encode(array("errors" => $errors));
    exit();
}


$current_password = $_POST['currentPassword'];
$password1 = $_POST['newPassword1'];
$password2 = $_POST['newPassword2'];

if ($user->authenticate($current_password)) {
    if ($password1 == $password2) {
        $user->set_password($password1);
        $messages = "Password Change Success!";
        echo json_encode(array("messages" => $messages));
    } else {
        $errors = ERROR_INVALID_VALUE . ": Confirm New Password";
        echo json_encode(array("errors" => $errors));
    }
} else {
    $errors = ERROR_INVALID_VALUE . ": Current Password";
    echo json_encode(array("errors" => $errors));
}
