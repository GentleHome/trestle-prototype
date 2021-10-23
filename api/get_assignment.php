<?php
require_once dirname(__FILE__) . './setup.php';

$canvas_token = $_GET['canvas_token'];
$course_id = $_GET['course_id'];
$assignment_id = $_GET['assignment_id'];
$source = $_GET['source'];

$headers = array(
    'Content-Type' => 'application/json',
    'Authorization' => 'Bearer ' . $canvas_token
);

// We need to be able to choose whether it's getting a course on Canvas or Google Classroom
if ($source === 'Canvas') {

    $response = Requests::get(
        'https://canvas.instructure.com/api/v1'
            . '/courses/'
            . $course_id
            . '/assignments/'
            . $assignment_id,
        $headers
    );
}

echo $response->body;
