<?php
require_once "bootstrap.php";
$user_id = $argv[1];

$user = $entityManager->find("User", $user_id);

if (!$user) {
    echo "User not Found!";
    exit(1);
}

$entityManager->remove($user);
$entityManager->flush();

echo 'User named ' . $user->get_username() . ' is deleted from the database';