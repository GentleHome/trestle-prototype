<?php
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

require_once dirname(__FILE__) . "/../bootstrap.php";
require_once dirname(__FILE__) . '/helpers/constants.php';
session_start();

$errors = [];
$messages = [];

if (!isset($_POST['username'])) {
    array_push($errors, "Username input is missing");
}

if (!isset($_POST['password1'])) {
    array_push($errors, "Password input is missing");
}

if (!isset($_POST['password2'])) {
    array_push($errors, "Confirm Password input is missing");
}

if (!count($errors) === 0) {

    echo json_encode(array("errors" => $errors));
    exit();
}

$username = $_POST['username'];
$password1 = $_POST['password1'];
$password2 = $_POST['password2'];

if($password1 === $password2){
    $user = new User();
    $user->set_username($username);
    $user->set_password($password1);
    try{
        $entityManager->persist($user);
        $entityManager->flush();
        array_push($messages, "Registration Success");
        echo json_encode(array("success" => $messages));
    }
    catch(UniqueConstraintViolationException $e){
        array_push($errors, "Username already exists!");
        echo json_encode(array("errors" => $errors));
    }
}
