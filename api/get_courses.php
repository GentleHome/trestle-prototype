<?php
require_once dirname(__FILE__) . '/./setup.php';

$google_courses = get_google_courses();
$canvas_courses = get_canvas_courses();

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

        return $courses;
    }
}

function get_canvas_courses()
{

    if (!isset($_GET['canvas_token'])) {

        return null;
        
    } else {

        $canvas_token = $_GET['canvas_token'];

        $headers = array(
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $canvas_token
        );

        $response = Requests::get('https://canvas.instructure.com/api/v1/courses', $headers);
        $courses = json_decode($response->body);

        return $courses;
    }
}
