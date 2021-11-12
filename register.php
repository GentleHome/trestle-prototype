<?php
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
require_once "bootstrap.php";
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
    }
    catch(UniqueConstraintViolationException $e){
        array_push($errors, "Username already exists!");
    }
}

$_SESSION[MESSAGES] = $messages;
$_SESSION[ERRORS] = $errors;

