<?php
require_once dirname(__FILE__) . './setup.php';
$collection = [];
$_GET["canvas_token"] = "7~98HJbrfWCTrgFHs6w02X40O5Zskjg9RGgidbVyNpC0uqIXS6RVVVALEojjn3xd6H";
if (isset($_GET['classroom'])) {
    echo json_encode(get_google_data());
    exit;
}

if (isset($_GET['canvas'])) {
    echo json_encode(get_canvas_data());
    exit;
}

array_push($collection, get_google_data());
array_push($collection, get_canvas_data());

// echo json_encode($collection);

function get_google_data()
{
    $client = get_client();

    if (!isset($_SESSION['access_token'])) {
        return array("oauthURL" => $client->createAuthUrl());
    } else {
        $client->setAccessToken(($_SESSION['access_token']));
        $service = new Google\Service\Classroom($client);
        $courses = $service->courses->listCourses()->getCourses();

        return $courses;
    }
}

function get_canvas_data()
{
    if (!isset($_GET['canvas_token'])) {
        return array("error" => null);
    } else {
        $canvas_token = $_GET['canvas_token'];
        $headers = array(
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $canvas_token
        );
        $canvas_collection = [];
        $response = Requests::get('https://canvas.instructure.com/api/v1/courses', $headers);
        $courses = json_decode($response->body);

        foreach ($courses as $course) {
            $course_id = $course->id;
            $course_code = $course->course_code;
            $assignments_response = Requests::get('https://canvas.instructure.com/api/v1/courses/' . $course_id . '/assignments', $headers);
            echo 'https://canvas.instructure.com/api/v1/courses/' . $course_id . '/assignments', $headers;
            echo var_dump($course);
            // get canvas announcements
            // $announcements_response = Requests::get('https://canvas.instructure.com/api/v1/announcements?context_codes[]=' . $course_code, $headers);
            // echo var_dump(json_decode($announcements_response->body));
            // echo '<pre>' . var_dump($assignments_response) . '</pre>';
            // get canvas quizzes
            // $quizzes_response = Requests::get('https://canvas.instructure.com/api/v1/courses/' . $course_id . '/quizzes', $headers);
            // array_push($canvas_collection, get_assignments($course_id, $headers, SOURCE_CANVAS));
            // array_push($canvas_collection, get_announcements($course_code, $headers, SOURCE_CANVAS));
        }
        // echo "<pre>" . var_dump($canvas_collection) . "</pre>";
    }
}
// returns assignments
function get_assignments($course_id, $headers, $source)
{
    $assignments = [];
    $response = Requests::get('https://canvas.instructure.com/api/v1/courses/' . $course_id . '/assignments', $headers);
    $assignments_response = json_decode($response->body);
    foreach ($assignments_response as $assignment) {
        array_push($assignments, parse_assignments($assignment, $source));
    }
    return $assignments;
}

function get_announcements($course_code, $headers, $source)
{
    $announcements = [];
    $response = Requests::get('https://canvas.instructure.com/api/v1/announcements?context_codes[]=' . $course_code, $headers);
    $announcements_response = json_decode($response->body);
    foreach ($announcements_response as $announcement) {
        array_push($announcements, $announcement);
        // array_push($announcements, parse_announcements($announcement, $source, $course_id));
    }
    return $announcements;
}

function parse_assignments($object, $source)
{
    $assignment = [];
    if ($source === SOURCE_CANVAS) {
        $assignment["source"]       = SOURCE_CANVAS;
        $assignment["type"]         = TYPE_ASSIGNMENT;
        $assignment["id"]           = (int)$object->id;
        $assignment["courseId"]     = (int)$object->course_id;
        $assignment["title"]        = $object->name;
        $assignment["description"]  = $object->description;
        $assignment["datePosted"]   = $object->created_at;
        $assignment["dueDate"]      = $object->due_at;
        $assignment["unlockDate"]   = $object->unlock_at;
        $assignment["link"]         = $object->html_url;
    }
    return $assignment;
}

function parse_announcements($object, $source, $course_id)
{
    $announcement = [];
    if ($source === SOURCE_CANVAS) {
        $announcement["source"]     = SOURCE_CANVAS;
        $announcement["id"]         = $object->id;
        $announcement["courseId"]   = $course_id;
        $announcement["title"]      = $object->title;
        $announcement["message"]    = $object->message;
        $announcement["link"]       = $object->html_url;
        $announcement["datePosted"] = $object->posted_at;
    }
    return $announcement;
}
