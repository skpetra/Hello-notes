<?php
require_once __DIR__ . '/__header.php';
require_once __DIR__ . '/../model/tagservice.class.php';
require_once __DIR__ . '/../model/noteservice.class.php';

// mjera predostrožnosti:
unset($_SESSION['kolab_br']);
unset($_SESSION['kolab_rc_br']);
unset($_SESSION['id_updated_note']);

$cs = new NoteService();
$id = $cs->getIdByUsername($_SESSION['username']);  // id trenutnog korisnika
$ts = new TagService();
$tags = $ts->getTagsByUserID($id); ?>

	<div class="add_new_note"> 
	<h2>New note</h2>
	<form action="index.php?rt=notes/new_note" method="post" enctype="multipart/form-data" >

		<!-- Unos naslova -->
		<input type="text" name="new_note_title" placeholder="Title" size="22"> <br>

		<!-- Unos sadržaja -->
		<input type="text" name="new_note_content" placeholder="Content..." size="22" style="height: 70px;"> <br>
		
		<!-- Odabir taga -->
		<br>
		<select name="tag">
			<option value="no_option">Choose tag</option>
			<?php foreach ($tags as $tag) {
			echo '<option value="' . $tag->name . '">' . $tag->name . '</option>';
			} ?>
		</select>

		<!-- Dodavanje kolaboratora --> 
		<button type="button" class="test"><img src='icons/add collaborator.jpg' width=20 height=20/></button> 
		
		<!-- 'Forma' za unos kolaboratora -->
		<div id="popis" style='display: none'>
		Input username:
		<br>
		<input type="text" list="datalist_imena" id="collab">
		<datalist id="datalist_imena"></datalist>
		<br>
		<button type="button" id="send-name">Send</button>
		<button type="button" id="exit-list">Exit</button>
		<br>
		<!-- Paragraf za unos liste dodanih kolaboratora -->
		<p id ="list_of_collabs"></p>
		<!-- Paragraf za unos povratne informacije u odabranom kolaboratoru -->
		<p id="poruka"></p>
		</div>

		<!-- Upload file-a -->
		<input id="file-input" type="file" name="new_file" style="color:white;"/>

		<br><br>
		<button type="submit" id="add_new_note" name="add_new_note">Save</button>
		<button type="submit" id="close" name="close">Close</button>
	</form>
	
</div>

<script src="javascript/new_note.js"></script>
<?php require_once __DIR__ . '/__footer.php';?>