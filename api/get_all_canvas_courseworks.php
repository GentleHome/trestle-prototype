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

$canvas_token = $user->get_canvas_token();


if (!is_null($canvas_token)) {
    get_canvas_data($canvas_token);
}

echo json_encode(array_merge(...array_filter($collection)));

function get_canvas_data(string $token)
{
    global $collection;

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

function get_canvas_assignments($course_id, $course_name, $headers, $source)
{
    $courseworks = [];
    $response = Requests::get('https://canvas.instructure.com/api/v1/courses/' . $course_id . '/assignments', $headers);
    $courseworks_response = json_decode($response->body);
    foreach ($courseworks_response as $coursework) {
        array_push($courseworks, parse_coursework($coursework, $source, $course_name));
    }
    return $courseworks;
}
