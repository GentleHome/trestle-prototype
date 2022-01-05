<?php
require_once dirname(__FILE__) . "/./bootstrap.php";
require_once dirname(__FILE__) . "/./setup.php";
require_once dirname(__FILE__) . '/./api/helpers/db_utils.php';
require_once dirname(__FILE__) . '/./api/helpers/constants.php';


session_start();
$client = get_client();
$user = get_logged_in_user($entityManager);
echo "USER ID: ".$_SESSION[USER_ID];
if (is_null($user)) {
    header("Location: ./forms.php");
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="settings.css">
    <script src="https://kit.fontawesome.com/5357b59286.js" crossorigin="anonymous"></script>
    <title>Trestle | Settings</title>
</head>
<body>
    <!--Start of header-->
    <header id="navigation">
        <a href="landingpage/landing.html" class="logo">Trestle</a>
        <input type="checkbox" id="menu-bar">
        <label for="menu-bar" class="fas fa-bars"></label>
        <nav class="navbar">
            <a href="#home">Home</a>
            <a href="calendar.html">Calendar</a>
            <a href="#taskchecklist">Task Checklist</a>
            <a href="#announcements">Announcements</a>
            <a href="#settings">Settings</a>
            <a href="#help">Help</a>
            <a href="#logout">Logout</a>
        </nav>
    </header>
    <!--End of header-->
        <div class="wrapper">
            <div class="sidebar">
                <h2>Trestle</h2>
                <ul>
                    <li><a href="#"><i class="fas fa-home"></i>Home</a></li>
                    <li><a href="#"><i class="far fa-calendar"></i>Calendar</a></li>
                    <li><a href="#"><i class="fas fa-tasks"></i>Task Checklist</a></li>
                    <li><a href="#"><i class="fas fa-bullhorn"></i>Announcements</a></li>
                    <li class="li-active"><a class="a-active" href="#"><i class="fas fa-cog"></i>Settings</a></li>
                    <li><a href="#"><i class="far fa-question-circle"></i>Help</a></li>
                    <li><a href="#"><i class="fas fa-sign-out-alt"></i>Log Out</a></li>
                </ul>
            </div>

            <!--Start of Settings-->
            <div class="body-container">
                <h1 class="heading">Trestle Settings</h1>
                <div class="settings-container">

                    <div class="gear">
                        <i id="gear" class="fas fa-cog"></i>
                    </div>

                    <div class="tokens">
                        <div class="col1">
                            <div class="labels">
                                <label>Current Canvas Token: </label>
                            </div>
                            <div class="code">
                                <p><?php if (!is_null($current_canvas_token)) { ?>
                                    <span> <?php echo htmlspecialchars($current_canvas_token, ENT_QUOTES, 'UTF-8'); ?></span>
                                    </br>
                                    <?php } ?>
                                </p>
                            </div>
                            <div class="icon">
                                <button class="copybtn"><i class="far fa-copy"></i></button>
                            </div>
                        </div>

                        <div class="col2">
                            <div class="labels">
                                <label>Teacher Token (sample): </label>
                            </div>
                            <div class="code">
                                <p>7~eP0MHZP46nz5Y3oNNkLeCA7i4Zo5b2kojJI5V0gZeoN2RTmrNYyWIHtFI37Qou0q</p>
                            </div>
                            <div class="icon">
                                <button class="copybtn"><i class="far fa-copy"></i></button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form">
                        <div class="field">
                            <form action="./set_canvas_token.php" method="POST">
                            <input id="setfield" type="text" name="code">
                        </div>
                        <div class="setbtn">
                            <button class="set-token" type="submit">Set Canvas Token</button>
                            </form>
                        </div>
                    </div>
                    
                    <div class="current">
                        <div class="col1">
                            <div class="labels">
                                <label>Current Google Token: </label>
                            </div>
                        </div>
                        <div class="col2">
                            <div class="code">
                                <?php if (!is_null($current_google_token)) { ?>
                                <p><?php echo $current_google_token["access_token"]; ?></p>
                                </br>
                            </div>
                        </div>
                    </div>
                    <div class="toggle">
                        <a href="./revoke.php"><button class="revoke">Revoke Access</button></a>
                        <?php } else { ?>
                        <a href="<?php echo $auth_url; ?>"><button class="grant">Grant Access</button></a>
                        <?php } ?>
                    </div>
                </div>
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
                function changePassword(){
                    let current = $("#current-pass").val(); 
                    let newpass = $("#new-pass").val(); 
                    let confirmpass = $("#confirm-pass").val();
                    if(current != "" && current != null && confirmpass != "" && confirmpass != null && newpass != "" && newpass != null){
                        if(newpass == confirmpass){
                            console.log(current, newpass, confirmpass);
                            $.ajax({
                            method: "POST",
                            url: "./api/change_password.php",
                            data: {
                                currentPassword : current,
                                newPassword1: newpass,
                                newPassword2: confirmpass,
                            },
                            success: function (data) {
                                console.log(data);
                                let newdata = JSON.parse(data);

                                if(newdata.hasOwnProperty("messages")){
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
                                }else{
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
            <!-- Change Password Form -->
            <div id="change_password_container" style="margin-left: 200px">
            <h2>Password Settings</h2>
                <form method="POST" onsubmit="changePassword(); return false" >
                    <input type="password" name="current" id="current-pass" required><br>
                    <input type="password" name="new" id="new-pass" onkeyup="checkPassword();" required><br>
                    <input type="password" name="confirm" id="confirm-pass" onkeyup="checkPassword();" required><br>
                    <p id="error-message" style="color: red;"></p>
                    <input type="submit" value="Save Changes">
                    <input type="reset" value="Cancel">
                </form>
            </div>
            </div>
            <br><br><br>
                
            <!--End of Settings-->
</body>
</html>