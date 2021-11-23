<?php
require_once dirname(__FILE__) . '/./setup.php';
require_once dirname(__FILE__) . "/../bootstrap.php";

$errors = array("errors" => []);
if (isset($_POST['code'])) {

    if (!isset($_SESSION[USER_ID]) && !TEST_MODE) {
        array_push($errors["errors"], ERROR_MISSING_LOGGED_IN_USER);
    }

    $user_id = !TEST_MODE ? $_SESSION[USER_ID] : 1;
    $user = $entityManager->find("User", $user_id);

    $user->set_canvas_token($_POST['code']);
    $entityManager->flush();

    header("Location: ./tests.php");
    exit;
}