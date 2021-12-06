<?php
require_once dirname(__FILE__) . "/./helpers/constants.php";
require_once dirname(__FILE__) . "/../bootstrap.php";
require_once dirname(__FILE__) . '/../setup.php';
require_once dirname(__FILE__) . '/./helpers/db_utils.php';
require_once dirname(__FILE__) . "/./helpers/parsers.php";

session_start();

$errors = array("errors" => []);

$user = get_logged_in_user($entityManager);

if (is_null($user)) {
    array_push($errors["errors"], ERROR_MISSING_LOGGED_IN_USER);
    echo json_encode($errors);
    exit;
}

$courses = [];

$google_token = $user->get_google_token();
$canvas_token = $user->get_canvas_token();

if (!is_null($google_token)) {
    $google_courses = get_google_courses($google_token);

    foreach ($google_courses as $google_course) {
        $course = parse_course($google_course, SOURCE_GOOGLE_CLASSROOM);
        array_push($courses, $course);
    }
}

if (!is_null($canvas_token)) {
    $canvas_courses = get_canvas_courses($canvas_token);

    foreach ($canvas_courses as $canvas_course) {
        $course = parse_course($canvas_course, SOURCE_CANVAS);
        array_push($courses, $course);
    }
}


echo json_encode($courses);
exit;

function get_google_courses(array $token)
{
    $client = get_client();
    $client->setAccessToken($token);

    if ($client->isAccessTokenExpired()) {
        if ($client->getRefreshToken()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        }
    }
    
    $service = new Google\Service\Classroom($client);
    $courses = $service->courses->listCourses()->getCourses();

    return $courses;
}

function get_canvas_courses(string $token)
{
    $headers = array(
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer ' . $token
    );

    $response = Requests::get('https://canvas.instructure.com/api/v1/courses', $headers);
    $courses = json_decode($response->body);

    return $courses;
    
}
