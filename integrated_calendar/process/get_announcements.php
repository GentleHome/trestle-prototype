<?php
require_once dirname(__FILE__) . './setup.php';
$collection = [];

// imma just set it here cause we dont have any storage for it yet
$_GET['canvas_token'] = "7~98HJbrfWCTrgFHs6w02X40O5Zskjg9RGgidbVyNpC0uqIXS6RVVVALEojjn3xd6H";

get_google_data();
get_canvas_data();
echo json_encode(array_merge(...array_filter($collection)));

function get_google_data()
{
    global $collection;
    $client = get_client();

    if (isset($_SESSION['access_token'])) {

        $client->setAccessToken(($_SESSION['access_token']));
        $service = new Google\Service\Classroom($client);
        $courses = $service->courses->listCourses()->getCourses();

        foreach ($courses as $course) {
            array_push($collection, get_google_announcements($course["id"], $course["name"], $service, SOURCE_GOOGLE_CLASSROOM));
        }
    }
}

function get_canvas_data()
{
    global $collection;

    if (isset($_GET['canvas_token'])) {

        $canvas_token = $_GET['canvas_token'];
        $headers = array(
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $canvas_token
        );

        $response = Requests::get('https://canvas.instructure.com/api/v1/courses', $headers);
        $courses = json_decode($response->body);

        foreach ($courses as $course) {
            array_push($collection, get_canvas_announcements($course->id, $course->name, $headers, SOURCE_CANVAS));
        }
    }
}
