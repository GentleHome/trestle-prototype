<?php
require_once dirname(__FILE__) . "/./helpers/constants.php";
require_once dirname(__FILE__) . "/../bootstrap.php";
require_once dirname(__FILE__) . '/../setup.php';
require_once dirname(__FILE__) . '/./helpers/db_utils.php';
require_once dirname(__FILE__) . '/./helpers/parsers_v2.php';

session_start();

$collection = [];
$errors = array("errors" => []);

$user = get_logged_in_user($entityManager);

if (is_null($user)) {
    array_push($errors["errors"], ERROR_MISSING_LOGGED_IN_USER);
    echo json_encode($errors);
    exit;
}

$google_token = $user->get_google_token();
$canvas_token = $user->get_canvas_token();

if (!is_null($google_token)) {
    get_google_data($google_token);
}

if (!is_null($canvas_token)) {
    get_canvas_data($canvas_token);
}

echo json_encode(array_merge(...array_filter($collection)));

function get_google_data(array $token)
{
    global $collection;

    $client = get_client();
    $client->setAccessToken($token);

    if ($client->isAccessTokenExpired()) {
        if ($client->getRefreshToken()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        }
    }

    $service = new Google\Service\Classroom($client);
    $courses = $service->courses->listCourses()->getCourses();

    foreach ($courses as $course) {
        array_push($collection, get_google_announcements($course["id"], $course["name"], $service, SOURCE_GOOGLE_CLASSROOM));
    }
}

function get_canvas_data(string $token)
{
    
    global $collection;

    $headers = array(
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer ' . $token
    );

    if (isset($_GET['start_date'])) {
        $start_date = new DateTime($_GET['start_date'], new DateTimeZone('Asia/Manila'));
        
        $response = Requests::get('https://canvas.instructure.com/api/v1/courses?start_date=' . $start_date->format('Y-m-d'), $headers);
        $courses = json_decode($response->body);
    } else {
        $response = Requests::get('https://canvas.instructure.com/api/v1/courses', $headers);
        $courses = json_decode($response->body);
    }
    
    foreach ($courses as $course) {
        if (isset($course->account_id)) { //bypassing restricted courses
            array_push($collection, get_canvas_announcements($course->id, $course->name, $headers, SOURCE_CANVAS));
        }
    }
}

function get_canvas_announcements($course_id, $course_name, $headers, $source)
{
    $announcements = [];
    $response = Requests::get('https://canvas.instructure.com/api/v1/announcements?context_codes[]=course_' . $course_id, $headers);
    $announcements_response = json_decode($response->body);
    foreach ($announcements_response as $announcement) {
        array_push($announcements, parse_announcements($announcement, $course_name, $course_id, $source));
    }
    return $announcements;
}

function get_google_announcements($course_id, $course_name, $service, $source)
{
    $announcements = [];
    $response = $service->courses_announcements;
    foreach ($response->listCoursesAnnouncements($course_id) as $announcement) {
        array_push($announcements, parse_announcements($announcement, $course_name, $course_id, $source));
    }
    return $announcements;
}
