<?php
require_once "bootstrap.php";
$user_id = $argv[1];

$user = $entityManager->find("User", $user_id);

if (!$user) {
    echo "User not Found!";
    exit(1);
}

$reminders = $user->get_reminders();

foreach($reminders as $reminder){
    echo "- ID: " . $reminder->get_id() . " Title: " . $reminder->get_title() . "\n";
}