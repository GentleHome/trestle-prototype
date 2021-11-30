<?php
require_once dirname(__FILE__) . "/../bootstrap.php";
require_once dirname(__FILE__) . '../setup.php';
require_once dirname(__FILE__) . './helpers/db_utils.php';
require_once dirname(__FILE__) . "/./helpers/parsers.php";

$user = get_logged_in_user($entityManager);

if (is_null($user)) {
    echo ERROR_MISSING_LOGGED_IN_USER;
}

$google_courses = get_google_courses($user);
$canvas_courses = get_canvas_courses($user);

$courses = [];

foreach ($google_courses as $google_course) {

    $course = parse_course($google_course, SOURCE_GOOGLE_CLASSROOM);

    array_push($courses, $course);
}

foreach ($canvas_courses as $canvas_course) {

    $course = parse_course($canvas_course, SOURCE_CANVAS);
    array_push($courses, $course);
}

echo json_encode($courses);
exit;


function get_google_courses(User $user)
{
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
        }
    }
    
    $service = new Google\Service\Classroom($client);
    $courses = $service->courses->listCourses()->getCourses();

    return $courses;
}

function get_canvas_courses(User $user)
{

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

    return $courses;
    
}
