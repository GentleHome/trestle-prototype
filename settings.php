<?php
require_once dirname(__FILE__) . "./bootstrap.php";
require_once dirname(__FILE__) . "./setup.php";
require_once dirname(__FILE__) . './api/helpers/db_utils.php';

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
    <title>Settings</title>
</head>

<body>

    <?php if (!is_null($current_canvas_token)) { ?>
        <span>Current Canvas Token: <?php echo $current_canvas_token; ?></span>
        </br>
    <?php } ?>
    Teacher Token(sample): 7~eP0MHZP46nz5Y3oNNkLeCA7i4Zo5b2kojJI5V0gZeoN2RTmrNYyWIHtFI37Qou0q</br>
    <form action="./set_canvas_token.php" method="POST">
        <input type="text" name="code">
        <input type="submit" value="Set Canvas Token">
    </form>
    <?php if (!is_null($current_google_token)) { ?>
        <span>Current Google Token: <?php echo $current_google_token["access_token"]; ?></span>
        </br>
        <a href="./revoke.php"><button>Revoke Access</button></a>
    <?php } else { ?>
        <a href="<?php echo $auth_url; ?>"><button>Grant Access</button></a>
    <?php } ?>
    </br>
    <a href="./api/tests.php">Test</a>
</body>

</html>