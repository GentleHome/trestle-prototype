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
    <link rel="stylesheet" href="../static/styles/tasks.css">
    <script src="https://kit.fontawesome.com/5357b59286.js" crossorigin="anonymous"></script>
    <script src="../static/script/task-checklist.js" async></script>
    <title>Trestle | Task Checklist</title>
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

    <!--Modal div-->
    <div class='add_sched_modal'>
        <div class='modal_content'>
        </div>
    </div>
    <!--Start of header-->
    <header id="navigation">
        <a href="../../landing.php" class="logo">Trestle</a>
        <input type="checkbox" id="menu-bar">
        <label for="menu-bar" class="fas fa-bars"></label>
        <nav class="navbar">
            <a href="../../landing.php">Home</a>
            <a href="./calendar.php">Calendar</a>
            <a href="#taskchecklist">Task Checklist</a>
            <a href="./announcements.php">Announcements</a>
            <a href="../../settings.php">Settings</a>
            <a href="../../help.php">Help</a>
            <a href="#" onclick="logout();">Logout</a>
        </nav>
    </header>
    <!--End of header-->
    <div class="wrapper">
        <div class="sidebar">
            <h2><a href="../../landing.php">Trestle</a></h2>
            <ul>
                <li><a href="../../landing.php"><i class="fas fa-home"></i>Home</a></li>
                <li><a href="./calendar.php"><i class="far fa-calendar"></i>Calendar</a></li>
                <li class="li-active"><a class="a-active" href="#"><i class="fas fa-tasks"></i>Task Checklist</a></li>
                <li><a href="./announcements.php"><i class="fas fa-bullhorn"></i>Announcements</a></li>
                <li><a href="../../settings.php"><i class="fas fa-cog"></i>Settings</a></li>
                <li><a href="../../help.php"><i class="far fa-question-circle"></i>Help</a></li>
                <li><a href="#" onclick="logout();"><i class="fas fa-sign-out-alt"></i>Log Out</a></li>
            </ul>
        </div>
        <!--Start of TASK BODY-->
        <div class="body-container" id="body-container">
            <h1 class="heading">Task Checklist</h1>
            <div class="task-container">
                <label class="cview">Have an Activity in mind? </label>
                <button id="add_task">Create Task</button>
                <div id="tasks_section">
                </div>
            </div>
        </div>
        <!--End of TASK-->
    </div>
</body>

</html>