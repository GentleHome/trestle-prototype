<?php 
session_start();
$errors = [];
if(!isset($_POST['username'])){
    array_push($errors, "Username input is missing");
}
if(!isset($_POST['password'])){
    array_push($errors, "Password input is missing");
}
$username = $_POST['username'];
$password = $_POST['password'];