<?php
require_once dirname(__FILE__) . './setup.php';

$client = get_client();
// note: the comments provided will be put at the 
// point of view as if i got the db and shit

// this would act as the column with the token
$tokenPath = './token.json';

// this will put the authorized token to a file
// @DB pov, you can do your stuff here on storing the token on DB
if (isset($_GET['code'])) {
    echo $_GET['code'];
    $authCode = $_GET['code'];
    $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);

    $client->setAccessToken($accessToken);
    // this will stop the proces of saving the
    // authorized token to the file if we encounter any
    // errors in the token.
    if ($accessToken['error']) {
        echo $accessToken['error'];
        header("Location: ./index.php");
        exit;
    }
}

if (!file_exists(dirname($tokenPath))) {
    mkdir(dirname($tokenPath), 0700, true);
}

file_put_contents($tokenPath, json_encode($client->getAccessToken()));

header("Location: ./index.php");
exit;
