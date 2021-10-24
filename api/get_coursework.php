<?php
// Consolidates all assignments, quizzes, and coursework into one endpoint
//
// - The structure of the JSON output must be properly determined
// - The output must contain info about the source, what type of coursework it is, and a link to the coursework
require_once dirname(__FILE__) . './setup.php';

if (!isset($_GET['course_id'])) {
    echo "No course ID!";
    exit;
}


if (!isset($_GET['coursework_id'])) {
    echo "No coursework ID!";
    exit;
}

if (!isset($_GET['source'])) {
    echo "No source selected!";
    exit;
}

if (!isset($_GET['type'])) {
    echo "No source selected!";
    exit;
}

$course_id = $_GET['course_id'];
$coursework_id = $_GET['coursework_id'];
$source = $_GET['source'];
$type = $_GET['type'];

// We need to be able to choose whether it's getting a course on Canvas or Google Classroom
if ($source === SOURCE_GOOGLE_CLASSROOM) {

    $google_coursework = get_google_coursework($course_id, $coursework_id);
    $coursework = parse_coursework($google_coursework, SOURCE_GOOGLE_CLASSROOM, TYPE_COURSEWORK);
} else if ($source === SOURCE_CANVAS) {

    if ($type === TYPE_ASSIGNMENT) {

        $canvas_assignment = get_canvas_assignment($course_id, $coursework_id);
        $coursework = parse_coursework($canvas_assignment, SOURCE_CANVAS, TYPE_ASSIGNMENT);

    } else if ($type === TYPE_QUIZ) {

        $canvas_quiz = get_canvas_quiz($course_id, $coursework_id);
        $coursework = parse_coursework($canvas_assignment, SOURCE_CANVAS, TYPE_QUIZ);
    }
}

echo json_encode($coursework);
exit;


function get_google_coursework($course_id, $coursework_id)
{
    $client = get_client();

    if (!isset($_SESSION['access_token'])) {

        echo $client->createAuthUrl();
        exit;
    } else {

        $client->setAccessToken($_SESSION['access_token']);
        $service = new Google\Service\Classroom($client);
        $coursework = $service->courses_courseWork->get($course_id, $coursework_id);

        return $coursework;
    }
}


function get_canvas_assignment($course_id, $coursework_id)
{
    if (!isset($_GET['canvas_token'])) {

        echo "Canvas token not set!";
        exit;
    } else {

        $canvas_token = $_GET['canvas_token'];

        $headers = array(
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $canvas_token
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
}


function get_canvas_quiz($course_id, $coursework_id)
{
    if (!isset($_GET['canvas_token'])) {

        echo "Canvas token not set!";
        exit;
    } else {

        $canvas_token = $_GET['canvas_token'];

        $headers = array(
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $canvas_token
        );

        $response = Requests::get('https://canvas.instructure.com/api/v1/courses/' 
        . $course_id 
        . '/quizzes'
        . $coursework_id
        , $headers);
        $quiz = json_decode($response->body);

        return $quiz;
    }
}
