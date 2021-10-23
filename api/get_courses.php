<?php
require_once dirname(__FILE__) . './setup.php';

$google_courses = get_google_courses();
$canvas_courses = get_canvas_courses();

$courses = [];

foreach ($google_courses as $google_course) {

    $course = [];
    $course["source"] = "GCLASS";
    $course["id"] = (int)$google_course["id"];
    $course["name"] = $google_course["name"];
    $course["heading"] = $google_course["descriptionHeading"];
    $course["link"] = $google_course["alternateLink"];

    array_push($courses, $course);
}

foreach ($canvas_courses as $canvas_course) {

    $course = [];
    $course["source"] = "CANVAS";
    $course["id"] = $canvas_course->id;
    $course["name"] = $canvas_course->name;
    $course["heading"] = null;
    $course["link"] = "https://canvas.instructure.com/courses/" . $canvas_course->id;

    array_push($courses, $course);
}

echo json_encode($courses);


function get_google_courses()
{

    $client = get_client();

    if (!isset($_SESSION['access_token'])) {

        echo $client->createAuthUrl();
        exit;
    } else {

        $client->setAccessToken($_SESSION['access_token']);
        $service = new Google\Service\Classroom($client);
        $courses = $service->courses->listCourses()->getCourses();

        // echo var_dump($courses);
        // echo json_encode($courses);

        return $courses;
    }
}

function get_canvas_courses()
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

        $response = Requests::get('https://canvas.instructure.com/api/v1/courses', $headers);
        $courses = json_decode($response->body);

        // echo var_dump($courses);
        // echo $response->body;

        return $courses;
    }
}
