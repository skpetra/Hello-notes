$(document).ready(function() {

    var initial = true; // pomoćna varijabla za prikaz liste dodanih kolaboratora
	var kolab = new Array();
	
    $('button.test').on('click', function(){
		// klikom na tipku otvara se kućica za odabir kolaboratora
		var mjesto = $(this).position();
		var height = Number($(this).height());
		var width = Number($(this).width());
		console.log(mjesto);
		var dohv = $('#popis');
		dohv.css('top', (Number(mjesto.top) + height + 7)+ 'px');
		dohv.css('left', (Number(mjesto.left) + width + 15) + 'px');
		dohv.css('display', 'table-cell');
    });
    $('#collab').on('input', function(e){
		// svakim unosom znakova se nude mogući username-ovi koji sadržavaju te znakove
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
    $('#send-name').on('click', function(){
		/*
		Klikom na tipku, uneseni username šalje se skripti koja provjerava je li
		taj username uopće validan, je li već odabran kao kolaborator, je li možda
		riječ o vlastitom username-u...
		*/
		var unos = $('#collab').val();
		$.ajax({
			url: "index.php?rt=notes/collab",
			data:
			{
				q: unos
			},
			success: function( data )
			{
				// ovdje se handle-aju svi slučajevi:
				if(data == '0') $('#poruka').html('Error: invalid username!');
				else if (data == '2') $('#poruka').html('Warning: this is your username.');
				else if (data == '-1') $('#poruka').html('You already chose this user.');
				else  {
				/*
				Ako je sve u redu, ispiše se odgovarajuća poruka te se odabrani
				username dodaje na popis.
				*/
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
	
    $('#popis').on('click', '#exit-list', function(){
		// izlaz iz kućice
		var dohv = $('#popis');
		dohv.css('display', 'none');
    });
  });
