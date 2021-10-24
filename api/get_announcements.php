<?php
require_once dirname(__FILE__) . './setup.php';

if (!isset($_GET['course_id'])) {
    echo "No course ID!";
    exit;
}
$course_id = $_GET['course_id'];

$google_announcements = get_google_announcements($course_id);
$canvas_announcements = get_canvas_announcements($course_id);

$announcements = [];

foreach ($google_announcements as $google_announcement) {

    $announcement["source"]     = "CANVAS";
    $announcement["id"]         = $google_announcement["id"];
    $announcement["title"]      = null;
    $announcement["message"]    = $google_announcement["text"];
    $announcement["link"]       = $google_announcement["alternateLink"];
    $announcement["datePosted"] = $google_announcement["creationTime"];

    array_push($announcements, $announcement);
}

foreach ($canvas_announcements as $canvas_announcement) {

    $announcement["source"]     = "CANVAS";
    $announcement["id"]         = $canvas_announcement->id;
    $announcement["title"]      = $canvas_announcement->title;
    $announcement["message"]    = $canvas_announcement->message;
    $announcement["link"]       = $canvas_announcement->html_url;
    $announcement["datePosted"] = $canvas_announcement->posted_at;

    array_push($announcements, $announcement);
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
