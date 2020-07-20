<?php

require_once 'db.class.php';

// echo "Zatražili bazu....", "<br>";

$db = DB::getConnection();

// echo "Tražili vezu....", "<br>";

//------------ KORISNICI ---------------

try{
  $st = $db -> prepare(
    'CREATE TABLE IF NOT EXISTS Users (' .
    'id int NOT NULL PRIMARY KEY AUTO_INCREMENT,' .
    'name varchar(50) NOT NULL,' .
    'surname varchar(50) NOT NULL,' .
    'username varchar(50) NOT NULL unique,' .
    'password_hash varchar(255) NOT NULL,' .
    'birth_date DATE DEFAULT NULL,' .
    'about varchar(10000) DEFAULT NULL,' .
    'facebook_user BOOLEAN NOT NULL DEFAULT FALSE)'
  );

  $st -> execute();
}
catch( PDOException $e ){ exit( "PDO error #1: " . $e->getMessage() ); }

// echo "Stvorena tablica korisnici. <br>";


//----------- BILJEŠKE ---------------------

try{
  $st = $db -> prepare(
    'CREATE TABLE IF NOT EXISTS Notes (' .
    'id int NOT NULL PRIMARY KEY AUTO_INCREMENT,' .
    'id_user INT NOT NULL,' .
    'title varchar(50) NOT NULL,' .
    'content varchar(10000) NOT NULL,' .
    'date DATETIME NOT NULL)'
  );

  $st -> execute();
}
catch( PDOException $e ){ exit( "PDO error #1: " . $e->getMessage() ); }

// echo "Stvorena tablica biljeske. <br>";


//-------------- TAGOVI ---------------

try{
  $st = $db -> prepare(
    'CREATE TABLE IF NOT EXISTS Tags (' .
    'name varchar(50) NOT NULL,' .
    'id_user int NOT NULL,' .
    'cr int,' .
    'cg int,' .
    'cb int,' .
    'primary key (name, id_user))'
  );

  $st -> execute();
}
catch( PDOException $e ){ exit( "PDO error #1: " . $e->getMessage() ); }

// echo "Stvorena tablica tagovi. <br>";


//------------ ODNOSI ----------------

try{
  $st = $db -> prepare(
    'CREATE TABLE IF NOT EXISTS Tag_relations (' .
    'name varchar(50) NOT NULL,' .
    'id int NOT NULL PRIMARY KEY)'
  );

  $st -> execute();
}
catch( PDOException $e ){ exit( "PDO error #1: " . $e->getMessage() ); }

// echo "Stvorena tablica odnosi. <br>";


// --------------------- BILJESKE_DATOTEKE ----------------------

try{
  $st = $db->prepare(
    'CREATE TABLE IF NOT EXISTS Files(' .
    'id int NOT NULL PRIMARY KEY AUTO_INCREMENT,' .
    'id_note int NOT NULL,' .
    'user_filename varchar(255) NOT NULL ,' .
    'filename varchar(255) NOT NULL ,' .
    'size int NOT NULL)'
  );

  $st->execute();
}
catch( PDOException $e ) { exit( "PDO error #1: " . $e->getMessage() ); }

// echo "Napravio tablicu biljeske_datoteke.<br />";


// --------------------- KOLABORATORI ----------------------
try{
  $st = $db->prepare(
    'CREATE TABLE IF NOT EXISTS Collaborators(' .
    'id_collaborator int NOT NULL,' .
    'id_note int NOT NULL ,' .
    'primary key (id_collaborator, id_note))'
  );

  $st->execute();
}
catch( PDOException $e ) { exit( "PDO error #1: " . $e->getMessage() ); }

// echo "Napravio tablicu kolaboratori.<br />";

 ?>
