<?php
require_once "bootstrap.php";

$user_id = $argv[1];
$title = $argv[2];
$message = $argv[3];

$user = $entityManager->find("User", $user_id);

if(!$user){
    echo "User not Found!";
    exit(1);
}
$reminder = new Reminder();

$reminder->set_type("CHECKLIST");
$reminder->set_user($user);
$reminder->set_title($title);
$reminder->set_message($message);

$reminder->set_remind_date(new DateTime($datetime="now", new DateTimeZone('Asia/Manila')));

$entityManager->persist($reminder);
$entityManager->flush();

echo "Created Reminder with ID " . $reminder->get_id() . " for ". $reminder->get_user()->get_username() ." Time: ". $reminder->get_date_created()->format('Y-m-d H:i:s') ."\n";
