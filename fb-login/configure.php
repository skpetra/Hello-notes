<?php
// Potrebno zbog Facebook klase.
require_once __DIR__ . '/vendor/autoload.php';

if (! session_id())
	session_start();

// U $fb spremamo id nase aplikacije kojeg smo dohvatili 
// sa "Facebook For Developers" stranice.
$fb = new Facebook\Facebook([
    'app_id' => '267024524641529',
    'app_secret' => 'cd53f85925307b43b778d96f1571473b',
    'default_graph_version' => 'v2.5',
	'persistent_data_handler' => 'session',
]);

$helper = $fb->getRedirectLoginHelper();

if (isset($_GET['state'])) {
    $helper->getPersistentDataHandler()->set('state', $_GET['state']);
}

$redirect = "https://rp2.studenti.math.hr/~dommiku/hellonotes/fb-login/callback.php";

// Za prava
$permissions = ['email'];

// Presumjeravamo na skriptu callback.php gdje se korisnik, 
// ako se korisnik uspjesno logirao, preusmjerava na naslovnicu
// nase aplikacije.
$login_url = $helper->getLoginUrl($redirect, $permissions);

?>
