<?php

session_start();

// inicijalizacija baze ukoliko već nije inicijalizirana
if (!isset($_SESSION['base_init'])) {
    require_once __DIR__ . '/app/database/prepareDB.php';
    $_SESSION['base_init'] = 'true';
}

// početno pristupamo stranici koja daje registraciju ili login
if ( !isset( $_GET['rt']) ){
    $controller = 'users';
    $action = 'start';
}
else{
    $parts = explode('/', $_GET['rt']);

    if ( isset( $parts[0]) && preg_match('/^[A-Za-z0-9  _]+$/', $parts[0]) )
        $controller = $parts[0];
    else
        $controller = 'users';

    if ( isset( $parts[1]) && preg_match('/^[A-Za-z0-9 _]+$/', $parts[1]) )
        $action = $parts[1];
    else
        $action = 'show_user_notes';
}

$controllerName = $controller . 'Controller';

if( !file_exists(__DIR__ . '/controller/' . $controllerName . '.php'))
    error_404();

require_once __DIR__ . '/controller/' . $controllerName . '.php';

if (!class_exists($controllerName))
    error_404();

$con = new $controllerName();

if(!method_exists($con, $action))
    error_404();

$con->$action();



// kontrola
// echo '<pre>$_GET=';
// print_r($_GET);
// echo '<pre>$_POST=';
// print_r($_POST);
// echo '<pre>$_FILES=';
// print_r($_FILES);
// echo '<br>$_SESSION=';
// print_r($_SESSION);
// echo'</pre>';

exit(0);

function error_404(){

    require_once __DIR__ . '/controller/_404Controller.php';
    $con = new _404Controller();
    $con->index();
    exit(0);
}

?>
