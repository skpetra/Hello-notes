<?php

// Popunjavamo tablice u bazi "probnim" podacima.

require_once __DIR__ . '/db.class.php';

//seed_table_users();
//seed_table_notes();
//seed_table_tags();
//seed_table_relations();
//seed_table_files();
//seed_table_collaborators();

exit( 0 );

//--------------------------------------------

function seed_table_users() {
  $db = DB::getConnection();

	// Ubaci korisnike u bazu
	try
	{
		$st = $db->prepare( 'INSERT INTO Users(name, surname, username, password_hash, birth_date, about, facebook_user) VALUES (:name, :surname, :username, :password_hash, :birth_date, :about, :facebook_user)' );

		$st->execute( array('name'=> 'Maja', 'surname'=> 'Majić', 'username'=>'majaa567', 'password_hash' => password_hash('majinasifra', PASSWORD_DEFAULT), 'birth_date'=>date('1993-01-01'), 'about'=>'Moje ime je Maja.', 'facebook_user'=>'0'));
		$st->execute( array('name'=> 'Ivan', 'surname'=> 'Ivić', 'username'=>'ivek99', 'password_hash' => password_hash('12345678', PASSWORD_DEFAULT), 'birth_date'=>date('1990-03-03'), 'about'=>'Nešto o meni.', 'facebook_user'=>'0'));
		$st->execute( array('name'=> 'Margarita', 'surname'=> 'Tolja', 'username'=>'megy0505', 'password_hash' => password_hash('marginasifra', PASSWORD_DEFAULT), 'birth_date' => date('1996-10-24'), 'about'=> 'Ja sam Marđi', 'facebook_user'=>'0'));
		$st->execute( array('name'=> 'Marko', 'surname'=> 'Horvat', 'username'=>'markec1207', 'password_hash' => password_hash('markovasifra', PASSWORD_DEFAULT), 'birth_date' => date('2000-12-07'), 'about'=> 'Marko. 20 godina.', 'facebook_user'=>'0'));
    $st->execute( array('name'=> 'Dominik', 'surname'=> 'Mik', 'username'=>'domy123', 'password_hash' => password_hash('domysifra', PASSWORD_DEFAULT), 'birth_date' => date('1997-08-06'), 'about'=> 'Dominikkkkk xD', 'facebook_user'=>'0'));
	}
  catch( PDOException $e ) { exit( "PDO error [insert Users]: " . $e->getMessage() ); }
  
	// echo "Ubacio u tablicu Users.<br />";

}

//--------------------------------------------
function seed_table_notes() {
  $db = DB::getConnection();

  // Ubaci bilješke u bazu
  try {
    $st = $db->prepare( 'INSERT INTO Notes(id_user, title, content, date) VALUES (:id_user, :title, :content, :date)' );

    $st->execute(array('id_user'=>'1', 'title'=>'Prva bilješka', 'content'=>'Ovdje testiram kako izgledaju bilješke!', 'date'=>date('2020-07-01')));
    $st->execute(array('id_user'=>'1', 'title'=>'Druga bilješka', 'content'=>'Ovdje također testiram kako izgledaju bilješke!', 'date'=>date('2020-07-01')));
    $st->execute(array('id_user'=>'1', 'title'=>'Legitimna bilješka', 'content'=>'Prebaciti sve pisane bilješke iz bilježnice ovdje!', 'date'=>date('2020-07-01')));
    $st->execute(array('id_user'=>'1', 'title'=>'Ujutro', 'content'=>'Buđenje u 6.30h, banana, trčanje, doručak!', 'date'=>date('2020-07-03')));
    $st->execute(array('id_user'=>'1', 'title'=>'Dobre serije za pogledati', 'content'=>'Dark, 12 Monkeys, The OA, Battlestar Galactica, Westworld', 'date'=>date('2020-07-04')));
    $st->execute(array('id_user'=>'1', 'title'=>'Treća bilješka', 'content'=>'Ovo pišem jer nemam puno mašte', 'date'=>date('2020-07-05')));
    $st->execute(array('id_user'=>'1', 'title'=>'Četvrta bilješka', 'content'=>'Doista je teško osmišljati smislene podatke za bazu', 'date'=>date('2020-07-05')));

    $st->execute(array('id_user'=>'2', 'title'=>'Moja prva bilješka', 'content'=>'Ovdje testiram kako izgledaju bilješke!', 'date'=>date('2020-07-01')));
    $st->execute(array('id_user'=>'2', 'title'=>'Tagovi', 'content'=>'Ovo je prva bilješka kojoj dodajem tag!', 'date'=>date('2020-07-01')));
    $st->execute(array('id_user'=>'2', 'title'=>'Napomena', 'content'=>'Dodati Maju i Dominika kao kolaboratore.', 'date'=>date('2020-07-02')));
    $st->execute(array('id_user'=>'2', 'title'=>'Kajdanke', 'content'=>'Idi u Melodiju (Masaryova) kupiti kajdanke (2xA4, 2xB5)', 'date'=>date('2020-07-02')));
    $st->execute(array('id_user'=>'2', 'title'=>'Partiture', 'content'=>'Isprintaj Mozartov Requiem i Schumannov Dichterliebe', 'date'=>date('2020-07-03')));
    $st->execute(array('id_user'=>'2', 'title'=>'Još nešto', 'content'=>'Smisli još nešto što ćeš ubaciti u neku bilješku', 'date'=>date('2020-07-04')));
    $st->execute(array('id_user'=>'2', 'title'=>'Arvo Part', 'content'=>'Poslušaj album estonske filharmonije "Da Pacem"', 'date'=>date('2020-07-05')));

    $st->execute(array('id_user'=>'3', 'title'=>'Prva bilješka', 'content'=>'Ovdje testiram kako izgledaju bilješke!', 'date'=>date('2020-07-01')));
    $st->execute(array('id_user'=>'3', 'title'=>'Popis za dućan', 'content'=>'kruh, mlijeko, kola, keksi(Oreo)', 'date'=>date('2020-07-01')));
    $st->execute(array('id_user'=>'3', 'title'=>'Pošta', 'content'=>'Poslati paket strini u Americi (rođendan)!', 'date'=>date('2020-07-02')));
    $st->execute(array('id_user'=>'3', 'title'=>'Ključevi', 'content'=>'Napravi kopiju ključeva za novi stan.', 'date'=>date('2020-07-03')));
    $st->execute(array('id_user'=>'3', 'title'=>'Još neke stvari', 'content'=>'Ostalo je još par kutija u kući koje treba prebaciti u stan...', 'date'=>date('2020-07-03')));
    $st->execute(array('id_user'=>'3', 'title'=>'Muzika', 'content'=>'Napravi neku playlist za trčanje', 'date'=>date('2020-07-04')));

    $st->execute(array('id_user'=>'4', 'title'=>'Prva bilješka', 'content'=>'Ovdje testiram kako izgledaju bilješke!', 'date'=>date('2020-07-01')));
    $st->execute(array('id_user'=>'4', 'title'=>'Podsjetnik', 'content'=>'Prebacio si podatke iz jednog foldera u drugi, još ih treba urediti!', 'date'=>date('2020-07-02')));
    $st->execute(array('id_user'=>'4', 'title'=>'Financije', 'content'=>'Dobro prođi i uredi tablicu s financijama. Pazi na greške.', 'date'=>date('2020-07-02')));
    $st->execute(array('id_user'=>'4', 'title'=>'Posao-sastanak', 'content'=>'VAŽNO!!! Sastanak 9. srpnja u 18h!!!', 'date'=>date('2020-07-03')));
    $st->execute(array('id_user'=>'4', 'title'=>'Doma', 'content'=>'Odmori. Dug dan je bio', 'date'=>date('2020-07-03')));
    $st->execute(array('id_user'=>'4', 'title'=>'Večera', 'content'=>'Večera s predsjednikom 18. srpnja u 19.15h', 'date'=>date('2020-07-05')));

    $st->execute(array('id_user'=>'5', 'title'=>'Prva bilješka', 'content'=>'Ovdje testiram kako izgledaju bilješke!', 'date'=>date('2020-07-01')));
    $st->execute(array('id_user'=>'5', 'title'=>'Praktikum', 'content'=>'Treba dovršiti projekt za praktikum... Još seed tables i komentari na kod!', 'date'=>date('2020-07-03')));
    $st->execute(array('id_user'=>'5', 'title'=>'Instrukcije', 'content'=>'Danas u 17h, matematika, 6. osnovne', 'date'=>date('2020-07-04')));
    $st->execute(array('id_user'=>'5', 'title'=>'Popis za dućan', 'content'=>'kruh, mlijeko, kola, keksi(Oreo)', 'date'=>date('2020-07-04')));
    $st->execute(array('id_user'=>'5', 'title'=>'Obveze', 'content'=>'Ići u dućan, prošetati psa, platit račune', 'date'=>date('2020-07-04')));
    $st->execute(array('id_user'=>'5', 'title'=>'IP', 'content'=>'Stpljivo čekati rezultate', 'date'=>date('2020-07-05')));
    $st->execute(array('id_user'=>'5', 'title'=>'Na jesen', 'content'=>'Nadajmo se samo statistika', 'date'=>date('2020-07-06')));
  } 
	catch (PDOException $e) {
		exit( "PDO error [insert Notes]: " . $e->getMessage() );
  }

  // echo "Ubacio u tablicu Notes.<br />";

}

//---------------------------------------------------
function seed_table_tags() {
  $db = DB::getConnection();

  // Ubaci tagove u bazu
  try {
	$st = $db->prepare('INSERT INTO Tags(name, id_user, cr, cg, cb) VALUES (:name, :id_user, :cr, :cg, :cb)');

	$st->execute(array('name'=>'Test bilješke', 'id_user'=>'1', 'cr'=>'9', 'cg'=>'125', 'cb'=>'121'));
	$st->execute(array('name'=>'Glazba', 'id_user'=>'2', 'cr'=>'47', 'cg'=>'138', 'cb'=>'89'));
	$st->execute(array('name'=>'Ostalo', 'id_user'=>'2', 'cr'=>'121', 'cg'=>'83', 'cb'=>'184'));
	$st->execute(array('name'=>'Selidba', 'id_user'=>'3', 'cr'=>'144', 'cg'=>'163', 'cb'=>'0'));
	$st->execute(array('name'=>'Svašta', 'id_user'=>'3', 'cr'=>'219', 'cg'=>'46', 'cb'=>'147'));
	$st->execute(array('name'=>'Službeno', 'id_user'=>'4', 'cr'=>'50', 'cg'=>'185', 'cb'=>'220'));
	$st->execute(array('name'=>'Ništa', 'id_user'=>'4', 'cr'=>'115', 'cg'=>'112', 'cb'=>'195'));
	$st->execute(array('name'=>'Faks', 'id_user'=>'5', 'cr'=>'59', 'cg'=>'40', 'cb'=>'84'));
	$st->execute(array('name'=>'Doma', 'id_user'=>'5', 'cr'=>'0', 'cg'=>'97', 'cb'=>'10'));
	$st->execute(array('name'=>'Ostalo', 'id_user'=>'5', 'cr'=>'61', 'cg'=>'186', 'cb'=>'140'));
  } catch (PDOException $e) {
    exit( "PDO error [insert Tags]: " . $e->getMessage() );
  }
  // echo "Ubacio u tablicu Tags.<br />";
}

//-------------------------------------------------------
function seed_table_relations() {
  $db = DB::getConnection();

  // Ubaci odnose u bazu

  try {
    $st = $db->prepare('INSERT INTO Tag_relations(name, id) VALUES (:name, :id)');

    $st->execute(array('name'=>'Test bilješke', 'id'=>'1'));
    $st->execute(array('name'=>'Test bilješke', 'id'=>'2'));
    $st->execute(array('name'=>'Test bilješke', 'id'=>'6'));
    $st->execute(array('name'=>'Test bilješke', 'id'=>'7'));
    $st->execute(array('name'=>'Glazba', 'id'=>'11'));
    $st->execute(array('name'=>'Glazba', 'id'=>'12'));
    $st->execute(array('name'=>'Glazba', 'id'=>'14'));
    $st->execute(array('name'=>'Ostalo', 'id'=>'8'));
    $st->execute(array('name'=>'Ostalo', 'id'=>'10'));
    $st->execute(array('name'=>'Selidba', 'id'=>'18'));
    $st->execute(array('name'=>'Selidba', 'id'=>'19'));
    $st->execute(array('name'=>'Svašta', 'id'=>'16'));
    $st->execute(array('name'=>'Svašta', 'id'=>'17'));
    $st->execute(array('name'=>'Svašta', 'id'=>'20'));
    $st->execute(array('name'=>'Službeno', 'id'=>'22'));
    $st->execute(array('name'=>'Službeno', 'id'=>'23'));
    $st->execute(array('name'=>'Službeno', 'id'=>'24'));
    $st->execute(array('name'=>'Službeno', 'id'=>'26'));
    $st->execute(array('name'=>'Faks', 'id'=>'28'));
    $st->execute(array('name'=>'Faks', 'id'=>'32'));
    $st->execute(array('name'=>'Faks', 'id'=>'33'));
    $st->execute(array('name'=>'Doma', 'id'=>'30'));
    $st->execute(array('name'=>'Doma', 'id'=>'31'));
  } catch (PDOException $e) {
    exit( "PDO error [insert Tag_relations]: " . $e->getMessage() );
  }
  // echo "Ubacio u tablicu Tag_relations.<br />";

}

//-------------------------------------------------------------
function seed_table_files() {
  $db = DB::getConnection();

  // Ubaci datoteke u bazu

  try {
    $st = $db->prepare('INSERT INTO Files(id, id_note, user_filename, filename, size) VALUES (:id, :id_note, :user_filename, :filename, :size)');

    $st->execute(array('id'=>'1', 'id_note'=>'21', 'user_filename'=>'datoteka_biljeske.txt', 'filename'=>'21_datoteka_biljeske.txt', 'size'=>'57'));
    $st->execute(array('id'=>'2', 'id_note'=>'23', 'user_filename'=>'financije.csv', 'filename'=>'23_financije.csv', 'size'=>'822'));
    $st->execute(array('id'=>'3', 'id_note'=>'16', 'user_filename'=>'financije.csv', 'filename'=>'16_financije.csv', 'size'=>'351'));
    $st->execute(array('id'=>'4', 'id_note'=>'20', 'user_filename'=>'note.txt', 'filename'=>'20_note.txt', 'size'=>'341'));
  } catch (PDOException $e) {
    exit( "PDO error [insert Files]: " . $e->getMessage() );
  }
  // echo "Ubacio u tablicu Files.<br />";

}

//------------------------------------------------------------
function seed_table_collaborators() {
  $db = DB::getConnection();

  // Ubaci kolaboratore u bazu

  try {
    $st = $db->prepare('INSERT INTO Collaborators(id_collaborator, id_note) VALUES (:id_collaborator, :id_note)');

    $st->execute(array('id_collaborator'=>'1', 'id_note'=>'10'));
    $st->execute(array('id_collaborator'=>'5', 'id_note'=>'10'));
    $st->execute(array('id_collaborator'=>'2', 'id_note'=>'26'));
    $st->execute(array('id_collaborator'=>'3', 'id_note'=>'24'));
    $st->execute(array('id_collaborator'=>'4', 'id_note'=>'28'));
    $st->execute(array('id_collaborator'=>'4', 'id_note'=>'5'));
    $st->execute(array('id_collaborator'=>'5', 'id_note'=>'5'));

  } catch (PDOException $e) {
    exit( "PDO error [insert Collaborators]: " . $e->getMessage() );
  }
  // echo "Ubacio u tablicu Collaborators.<br />";

}


 ?>
