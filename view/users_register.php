<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title> Hello notes! </title>
    <link rel = "icon" href ="icons/new_note.png" type = "image/x-icon">
    <style>
        <?php require_once __DIR__ . '/../css/style.css'; ?>
    </style>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Cinzel|Bad+Script">
</head>

<body id="body">

    <div class="login">
        <a href="index.php?rt=users/start"><h1>Hello notes!</h1></a>
        
        <br><br>
        <form action="index.php?rt=users/check_register" method="post">
            <div class="register_input">
                <h2>Registration<h2>
                <span class="span_register">
                    <input type="text" name="new_name" placeholder="Name"> <br>
                </span>
                <span class="span_register">
                    <input type="text" name="new_surname" placeholder="Surname"> <br>
                </span>
                <span class="span_register">
                    <input type="text" name="new_username" placeholder="Username"> <br>
                </span>
                <span class="span_register">
                    <input type="password" name="new_password" placeholder="Password"> <br>
                </span>
                <span class="span_register">
                    <input type="date" name="new_bdate" style="height:'24';" value="0000-00-00"> <br>
                </span>
                <span class="span_register">
                    <input type="text" name="new_about" placeholder="About..." style="height:60px"> <br>
                </span>

                <br>
                <button type="submit" id="register" name="new_user">Register</button>
            </div>
        </form>
    </div>
    <br>
    <p class="poruka_o_gresci"><?php echo $reg_message; ?></p> 

</body>
</html>
