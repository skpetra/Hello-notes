<?php
require_once __DIR__ . '/__header.php';
require_once __DIR__ . '/../model/tagservice.class.php';
require_once __DIR__ . '/../model/noteservice.class.php'; ?>

<script src="javascript/tags_form.js"></script>

<!-- 'Forma' za unos novog taga -->
<div class="tag_form">
	<form action="index.php?rt=tags/new_tag" method="post">
		<h2>New tag</h2>
		<input type="text" name="tag_name_input" id="new_tag_input" placeholder="Tag name">
		<br>
		<p>Choose color by moving the sliders:</p>
		<br>
		<input type="range" class="tcolor" id="tcr" name="tcr" min=0, max=255 value=255><br>
		<input type="range" class="tcolor" id="tcg" name="tcg" min=0, max=255 value=255><br>
		<input type="range" class="tcolor" id="tcb" name="tcb" min=0, max=255 value=255><br>
		<button type="submit" name="tag_form_exit">Exit</button>
		<button type="submit" name="tag_form_submit">Submit</button>
		<p id="tag_form_message"><?php
		if (isset($_SESSION['error'])) {
			if ($_SESSION["error"] == 1)
				echo 'Tag with this name already exists!';
			else if ($_SESSION["error"] == -1)
				echo 'Invalid format!';
			unset($_SESSION["error"]);
		} ?></p>
	</form>
</div>
