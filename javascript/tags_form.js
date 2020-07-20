$(document).ready(function() {
	// defaultna boja nam je bijela
	var r = '255';
	var g = '255';
	var b = '255';
	$("input.tcolor").on('change', function(){
		// pri svakoj promjeni slidera, mjenja se pozadinska boja div objekta
		//    na način da odgovara odabranoj boji
		r = $("#tcr").val();
		g = $("#tcg").val();
		b = $("#tcb").val();
		$("div.tag_form").css('background-color', 'rgb('+r+','+g+','+b+')');
	});
});
