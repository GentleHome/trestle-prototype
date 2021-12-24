<?php
require_once dirname(__FILE__) . "/./bootstrap.php";
require_once dirname(__FILE__) . "/./setup.php";
require_once dirname(__FILE__) . '/./api/helpers/db_utils.php';

session_start();
$client = get_client();
$user = get_logged_in_user($entityManager);

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
            </div>
            <!--End of Settings-->
</body>
</html>