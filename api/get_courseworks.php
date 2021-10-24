<?php
// Consolidates all assignments, quizzes, and courseworks into one endpoint
//
// - The structure of the JSON output must be properly determined
// - The output must contain info about the source, what type of coursework it is, and a link to the coursework
require_once dirname(__FILE__) . './setup.php';

if (!isset($_GET['course_id'])) {
    echo "No course ID!";
    exit;
}

if (!isset($_GET['source'])) {
    echo "No source selected!";
    exit;
}

$course_id = $_GET['course_id'];
$source = $_GET['source'];

// We need to be able to choose whether it's getting a course on Canvas or Google Classroom
if ($source === 'GCLASS') {

    $google_courseworks = get_google_courseworks($course_id);

    $courseworks = [];

    foreach ($google_courseworks as $google_coursework) {
        $coursework["source"]   = "GCLASS";
        $coursework["type"]     = "COURSEWORK";
        $coursework["id"]       = (int)$google_coursework["id"];
        $coursework["courseId"] = (int)$google_coursework["courseId"];
        $coursework["title"]     = $google_coursework["title"];
        $coursework["description"]     = $google_coursework["description"];
        $coursework["datePosted"] = $google_coursework["creationTime"];
        $coursework["dueDate"]  = $google_coursework["dueDate"];
        $coursework["unlockDate"] = null;
        $coursework["link"]     = $google_coursework["alternateLink"];

        array_push($courseworks, $coursework);
    }
} else if ($source === 'CANVAS') {

    $canvas_assignments = get_canvas_assignments($course_id);
    $canvas_quizzes = get_canvas_quizzes($course_id);

    $courseworks = [];

    foreach ($canvas_assignments as $canvas_assignment) {
        $coursework["source"]   = "CANVAS";
        $coursework["type"]     = "ASSIGNMENT";
        $coursework["id"]       = (int)$canvas_assignment->id;
        $coursework["courseId"] = (int)$canvas_assignment->course_id;
        $coursework["title"]     = $canvas_assignment->name;
        $coursework["description"]     = $canvas_assignment->description;
        $coursework["datePosted"] = $canvas_assignment->created_at;
        $coursework["dueDate"]  = $canvas_assignment->due_at;
        $coursework["unlockDate"] = $canvas_assignment->unlock_at;
        $coursework["link"]     = $canvas_assignment->html_url;

        array_push($courseworks, $coursework);
    }

    foreach ($canvas_quizzes as $canvas_quiz) {
        $coursework["source"]   = "CANVAS";
        $coursework["type"]     = "QUIZ";
        $coursework["id"]       = (int)$canvas_quiz->id;
        $coursework["courseId"] = (int)$canvas_quiz->course_id;
        $coursework["title"]     = $canvas_quiz->title;
        $coursework["description"]     = $canvas_quiz->description;
        $coursework["datePosted"] = $canvas_quiz->created_at;
        $coursework["dueDate"]  = $canvas_quiz->due_at;
        $coursework["unlockDate"] = $canvas_quiz->unlock_at;
        $coursework["link"]     = $canvas_quiz->html_url;

        array_push($courseworks, $coursework);
    }
}

echo json_encode($courseworks);

function get_google_courseworks($course_id)
{
    $client = get_client();

    if (!isset($_SESSION['access_token'])) {

        echo $client->createAuthUrl();
        exit;
    } else {

        $client->setAccessToken($_SESSION['access_token']);
        $service = new Google\Service\Classroom($client);
        $courseworks = $service->courses_courseWork->listCoursesCourseWork($course_id);

        return $courseworks;
    }
}

function get_canvas_assignments($course_id)
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

        $response = Requests::get('https://canvas.instructure.com/api/v1/courses/' . $course_id . '/assignments', $headers);
        $assignments = json_decode($response->body);

        return $assignments;
    }
}

function get_canvas_quizzes($course_id)
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

        $response = Requests::get('https://canvas.instructure.com/api/v1/courses/' . $course_id . '/quizzes', $headers);
        $assignments = json_decode($response->body);

        return $assignments;
    }
}
