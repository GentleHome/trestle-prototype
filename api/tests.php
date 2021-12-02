<?php
require_once dirname(__FILE__) . "/./helpers/constants.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../forms.php");
    exit;
}

if ($_SESSION[MESSAGES]) {
    foreach ($_SESSION[MESSAGES] as $message) {
        echo $message;
    }
}

if ($_SESSION[ERRORS]) {
    foreach ($_SESSION[ERRORS] as $message) {
        echo $message;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title></title>
    <script src="test-buttons.js"></script>
</head>

<body>
    <a href="../settings.php">Settings</a>
    </br>
    </br>
    
    <!-- Get Courses -->
    <button id="get-courses-button">Get All Courses</button> = <textarea id="get-courses-response" disabled placeholder="Response"></textarea></br></br>
    </br></br>

    <!-- Get All Courseworks -->
    <button id="get-all-courseworks-button">Get All Courseworks</button> = <textarea id="get-all-courseworks-response" disabled placeholder="Response"></textarea></br></br>

    <!-- Get All Announcements -->
    <button id="get-all-announcements-button">Get All Announcements</button> = <textarea id="get-all-announcements-response" disabled placeholder="Response"></textarea></br></br>

    <!-- Get Course -->
    <div class="specific-course-div" style="border: 1px solid black; padding: 15px;">
        <input type="text" id="get-course-id" placeholder="Course ID">
        <select id="get-source-option">
            <option selected value="CANVAS">Canvas</option>
            <option value="GCLASS">Google Classroom</option>
        </select></br></br>

        <button id="get-course-button">Get Course</button> = <textarea id="get-course-response" disabled placeholder="Response"></textarea></br></br>

        <!-- Get Courseworks -->
        <button id="get-courseworks-button">Get Courseworks</button> = <textarea id="get-courseworks-response" disabled placeholder="Response"></textarea></br></br>

        <!-- Get Coursework -->
        <select id="get-coursework-type-option">
            <option selected value="ASSIGNMENT">Assignment</option>
            <option value="COURSEWORK">Coursework</option>
            <option value="QUIZ">Quiz</option>
        </select>

        <input type="text" id="get-coursework-id" placeholder="Coursework ID">
        <button id="get-coursework-button">Get Coursework</button> = <textarea id="get-coursework-response" disabled placeholder="Response"></textarea></br></br>

        <button id="get-announcements-button">Get Announcements</button> = <textarea id="get-announcements-response" disabled placeholder="Response"></textarea></br></br>
    </div>

    <select id="get-reminder-type-option">
        <option selected value="RMDR">Reminder</option>
        <option value="TASK">Task</option>
    </select>

    <!-- Get Tasks -->
    <button id="get-tasks-button">Get Tasks</button> = <textarea id="get-tasks-response" disabled placeholder="Response"></textarea></br></br>

    <!-- Get Reminders -->
    <button id="get-reminders-button">Get Reminders</button> = <textarea id="get-reminders-response" disabled placeholder="Response"></textarea></br></br>

    <!-- Post Reminders -->
    <button id="post-reminders-button">Post Reminders</button> = <textarea id="post-reminders-response" disabled placeholder="Response"></textarea></br></br>



</body>

</html>