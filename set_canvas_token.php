<?php
require_once dirname(__FILE__) . '/./setup.php';
require_once dirname(__FILE__) . "/./bootstrap.php";
require_once dirname(__FILE__) . './api/helpers/constants.php';
session_start();

$errors = array("errors" => []);
if (isset($_POST['code'])) {

    if (!isset($_SESSION[USER_ID]) && !TEST_MODE) {
        array_push($errors["errors"], ERROR_MISSING_LOGGED_IN_USER);
    }

    $code = trim($_POST['code']);

    $user_id = !TEST_MODE ? $_SESSION[USER_ID] : 1;
    $user = $entityManager->find("User", $user_id);

    if(empty($_POST['code'])){
        $user->set_canvas_token(null);
    } else {
        $user->set_canvas_token($code);
    }
    
    $entityManager->flush();

    header("Location: ./settings.php");
    exit;
}