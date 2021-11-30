<?php
require_once dirname(__FILE__) . "/../bootstrap.php";
require_once dirname(__FILE__) . '../setup.php';
require_once dirname(__FILE__) . './helpers/db_utils.php';
require_once dirname(__FILE__) . "/./helpers/parsers.php";

if (!isset($_GET['course_id'])) {
    echo ERROR_MISSING_VALUE . ': Course ID';
    exit;
}

if (!isset($_GET['coursework_id'])) {
    echo ERROR_MISSING_VALUE . ': Coursework ID';
    exit;
}

if (!isset($_GET['source'])) {
    echo ERROR_MISSING_VALUE . ': Source';
    exit;
}

if (!isset($_GET['type'])) {
    echo ERROR_MISSING_VALUE . ': Type';
    exit;
}

$course_id = $_GET['course_id'];
$coursework_id = $_GET['coursework_id'];
$source = $_GET['source'];
$type = $_GET['type'];

$user = get_logged_in_user($entityManager);

if (is_null($user)) {
    echo ERROR_MISSING_LOGGED_IN_USER;
}

if ($source === SOURCE_GOOGLE_CLASSROOM) {

    $google_coursework = get_google_coursework($user, $course_id, $coursework_id);
    $coursework = parse_coursework($google_coursework, SOURCE_GOOGLE_CLASSROOM, TYPE_COURSEWORK);
} else if ($source === SOURCE_CANVAS) {

    if ($type === TYPE_ASSIGNMENT) {

        $canvas_assignment = get_canvas_assignment($user, $course_id, $coursework_id);
        $coursework = parse_coursework($canvas_assignment, SOURCE_CANVAS, TYPE_ASSIGNMENT);

    } else if ($type === TYPE_QUIZ) {

        $canvas_quiz = get_canvas_quiz($user, $course_id, $coursework_id);
        $coursework = parse_coursework($canvas_assignment, SOURCE_CANVAS, TYPE_QUIZ);
    }
}

echo json_encode($coursework);
exit;


function get_google_coursework(User $user, $course_id, $coursework_id)
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
            $user->set_google_token($client->getAccessToken());
        }
    }
    
    $service = new Google\Service\Classroom($client);
    $coursework = $service->courses_courseWork->get($course_id, $coursework_id);

    return $coursework;
}


function get_canvas_assignment(User $user, $course_id, $coursework_id)
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

    $response = Requests::get(
        'https://canvas.instructure.com/api/v1/courses/'
            . $course_id
            . '/assignments/'
            . $coursework_id,
        $headers
    );
    $assignment = json_decode($response->body);

    return $assignment;
    
}


function get_canvas_quiz(User $user, $course_id, $coursework_id)
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

    $response = Requests::get('https://canvas.instructure.com/api/v1/courses/' 
    . $course_id 
    . '/quizzes'
    . $coursework_id
    , $headers);
    $quiz = json_decode($response->body);

    return $quiz;
    
}
