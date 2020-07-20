<!-- _header je kad je korisnik ulogiran -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hello notes!</title>
    <link rel = "icon" href ="icons/new_note.jpg" type = "image/x-icon">
 	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Cinzel|Bad+Script">
	<script src="javascript/__header.js"></script>

	<style>
		<?php require_once __DIR__ . '/../css/style.css'; ?>
  	</style>
</head>

<body>
	<div class="vl"></div>

		<div class="top_right">
		
			<div class="dropdown">
				<button class="btn-primary" id="menu1" type="button" data-toggle="dropdown">
				<?php 
					if(!isset($_SESSION['name']))
						echo $_SESSION['username'];
					else echo $_SESSION['name'];
				?>
				</button>

				<ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
					<li role="presentation"><a role="menuitem" tabindex="-1" href="index.php?rt=users/show_user_details">My details</a></li>
					<?php if(!isset($_SESSION['name'])){ ?>
					<li role="presentation"><a role="menuitem" tabindex="-1" href="index.php?rt=users/show_update_password">New password</a></li>
					<?php } ?>
				</ul>
			</div>

			<a href="index.php?rt=users/how_to" class="question" style="padding:5px; text-decoration: none;color:black;"><strong>?</strong></a>
			<a href='index.php?rt=users/logout' style="padding:5px; text-decoration: none; color:black;"><strong>X</strong></a>
		<div>
	
		<div class="title">
			<a href="index.php?rt=users/show_user_notes">
				<h1 id="center">Hello notes!</h1>
			</a>

			<!-- Forma za pretragu bilješki -->
			<form action="index.php?rt=search/search_notes" method="post">
			<input type="text" name="search" id="search_input" class="search" placeholder="  Search" >
			<input type="submit" id="search_button" value="O">
			</form>
		</div>

		<!-- Moje bilješke, dodaj bilješku -->
    	<ul>
			<div class="nav_lista">
				<li class="nav"><a href='index.php?rt=notes/show_new_note'>New note</a></li>
				<li class="nav"><a href="index.php?rt=users/show_user_notes">My notes</a></li>
				<li class="nav" id="tags_show"><a href="index.php?rt=tags/show_new_tag">New tag</a></li>



				<?php
				require_once __DIR__ . '/../model/tagservice.class.php';
				require_once __DIR__ . '/../model/noteservice.class.php';

				$cs = new NoteService();
				$id = $cs->getIdByUsername($_SESSION['username']);
				// prikupljamo sve tagove koji pripadaju korisniku
				$ts = new TagService();
				$tags = $ts->getTagsByUserID($id);

				// prikupljamo tagove kojima su dodijeljene bilješke na kojima je korisnik kolaborator
				$collab_tags = $ts->getCollabTag($id);
				$tags = array_merge($tags, $collab_tags);

				/*
				Sve prikupljene tagove prikazujemo u traci.
				Razlikujemo dvije navedene vrste tagova, tako da će ime potonjih tagova
				biti napisano u kurzivu, a ostalih 'normalno'.
				*/

				foreach ($tags as $tag) {
					$tag_name = $tag->name;
					$text = 'normal';
					if ($id !== $tag->id_user) $text = 'italic';
					
					echo '<form class="form_tags" action="index.php?rt=users/show_user_notes" method="post" style="display:inline;">';
					echo '<li class="nav"><button type="submit" name="tag_name" class="tag_name" value="' . $tag_name . '_' . $tag->id_user;
					echo '" style="display:inline;border:solid;border-color:rgb('.$tag->cr . ',' . $tag->cg . ',' . $tag->cb . ');';
					echo 'font-style:' . $text . ';';
					echo ')">' . $tag_name . '</li></form>';
				}
				?>
			</div>
		</ul>

</body>
</html>