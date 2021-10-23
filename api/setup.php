<?php
require_once __DIR__ . '/vendor/autoload.php';

$client = new Google\Client();
$client->setAuthConfig('client_secret.json');

$client->addScope(Google\Service\Classroom::CLASSROOM_COURSES_READONLY);
$client->addScope(Google\Service\Classroom::CLASSROOM_COURSEWORK_ME_READONLY);
$client->addScope(Google\Service\Classroom::CLASSROOM_COURSEWORK_STUDENTS_READONLY);

$client->setIncludeGrantedScopes(true);

session_start();