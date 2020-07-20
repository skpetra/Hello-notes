<?php require_once __DIR__ . '/__header.php';
require_once __DIR__ . '/../model/tagservice.class.php';
require_once __DIR__ . '/../model/noteservice.class.php';
unset($_SESSION['kolab_br']);
unset($_SESSION['kolab_rc_br']);
unset($_SESSION['id_updated_note']);
$_SESSION['id_updated_note'] = $note->id;

$cs = new NoteService();
$id = $cs->getIdByUsername($_SESSION['username']);  // id trenutnog korisnika
$ts = new TagService();
$tags = $ts->getTagsByUserID($id);
?>

<div class="update_note">
<h2>Update note</h2>

    <form method="post" action="index.php?rt=notes/save_updated_note" enctype="multipart/form-data">
        <input type="text" id="title" name="title" value="<?php echo $note->title;?>" style="color:black;">
        <br>
        <input type="text" id="content" name="content" value="<?php echo $note->content;?>" style="color:black;height:50px; word-wrap: break-word; word-break: break-all;">
        <?php
        	if($ns->getUserFilenameByNoteId($note->id)){
		?><br>
		<?php
			echo $ns->getUserFilenameByNoteId($note->id);
		?>
        	<a href='index.php?rt=notes/delete_file&id_note=<?php echo $_GET['selected_note']?>' style="color:black; border-radius: 5px; background-color: white; padding:2px;">-</a>
		<?php
			}
		?>

		<br>
        <!-- dodaj kolaboratore -->
        <button type="button" class="test"><img src='icons/add collaborator.jpg' width=20 height=20/></button>
        <div id="popis" style='display: none'>
          Input username:
          <br>
          <input type="text" list="datalist_imena" id="collab">
          <datalist id="datalist_imena"></datalist>
          <br>
          <button type="button" id="send-name">Send</button>
          <button type="button" id="exit-list">Exit</button>
          <br>
          <p id ="list_of_collabs"></p>
          <p id="poruka"></p>
		</div>

        <!-- makni kolaboratore -->
        <button type="button" class="remove_collab" style="color:black; margin-top:10px;border-radius: 5px; background-color: white; padding:2px;">-</button>
        <div id="popis_rc" class="<?php echo $note->id; ?>" style='display: none'>
          Input username:
          <br>
          <input type="text" list="datalist_imena_rc" id="remove_c" style="color:black;">
          <datalist id="datalist_imena_rc"></datalist>
          <br>
          <button type="button" id="send-name-rc">Send</button>
          <button type="button" id="exit-list-rc">Exit</button>
          <br>
          <p id ="list_of_collabs_rc"></p>
          <p id="poruka_rc"></p>
        </div>

		<!-- tag -->
		<br>
        <select name="tag" style="color:black;margin:10px;">
          <option value="keep_current" style="color:black;" selected>Keep the current tag</option>
          <option value="remove_current" style="color:black;">Remove current tag</option>
          <?php foreach ($tags as $tag) {
            echo '<option value="' . $tag->name . '" style="color:black;">' . $tag->name . '</option>';
          } ?>
		</select><br>

        <!-- datoteka -->
        <input id="file-input" type="file" name="new_file"/>
        <br>
        <button type="submit" name="submit" id="close">Update</button>
        <a href='index.php?rt=users/show_user_notes' id="close_update_note">Close</a>
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.js"></script>
<script src="javascript/notes_update_note.js"></script>
<?php require_once __DIR__ . '/__footer.php';?>
