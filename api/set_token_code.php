<?php
require_once dirname(__FILE__) . './setup.php';

$client = get_client();

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    if (!isset($token['error'])) {
        $client->setAccessToken($token['access_token']);
        $_SESSION['access_token'] = $token['access_token'];
    }
}

header("./tests.php");
exit;
