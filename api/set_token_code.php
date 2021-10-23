<?php
if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    if (!isset($token['error'])) {
        $client->setAccessToken($token['access_token']);
        $_SESSION['access_token'] = $token['access_token'];
    }
}

header("./tests.php");
