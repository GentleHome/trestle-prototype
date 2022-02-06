<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: ./index.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="help.css">
    <script src="https://kit.fontawesome.com/5357b59286.js" crossorigin="anonymous"></script>
    <title>Trestle | Help</title>
</head>

<body>
    <!-- Function for logout -->
    <script>
        function logout() {
            Swal.fire({
                title: 'Are you sure you want to log out?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirm'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        method: 'POST',
                        url: '../../logout.php',
                        success: function () {
                            window.location.replace('../../index.php');
                        }
                    })
                }
            })
        }
    </script>

    <!--Start of header-->
    <header id="navigation">
        <a href="./landing.php" class="logo">Trestle</a>
        <input type="checkbox" id="menu-bar">
        <label for="menu-bar" class="fas fa-bars"></label>
        <nav class="navbar">
            <a href="./landing.php">Home</a>
            <a href="./integrated_calendar/views/calendar.php">Calendar</a>
            <a href="./integrated_calendar/views/task_checklist.php">Task Checklist</a>
            <a href="./integrated_calendar/views/announcements.php">Announcements</a>
            <a href="./settings.php">Settings</a>
            <a href="#help">Help</a>
            <a href="#" onclick="logout();">Logout</a>
        </nav>
    </header>
    <!--End of header-->
    <div class="wrapper">
        <div class="sidebar">
            <h2><a href="./landing.php">Trestle</a></h2>
            <ul>
                <li><a href="./landing.php"><i class="fas fa-home"></i>Home</a></li>
                <li><a href="./integrated_calendar/views/calendar.php"><i class="far fa-calendar"></i>Calendar</a></li>
                <li><a href="./integrated_calendar/views/task_checklist.php"><i class="fas fa-tasks"></i>Task Checklist</a></li>
                <li><a href="./integrated_calendar/views/announcements.php"><i class="fas fa-bullhorn"></i>Announcements</a></li>
                <li><a href="./settings.php"><i class="fas fa-cog"></i>Settings</a></li>
                <li class="li-active"><a class="a-active" href="#help"><i class="far fa-question-circle"></i>Help</a></li>
                <li><a href="#" onclick="logout();"><i class="fas fa-sign-out-alt"></i>Log Out</a></li>
            </ul>
        </div>
        <!--Start of HELP BODY-->
        <div class="body-container" id="body-container">
            <h1 class="heading">Trestle Help</h1>
            <div class="help-container">

                <div class="help-nav">

                  <div class="label">
                    <a href="#connect-canvas">How to Connect Canvas?</a>
                  </div>

                  <div class="label">
                    <a href="#connect-classroom">How to Connect Classroom?</a>
                  </div>

                  <div class="label">
                    <a href="#addsched">How to Add Schedule on Calendar?</a>
                  </div>

                  <div class="label">
                    <a href="#updatesched">How to Update Schedule on Calendar?</a>
                  </div>

                  <div class="label">
                    <a href="#createtask">How to Create a Task on Task Checklist?</a>
                  </div>

                  <div class="label">
                    <a href="#edittask">How to Edit a Task on Task Checklist?</a>
                  </div>

                </div>
            <br>

                <!--Help cards-->
                <!--Start of Canvas help-->
                <div id="help-wrapper">
                  <h2 id="connect-canvas">How to Connect Canvas?</h2>

                  <section class="card1">
                    <div class="help-image">
                      <img src="help_canvas/step1.png" alt="">
                    </div>
                    <div>
                      <div class="number"><h3>Step </h3><span>01</span></div>
                      <p>Go to canvas instructure log in page <span><a href="https://canvas.instructure.com/" target="_blank">https://canvas.instructure.com</a></span> and input your user information.</p>
                    </div>
                  </section>

                  <section class="card2">
                    <div>
                      <div class="number"><span>02</span><h3>Step </h3></div>
                      <p>Once you're on your dashboard, click "Accounts" from the left sidebar to view your account settings.</p>
                    </div>
                    <div class="help-image">
                      <img src="help_canvas/step2.png" alt="">
                    </div>
                  </section>

                  <section class="card1">
                    <div class="help-image">
                      <img src="help_canvas/step3.png" alt="">
                    </div>
                    <div>
                      <div class="number"><h3>Step </h3><span>03</span></div>
                      <p>After you clicked "Account" a pop-up menu will appear, from there, click "Settings".</p>
                    </div>
                  </section>

                  <section class="card2">
                    <div>
                      <div class="number"><span>04</span><h3>Step </h3></div>
                      <p>Once you're on settings, scroll down and look for "Approved Integrations" to generate your canvas token.</p>
                    </div>
                    <div class="help-image">
                      <img src="help_canvas/step4.png" alt="">
                    </div>
                  </section>

                  <section class="card1">
                    <div class="help-image">
                      <img src="help_canvas/step5.png" alt="">
                    </div>
                    <div>
                      <div class="number"><h3>Step </h3><span>05</span></div>
                      <p>At "Approved Integrations", click the "New Access token" button. A pop-up modal will appear asking you for some information.</p>
                    </div>
                  </section>

                  <section class="card2">
                    <div>
                      <div class="number"><span>06</span><h3>Step </h3></div>
                      <p>On the "Purpose" input-field, enter the word "Trestle" as you will use the generated token for your Trestle account. Click "Generate Token" after you're done.</p>
                    </div>
                    <div class="help-image">
                      <img src="help_canvas/step6.png" alt="">
                    </div>
                  </section>

                  <section class="card1">
                    <div class="help-image">
                      <img src="help_canvas/step7.png" alt="">
                    </div>
                    <div>
                      <div class="number"><h3>Step </h3><span>07</span></div>
                      <p>After canvas successfully generates your token, copy the whole line as you see on the image and proceed to Trestle settings.</p>
                    </div>
                  </section>

                  <section class="card2">
                    <div>
                      <div class="number"><span>08</span><h3>Step </h3></div>
                      <p>Paste the token at the input-field below the canvas logo and click "Set Canvas Token". After that, your canvas account is now connected to your Trestle account.</p>
                    </div>
                    <div class="help-image">
                      <img src="help_canvas/step8.png" alt="">
                    </div>
                  </section>
                </div>
                <!--End of Canvas help-->

                <!--Start of Classroom help-->
                <div id="help-wrapper">
                  <h2 id="connect-classroom">How to Connect Classroom?</h2>

                  <section class="card1">
                    <div class="help-image">
                      <img src="help_classroom/step1.png" alt="">
                    </div>
                    <div>
                      <div class="number"><h3>Step </h3><span>01</span></div>
                      <p>To connect your Google Classroom account, click "Grant Access" from Trestle settings.</p>
                    </div>
                  </section>

                  <section class="card2">
                    <div>
                      <div class="number"><span>02</span><h3>Step </h3></div>
                      <p>You will be asked to log in your google account.</p>
                    </div>
                    <div class="help-image">
                      <img src="help_classroom/step2.png" alt="">
                    </div>
                  </section>

                  <section class="card1">
                    <div class="help-image">
                      <img src="help_classroom/step3.png" alt="">
                    </div>
                    <div>
                      <div class="number"><h3>Step </h3><span>03</span></div>
                      <p>After logging in, click continue to verify that you trust Trestle to handle your information.</p>
                    </div>
                  </section>

                  <section class="card2">
                    <div>
                      <div class="number"><span>04</span><h3>Step </h3></div>
                      <p>Click all the checkbox to let Trestle handle and manage your Classroom Announcements, Schoolworks, and Classes.</p>
                    </div>
                    <div class="help-image">
                      <img src="help_classroom/step4.png" alt="">
                    </div>
                  </section>

                  <section class="card1">
                    <div class="help-image">
                      <img src="help_classroom/step5.png" alt="">
                    </div>
                    <div>
                      <div class="number"><h3>Step </h3><span>05</span></div>
                      <p>Click "Continue" after you checked all the boxes needed.</p>
                    </div>
                  </section>

                  <section class="card2">
                    <div>
                      <div class="number"><span>06</span><h3>Step </h3></div>
                      <p>Once "Grant Access" button changed to "Revoke Access", it means you successfully connected your Google Classroom Account.</p>
                    </div>
                    <div class="help-image">
                      <img src="help_classroom/step6.png" alt="">
                    </div>
                  </section>
                </div>
                <!--End of Classroom help-->

                <!--Start of Add Schedule help-->
                <div id="help-wrapper">
                  <h2 id="addsched">How to Add Schedule on Calendar?</h2>

                  <section class="card1">
                    <div class="help-image">
                      <img src="help_addsched/step1.png" alt="">
                    </div>
                    <div>
                      <div class="number"><h3>Step </h3><span>01</span></div>
                      <p>On the Calendar, click any date to start adding or updating schedules.</p>
                    </div>
                  </section>

                  <section class="card2">
                    <div>
                      <div class="number"><span>02</span><h3>Step </h3></div>
                      <p>After clicking a date, a pop-up modal will appear. Click "Add a Schedule" to proceed.</p>
                    </div>
                    <div class="help-image">
                      <img src="help_addsched/step2.png" alt="">
                    </div>
                  </section>

                  <section class="card1">
                    <div class="help-image">
                      <img src="help_addsched/step3.png" alt="">
                    </div>
                    <div>
                      <div class="number"><h3>Step </h3><span>03</span></div>
                      <p>Select the type of Schedule you want to add from "Reminder" to "Task".</p>
                    </div>
                  </section>

                  <section class="card2">
                    <div>
                      <div class="number"><span>04</span><h3>Step </h3></div>
                      <p>The difference between Reminders and Task is that reminders are simple note on your calendar to help you remember, while a Task is a personalized activity that you want to do.</p>
                    </div>
                    <div class="help-image">
                      <img src="help_addsched/step4.png" alt="">
                    </div>
                  </section>

                  <section class="card1">
                    <div class="help-image">
                      <img src="help_addsched/step5.png" alt="">
                    </div>
                    <div>
                      <div class="number"><h3>Step </h3><span>05</span></div>
                      <p>After selecting the type, fill-up the rest of the input fields.</p>
                    </div>
                  </section>

                  <section class="card2">
                    <div>
                      <div class="number"><span>06</span><h3>Step </h3></div>
                      <p>After you are done, click the "Post Reminder" button to submit your personalized schedule.</p>
                    </div>
                    <div class="help-image">
                      <img src="help_addsched/step6.png" alt="">
                    </div>
                  </section>
                </div>
                <!--End of Add sched help-->

                <!--Start of Update Schedule help-->
                <div id="help-wrapper">
                  <h2 id="updatesched">How to Update Schedule on Calendar?</h2>

                  <section class="card1">
                    <div class="help-image">
                      <img src="help_update/step1.png" alt="">
                    </div>
                    <div>
                      <div class="number"><h3>Step </h3><span>01</span></div>
                      <p>Click any existing Task or Reminder from the Calendar.</p>
                    </div>
                  </section>

                  <section class="card2">
                    <div>
                      <div class="number"><span>02</span><h3>Step </h3></div>
                      <p>Select the "Update" button to proceed on information editing.</p>
                    </div>
                    <div class="help-image">
                      <img src="help_update/step2.png" alt="">
                    </div>
                  </section>

                  <section class="card1">
                    <div class="help-image">
                      <img src="help_update/step3.png" alt="">
                    </div>
                    <div>
                      <div class="number"><h3>Step </h3><span>03</span></div>
                      <p>Edit all the information you want to change inside the input-fields.</p>
                    </div>
                  </section>

                  <section class="card2">
                    <div>
                      <div class="number"><span>04</span><h3>Step </h3></div>
                      <p>After you are done editing, click "Edit Reminder" to update your schedule.</p>
                    </div>
                    <div class="help-image">
                      <img src="help_update/step4.png" alt="">
                    </div>
                  </section>
                </div>
                <!--End of update sched help-->

                <!--Start of create task help-->
                <div id="help-wrapper">
                  <h2 id="createtask">How to Create a Task on Task Checklist?</h2>

                  <section class="card1">
                    <div class="help-image">
                      <img src="help_create/step1.png" alt="">
                    </div>
                    <div>
                      <div class="number"><h3>Step </h3><span>01</span></div>
                      <p>On the Task Checklist Page, click "Create Task" button.</p>
                    </div>
                  </section>

                  <section class="card2">
                    <div>
                      <div class="number"><span>02</span><h3>Step </h3></div>
                      <p>Fill-up the necessary information for the input-fields.</p>
                    </div>
                    <div class="help-image">
                      <img src="help_create/step2.png" alt="">
                    </div>
                  </section>

                  <section class="card1">
                    <div class="help-image">
                      <img src="help_create/step3.png" alt="">
                    </div>
                    <div>
                      <div class="number"><h3>Step </h3><span>03</span></div>
                      <p>After you are done on the information for your task, click "Create Task".</p>
                    </div>
                  </section>
                </div>
                <!--End of create task help-->

                <!--Start of edit task help-->
                <div id="help-wrapper">
                  <h2 id="edittask">How to Edit a Task on Task Checklist?</h2>

                  <section class="card1">
                    <div class="help-image">
                      <img src="help_edit/step1.png" alt="">
                    </div>
                    <div>
                      <div class="number"><h3>Step </h3><span>01</span></div>
                      <p>Click the "Edit" button of the task that you want to edit.</p>
                    </div>
                  </section>

                  <section class="card2">
                    <div>
                      <div class="number"><span>02</span><h3>Step </h3></div>
                      <p>You can change the information on the input-fields as you like.</p>
                    </div>
                    <div class="help-image">
                      <img src="help_edit/step2.png" alt="">
                    </div>
                  </section>

                  <section class="card1">
                    <div class="help-image">
                      <img src="help_edit/step3.png" alt="">
                    </div>
                    <div>
                      <div class="number"><h3>Step </h3><span>03</span></div>
                      <p>After you are done editing the information for your task, click "Save Changes".</p>
                    </div>
                  </section>
                </div>
                <!--End of edit task help-->

            </div>
        </div>
    </div>
</body>

</html>