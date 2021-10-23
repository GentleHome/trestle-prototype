<?php
require_once dirname(__FILE__) . './setup.php';

$client = get_client();
$canvas_token = $_GET['canvas_token'];

if (!isset($_SESSION['access_token'])) {
    header($client->createAuthUrl());
} else {
    $client->setAccessToken($_SESSION['access_token']);
    $service = new Google\Service\Classroom($client);
    $data_courses = $service->courses->listCourses()->getCourses();
}

/* Canvas Request
$headers = array(
    'Content-Type' => 'application/json',
    'Authorization' => 'Bearer ' . $canvas_token
);
$response = Requests::get('https://canvas.instructure.com/api/v1/courses', $headers);
 */

// We'll need to determine the proper merging logic here
// echo $response->body;
