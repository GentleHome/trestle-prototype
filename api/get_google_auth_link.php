<?php
require_once dirname(__FILE__) . '../setup.php';
$code = array("oauthURL" => $client->createAuthUrl());

echo json_encode($code);