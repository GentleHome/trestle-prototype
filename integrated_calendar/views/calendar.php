<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: ./index.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://kit.fontawesome.com/5357b59286.js" crossorigin="anonymous"></script>
    <script src="../static/script/interface/calendar-interface.js"></script>
    <script src="../static/script/models/calendar-model.js"></script>
    <script src="../static/script/calendar.js"></script>
    <link rel="stylesheet" href="../static/styles/calendar.css" />
    <title>Trestle | Calendar</title>
</head>

<body>
    <!--Start of Modal-->
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
            <a href="">Calendar</a>
            <a href="./task_checklist.php">Task Checklist</a>
            <a href="./announcements.php">Announcements</a>
            <a href="../../settings.php">Settings</a>
            <a href="../../help.php">Help</a>
            <a href="#" onclick="logout();">Logout</a>
        </nav>
    </header>
    <!--End of header-->
    <div class="wrapper">
        <div class="sidebar">
            <h2>Trestle</h2>
            <ul>
                <li><a href="../../landing.php"><i class="fas fa-home"></i>Home</a></li>
                <li class="li-active"><a class="a-active" href="#"><i class="far fa-calendar"></i>Calendar</a></li>
                <li><a href="./task_checklist.php"><i class="fas fa-tasks"></i>Task Checklist</a></li>
                <li><a href="./announcements.php"><i class="fas fa-bullhorn"></i>Announcements</a></li>
                <li><a href="../../settings.php"><i class="fas fa-cog"></i>Settings</a></li>
                <li><a href="../../help.php"><i class="far fa-question-circle"></i>Help</a></li>
                <li><a href="#" onclick="logout();"><i class="fas fa-sign-out-alt"></i>Log Out</a></li>
            </ul>
        </div>
        <!--Start of Calendar-->
        <div class="body-container" id="body-container">
            <h1 class="heading">Trestle Calendar</h1>
            <div class="calendar-container">
                <span class=dot></span>
                <div id='date'></div>

                <div class="date_navigation">
                    <label class="cview">Calendar View: </label>
                    <select class="choose_view">
                        <option value="0" selected>Year</option>
                        <option value="1">Month</option>
                        <option value="2">Week</option>
                    </select>
                    <div>
                        <button id="prev"><</button>
                                <button id="today">Today</button>
                                <button id="next">></button>
                    </div>
                </div>

                <div class="legends">
                    <label>Legend: </label>
                    <div class="def">
                        <div class="color1"></div>
                        <div class="label">
                            <p>Submitted</p>
                        </div>
                    </div>
                    <div class="def">
                        <div class="color2"></div>
                        <div class="label">
                            <p>Undone</p>
                        </div>
                    </div>
                    <div class="def">
                        <div class="color3"></div>
                        <div class="label">
                            <p>Reminder</p>
                        </div>
                    </div>
                    <div class="def">
                        <div class="color4"></div>
                        <div class="label">
                            <p>Today</p>
                        </div>
                    </div>
                    <div class="def">
                        <div class="color5"></div>
                        <div class="label">
                            <p>Selector</p>
                        </div>
                    </div>
                </div>
                <br>
                <p id="date_indication"></p>
                <div class="container"></div>
            </div>
        </div>
    </div>
</body>

</html>