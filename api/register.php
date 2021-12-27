<?php
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

require_once dirname(__FILE__) . "/../bootstrap.php";
require_once dirname(__FILE__) . '/helpers/constants.php';
session_start();

$errors = "";
$messages = "";

if (!isset($_POST['username']) || $_POST['username'] == null ||!isset($_POST['password1']) || $_POST['password1'] == null || !isset($_POST['password2']) || $_POST['password2'] == null) {
    $errors = "All fields are required.";
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
        $messages = "Registration Success!";
        echo json_encode(array("success" => $messages));
        exit();
    }
    catch(UniqueConstraintViolationException $e){
        $errors = "Username already exists!";
        echo json_encode(array("errors" => $errors));
        exit();
    }
}
