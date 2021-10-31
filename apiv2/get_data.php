<?php
require_once dirname(__FILE__) . './setup.php';
$collection = [];
if (isset($_GET['classroom'])) {
    echo json_encode(get_google_data());
    exit;
}

if (isset($_GET['canvas'])) {
    echo json_encode(get_canvas_data());
    exit;
}

array_push($collection, get_google_data());
array_push($collection, get_canvas_data());

echo json_encode($collection);


function get_google_data()
{
    $client = get_client();

    if (!isset($_SESSION['access_token'])) {
        return array("oauthURL" => $client->createAuthUrl());
    } else {
        $client->setAccessToken(($_SESSION['access_token']));
        $service = new Google\Service\Classroom($client);
        $courses = $service->courses->listCourses()->getCourses();

        return $courses;
    }
}

function get_canvas_data()
{
    if (!isset($_GET['canvas_token'])) {
        return array("error" => null);
    } else {
        $canvas_token = $_GET['canvas_token'];

        $headers = array(
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $canvas_token
        );

        $response = Requests::get('https://canvas.instructure.com/api/v1/courses', $headers);
        $courses = json_decode($response->body);

        return $courses;
    }
}
