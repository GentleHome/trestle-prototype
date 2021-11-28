<?php
require_once dirname(__FILE__) . './setup.php';
$collection = [];

// imma just set it here cause we dont have any storage for it yet
// $_GET['canvas_token'] = "7~98HJbrfWCTrgFHs6w02X40O5Zskjg9RGgidbVyNpC0uqIXS6RVVVALEojjn3xd6H";

// get_google_data();
// get_canvas_data();
new_get_google_data();
echo json_encode(array_merge(...array_filter($collection)));

function new_get_google_data()
{
    global $collection;
    $client = get_client();
    //  check 'token.json' for access token
    // note: @DB pov, else if can refer to the column for our
    // stored token on DB
    if (file_exists('./token.json')) {
        $accessToken = json_decode(file_get_contents('./token.json'), true);
        $client->setAccessToken($accessToken);
    }
    // will check if access token is expired
    if ($client->isAccessTokenExpired()) {
        // if refresh token is possible
        if ($client->getRefreshToken()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        }
    }
    // try to construct the service object
    // if it fails and throws errors, most likely because the token is invalid
    // will catch the error and try to re-authorize
    try {
        $service = new Google\Service\Classroom($client);
        $courses = $service->courses->listCourses()->getCourses();

        foreach ($courses as $course) {
            array_push($collection, get_google_assignments($course["id"], $course["name"], $service, SOURCE_GOOGLE_CLASSROOM));
            array_push($collection, get_google_announcements($course["id"], $course["name"], $service, SOURCE_GOOGLE_CLASSROOM));
        }
    } catch (Exception $e) {
        array_push($collection, array("oauthURL" => $client->createAuthUrl()));
    }
}

function get_google_data()
{
    global $collection;
    $client = get_client();

    if (!isset($_SESSION['access_token'])) {
        array_push($collection, array("oauthURL" => $client->createAuthUrl()));
    } else {
        $client->setAccessToken(($_SESSION['access_token']));
        $service = new Google\Service\Classroom($client);
        $courses = $service->courses->listCourses()->getCourses();

        foreach ($courses as $course) {
            array_push($collection, get_google_assignments($course["id"], $course["name"], $service, SOURCE_GOOGLE_CLASSROOM));
            array_push($collection, get_google_announcements($course["id"], $course["name"], $service, SOURCE_GOOGLE_CLASSROOM));
        }
    }
}

function get_canvas_data()
{
    global $collection;

    if (!isset($_GET['canvas_token'])) {
        // return array("error" => null);
        array_push($collection, array("error" => null));
    } else {
        $canvas_token = $_GET['canvas_token'];
        $headers = array(
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $canvas_token
        );

        $response = Requests::get('https://canvas.instructure.com/api/v1/courses', $headers);
        $courses = json_decode($response->body);

        foreach ($courses as $course) {
            array_push($collection, get_canvas_assignments($course->id, $course->name, $headers, SOURCE_CANVAS));
            array_push($collection, get_canvas_announcements($course->id, $course->name, $headers, SOURCE_CANVAS));
        }
    }
}
