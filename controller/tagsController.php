<?php

include __DIR__ . '/../app/database/db.class.php';
require_once __DIR__ . '/../model/noteservice.class.php';
require_once __DIR__ . '/../model/tagservice.class.php';

class TagsController {

	function show_new_tag() {
		require_once __DIR__ . '/../view/tags_form.php';
	}

	function new_tag() {
		$ns = new NoteService();
		$ts = new TagService();
		$name = $_POST["tag_name_input"]; // ime taga
		// odabrana boja:
		$cr = $_POST["tcr"];
		$cg = $_POST["tcg"];
		$cb = $_POST["tcb"];

		if (isset($_POST["tag_form_exit"])){
			$user_id = $ns->getIdByUsername($_SESSION['username']);
			$notes = $ns->getNotesByUserId($user_id);
			$collab_notes = $ns->getNotesByCollabId($user_id);
			$notes = array_merge($notes, $collab_notes);
			require_once __DIR__ . '/../view/users_user_notes.php';
			return;
		}
		$id_user = $ns->getIdByUsername($_SESSION["username"]);
		if ($ts->tagExist($id_user, $name)) {
			/*
			Ako je tag s tim imenom već stvoren kod tog korisnika,
			šaljemo poruku o greški.
			*/
			$_SESSION["error"] = 1;
			$this->show_new_tag();
			return;
		}

		if(!preg_match('/^[a-žA-Ž0-9 ]+$/', $name) or $name === '') {
			// provjeravamo je li ime taga 'dobro'
			$_SESSION["error"] = -1;
			$this->show_new_tag();
			return;
		}

		// potom ubacujemo taj tag u našu tablicu
		try{
			$db = DB::getConnection();
			$st = $db->prepare( 'INSERT INTO Tags (name, id_user, cr, cg, cb) VALUES (:name, :id_user, :cr, :cg, :cb)' );
			$st->execute( array( 'name' => $name, 'id_user' => $id_user, 'cr'=>$cr, 'cg'=>$cg, 'cb'=>$cb ) );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

		$user_id = $ns->getIdByUsername($_SESSION['username']);
		$notes = $ns->getNotesByUserId($user_id);
		$collab_notes = $ns->getNotesByCollabId($user_id);
		$notes = array_merge($notes, $collab_notes);
		require_once __DIR__ . '/../view/users_user_notes.php';
	}

	function delete_tag() {
		$ns = new NoteService();
		$id_user = $ns->getIdByUsername($_SESSION['username']); // id trenutnog korisnika
		$name = $_GET['tag']; // ime taga
		$id_c = $_GET['id_c']; // id korisnika koji je stvorio tag

		if ($id_user !== $id_c){
			/*
			Provjeravamo je li korisnik koji je trenutno ulogiran i stvorio tag koji se
			pokušava brisati. Ako nije, onda odmah prekidamo proces i našoj __header.js
			skripti šaljemo 'signal 0'.
			*/
			echo '0';
			exit(0);
		}

		// ako smo 'prešli prepreku', brišemo tag
		try {
			$db = DB::getConnection();
			$st = $db->prepare( 'DELETE FROM Tags WHERE name=:name AND id_user=:id_user');
			$st->execute(array('name'=>$name, 'id_user'=>$id_user));
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

		$notes = $ns->getNotesByUserId($id_user);

		// potom brišemo sve odnose obrisanog taga s bilješkama koje su mu pripadale
		foreach ($notes as $note) {
			try {
				$db = DB::getConnection();
				$st = $db->prepare( 'DELETE FROM Tag_relations WHERE id=:id_note');
				$st->execute(array('id_note'=>$note->id));
			}
			catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
		}
	}
}

?>
