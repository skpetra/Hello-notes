<?php require_once __DIR__ . '/__header.php';?>

<div class="update_user_details">
    <h2>Update user details</h2> 

    <form method="post" action="index.php?rt=users/update_details" >

            <strong>Name:</strong> <input type="text" name="update_name" value=<?php echo '"' . $name . '"'; ?>> <br>

            <strong>Surname:</strong> <input type="text" name="update_surname" value=<?php echo '"' . $surname . '"'; ?>> <br>

            <?php if(!isset($_SESSION['name'])){ ?>
                <strong>Username:</strong> <input type="text" name="update_username" value=<?php echo '"' . $_SESSION['username'] . '"';?>> <br>
            <?php 
            }
            ?>
            <strong>Birth date:</strong> <input style="color:black"type="date" name="update_bdate" value=<?php echo '"' . $birth_date . '"'; ?> > <br>

            <strong>About:</strong> <input type="text" name="update_about" style="height: 60px"value= <?php echo '"' . $about . '"'; ?>> <br>
        <br>
        <p class="center">
            <button type="submit" name="submit">Update</button>
            <a href='index.php?rt=users/show_user_notes' id="close_user_details">Close</a>
        </p>
    </form>
</div>

<?php require_once __DIR__ . '/__footer.php';?>