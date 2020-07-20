<?php

require_once __DIR__ . '/configure.php';
require_once __DIR__ . '/../model/noteservice.class.php';
require_once __DIR__ . '/../app/database/db.class.php';


	try {
		$accessToken = $helper->getAccessToken();
	}
	catch(\Facebook\Exceptions\FacebookResponseException $e) {
		echo 'Graph returned an error: ' . $e->getMessage();
		exit();
	}
	catch(\Facebook\Exceptions\FacebookSDKException $e){
		echo 'Facebook SDK returned an error: ' . $e->getMessage();
		exit();
	}

	// Vracamo se na pocetak aplikacije ako nismo dobili acesstoken.
	if( !isset($accessToken)){
		header('Location: https://rp2.studenti.math.hr/~dommiku/hellonotes/index.php?rt=users/start');
		exit();
	}

	//Longlived token
	$oAuth2Client = $fb->getOAuth2Client();
	try{
		$accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
	}
	catch(Facebook\Exceptions\FacebookSDKException $e) {
		echo $e->getMessage();
		exit;
	}

	// Moramo sacuvati accessToken.
	if (isset($accessToken)) {
		$_SESSION['accessToken'] = (string) $accessToken;
	}

	// Dohvacanje podataka korisnika i spremanje u session.
	$response = $fb->get("/me?fields=id, first_name, last_name, about, picture.type(large)", $accessToken);
	$userData = $response->getGraphNode()->asArray();
	$userData = $response->getDecodedBody();
	$_SESSION['userData'] = $userData;

	// Za unos u bazu.
	$first_name = $_SESSION['userData']['first_name'];
	$last_name = $_SESSION['userData']['last_name'];
	$username = $_SESSION['userData']['first_name'] . $_SESSION['userData']['id'];
	$password_hash = 'nepoznato';
	$birth_date=null;
	$about = '';
	$_SESSION['username'] = $username;
	$_SESSION['name'] = $first_name;


	$ns = new NoteService();
	// Ako ne postoji, dodaj novog korisnika
	if( $ns->getIdByUsername($username) === null){
		$ns->add_new_facebook_user($first_name, $last_name, $username, $password_hash, $birth_date, $about); 
		// Preusmjeravanje na naslovnicu.         
		$redirect=$url;
		header('Location: https://rp2.studenti.math.hr/~dommiku/hellonotes/index.php?rt=users/show_user_notes');
	}
	else{ // Ako postoji, preusmjeri ga na naslovnicu.
		$redirect=$url;
		header('Location: https://rp2.studenti.math.hr/~dommiku/hellonotes/index.php?rt=users/show_user_notes');
	}

	exit();
?>
