<?php
require_once __DIR__ . '/../app/database/db.class.php';
require_once __DIR__ . '/user.class.php';
require_once __DIR__ . '/note.class.php';


class NoteService
{
	//-------------------- USER -----------------------------------------

	// login korisnika
	function getPasswordByUsername( $username ){
		try{
			$db = DB::getConnection();
			$st = $db->prepare( 'SELECT password_hash FROM Users WHERE username=:username' );
			$st->execute( array( 'username' => $username ) );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

		$row = $st->fetch();
		if( $row === false )
			return null;
		else
			return $row['password_hash'];
	}

	// registracija novog korisnika
	function add_new_user($name, $surname, $username, $password_hash, $birth_date, $about){

		$db = DB::getConnection();

		//registriramo usera
		try{
			$db = DB::getConnection();
			$st = $db->prepare( 'INSERT INTO Users(username, password_hash, name, surname, birth_date, about) VALUES (:username, :password_hash, :name, :surname, :birth_date, :about)' );
			$st->execute( array( 'username' => $username, 'password_hash' => $password_hash,'name'=> $name, 'surname'=> $surname,  'birth_date' => $birth_date, 'about'=> $about));
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

	}

	// dodavanje facebook korisnika u bazu ukoliko mu je prvu posjet 
	function add_new_facebook_user($name, $surname, $username, $password_hash, $birth_date, $about){

		$db = DB::getConnection();

		$facebook_user=true;

		try{
			$db = DB::getConnection();
			$st = $db->prepare( 'INSERT INTO Users(username, password_hash, name, surname, birth_date, about, facebook_user) VALUES (:username, :password_hash, :name, :surname, :birth_date, :about, :facebook_user)' );
			$st->execute( array( 'username' => $username, 'password_hash' => $password_hash,'name'=> $name, 'surname'=> $surname,  'birth_date' => $birth_date, 'about'=> $about, 'facebook_user'=>$facebook_user));
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

	}

	// dohvaćanje prezimena prijavljenog korisnika preko usernamea
	public function getSurnameByUsername( $username ){
		try{
			$db = DB::getConnection();
			$st = $db->prepare( 'SELECT surname FROM Users WHERE username=:username' );
			$st->execute( array( 'username' => $username ) );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

		$row = $st->fetch();
		if( $row === false )
			return null;
		else
			return $row['surname'];
	}

	// dohvaćanje imena prijavljenog korisnika preko usernamea
	public function getNameByUsername( $username ){

		try{
			$db = DB::getConnection();
			$st = $db->prepare( 'SELECT name FROM Users WHERE username=:username' );
			$st->execute( array( 'username' => $username ) );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }


		$row = $st->fetch();
		if( $row === false )
			return null;
		else
			return $row['name'];
	}

	// dohvaćanje datuma rođenja prijavljenog korisnika preko usernamea
	public function getBdateByUsername( $username ){
		try{
			$db = DB::getConnection();
			$st = $db->prepare( 'SELECT birth_date FROM Users WHERE username=:username' );
			$st->execute( array( 'username' => $username ) );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

		$row = $st->fetch();
		if( $row === false )
			return null;
		else
			return $row['birth_date'];
	}

	// dohvaćanje 'abouta' prijavljenog korisnika preko usernamea
	public function getAboutByUsername( $username ){
		try{
			$db = DB::getConnection();
			$st = $db->prepare( 'SELECT about FROM Users WHERE username=:username' );
			$st->execute( array( 'username' => $username ) );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

		$row = $st->fetch();
		if( $row === false )
			return null;
		else
			return $row['about'];
	}

	// dohvaćanje usernamea prijavljenog korisnika preko id korisnika u bazi
	public function getUsernameById( $id ){
		try{
			$db = DB::getConnection();
			$st = $db->prepare( 'SELECT username FROM Users WHERE id=:id' );
			$st->execute( array( 'id' => $id ) );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

		$row = $st->fetch();
		if( $row === false )
			return null;
		else
			return $row['username'];
	}

	// izmjena usernamea
	function user_update_username(){

		// provjeri postoji li vec takav korisnik
		if ( $this->getIdByUsername($_POST['update_username']) !== null ){
			require_once __DIR__ . '/__header.php';
			echo "<p style='margin-top: -22%;'>Username already exists!</p>";
			require_once __DIR__ . '/__footer.php';
			exit();
		}

		//update db
		$id = $this->getIdByUsername($_SESSION['username']);
		$username = $_POST['update_username'];
		try{
			$db = DB::getConnection();
			$st = $db->prepare( 'UPDATE Users SET username=:username WHERE id=:id' );
			$st->execute( array( 'username' => $username, 'id' => $id ) );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

		$_SESSION['username'] = $username;
	}

	// izmjena imena
	function user_update_name(){

		//update db
		$id = $this->getIdByUsername($_SESSION['username']);
		$name = $_POST['update_name'];
		try{
			$db = DB::getConnection();
			$st = $db->prepare( 'UPDATE Users SET name=:name WHERE id=:id' );
			$st->execute( array( 'name' => $name, 'id' => $id  ) );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
	}

	// izmjena prezimena
	function user_update_surname(){

		//update db
		$id = $this->getIdByUsername($_SESSION['username']);
		$surname = $_POST['update_surname'];
		try{
			$db = DB::getConnection();
			$st = $db->prepare( 'UPDATE Users SET surname=:surname WHERE id=:id' );
			$st->execute( array( 'surname' => $surname, 'id' => $id ) );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
	}

	// izmjena datuma rođenja
	function user_update_bdate(){

		//update db
		$id = $this->getIdByUsername($_SESSION['username']);
		$birth_date = $_POST['update_bdate'];
		if($birth_date==='') $birth_date= NULL;
		try{
			$db = DB::getConnection();
			$st = $db->prepare( 'UPDATE Users SET birth_date=:birth_date WHERE id=:id' );
			$st->execute( array( 'birth_date' => $birth_date, 'id' => $id ) );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
	}

	// izmjena about
	function user_update_about(){

		//update db
		$id = $this->getIdByUsername($_SESSION['username']);
		$about = $_POST['update_about'];
		try{
			$db = DB::getConnection();
			$st = $db->prepare( 'UPDATE Users SET about=:about WHERE id=:id' );
			$st->execute( array( 'about' => $about, 'id' => $id ) );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
	}

	// izmjena svih korisnokovih detalja ako ih je izmijenio
	function user_update_details(){

		if( $_POST['update_username']==='' || $_POST['update_name']==='' || $_POST['update_surname']==='' ){
			echo '<script language="javascript">';
			echo 'alert("Fill in all required entry fields!")';
			echo '</script>';
		}
		else{

			// ako je postavljeno a bit ce ako ga ne izbrise
			// i ako je razlicito od starog
			if (isset($_POST['update_username']) && $_POST['update_username'] !== $_SESSION['username'])
				$this->user_update_username();

			// postavljeno je novo ime
			if( isset($_POST['update_name']) && $_POST['update_name'] !== $this->getNameByUsername($_SESSION['username']) )
				$this->user_update_name();

			if( isset($_POST['update_surname']) && $_POST['update_surname'] !== $this->getSurnameByUsername($_SESSION['username']) )
				$this->user_update_surname();

			if( isset($_POST['update_bdate']) && $_POST['update_bdate'] !== $this->getBdateByUsername($_SESSION['username']) )
				$this->user_update_bdate();

			if( isset($_POST['update_about']) && $_POST['update_about'] !== $this->getAboutByUsername($_SESSION['username']) )
				$this->user_update_about();
		}

	}

	// novi password
	public function user_update_password(){

		if( isset($_POST['update_password']) && isset($_POST['confirm_password'])){

			if (preg_match( '/^[a-žA-Ž0-9]{6,}$/', $_POST['update_password']) &&
				$_POST['update_password'] === $_POST['confirm_password']){

				$password_hash = password_hash($_POST['update_password'], PASSWORD_DEFAULT);
				$id = $this->getIdByUsername($_SESSION['username']);
				try{
					$db = DB::getConnection();
					$st = $db->prepare( 'UPDATE Users SET password_hash=:password_hash WHERE id=:id' );
					$st->execute( array( 'password_hash' => $password_hash, 'id' => $id ) );
				}
				catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

				return True;
			}
			else return False;

		}

	}

	//	------------------ BILJEŠKE ---------------------

	// dohvat svih biljeski nekog korisnika preko id
    public function getNotesByUserId( $id_user ){

		try
		{
			$db = DB::getConnection();
			$st = $db->prepare( 'SELECT id FROM Notes WHERE id_user=:id_user' );
			$st->execute( array( 'id_user' => $id_user ) );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

		$arr = array();
		while( $row = $st->fetch() )
		{
			$note = $this->getNoteById($row['id']);
			$arr[] = $note;
		}

		return $arr;
    }

	// bilješke na kojima je dani user kolaborator
	public function getNotesByCollabId( $id_user ){

		try
		{
			$db = DB::getConnection();
			$st = $db->prepare( 'SELECT id_note FROM Collaborators WHERE id_collaborator=:id_user' );
			$st->execute( array( 'id_user' => $id_user ) );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

		$arr = array();
		while( $row = $st->fetch() )
		{
			$note = $this->getNoteById($row['id_note']);
			$arr[] = $note;
		}

		return $arr;
	}

	// bilješke za određenog kolaboratora i tag name
	public function getNotesByCollabIdandtagName( $id_user, $tag_name){

		try
		{
			$db = DB::getConnection();
			$st = $db->prepare( 'SELECT id_note FROM Collaborators, Tag_relations, Tags, Notes' .
				' WHERE Collaborators.id_collaborator=:id_user AND Collaborators.id_note=Tag_relations.id AND ' .
				'Tag_relations.name=:name AND Tags.name=Tag_relations.name AND Tags.id_user=:id_user AND ' .
				'Collaborators.id_note=Notes.id AND Notes.id_user=:id_user' );
			$st->execute( array( 'id_user' => $id_user, 'name' => $tag_name) );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

		$arr = array();
		while( $row = $st->fetch() )
		{
			$note = $this->getNoteById($row['id_note']);
			$arr[] = $note;
		}

		return $arr;
	}

	// sohvat biljeske s id-em $id
    function getNoteById( $id )
	{
		try
		{
			$db = DB::getConnection();
			$st = $db->prepare( 'SELECT id, id_user, title, content, date FROM Notes WHERE id=:id' );
			$st->execute( array( 'id' => $id ) );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

		$row = $st->fetch();

		if( $row === false )
			return null;
		else
		{
			return new Note( $row['id'], $row['id_user'], $row['title'], $row['content'], $row['date']);
		}
    }

	// id korisnika preko usernamea
    function getIdByUsername( $username )
	{
		try
		{
			$db = DB::getConnection();
			$st = $db->prepare( 'SELECT id FROM Users WHERE username=:username' );
			$st->execute( array( 'username' => $username ) );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

		$row = $st->fetch();
		if( $row === false )
			return null;
		else
			return $row['id'];
	}


	// ----------------- DATOTEKE -------------------

	// dodavanje datoteke u bilješku
	// podržani su .csv i .txt formati
	function uploadFile($id_note){
		$user_filename = $_FILES['new_file']['name'];
		$filename = $id_note . '_' . $user_filename;
		$destination = realpath(dirname(__FILE__) . '/..').'/uploads/' . $filename;
		$extension = pathinfo( $user_filename, PATHINFO_EXTENSION );

		$file = $_FILES['new_file']['tmp_name'];
		$size = $_FILES['new_file']['size'];


		//upload
		if(!in_array($extension, ['csv', 'txt'])) echo "Your file extension must be .csv or .txt";
	    elseif ($_FILES['new_file']['size'] > 1000000) echo "File too large!"; //ne više od 1mb
	    else{
	        // move the uploaded (temporary) file to the specified destination
	        if (move_uploaded_file($file, $destination) ) {
	            try{
					$db = DB::getConnection();
					$st = $db->prepare( 'INSERT INTO Files (id_note, user_filename, filename, size) VALUES (:id_note, :user_filename, :filename, :size)' );
					$st->execute( array('id_note'=>$id_note, 'user_filename' => $user_filename, 'filename' => $filename, 'size' => $size) );

					// echo "File uploaded successfully";
	            	return $filename;
				}
				catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
	        }
	        else {
	        	echo "Failed to upload file.";
	        	echo "filename " . $user_filename;
	        	echo " extension" . $extension;
	        	echo $_FILES['new_file']['error'];
	        }
	        return false;
	    }
	}

	// dohvati file po njegovom id-u
	function getFileById( $id ){
	    try{
			$db = DB::getConnection();
			$st = $db->prepare( 'SELECT * FROM Files WHERE id=:id' );
			$st->execute( array( 'id' => $id) );

			$row = $st->fetch();
			if( $row === false ) return null;
			else return $row;
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
	}

	// dohvati ime koje file imau upload folderu
	function getFilenameById( $id ){
	    try{
			$db = DB::getConnection();
			$st = $db->prepare( 'SELECT filename FROM Files WHERE id=:id' );
			$st->execute( array( 'id' => $id) );

			$row = $st->fetch();
			if( $row === false ) return null;
			else return $row['filename'];
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
	}

	// dohvati file ime kojim ga je korisnik uploadao preko id filea
	function getUserFilenameById( $id ){
	    try{
			$db = DB::getConnection();
			$st = $db->prepare( 'SELECT user_filename FROM Files WHERE id=:id' );
			$st->execute( array( 'id' => $id) );

			$row = $st->fetch();
			if( $row === false ) return null;
			else return $row['user_filename'];
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
	}

	// dohvati ime filea preko id biljeske
	function getFilenameByNoteId( $id_note ){
	    try{
			$db = DB::getConnection();
			$st = $db->prepare( 'SELECT filename FROM Files WHERE id_note=:id_note' );
			$st->execute( array( 'id_note' => $id_note) );

			$row = $st->fetch();
			if( $row === false ) return null;
			else return $row['filename'];
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
	}

	// dohvati file ime kojim ga je korisnik uploadao preko id biljeske
	function getUserFilenameByNoteId( $id_note ){
	    try{
			$db = DB::getConnection();
			$st = $db->prepare( 'SELECT user_filename FROM Files WHERE id_note=:id_note' );
			$st->execute( array( 'id_note' => $id_note) );

			$row = $st->fetch();
			if( $row === false ) return null;
			else return $row['user_filename'];
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
	}

	// update datuma biljeske
	function note_update_date($id){
		//provjeri postoji li biljeska
		try{
			$db = DB::getConnection();
			$st = $db->prepare( 'SELECT * FROM Notes WHERE id=:id');
			$st->execute( array( 'id' => $id )  );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
		if( $st->rowCount() !== 1 ) throw new Exception( 'Ne postoji bilješka koju želite mijenjati.' );

		//update db
		$date = date( 'Y-m-d H:i:s');
		try{
			$db = DB::getConnection();
			$st = $db->prepare( 'UPDATE Notes SET date=:date WHERE id=:id' );
			$st->execute( array( 'date' => $date, 'id' => $id ) );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
	}

	// update imena biljeske
	function note_update_title($id, $title){
		//provjeri postoji li biljeska
		try{
			$db = DB::getConnection();
			$st = $db->prepare( 'SELECT * FROM Notes WHERE id=:id');
			$st->execute( array( 'id' => $id )  );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
		if( $st->rowCount() !== 1 ) throw new Exception( 'Ne postoji bilješka koju želite mijenjati.' );

		//update db
		try{
			$db = DB::getConnection();
			$st = $db->prepare( 'UPDATE Notes SET title=:title WHERE id=:id' );
			$st->execute( array( 'title' => $title, 'id' => $id ) );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
	}

	// update sadržaja biljeske
	function note_update_content($id, $content){
		//provjeri postoji li biljeska
		try{
			$db = DB::getConnection();
			$st = $db->prepare( 'SELECT * FROM Notes WHERE id=:id');
			$st->execute( array( 'id' => $id )  );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
		if( $st->rowCount() !== 1 ) throw new Exception( 'Ne postoji bilješka koju želite mijenjati.' );

		//update db
		try{
			$db = DB::getConnection();
			$st = $db->prepare( 'UPDATE Notes SET content=:content WHERE id=:id' );
			$st->execute( array( 'content' => $content, 'id' => $id ) );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
	}

	// upload novog filea
	function note_update_file($id_note, $new_file){
		//provjeri postoji li biljeska
		try{
			$db = DB::getConnection();
			$st = $db->prepare( 'SELECT * FROM Files WHERE id_note=:id_note');
			$st->execute( array( 'id_note' => $id_note )  );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
		// nije postojala pa je dodaj novu
		if( $st->rowCount() !== 1 ){
			$this->uploadFile($id_note);
		}
		// inace updejtaj novu datoteku
		// razlika jedino sto id ostaje isti --- id datoteke i biljeske
		else{


			$row = $st->fetch();
			$id = $row['id']; //id filea koji je vec bio
			$filename_to_remove = $this->getFilenameByNoteId($id_note);

			// obrisi staru datoteku
			$filepath = realpath(dirname(__FILE__) . '/..').'/uploads/' . $filename_to_remove;
			if (is_file($filepath))
				unlink($filepath);


			$new_user_filename = $_FILES['new_file']['name'];
			$filename = $id_note . $new_user_filename;
			$destination = realpath(dirname(__FILE__) . '/..').'/uploads/' . $filename;
			$extension = pathinfo( $new_user_filename, PATHINFO_EXTENSION );
			$file = $_FILES['new_file']['tmp_name'];
			$size = $_FILES['new_file']['size'];

			//update db
			if(!in_array($extension, ['csv', 'txt'])){
				echo '<script language="javascript">';
				echo 'alert("Your file extension must be .csv or .txt")';
				echo '</script>';
			}
			elseif ($_FILES['new_file']['size'] > 1000000){ //ne više od 1mb
				echo '<script language="javascript">';
				echo 'alert("File too large!")';
				echo '</script>';
			}
			else{
				// move the uploaded (temporary) file to the specified destination
				if (move_uploaded_file($file, $destination) ) {
					try{
						$db = DB::getConnection();
						$st = $db->prepare( 'UPDATE Files SET id=:id, id_note=:id_note, user_filename=:new_user_filename, filename=:filename, size=:size WHERE id=:id' );
						$st->execute( array( 'id' => $id, 'id_note'=>$id_note, 'new_user_filename' => $new_user_filename, 'filename'=>$filename, 'size' => $size) );
						// echo "File uploaded successfully";
						return $new_user_filename;
					}
					catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }
				}
				else {
					echo "Failed to upload file.";
					echo "filename " . $new_user_filename;
					echo " extension" . $extension;
					echo $_FILES['new_file']['error'];
				}
				return false;
			}
		}
	}
}

?>
