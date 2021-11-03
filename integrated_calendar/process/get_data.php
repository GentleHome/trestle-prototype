<?php
require_once dirname(__FILE__) . './setup.php';
$collection = [];

// imma just set it here cause we dont have any storage for it yet
$_GET['canvas_token'] = "7~98HJbrfWCTrgFHs6w02X40O5Zskjg9RGgidbVyNpC0uqIXS6RVVVALEojjn3xd6H";

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

        $google_collection = [];
        $courseworks = [];
        $announcements = [];

        foreach ($courses as $course) {
            array_push($courseworks, get_google_assignments($course["id"], $course["name"], $service, SOURCE_GOOGLE_CLASSROOM));
            array_push($announcements, get_google_announcements($course["id"], $course["name"], $service, SOURCE_GOOGLE_CLASSROOM));
        }
        array_push($google_collection, array("courseworks" => $courseworks));
        array_push($google_collection, array("announcements" => $announcements));
        return $google_collection;
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

        $canvas_collection = [];
        $courseworks = [];
        $announcements = [];

        foreach ($courses as $course) {
            array_push($courseworks, get_canvas_assignments($course->id, $course->name, $headers, SOURCE_CANVAS));
            array_push($announcements, get_canvas_announcements($course->id, $course->name, $headers, SOURCE_CANVAS));
        }
        array_push($canvas_collection, array("courseworks" => $courseworks));
        array_push($canvas_collection, array("announcements" => $announcements));

        return $canvas_collection;
    }
}
