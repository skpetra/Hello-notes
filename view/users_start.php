<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hello notes</title>
    <link rel = "icon" href ="icons/new_note.png" type = "image/x-icon">
    <style>
        <?php require_once __DIR__ . '/../css/style.css'; ?>
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.js"></script>
    <script src="javascript/users_start.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Cinzel|Bad+Script">
</head>

<body id="body">
    <div id="start">

        <h1>Hello notes!</h1>
        <br><br>

        <span class="start_element">
        <a href="index.php?rt=users/login" id="start_login">Login</a>
        </span>

        <span class="start_element">
        <a href="index.php?rt=users/register" id="start_register">Register</a>
        </span>

        <button class="question" id="help_reg" style="display: inline;">?</button>
        <div class="div_help_reg" style="display:none;">
            Registration to this site is easy. You just need to choose a username at
            least 6 characters long, containing at least one letter and one number
            and choose a strong password at least 6 characters long, containing at least
            one letter and one number.
            <br>
            You can also log in using your <i>Facebook</i> credentials.
        </div>
    </div>
</body>
</html>