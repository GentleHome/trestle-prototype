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

if (!is_null($google_token)) {
    get_google_data($google_token);
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
        array_push($collection, get_google_assignments($course["id"], $course["name"], $service, SOURCE_GOOGLE_CLASSROOM));
    }
}

function get_google_assignments($course_id, $course_name, $service, $source)
{
    $courseworks = [];
    $response = $service->courses_courseWork;
    foreach ($response->listCoursesCourseWork($course_id) as $coursework) {
        array_push($courseworks, parse_coursework($coursework, $source, $course_name, $service, $course_id, $coursework["id"]));
    }
    return $courseworks;
}
