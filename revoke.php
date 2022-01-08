<?php
require_once dirname(__FILE__) . "/./bootstrap.php";
require_once dirname(__FILE__) . '/./setup.php';
require_once dirname(__FILE__) . '/./api/helpers/db_utils.php';

session_start();
$client = get_client();
$user = get_logged_in_user($entityManager);

if (is_null($user)) {
    header("Location: ./settings.php");
    exit;
}

// will take the stored token and will revoke it.
$token = $user->get_google_token();
if ($token) {
    $accessToken = $user->get_google_token();
    $client->setAccessToken($accessToken);
}

$client->revokeToken();
$user->set_google_token(null);

$entityManager->flush();

header('Location: ./settings.php');
