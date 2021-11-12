<?php
require_once "bootstrap.php";
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
    
    $_SESSION[ERRORS] = $errors;
    header("Location: ./forms.php");
    exit();

} else {

    $username = $_POST['username'];
    $password = $_POST['password'];
}

$user = $entityManager->getRepository('User')->findOneBy(array('username' => $username));

if (!$user) {

    array_push($errors, "User not found");

} else if ($user->authenticate($password)) {

    $_SESSION[USER_ID] = $user->get_id();

    array_push($messages, "Login Successful!");
    $_SESSION[MESSAGES] = $messages;

    header("Location: ./api/tests.php");
    exit();

} else {

    array_push($errors, "Authentication Error");
    
}

$_SESSION[ERRORS] = $errors;
header("Location: ./forms.php");
