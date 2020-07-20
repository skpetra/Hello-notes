$(document).ready(function() {
     var initial = true; // detektor za početak popisa dodanih kolaboratora
      var kolab = new Array(); // popis dodanih
      var initial_rc = true; // detektor za početak popisa maknutih kolaboratora
      var kolab_rc = new Array(); // popis maknutih
    $('button.test').on('click', function(){
      // ponovno 'forma' za dodavanje kolaboratora
      var mjesto = $(this).position();
      var height = Number($(this).height());
      var width = Number($(this).width());
      console.log(mjesto);
      var dohv = $('#popis');
      dohv.css('top', (Number(mjesto.top) + height + 7)+ 'px');
      dohv.css('left', (Number(mjesto.left) + width + 15) + 'px');
      dohv.css('display', 'table-cell');
    });
    $('button.remove_collab').on('click', function(){
      // 'forma' za micanje kolaboratora
      var mjesto = $(this).position();
      var height = Number($(this).height());
      var width = Number($(this).width());
      console.log(mjesto);
      var dohv = $('#popis_rc');
      dohv.css('top', (Number(mjesto.top) + height + 7)+ 'px');
      dohv.css('left', (Number(mjesto.left) + width + 15) + 'px');
      dohv.css('display', 'table-cell');
    });
    $('#collab').on('input', function(e){
      // prijedlozi postojećih username-ova
      var unos = $( this ).val();
      $.ajax({
        url: "index.php?rt=notes/suggest",
        data:
        {
            q: unos
        },
        success: function( data )
        {
          console.log(data);
          $( "#datalist_imena" ).html( data );
        },
        error: function(xhr, status){
          console.log("Greška: " + status);
        }
      });
    });
    $('#remove_c').on('input', function(e){
      // također prijedlozi postojećih username-ova
      var unos = $( this ).val();
      $.ajax({
        url: "index.php?rt=notes/suggest",
        data:
        {
            q: unos
        },
        success: function( data )
        {
          console.log(data);
          $( "#datalist_imena_rc" ).html( data );
        },
        error: function(xhr, status){
          console.log("Greška: " + status);
        }
      });
    });
    $('#send-name').on('click', function(){
      // jednako kao u new_note.js, handle-a se username potencijalnog kolaboratora
      var unos = $('#collab').val();
      $.ajax({
        url: "index.php?rt=notes/collab",
        data:
        {
            q: unos
        },
        success: function( data )
        {
          console.log(data);
          if(kolab_rc.includes(unos)) $('#poruka').html('This user is already chosen<br>to be removed from this note.');
          else if(data == '0') $('#poruka').html('Error: invalid username!');
          else if (data == '2') $('#poruka').html('Warning: this is your username.');
          else if (data == '-1') $('#poruka').html('This user already is a collaborator.');
          else  {
            $('#poruka').html('User found!');
            if (!kolab.includes(data)) kolab.push(data);
            if (initial) {
              initial = false;
              $('#list_of_collabs').append('List of added collaborators:');
            }
            $('#list_of_collabs').append('<br>');
            $('#list_of_collabs').append(data);
          }

          console.log(kolab);

        },
        error: function(xhr, status){
          console.log("Greška: " + status);
        }
      });
    });
    $('#send-name-rc').on('click', function(){
      /*
      Slanjem username-a korisnika koji se želi maknuti s liste kolaboratora,
      provjerava se može li se ta akcija izvršiti. Stoga se pazi da je taj
      username validan (i da uopće postoji u bazi), da je doista riječ o nekom
      kolaboratoru, da nije riječ o trenutnom korisniku i da taj username
     već nije odabran za dodavanje na listu kolaboratora.
      */
      var unos = $('#remove_c').val();
      $.ajax({
        url: "index.php?rt=notes/remove_collab",
        data:
        {
            q: unos,
            id: $('#popis_rc').attr('class')
        },
        success: function( data )
        {
          console.log(data);
          if(kolab.includes(unos)) $('#poruka_rc').html('This user is already<br>chosen as a collaborator.');
          else if(data == '0') $('#poruka_rc').html('Error: invalid username!');
          else if (data == '2') $('#poruka_rc').html('Warning: this is your username.');
          else if (data == '-1') $('#poruka_rc').html('This user is not a collaborator<br>on this note.');
          else if (data == '-2') $('#poruka_rc').html('You already chose this user.');
          else  {
            $('#poruka_rc').html('User found!');
            if (!kolab_rc.includes(data)) kolab_rc.push(data);
            if (initial_rc) {
              initial_rc = false;
              $('#list_of_collabs_rc').append('List of removed collaborators:');
            }
            $('#list_of_collabs_rc').append('<br>');
            $('#list_of_collabs_rc').append(data);
          }

          console.log(kolab_rc);

        },
        error: function(xhr, status){
          console.log("Greška: " + status);
        }
      });
    });
    $('#popis').on('click', '#exit-list', function(){
      var dohv = $('#popis');
      dohv.css('display', 'none');
    });
    $('#popis_rc').on('click', '#exit-list-rc', function(){
      var dohv = $('#popis_rc');
      dohv.css('display', 'none');
    });
  });
