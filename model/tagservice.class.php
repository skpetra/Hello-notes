<?php

require_once __DIR__ . '/../app/database/db.class.php';
require_once __DIR__ . '/tag.class.php';
require_once __DIR__ . '/noteservice.class.php';

class TagService {
	// funkcija kojom se dobiva popis tagova koje je stvorio korisnik s ID-jem
  function getTagsByUserID($id) {
    try
		{
			$db = DB::getConnection();
			$st = $db->prepare( 'SELECT name, id_user, cr, cg, cb FROM Tags WHERE id_user=:id' );
			$st->execute( array( 'id' => $id ) );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

    $arr = array();
		while( $row = $st->fetch() )
		{
			$new = new Tag($row['name'], $row['id_user'], $row['cr'], $row['cg'], $row['cb']);
      $arr[] = $new;
		}

		return $arr;
  }
    /* funkcija koja vraća sve tagove u kojima se nalaze bilješke na kojima je
    korisnik s danim ID-jem kolaborator */
  function getCollabTag($id) {
    $ns = new NoteService();
    $notes = $ns->getNotesByCollabId($id);

    $tags = array();
    foreach ($notes as $note) {
      $tag = $this->getTagByNoteID($note->id);
      if(!in_array($tag, $tags) and $tag->name!=='#') $tags[]=$tag;
    }

		return $tags;
  }

  // funkcija koja vraća tag kojem pripada bilješka s određenim ID-jem
  function getTagByNoteID($id) {
    try
		{
			$db = DB::getConnection();
			$st = $db->prepare( 'SELECT Tags.* FROM Tags, Tag_relations, Notes ' . 'WHERE Tag_relations.id=:id AND Tag_relations.name=Tags.name AND Notes.id_user=Tags.id_user AND Notes.id=:id' );
			$st->execute( array( 'id' => $id ) );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

    $arr = array();
		if( $row = $st->fetch() ) {
		    $new = new Tag($row['name'], $row['id_user'], $row['cr'], $row['cg'], $row['cb']);
        return $new;
    }
    else {
      $new = new Tag('#', '#', '#', '#', '#');
      return $new;
    }
  }
// funkcija koja vraća sve bilješke koje pripadaju nekom tagu, kod određenog korisnika
  function getNotesByTagName($id_user, $name) {
    try
		{
			$db = DB::getConnection();
			$st = $db->prepare( 'SELECT Notes.* FROM Notes, Tag_relations, Tags' .
        ' WHERE Notes.id_user=:id_user AND Notes.id=Tag_relations.id AND ' .
        'Tag_relations.name=:name AND Tags.name=:name AND Tags.id_user=:id_user' );
			$st->execute( array( 'id_user' => $id_user, 'name' => $name ) );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

    $arr = array();
		while( $row = $st->fetch() )
		{
			$arr[] = new Note($row['id'], $row['id_user'], $row['title'], $row['content'], $row['date']);
		}

		return $arr;
  }

  // funkcija koja provjerava postoji li tag određenog imena kod određenog korisnika
  function tagExist($id_user, $name) {
    try
		{
			$db = DB::getConnection();
			$st = $db->prepare( 'SELECT * FROM Tags' .
        ' WHERE id_user=:id_user and name=:name' );
			$st->execute( array( 'id_user' => $id_user, 'name' => $name ) );
		}
		catch( PDOException $e ) { exit( 'PDO error ' . $e->getMessage() ); }

    while($row = $st->fetch()) return 1;
    return 0;
  }


}


 ?>
