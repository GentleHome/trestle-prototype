<?php
require_once "bootstrap.php";

$users = $entityManager->getRepository("User")->findAll();

foreach ($users as $user) {
    echo "- ID: ". $user->get_id() . " Username: " . $user->get_username() . "\n";
}