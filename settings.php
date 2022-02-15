<?php
require_once dirname(__FILE__) . "/./bootstrap.php";
require_once dirname(__FILE__) . "/./setup.php";
require_once dirname(__FILE__) . '/./api/helpers/db_utils.php';
require_once dirname(__FILE__) . '/./api/helpers/constants.php';


session_start();
$client = get_client();
$user = get_logged_in_user($entityManager);

if (is_null($user)) {
    header("Location: ./index.php");
    exit;
}

$current_canvas_token = $user->get_canvas_token();
$current_google_token = $user->get_google_token();
$auth_url = $client->createAuthUrl();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="settings.css">
    <script src="https://kit.fontawesome.com/5357b59286.js" crossorigin="anonymous"></script>
    <title>Trestle | Settings</title>
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
                        url: './logout.php',
                        success: function() {
                            window.location.replace('./index.php');
                        }
                    })
                }
            })
        }
    </script>

    <!--Start of header-->
    <header id="navigation">
        <a href="landingpage/landing.php" class="logo">Trestle</a>
        <input type="checkbox" id="menu-bar">
        <label for="menu-bar" class="fas fa-bars"></label>
        <nav class="navbar">
            <a href="./integrated_calendar/views/calendar.php">Calendar</a>
            <a href="./integrated_calendar/views/task_checklist.php">Task Checklist</a>
            <a href="./integrated_calendar/views/announcements.php">Announcements</a>
            <a href="#">Settings</a>
            <a href="./help.php">Help</a>
            <a href="#" onclick="logout();">Logout</a>
        </nav>
    </header>
    <!--End of header-->



    <div class="wrapper">
        <div class="sidebar">
            <h2>Trestle</h2>
            <ul>
                <li><a href="./integrated_calendar/views/calendar.php"><i class="far fa-calendar"></i>Calendar</a></li>
                <li><a href="./integrated_calendar/views/task_checklist.php"><i class="fas fa-tasks"></i>Task Checklist</a></li>
                <li><a href="./integrated_calendar/views/announcements.php"><i class="fas fa-bullhorn"></i>Announcements</a></li>
                <li class="li-active"><a class="a-active" href="#"><i class="fas fa-cog"></i>Settings</a></li>
                <li><a href="./help.php"><i class="far fa-question-circle"></i>Help</a></li>
                <li><a href="#" onclick="logout();"><i class="fas fa-sign-out-alt"></i>Log Out</a></li>
            </ul>
        </div>

        <!--Start of Settings-->
        <div class="body-container">

            <h1 class="heading">Trestle Settings</h1>

            <div class="settings-container">
                <div class="gear">
                    <i class="fas fa-cog"></i>
                </div>
                <h2>User Information</h2>
                <div class="user-container">
                    <div class="user-name">
                        <label>Username: </label>
                        <p><?php echo $user->get_username(); ?></p>
                    </div>

                    <!-- Change Password Form -->
                    <div id="change_password_container">
                        <h2>Password Settings</h2>
                        <form method="POST" onsubmit="changePassword(); return false">
                            <div class="form-div"><label for="current">Current Password: </label><input type="password" name="current" id="current-pass" autocomplete="off" required></div>
                            <div class="form-div"><label for="new">New Password: </label><input type="password" name="new" id="new-pass" onkeyup="checkPassword();" required></div>
                            <div class="form-div"><label for="confirm">Confirm password: </label><input type="password" name="confirm" id="confirm-pass" onkeyup="checkPassword();" required></div>
                            <p id="error-message" style="color: red;"></p>
                            <div class="button-container">
                                <input class="save" type="submit" value="Save Changes">
                                <input class="cancel" type="reset" value="Cancel">
                            </div>
                        </form>
                    </div>

                </div>
                <h2>Token Settings</h2>
                <div class="token-container">
                    <!--Canvas-->
                    <div class="canvas-token">

                        <div class="set-image">
                            <img src="images/canvas.png" alt="">
                        </div>

                        <div class="set-form">
                            <form action="./set_canvas_token.php" method="POST">
                                <input class="token-field" id="setfield" type="text" name="code" placeholder="Paste token here">
                                <button class="set-token" type="submit">Set Canvas Token</button>
                            </form>
                        </div>

                    </div>
                    <!--Google-->

                    <!-- Revoke access script -->
                    <script>
                        let revokeAccess = () => {
                            Swal.fire({
                                title: 'Are you sure you want to REVOKE access?',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Confirm'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.href = './revoke.php';
                                }
                            })
                        }
                    </script>
                    <!-- end of revoke access script -->
                    <div class="google-token">

                        <div class="set-image">
                            <img src="images/classroom.png" alt="">
                        </div>

                        <div class="code" style="display:block">
                            <?php if (!is_null($current_google_token)) { ?>
                                <p style="display:none"><?php echo $current_google_token["access_token"]; ?></p>
                                </br>
                        </div>

                        <button onclick="revokeAccess();" class="revoke">Revoke Access</button>
                    <?php } else { ?>
                        <a href="<?php echo $auth_url; ?>"><button class="grant">Grant Access</button></a>
                    <?php } ?>

                    </div>
                </div>
            </div>
        </div>
        <!--End of Settings-->
        <!-- Change Password Script -->
        <script>
            function checkPassword() {
                let newpass = $("#new-pass").val();
                let confirmpass = $("#confirm-pass").val();
                if ((newpass != "") && (confirmpass != "") && newpass != confirmpass) {
                    $('#error-message').text('Password does not match.');
                } else {
                    $('#error-message').text('');
                }
            }

            function changePassword() {
                let current = $("#current-pass").val();
                let newpass = $("#new-pass").val();
                let confirmpass = $("#confirm-pass").val();
                if (current != "" && current != null && confirmpass != "" && confirmpass != null && newpass != "" && newpass != null) {
                    if (newpass == confirmpass) {
                        console.log(current, newpass, confirmpass);
                        $.ajax({
                            method: "POST",
                            url: "./api/change_password.php",
                            data: {
                                currentPassword: current,
                                newPassword1: newpass,
                                newPassword2: confirmpass,
                            },
                            success: function(data) {
                                console.log(data);
                                let newdata = JSON.parse(data);

                                if (newdata.hasOwnProperty("messages")) {
                                    $("#current-pass").val("");
                                    $("#new-pass").val("");
                                    $("#confirm-pass").val("");
                                    $("#error-message").text("");
                                    Swal.fire({
                                        icon: 'success',
                                        title: newdata.messages,
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                } else {
                                    $("#error-message").text("");
                                    Swal.fire({
                                        icon: 'error',
                                        title: newdata.errors,
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                }
                            }
                        });
                    }
                }
            }
        </script>
</body>

</html>