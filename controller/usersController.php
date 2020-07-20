<?php

require_once __DIR__ . '/../model/noteservice.class.php';
require_once __DIR__ . '/../model/tagservice.class.php';


class UsersController
{
	// prikaz početne stranice za login ili registraciju
    public function start(){
		require_once __DIR__ . '/../view/users_start.php';
	}
	// prikaz stranice za login
    public function login(){
		$login_message="";
        require_once __DIR__ . '/../view/users_login.php';
	}
	// prikaz stranice za registraciju
    public function register(){
		$reg_message = "";
        require_once __DIR__ . '/../view/users_register.php';
    }

	// provjera logina
    public function check_login(){

		$ns = new NoteService();
		$login_message = "";

		if( isset( $_POST['username']) && ( $_POST['username'] !== "") ){ //napisan je username

			//sanitizacija
			$username = filter_var($_POST['username'],FILTER_SANITIZE_STRING);

            //provjeri je li username u bazi
			if( $ns->getIdByUsername($_POST['username']) !== null ){

				//dohvati lozinku tog studenta
				if(isset($_POST['username']))
					$pass = $ns->getPasswordByUsername( $_POST['username'] );

				if(isset( $_POST['password'] )){
					if( password_verify($_POST['password'], $pass) ){
						//lozinka je dobra

						//zapamti ulogiranog korisnika
						$_SESSION['username'] = $username;
						$_SESSION['i'] = -1;
						$this->show_user_notes();
						exit();
					}
				}
			}
			//ovdje cemo doci ako: username je null ILI pass nije postavljen
			$login_message = "You have entered an invalid username or password.";

			//vrati na login
			require_once __DIR__ . '/../view/users_login.php';
			exit();
		}
		else{
            $login_message = "Fill in all required entry fields!";
            require_once __DIR__ . '/../view/users_login.php';
		}
    }

	// provjera registracije korisnika
    public function check_register(){

		$ns = new NoteService();
		$reg_message = "";
		$errors = array('name' => 0, 'surname' => 0, 'username' => 0, 'password' => 0);
		if( !isset($_POST['new_name']) || !preg_match( '/^[a-žA-Ž]+[ ]?[a-žA-Ž]*$/',$_POST['new_name'])) {

			$errors['name'] = 1;
		}
		if( !isset($_POST['new_surname']) || !preg_match( '/^[a-žA-Ž]+[ |-]?[a-žA-Ž]*$/',$_POST['new_surname'])) {

			$errors['surname'] = 1;
		}
		if( !isset($_POST['new_username']) || !preg_match( '/^[a-žA-Ž0-9_]{2,}$/',$_POST['new_username'])) {

			$errors['username'] = 1;
		}
		if( !isset($_POST['new_password']) || !preg_match( '/^[a-žA-Ž0-9]{6,}$/',$_POST['new_password'])) {

			$errors['password'] = 1;
		}

		if ( $errors['name'] !== 0 || $errors['surname'] !== 0 || $errors['username'] !== 0 || $errors['password'] !== 0)
		{
		?>
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.js"></script>
			<script type="text/javascript" > </script>
			<?php
			$i = -1;
			foreach( $errors as $key => $value){

				$i++;
				$text = $_POST['new_' . $key];
			?>
			<script>
				$(document).ready( function()
				{
					var i='<?php echo $i;?>';
					var object ={
						key:'<?php echo $key; ?>',
						value: '<?php echo $value; ?>',
						text: '<?php echo $text; ?>'
					};
					localStorage.setItem(i, JSON.stringify(object));
				});
			</script>
			<?php } ?>
			<script src="javascript/check_inputs.js"> </script>
			<?php
			$reg_message = "Fill in all required entry fields!";
			require_once __DIR__ . '/../view/users_register.php';
			exit();
		}

		//ako postoji vec korisnicno ime
		$user_check = $ns->getIdByUsername($_POST['new_username']);

		if( $user_check === null){

			//sanitiziraj
			$name = filter_var($_POST['new_name'], FILTER_SANITIZE_STRING);
			$surname = filter_var($_POST['new_surname'], FILTER_SANITIZE_STRING);
			$username = filter_var($_POST['new_username'], FILTER_SANITIZE_STRING);
			$password_hash = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

			$birth_date= null;
			if (isset($_POST['new_bdate']) && $_POST['new_bdate']!='')
				$birth_date = $_POST['new_bdate'];

			if(isset($_POST['new_about']))
				$about = $_POST['new_about'];
			else $about = '';

			//dodaj u bazu
			$ns->add_new_user($name, $surname, $username, $password_hash, $birth_date, $about);

			//zapamti ulogiranog korisnika
			$_SESSION['username'] = $_POST['new_username'];

			$this->welcome();
			exit();
		}
		else{
			?>
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.js" type="text/javascript"></script>
			<script type="text/javascript" > </script>
			<script>
			$(document).ready( function () {
				var name = '<?php echo $_POST['new_name'] ?>';
				var surname = '<?php echo $_POST['new_surname'] ?>';
				var password = '<?php echo $_POST['new_password'] ?>';
				$('[name="new_name"]').val(name);
				$('[name="new_surname"]').val(surname);
				$('[name="new_password"]').val(password);
				$('[name="new_username"]').css('border-bottom', '2px solid red');
			});
			</script>
			<?php
			$reg_message = "Username already exists. Try again!";
			require_once __DIR__ . '/../view/users_register.php';
			exit();
		}
	}


	//obradi logout
	public function logout(){

		session_unset();
		session_destroy();

		$this->start();
		exit();
	}


    public function show_user_details(){

        $note = new NoteService();
        $name = $note->getNameByUsername($_SESSION['username']);
        $surname = $note->getSurnameByUsername($_SESSION['username']);
        $birth_date =  $note->getBdateByUsername($_SESSION['username']);
        $about = $note->getAboutByUsername($_SESSION['username']);

        require_once __DIR__ . '/../view/user_details.php';
    }


	// prikaz svih korisnikovih bilješki
    public function show_user_notes(){

		$ns = new NoteService();
		$user_id = $ns->getIdByUsername($_SESSION['username']);

		// treba provjeriti traže li se bilješke s određenim tagom
		if (isset($_POST["tag_name"])) {

			$info = explode('_', $_POST["tag_name"]);
			$tag_name = $info[0];
			$id_creator = $info[1];
			$ts = new TagService();
			// razlikujemo slučajeve kada je odabran tag trenutnog korisnika
			//  od slučaja kada je odabran tag nekog kolaboratora

			if ($user_id == $id_creator) {
				// ovo je slučaj kada je odabran tag trenutnog korisnika
				$notes = $ts->getNotesByTagName($user_id, $tag_name);
				$collab_notes = $ns->getNotesByCollabIdandtagName($user_id, $tag_name);
				}
			else {
			/*
			ovo je slučaj kad je odabran tag kolaboratora.
			Pazimo da prikazujemo samo bilješke koje je taj kolaborator
			podijelio s trenutnim kornsikom!
			*/
				try{
					$db = DB::getConnection();
					$st = $db->prepare( 'SELECT id_note FROM Collaborators, Tag_relations, Tags' .
						' WHERE Collaborators.id_collaborator=:id_user AND Collaborators.id_note=Tag_relations.id AND ' .
						'Tag_relations.name=:name AND Tags.name=:name AND Tags.id_user=:id_creator' );
					$st->execute( array( 'id_user' => $user_id, 'name' => $tag_name, 'id_creator'=>$id_creator ) );
				}
				catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

				$notes = array();
				while( $row = $st->fetch() ){

					$note = $ns->getNoteById($row['id_note']);
					$notes[] = $note;
				}
				$collab_notes = array();
			}
		}
      	else {
			$notes = $ns->getNotesByUserId($user_id);
			$collab_notes = $ns->getNotesByCollabId($user_id);
      	}
     	$notes = array_merge($notes, $collab_notes);

      	require_once __DIR__ . '/../view/users_user_notes.php';
    }

    public function welcome(){
        $ns = new NoteService();
        require_once __DIR__ . '/../view/users_welcome.php';
    }


	public function show_update_details(){

        $note = new NoteService();
        $name = $note->getNameByUsername($_SESSION['username']);
        $surname = $note->getSurnameByUsername($_SESSION['username']);
        $birth_date =  $note->getBdateByUsername($_SESSION['username']);
        $about = $note->getAboutByUsername($_SESSION['username']);

        require_once __DIR__ . '/../view/users_update_details.php';
	}

	public function show_update_password(){

		require_once __DIR__ . '/../view/user_update_password.php';
	}

	public function update_password(){

		$ns = new NoteService();
		if ($ns->user_update_password()){
			echo '<script language="javascript">';
			echo 'alert("Uspješno promjenjena")';
			echo '</script>';
			$this->show_user_notes();
		}
		else{
			echo '<script language="javascript">';
			echo 'alert(" lozinka bla bla, pokušajte ponovno!")';
			echo '</script>';
			$this->show_update_password();
		}
	}

    public function update_details(){

		$ns = new NoteService();
		$ns->user_update_details();
		$name = $ns->getNameByUsername($_SESSION['username']);
        $surname = $ns->getSurnameByUsername($_SESSION['username']);
        $birth_date =  $ns->getBdateByUsername($_SESSION['username']);
		$about = $ns->getAboutByUsername($_SESSION['username']);

        require_once __DIR__ . '/../view/user_details.php';
	}

	public function how_to() {
		// prikaz about (how to) sekcije
		require_once __DIR__ . '/../view/how_to.php';
	}
}
?>
