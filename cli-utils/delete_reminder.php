<?php
require_once "bootstrap.php";

$reminder_id = $argv[1];

$reminder = $entityManager->find("Reminder", $reminder_id);

if (!$reminder) {
    echo "Reminder not Found!";
    exit(1);
}

$entityManager->remove($reminder);
$entityManager->flush();

echo 'Reminder titled ' . $reminder->get_title() . ', created at ' . $reminder->get_date_created()->format('Y-m-d H:i:s') . ' is deleted from the database';