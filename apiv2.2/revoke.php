<?php
require_once dirname(__FILE__) . './setup.php';
$client = get_client();

// will take the stored token and will revoke it.
if (file_exists('./token.json')) {
    $accessToken = json_decode(file_get_contents('./token.json'), true);
    $client->setAccessToken($accessToken);
}
$client->revokeToken();

header('location:index.php');
