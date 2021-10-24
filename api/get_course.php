<?php
require_once dirname(__FILE__) . './setup.php';

if(!isset($_GET['course_id'])){
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

    $google_course = get_google_course($course_id);

    $course["source"]   = "GCLASS";
    $course["id"]       = (int)$google_course["id"];
    $course["name"]     = $google_course["name"];
    $course["heading"]  = $google_course["descriptionHeading"];
    $course["link"]     = $google_course["alternateLink"];
    
} else if ($source === 'CANVAS') {

    $canvas_course = get_canvas_course($course_id);

    $course["source"]   = "CANVAS";
    $course["id"]       = $canvas_course->id;
    $course["name"]     = $canvas_course->name;
    $course["heading"]  = null;
    $course["link"]     = "https://canvas.instructure.com/courses/" . $canvas_course->id;

}

echo json_encode($course);


function get_google_course($course_id)
{
    $client = get_client();

    if (!isset($_SESSION['access_token'])) {

        echo $client->createAuthUrl();
        exit;

    } else {

        $client->setAccessToken($_SESSION['access_token']);
        $service = new Google\Service\Classroom($client);
        $course = $service->courses->get($course_id)->get;

        return $course;
    }
}


function get_canvas_course($course_id)
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

        $response = Requests::get('https://canvas.instructure.com/api/v1/courses/' . $course_id, $headers);
        $course = json_decode($response->body);

        return $course;
    }
}
