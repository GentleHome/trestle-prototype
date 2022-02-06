<?php
require_once dirname(__FILE__) . "/../bootstrap.php";
require_once dirname(__FILE__) . '/helpers/constants.php';
session_start();

$errors = "";
$messages = "";

if (!isset($_POST['username']) || !isset($_POST['password']) || $_POST['username']==null || $_POST['password']==null) {
    $errors = "All fields are required.";
    echo json_encode(array("errors" => $errors));
    exit();
}

$username = $_POST['username'];
$password = $_POST['password'];

$user = $entityManager->getRepository('User')->findOneBy(array('username' => $username));

if ($user && $user->authenticate($password)) {
    $_SESSION[USER_ID] = $user->get_id();

    $messages = "Login Successful!";
    echo json_encode(array("messages" => $messages));
    
    exit();   
} else {
    $errors = "Username or password is incorrect.";
    echo json_encode(array("errors"=> $errors));
    exit();   
}