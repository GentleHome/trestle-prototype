<?php
require_once "bootstrap.php";

$newUsername = $argv[1];
$newPassword = $argv[2];

$user = new User();
$user->set_username($newUsername);
$user->set_password($newPassword);
$entityManager->persist($user);
$entityManager->flush();

echo "Created User with ID " . $user->get_id() . "\n";
