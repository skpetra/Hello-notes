$(document).ready(function() {

	$(document).on('contextmenu', 'button.tag_name', function(e){

		// desnim klikom na neki tag, pokreće se proces za brisanje tog taga
		var info = $(this).val().split('_');
		var tag_name = info[0];
		var id_creator = info[1];

		if (!confirm("Are you sure you want to delete this tag?"))
			return false;

		$.ajax({
			url: "index.php?rt=tags/delete_tag",
			data: {
				tag: tag_name,
				id_c: id_creator
			},
			success: function( data )
			{
				// moramo paziti da korisnik ne izbriše tag koji ne pripada njemu!
				if(data === '0') alert('You can\'t delete this tag.');
				else window.location.replace("index.php?rt=users/show_user_notes");
			},
			error: function(xhr, status){
				console.log("Greška: " + status);
			}
		});
		return false;
	});


	// user details
	$(".btn-primary").dropdown();

});
