<?php

require_once __DIR__ . '/../app/database/db.class.php';
require_once __DIR__ . '/../model/noteservice.class.php';

class SearchController {

	// funkcija za pretraživanje bilješki po naslovu i sadržaju
	function search_notes() {

		$ns = new NoteService();
		$id = $ns->getIdByUsername( $_SESSION["username"] );
		$tekst = $_POST["search"];

		// postavljamo zahtjev na duljinu fraze
		$min_length = 3;
		if (strlen($tekst) < 3) {
			require_once __DIR__ . '/../view/__header.php';
			echo "<p style='margin-top: -22%;'>  Minimum length required is " . $min_length . ".</p><br>";
			require_once __DIR__ . '/../view/__footer.php';
		}
		else {
			try{
				$db = DB::getConnection();
				$st = $db->prepare( 'SELECT * FROM Notes WHERE ((title LIKE "%":tekst"%")' . 'OR (content LIKE "%":tekst"%")) AND id_user=:id' );
				$st->execute( array( 'tekst' => $tekst, 'id' => $id ) );
			}
			catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

			$notes = array();
			while( $row = $st->fetch() ){
				$notes[] = new Note($row['id'], $row['id_user'], $row['title'], $row['content'], $row['date']);
			}
			// ne smijemo zaboraviti pretražiti i one bilješke na kojima je korisnik kolaborator
			try{
				$db = DB::getConnection();
				$st = $db->prepare( 'SELECT Notes.* FROM Notes, Collaborators WHERE ((title LIKE "%":tekst"%")' . 'OR (content LIKE "%":tekst"%")) AND id_collaborator=:id_user AND id=id_note' );
				$st->execute( array( 'tekst' => $tekst, 'id_user' => $id ) );
			}
			catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
			
			while( $row = $st->fetch() ){
				$notes[] = new Note($row['id'], $row['id_user'], $row['title'], $row['content'], $row['date']);
			}
			require_once __DIR__ . '/../view/users_user_notes.php'; 
		}
	}
}


 ?>
