<?php
require_once dirname(__FILE__) . '/./setup.php';
require_once dirname(__FILE__) . "/./helpers/parsers.php";

if (!isset($_GET['course_id'])) {
    echo ERROR_MISSING_VALUE . ': Course ID';
    exit;
}

if (!isset($_GET['source'])) {
    echo ERROR_MISSING_VALUE . ': Source';
    exit;
}

$user = get_logged_in_user($entityManager);

if (is_null($user)) {
    echo ERROR_MISSING_LOGGED_IN_USER;
}

$course_id = $_GET['course_id'];
$source = $_GET['source'];

$announcements = [];

if ($source === SOURCE_GOOGLE_CLASSROOM) {

    $google_announcements = get_google_announcements($user, $course_id);
    foreach ($google_announcements as $google_announcement) {

        $announcement = parse_announcement($google_announcement, SOURCE_GOOGLE_CLASSROOM, $course_id);
        array_push($announcements, $announcement);
    }

} else if ($source === SOURCE_CANVAS) {

    $canvas_announcements = get_canvas_announcements($user, $course_id);
    foreach ($canvas_announcements as $canvas_announcement) {

        $announcement = parse_announcement($canvas_announcement, SOURCE_CANVAS, $course_id);
        array_push($announcements, $announcement);
    }

}

echo json_encode($announcements);


function get_google_announcements(User $user, $course_id)
{

    $client = get_client();
    $token = $user->get_google_token();

    if (is_null($token)) {
        echo ERROR_GOOGLE_TOKEN_NOT_SET;
        exit;
    }

    $client->setAccessToken($token);
    $service = new Google\Service\Classroom($client);
    $announcements = $service->courses_announcements->listCoursesAnnouncements($course_id);

    return $announcements;
}

function get_canvas_announcements(User $user, $course_id)
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

    $response = Requests::get('https://canvas.instructure.com/api/v1/announcements?context_codes[]=' . $course_id, $headers);
    $announcements = json_decode($response->body);

    return $announcements;
}
