<?php
require_once dirname(__FILE__) . '/./setup.php';
require_once dirname(__FILE__) . "/./bootstrap.php";
require_once dirname(__FILE__) . '/./api/helpers/constants.php';
session_start();

$client = get_client();
if (isset($_GET['code'])) {

    $response = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    if (!isset($response['error'])) {

        if (!isset($_SESSION[USER_ID]) && !TEST_MODE) {

            array_push($errors["errors"], ERROR_MISSING_LOGGED_IN_USER);
        }

        $user_id = !TEST_MODE ? $_SESSION[USER_ID] : 1;
        $user =  $entityManager->find("User", $user_id);

        $client->setAccessToken($response);
        $user->set_google_token($response);

        $entityManager->flush();

        header("Location: ./api/tests.php");
        exit;
    } else {
        echo $response['error'];
    }

} else if (isset($_GET['error'])) {
    echo $_GET['error'];
}
