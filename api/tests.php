<?php
require_once dirname(__FILE__) . "/./helpers/constants.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../forms.php");
    exit;
}

if (isset($_SESSION[MESSAGES])) {
    foreach ($_SESSION[MESSAGES] as $message) {
        echo $message;
    }
}

if (isset($_SESSION[ERRORS])) {
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
    <div class="specific-course-div" style="border: 1px solid black; padding: 15px; padding-top: 0px;">
        <h1>A bit more specific:</h1>
        <input type="text" id="get-course-id" placeholder="Course ID">
        <select id="get-source-option">
            <option selected value="CANVAS">Canvas</option>
            <option value="GCLASS">Google Classroom</option>
        </select></br></br>

        <button id="get-course-button">Get Course</button> = <textarea id="get-course-response" disabled placeholder="Response"></textarea></br></br>

        <!-- Get Courseworks -->
        <button id="get-courseworks-button">Get Courseworks</button> = <textarea id="get-courseworks-response" disabled placeholder="Response"></textarea></br></br>

        <!-- Get Coursework -->
        <input type="text" id="get-coursework-id" placeholder="Coursework ID">
        <button id="get-coursework-button">Get Coursework</button> = <textarea id="get-coursework-response" disabled placeholder="Response"></textarea></br></br>

        <button id="get-announcements-button">Get Announcements</button> = <textarea id="get-announcements-response" disabled placeholder="Response"></textarea></br></br>
    </div>
    </br>


    <div class="reminders-div" style="border: 1px solid black; padding: 15px; padding-top: 0px;">
        <h1>Tasks and Reminders:</h1>

        <!-- Get Reminders -->
        Reminders type:
        <select id="get-reminder-type-option" name="type">
            <option selected value="ALL">All</option>
            <option selected value="RMDR">Reminder</option>
            <option value="TASK">Task</option>
        </select>

        <button id="get-reminders-button">Get Reminders</button> = <textarea id="get-reminders-response" disabled placeholder="Response"></textarea></br></br>

        <!-- Delete Reminder -->
        <form id="delete-reminder-form">
            <input type="text" id="delete-reminder-id" placeholder="Reminder ID" name="reminder-id">
        </form> 
        <button id="delete-reminder-button">Delete Reminder</button> = <textarea id="delete-reminder-response" disabled placeholder="Response"></textarea></br></br>


        <!-- Post Reminders -->
        <div class="post-reminders-div" style="border: 1px solid black; padding: 15px; padding-top: 5px;">
            <form id="post-reminder-form">

                Reminder type:
                <select id="post-reminder-type-option" name="type">
                    <option selected value="RMDR">Reminders</option>
                    <option value="TASK">Task</option>
                </select>

                Title:
                <input type="text" name="title">

                Remind Date:
                <input type="date" name="remind-date">

                Is Recurring:
                <input type="checkbox" name="is-recurring">

                </br>

                Message (Nullable):
                <textarea name="message"></textarea>

            </form>
            <button id="post-reminder-button">Post Reminder</button> = <textarea id="post-reminder-response" disabled placeholder="Response"></textarea></br></br>
        </div>
        </br>

        <!-- Edit Reminders -->
        <div class="edit-reminders-div" style="border: 1px solid black; padding: 15px; padding-top: 5px;">
            <form id="edit-reminder-form">

                Reminder ID:
                <input type="text" name="reminder-id">

                Reminder type:
                <select id="edit-reminder-type-option" name="type">
                    <option selected value="RMDR">Reminders</option>
                    <option value="TASK">Task</option>
                </select>

                Title:
                <input type="text" name="title">

                Remind Date:
                <input type="date" name="remind-date">

                Is Recurring:
                <input type="checkbox" name="is-recurring">

                </br>

                Message (Nullable):
                <textarea name="message"></textarea>

            </form>
            <button id="edit-reminder-button">Edit Reminder</button> = <textarea id="edit-reminder-response" disabled placeholder="Response"></textarea></br></br>
        </div>
    </div>
</body>

</html>