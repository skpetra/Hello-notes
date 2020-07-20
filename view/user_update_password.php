<?php require_once __DIR__ . '/__header.php';?>

<div class="update_pass">

    <h2>Update password</h2> 

    <form method="post" action="index.php?rt=users/update_password" >

        <strong>New password:</strong> <input type="password" name="update_password"> <br>
        <strong>Confirm password:</strong> <input type="password" name="confirm_password"> <br>
        <br>
        <p class="center">
            <button type="submit" name="submit">Update</button>
            <a href='index.php?rt=users/show_user_notes' id="close_user_details">Close</a>
        </p>
    </form>
</div>

<?php require_once __DIR__ . '/__footer.php';?>