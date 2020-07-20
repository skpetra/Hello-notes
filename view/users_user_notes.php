<?php require_once __DIR__ . '/__header.php';
require_once __DIR__ . '/../model/tagservice.class.php';?>

<div class="notes">
	<?php
		foreach($notes as $note):
		$creator = $_SESSION['username'];
		if ($note->id_user !== $ns->getIdByUsername($_SESSION['username'])) {
			$creator = $ns->getUsernameById($note->id_user);
			$ts = new TagService();
		}
		$tag = $ts->getTagByNoteID($note->id);

		if ($tag->name == '#') $color ='white';
		else{
			$color = 'rgb('.$tag->cr.','.$tag->cg.','.$tag->cb.')';
		}
    ?>
    <div class="okvir_biljeske" style="border-color:<?php echo $color; ?>;">
			<h3 style="background-color:<?php echo $color; ?>;"><?php echo $note->title; ?>
				<p class="id_made_by" style='font-size:0.5em;text-align:center;padding-top:5px;'>
			<?php
				if ($note->id_user !== $ns->getIdByUsername($_SESSION['username'])) {
					echo 'Creator: ';
					echo $creator;
				}
			?> </p>
			</h3>
		<?php // echo $note->date; ?>
        <p style="text-align:left;margin:5px;"><?php echo $note->content; ?></p>
			<?php
			if($ns->getUserFilenameByNoteId($note->id)){
			?>
				<a style="margin-left:5px;margin-right:5px;" href='index.php?rt=notes/download_file&download=<?php echo $note->id; ?>'><?php echo $ns->getUserFilenameByNoteId($note->id);?></a>
			<?php
				}
			?>
			<br>
		<div id="note_icons">
			<a href='index.php?rt=notes/update_selected_note&selected_note=<?php echo $note->id; ?>'><img src='icons/update_details.jpg' height=20 width=20/></a>
			<a id="delete" href='index.php?rt=notes/delete_note&delete_note=<?php echo $note->id; ?>'><img src="icons/delete.jpg" height=20 width=20/></a>				</div>
    </div>

<?php endforeach; ?>
</div>

<?php require_once __DIR__ . '/__footer.php';?>
