<?php

require_once __DIR__ . '/../app/database/db.class.php';
require_once __DIR__ . '/../model/note.class.php';
require_once __DIR__ . '/../model/noteservice.class.php';

class NotesController{

	// kreiranje nove bilješke
    public function new_note(){

		$ns = new NoteService();
		
		// ukoliko nismo izasli forme za kreiranje
        if (!isset($_POST['close'])){

			$id_user = $ns->getIdByUsername($_SESSION['username']);
			//input u new_note
			$title = $_POST['new_note_title']; 
			$content = $_POST['new_note_content'];
			$date = date( 'Y-m-d H:i:s');
						
			// biljeska mora imati naslov
			if ($title === ''){
?>
				<script> alert("Your note title is empty. Try again!");</script>
				<?php
				require_once __DIR__ . '/../view/new_note.php';
				exit();
			}
			// inace dodajemo novu biljesku
			else if( isset($_POST['add_new_note'])){
				
				$tag = $_POST['tag'];
				// ubacujemo biljesku u bazu
				$db = DB::getConnection();
				$st = $db->prepare( 'INSERT INTO Notes(id_user, title, content, date) VALUES (:id_user, :title, :content, :date )' );
				$st->execute( array('id_user' => $id_user, 'title' => $title, 'content' => $content, 'date'=> $date) );

				// ubacujemo datoteku u bazu ukoliko je korisnik dodao
				// dohvat sljedeceh id-a
				$st = $db->prepare( 'SELECT id FROM Notes');
				$st->execute();
				while( $row = $st->fetch() )
					$id = $row['id'];
				if ( isset($_FILES['new_file']) && $_FILES['new_file']['error'] === 0 )
					$filename = $ns->uploadFile($id); //vraća nam filename

				// dodavanje taga ukoliko ga je korisnik odabrao
				if ($tag !== "no_option" && $tag !== "none"){
					$st = $db->prepare( 'INSERT INTO Tag_relations(name, id) VALUES (:name, :id)' );
					$st->execute( array( 'name' => $tag, 'id' => $id) );
				}

				// dodavanje kolaboratora ukoliko ga je korisnik odabrao
				if (isset($_SESSION['kolab_br'])) {
					$br = intval($_SESSION['kolab_br']);
					for ($i = 0; $i < $br; $i++) {
						$id_c = $ns->getIdByUsername($_SESSION['kolab_' . $i]);
						try{
							$st = $db->prepare( 'INSERT INTO Collaborators(id_collaborator, id_note) VALUES(:id_c, :id_n)');
							$st->execute(array('id_c'=>$id_c, 'id_n'=>$id));
						}
						catch( PDOException $e ) {
							exit( 'PDO error ' . $e->getMessage() );
						}
						unset($_SESSION['kolab_' . $i]);
					}
					unset($_SESSION['kolab_br']);
				}
			}
		}
		// nakon sto je korisnik dodao biljesku ili odustao od dodavanja idemo na sve korisnikove biljeske
		$user_id = $ns->getIdByUsername($_SESSION['username']);
		$notes = $ns->getNotesByUserId($user_id);
		$collab_notes = $ns->getNotesByCollabId($user_id);
		$notes = array_merge($notes, $collab_notes);
		unset( $_POST['add_new_note']);
		require_once __DIR__ . '/../view/users_user_notes.php';
    }

	// brisanje bilješke
    public function delete_note(){

		$id = $_GET['delete_note'];
        try{
			$db = DB::getConnection();
			$st = $db->prepare('DELETE FROM Notes WHERE id = "' . $id . '" ');
			$st->execute( array('id' => $id) );
			$st = $db->prepare('DELETE FROM Files WHERE id_note = "' . $id . '" ');
			$st->execute( array('id_note' => $id) );
			$st = $db->prepare('DELETE FROM Collaborators WHERE id_note = "' . $id . '" ');
			$st->execute( array('id_note' => $id) );
			$st = $db->prepare('DELETE FROM Tag_relations WHERE id = "' . $id . '" ');
			$st->execute( array('id_note' => $id) );
        }
        catch( PDOException $e ) {
          exit( 'PDO error ' . $e->getMessage() );
		}
		
		// nakon sto je korisnik izbrisao biljesku idemo na sve korisnikove biljeske
        $ns = new NoteService();
        $user_id = $ns->getIdByUsername($_SESSION['username']);
        $notes = $ns->getNotesByUserId($user_id);
        $collab_notes = $ns->getNotesByCollabId($user_id);
        $notes = array_merge($notes, $collab_notes);
        require_once __DIR__ . '/../view/users_user_notes.php';
    }


	// prikaz forme za unos nove bilješke
    public function show_new_note(){

		require_once __DIR__ . '/../view/new_note.php';
    }

	// prikaz forme za izmjenu bilješke
    public function update_selected_note(){

		$ns = new NoteService();
		$id = $_GET['selected_note'];
		$note = $ns->getNoteById($id);
		require_once __DIR__ . '/../view/notes_update_note.php';
    }

	// brisanje datoteke u prikazu za uređivanje datoteke
    public function delete_file(){

		$ns = new NoteService();
		$id = $_GET['id_note'];
		$note = $ns->getNoteById($id);

		try{
			$db = DB::getConnection();
			$st = $db->prepare('DELETE FROM Files WHERE id_note = "' . $id . '" ');
			$st->execute( array('id_note' => $id) );
		}
		catch( PDOException $e ) {
			exit( 'PDO error ' . $e->getMessage() );
		}

		// nakon brisanja datoteke iz bilješke vraćamo se na prikaz svih korisnikovih bilješki
		$user_id = $ns->getIdByUsername($_SESSION['username']);
		$notes = $ns->getNotesByUserId($user_id);
		$collab_notes = $ns->getNotesByCollabId($user_id);
		$notes = array_merge($notes, $collab_notes);
		require_once __DIR__ . '/../view/users_user_notes.php';

    }

	// izmjena bilješke
    public function save_updated_note(){

		$id_un = $_SESSION['id_updated_note'];
		$ns = new NoteService();
		$ns->note_update_title($id_un, $_POST['title']);
		$ns->note_update_content($id_un, $_POST['content']);
		$ns->note_update_date($id_un);

		// update taga - ovisno o tome zadržava li se ili briše ili bira neki drugi
		if($_POST['tag'] !== "keep_current") {

			$db = DB::getConnection();
			$st = $db->prepare( 'DELETE FROM Tag_relations WHERE id=:id' );
			$st->execute(array('id' => $id_un));

			if ($_POST['tag'] !== "remove_current"){
					$db = DB::getConnection();
					$st = $db->prepare( 'INSERT INTO Tag_relations(name, id) VALUES (:name, :id)' );
					$st->execute( array( 'name' => $_POST['tag'], 'id' => $id_un) );
			}
		}

		// update dodanih kolaboratora
		if (isset($_SESSION['kolab_br'])) {
			$br = intval($_SESSION['kolab_br']);
			for ($i = 0; $i < $br; $i++) {

				$id_c = $ns->getIdByUsername($_SESSION['kolab_' . $i]);
				try{
					$db = DB::getConnection();
					$st = $db->prepare( 'INSERT INTO Collaborators(id_collaborator, id_note) VALUES(:id_c, :id_n)');
					$st->execute(array('id_c'=>$id_c, 'id_n'=>$id_un));
				}
				catch( PDOException $e ) {
					exit( 'PDO error ' . $e->getMessage() );
				}
				unset($_SESSION['kolab_' . $i]);
			}
			unset($_SESSION['kolab_br']);
		}

		// micanje kolaboratora, ako su postavljene potrebne varijable
		if (isset($_SESSION['kolab_rc_br'])) {
			$br_rc = intval($_SESSION['kolab_rc_br']);
			for ($i = 0; $i < $br_rc; $i++) {
				$id_c = $ns->getIdByUsername($_SESSION['kolab_rc_' . $i]);
				try{
					$db = DB::getConnection();
					$st = $db->prepare( 'DELETE FROM Collaborators WHERE id_collaborator=:id_c AND id_note=:id_n');
					$st->execute(array('id_c'=>$id_c, 'id_n'=>$id_un));
				}
				catch( PDOException $e ) {
					exit( 'PDO error ' . $e->getMessage() );
				}
				unset($_SESSION['kolab_rc_' . $i]);
			}
			unset($_SESSION['kolab_rc_br']);
		}

		// ako je postavljena nova datoteka
		if ( isset($_FILES['new_file']) && $_FILES['new_file']['error'] === 0 )
			$filename = $ns->note_update_file($id_un, $_FILES['new_file']);
		
		// nakon izmjene bilješke idemo na prikaz svih bilješki
		unset($_SESSION['id_updated_note']);
		$user_id = $ns->getIdByUsername($_SESSION['username']);
		$notes = $ns->getNotesByUserId($user_id);
		$collab_notes = $ns->getNotesByCollabId($user_id);
		$notes = array_merge($notes, $collab_notes);
		require_once __DIR__ . '/../view/users_user_notes.php';
    }

	// funkcija za preuzimanje datoteke
    public function download_file(){

      	// pošto jedna bilješka trenutno sadrži samo 1 datoteku možemo brisati po dobivenom id_note
        $id_note = $_GET['download'];
        $ns = new NoteService();
        $filename = $ns->getFilenameByNoteId($id_note);
        $user_filename = $ns->getUserFilenameByNoteId($id_note);
        $filepath = realpath(dirname(__FILE__) . '/..').'/uploads/' . $filename;

        if (file_exists($filepath)) {

            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . $user_filename);
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filepath));
            readfile($filepath);
        }
    }

    // funkcija koja šalje prijedloge username-ova pri unosu kolaboratora
    function suggest() {
		$imena = [];

		try{
			$db = DB::getConnection();
			$st = $db->prepare( 'SELECT username FROM Users');
			$st->execute();
		}
		catch( PDOException $e ) { 
			exit( 'PDO error ' . $e->getMessage() ); 
		}

		while($row = $st->fetch())
			$imena[] = $row['username'];

		$q = $_GET[ "q" ];

		foreach( $imena as $ime )
			if( strpos( $ime, $q ) !== false and $ime !== $q)
				echo "<option value='" . $ime . "' />\n";
    }

    // funkcija koja zapravo postavlja pomoćne varijable za handle-anje provjere valjanosti unesenog kolaboratora 
    function collab() {

		if(!isset($_SESSION['kolab_br'])) $_SESSION['kolab_br'] = 0;
			$br_k = intval($_SESSION['kolab_br']);

		$username = $_GET['q'];
		$ns = new NoteService();
		$id = $ns->getIdByUsername($username);
		$id_n = '0';

		for($i = 0; $i < $br_k; $i++) {
			// ukoliko je username već dodan, šalje se odgovarajuća povratna informacija
			if ($_SESSION['kolab_'.$i] === $username){
				echo '-1';
				exit(0);
			}
		}

		if (isset($_SESSION['id_updated_note'])) {
			// ukoliko je riječ o update-u neke bilješke, onda znamo koji joj je ID
			$id_n = $_SESSION['id_updated_note'];
			try{
				$db = DB::getConnection();
				$st1 = $db->prepare( 'SELECT id_collaborator, id_note FROM Collaborators WHERE id_collaborator=:id AND id_note=:id_n');
				$st1->execute(array('id'=>$id, 'id_n' => $id_n));
			}
			catch( PDOException $e ) { exit( 'PDO error2 ' . $e->getMessage() ); }
			
			if ($row = $st1->fetch()) {
				// za tu bilješku provjeravamo je li uneseni username već kolaborator na njoj
				echo '-1';
				exit(0);
			}
		}
		else {
			// inače, stvaramo novu bilješku pa nalazimo njezin ID
			try{
				$db = DB::getConnection();
				$st = $db->prepare( 'SELECT id FROM Notes');
				$st->execute();
			}
			catch( PDOException $e ) { exit( 'PDO error1 ' . $e->getMessage() ); }
			
			while ($row = $st->fetch()) {
				$id_n = $row['id'];
			}

			$id_n = intval($id_n);
		}

		// provjeravamo je li uneseni username uopće validan
		try{
			$db = DB::getConnection();
			$st = $db->prepare( 'SELECT username FROM Users WHERE username=:username');
			$st->execute(array('username'=>$username));
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
		if ($row = $st->fetch()) {
			// ako je username u redu, imamo slučajeve:
			if ($row['username'] === $_SESSION['username']) {
				// riječ je o trenutnom korisniku, javljamo 'upozorenje'
				echo '2';
				exit(0);
			}
			else {
				/*
				Username je ok, dodajemo ga u $_SESSION i pamtimo.
				Naime, nećemo odmah dodavati username u bazu kolaboratora jer postoji
				mogućnost da korisnik odustane od kreiranja bilješke, pa smo stoga unijeli
				username a nismo trebali. Zato ga pamtimo u $_SESSION pa ćemo kasnije,
				ukoliko sve prođe 'u redu', sve potrebne username-ove ubaciti u bazu.
				*/
				$_SESSION['kolab_' . $br_k] = $row['username'];
				$_SESSION['kolab_br'] = $br_k + 1;
				echo $row['username'];
				exit(0);
			}
		}
		else {
			echo '0';
			exit(0);
		}
	}

    // slična funkcija, ali ona brine o valjanosti username kolaboratora koji se želi maknuti 
    function remove_collab() {

		if(!isset($_SESSION['kolab_rc_br'])) $_SESSION['kolab_rc_br'] = 0;
			$br_k = intval($_SESSION['kolab_rc_br']);

		$username = $_GET['q'];
		$ns = new NoteService();
		$id = $ns->getIdByUsername($username);
		$id_n = intval($_GET['id']);

		// provjera je li korisnik već odabran
		for($i = 0; $i < $br_k; $i++) {
			if ($_SESSION['kolab_rc_'.$i] === $username){
				echo '-2';
				exit(0);
			}
		}

		// provjera postoji li uneseni korisnik
		// i je li riječ o korisniku koji je trenutno prijavljen
		try{
			$db = DB::getConnection();
			$st1 = $db->prepare( 'SELECT username FROM Users WHERE username=:username');
			$st1->execute(array('username' => $username));
		}
		catch( PDOException $e ) { exit( 'PDO error2 ' . $e->getMessage() ); }
		if($row = $st1->fetch()) {
			if ($row['username'] === $_SESSION['username']) {
				echo '2';
				exit(0);
			}
		}
		else {
			echo '0';
			exit(0);
		}


		try {
			$db = DB::getConnection();
			$st1 = $db->prepare( 'SELECT id_collaborator, id_note FROM Collaborators WHERE id_collaborator=:id AND id_note=:id_n');
			$st1->execute(array('id'=>$id, 'id_n' => $id_n));
		}
		catch( PDOException $e ) { exit( 'PDO error2 ' . $e->getMessage() ); }
		
		if ($row = $st1->fetch()) {
			$_SESSION['kolab_rc_' . $br_k] = $ns->getUsernameById($row['id_collaborator']);
			$_SESSION['kolab_rc_br'] = $br_k + 1;
			echo $ns->getUsernameById($row['id_collaborator']);
			exit(0);
		}
		else {
			echo '-1';
			exit(0);
		}
		echo '0';
		exit(0);
		}
  }






?>
