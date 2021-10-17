<?php
    require_once dirname(__FILE__) . '/vendor/autoload.php';

    $canvas_token = $_GET['canvas_token'];
    $headers = array(
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer ' . $canvas_token
    );
    $response = Requests::get('https://canvas.instructure.com/api/v1/courses', $headers);

    echo $response->body;
?>