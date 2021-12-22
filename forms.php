<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Trestle</title>
</head>

<body>
    <!--Form Container-->
    <div class="formbox">
        <div class="buttonbox">
            <div id="btn"></div>
            <button type="button" class="togglebutton" onclick="login()">LOGIN</button>
            <button type="button" class="togglebutton" onclick="register()">REGISTER</button>

            <!--forms-->
            <!--login-->
            <form id="login" class="inputgroup" action="./login.php" method="POST">
                <input type="text" name="username" class="inputfield" required><span class="placeholder">Username</span>
                <input type="password" name="password" class="inputfield" required><span class="placeholder">Password</span>
                <input type="checkbox" class="checkbox"><span class="box">Remember Password</span>
                <button type="submit" name="login" value="login" class="submitbtn">LOGIN</button>
            </form>
            <!--end of login-->

            <!--registration-->
            <form id="register" class="inputgroup" action="./register.php" method="POST">
                <input type="text" name="username" class="inputfield" required><span class="placeholder">Username</span>
                <input type="password" name="password1" class="inputfield" required><span class="placeholder">Password</span>
                <input type="password" name="password2" class="inputfield" required><span class="placeholder">Confirm Password</span>
                <button type="submit" name="register" class="submitbtn">REGISTER</button>
            </form>
            <!--end of registration-->
        </div>
    </div>
    <!--end of forms-->
</body>

</html>