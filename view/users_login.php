<?php

require_once __DIR__ . '/../fb-login/configure.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title> Hello notes! </title>
    <link rel = "icon" href ="icons/new_note.png" type = "image/x-icon">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.js"></script>
    <style>
        <?php require_once __DIR__ . '/../css/style.css'; ?>
    </style>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Cinzel|Bad+Script">
</head>

<body id="body">

    <div class="login">
    <a href="index.php?rt=users/start"><h1>Hello notes!</h1></a>
    <br>
    <br>
    <form action="index.php?rt=users/check_login" method="post">
        <div class="login_input">
            <h2>Login</h2>
            <span class="span_login">
            <input type="text" name="username" height="20" placeholder="Username"> <br>
            </span>

            <span class="span_login">
            <input type="password" name="password" placeholder="Password"> <br><br>
            </span>
            <button type="submit" id="log_in" name="log_in">Login</button> <br>

            <br> OR <br>

            <h2> Facebook login </h2>
            <button id= "fb_login"><a href="<?php echo $login_url ?>" id="fb_login">Login</a></button>
        </div>
    </form>
    </div>
    <br>
    <p class="poruka_o_gresci" style="color:rgb(168, 7, 58);"><?php echo $login_message; ?></p> 

</body>
</html>




