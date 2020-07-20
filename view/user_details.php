<?php require_once __DIR__ . '/__header.php';?>

<div class="user_details">

	<h2 style="color: white">User details</h2> 

	<strong>Name:</strong> <?php echo $name; ?> <br>

	<strong>Surname:</strong> <?php echo $surname; ?> <br>

	<strong>Username:</strong> <?php echo $_SESSION['username']; ?><br>

	<?php if($birth_date!='') echo '<strong>Birthday:</strong> ' . $birth_date . '<br>'; ?>
			
	<?php if($about!='') echo '<strong>About: </strong>' . $about . '<br>'; ?>

	<br>
	<!-- azuriranje -->
	<p class="center">
		<a href='index.php?rt=users/show_update_details' ><img src='icons/update_details.jpg' height=30 width=30 style="border-radius: 5px;"/></a>

		<a href='index.php?rt=users/show_user_notes' id="close_user_details" style="color:black; border-radius: 5px; background-color: white; padding:7px;">Close</a>
	</p>
</div>

<?php require_once __DIR__ . '/__footer.php';?>
