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
    <!--Intro, bounce ball-->
    <div class="startbg"></div>
    <div class="container">
        <div class="logo"></div>
    </div>
    <div class="shadow"></div>
    <!--end of intro-->

    <!--MainPage Design-->
    <header>
        <section>
            <div class="hero">
                <img src="images/loginbg.png" alt="">
                <h1 class="headline">Trestle</h1>
                <br>
                <p class="desc">Be a Trestler and have E-to-Z task management from e-learning platforms such as Canvas Instructure and Google Classroom. We bridge your E-ducation by organizing your assignments, projects, and deadlines all in one place.</p>
            </div>
        </section>
    </header>
    <div class="slider"></div>
    <div class="circle1"></div>
    <div class="circle2"></div>
    <div class="circle3"></div>
    <div class="circle4"></div>
    <div class="circle5"></div>
    <div class="circle6"></div>
    <div class="circle7"></div>
    <!--end of MPdesign-->

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
                <input type="email" name="email" class="inputfield" required><span class="placeholder">Email</span>
                <input type="password" name="password1" class="inputfield" required><span class="placeholder">Password</span>
                <input type="password" name="password2" class="inputfield" required><span class="placeholder">Confirm Password</span>
                <input type="checkbox" class="checkbox"><span class="box">I Agree to the Terms & Conditions</span>
                <button type="submit" name="register" class="submitbtn">REGISTER</button>
            </form>
            <!--end of registration-->
        </div>
    </div>
    <!--end of forms-->

    <!--social media icons-->
    <div class="icons">
        <a href="#"><img class="pic1" src="images/1.png"></a>
        <a href="#"><img class="pic2" src="images/2.png"></a>
        <a href="#"><img class="pic3" src="images/3.png"></a>
    </div>
    <!--end of social media icons-->
    <!--end of Form Container-->

    <!--scripts-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/latest/TweenMax.min.js" integrity="sha512-8Wy4KH0O+AuzjMm1w5QfZ5j5/y8Q/kcUktK9mPUVaUoBvh3QPUZB822W/vy7ULqri3yR8daH3F58+Y8Z08qzeg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/latest/TimelineMax.min.js" integrity="sha512-lJDBw/vKlGO8aIZB8/6CY4lV+EMAL3qzViHid6wXjH/uDrqUl+uvfCROHXAEL0T/bgdAQHSuE68vRlcFHUdrUw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="designscript.js"></script>
    <!--end of scripts-->
</body>

</html>