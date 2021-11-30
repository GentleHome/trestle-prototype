<?php
require_once dirname(__FILE__) . './setup.php';
include dirname(__FILE__) . './helpers/utility.php';

$collection = [];

// imma just set it here cause we dont have any storage for it yet
// $_GET['canvas_token'] = "7~98HJbrfWCTrgFHs6w02X40O5Zskjg9RGgidbVyNpC0uqIXS6RVVVALEojjn3xd6H";

$user = get_logged_in_user($entityManager);

if (is_null($user)) {
    echo ERROR_MISSING_LOGGED_IN_USER;
}

get_google_data($user);
get_canvas_data($user);
echo json_encode(array_merge(...array_filter($collection)));

function get_google_data(User $user)
{
    global $collection;
    $client = get_client();
    $token = $user->get_google_token();

    if (is_null($token)) {
        echo ERROR_GOOGLE_TOKEN_NOT_SET;
        exit;
    }

    $client->setAccessToken($token);

    if ($client->isAccessTokenExpired()) {
        if ($client->getRefreshToken()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            $user->set_google_token($client->getAccessToken());
        }
    }

    $service = new Google\Service\Classroom($client);
    $courses = $service->courses->listCourses()->getCourses();

    foreach ($courses as $course) {
        array_push($collection, get_google_assignments($course["id"], $course["name"], $service, SOURCE_GOOGLE_CLASSROOM));
    }
    
}

function get_canvas_data(User $user)
{
    global $collection;

    $token = $user->get_canvas_token();

    if (is_null($token)) {
        echo ERROR_CANVAS_TOKEN_NOT_SET;
        exit;
    }

    $headers = array(
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer ' . $token
    );

    $response = Requests::get('https://canvas.instructure.com/api/v1/courses', $headers);
    $courses = json_decode($response->body);

    foreach ($courses as $course) {
        array_push($collection, get_canvas_assignments($course->id, $course->name, $headers, SOURCE_CANVAS));
    }
}
