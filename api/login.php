<?php
require_once dirname(__FILE__) . "/../bootstrap.php";
require_once dirname(__FILE__) . '/helpers/constants.php';
session_start();

$errors = [];
$messages = [];

if (!isset($_POST['username'])) {
    array_push($errors, "Username input is missing");
}

if (!isset($_POST['password'])) {
    array_push($errors, "Password input is missing");
}

if(!count($errors) === 0){
    echo json_encode(array("errors" => $errors));
    exit();
}

$username = $_POST['username'];
$password = $_POST['password'];

$user = $entityManager->getRepository('User')->findOneBy(array('username' => $username));

if (!$user) {
    array_push($errors, "User not found");
} else if ($user->authenticate($password)) {
    $_SESSION[USER_ID] = $user->get_id();

    array_push($messages, "Login Successful!");
    echo json_encode(array("messages" => $messages));
    
    exit();
} else {
    array_push($errors, "Authentication Error");
}

echo json_encode(array("errors"=> $errors));
