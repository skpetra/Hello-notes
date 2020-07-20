<?php require_once __DIR__ . '/__header.php';?>

<h2>Dobrodošli, 
    <?php 
        // facebook korisniku je u SESSIONU spremljeno ime 
        if(isset($_SESSION['name']))
            echo '<a href="index.php?rt=users/show_user_details" id="username">' . $_SESSION['name'] . '</a>';

        else echo '<a href="index.php?rt=users/show_user_details" id="username">' . $_SESSION['username'] . '</a>';

    ?>
</h2> 

<?php require_once __DIR__ . '/__footer.php';?>