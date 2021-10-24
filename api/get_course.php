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

if ($source === SOURCE_GOOGLE_CLASSROOM) {

    $google_course = get_google_course($course_id);
    $course = parse_course($google_course, SOURCE_GOOGLE_CLASSROOM);
    
} else if ($source === SOURCE_CANVAS) {

    $canvas_course = get_canvas_course($course_id);
    $course = parse_course($canvas_course, SOURCE_CANVAS);

}

echo json_encode($course);
exit;


function get_google_course($course_id)
{
    $client = get_client();

    if (!isset($_SESSION['access_token'])) {

        echo $client->createAuthUrl();
        exit;

    } else {

        $client->setAccessToken($_SESSION['access_token']);
        $service = new Google\Service\Classroom($client);
        $course = $service->courses->get($course_id);

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
