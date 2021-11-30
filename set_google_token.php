<?php
require_once dirname(__FILE__) . '/./setup.php';
require_once dirname(__FILE__) . "/./bootstrap.php";

$client = get_client();
if (isset($_GET['code'])) {

    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    if (!isset($token['error'])) {

        if (!isset($_SESSION[USER_ID]) && !TEST_MODE) {

            array_push($errors["errors"], ERROR_MISSING_LOGGED_IN_USER);
        }

        $user_id = !TEST_MODE ? $_SESSION[USER_ID] : 1;
        $user = $entityManager->find("User", $user_id);

        $client->setAccessToken($token['access_token']);
        $user->set_google_token($token['access_token']);
        $entityManager->flush();

        header("Location: ./tests.php");
        exit;
    } else {
        echo $token['error'];
    }
}
