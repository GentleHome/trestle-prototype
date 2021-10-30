<?php
require_once dirname(__FILE__) . './setup.php';

if (!isset($_GET['course_id'])) {
    echo "No course ID!";
    exit;
}

if (!isset($_GET['source'])) {
    echo "No source!";
    exit;
}

$course_id = $_GET['course_id'];
$source = $_GET['source'];

$announcements = [];

if ($source === SOURCE_GOOGLE_CLASSROOM) {

    $google_announcements = get_google_announcements($course_id);
    foreach ($google_announcements as $google_announcement) {

        $announcement = parse_announcement($google_announcement, SOURCE_GOOGLE_CLASSROOM, $course_id);
        array_push($announcements, $announcement);
    }

} else if ($source === SOURCE_CANVAS) {

    $canvas_announcements = get_canvas_announcements($course_id);
    foreach ($canvas_announcements as $canvas_announcement) {

        $announcement = parse_announcement($canvas_announcement, SOURCE_CANVAS, $course_id);
        array_push($announcements, $announcement);
    }

}

echo json_encode($announcements);


function get_google_announcements($course_id)
{

    $client = get_client();

    if (!isset($_SESSION['access_token'])) {

        echo $client->createAuthUrl();
        exit;
    } else {

        $client->setAccessToken($_SESSION['access_token']);
        $service = new Google\Service\Classroom($client);
        $announcements = $service->courses_announcements->listCoursesAnnouncements($course_id);

        return $announcements;
    }
}

function get_canvas_announcements($course_id)
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

        $response = Requests::get('https://canvas.instructure.com/api/v1/announcements?context_codes[]=' . $course_id, $headers);
        $announcements = json_decode($response->body);

        return $announcements;
    }
}
