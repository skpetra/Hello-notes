$(document).ready(function() {

	var test = true;
	$("button#help_reg").on('click', function(){
		// tipka kojom se prikazuju upute za registraciju
		var dohv = $('div.div_help_reg');
		if (!test) {
			dohv.css('display', 'none');
			test = true;
		}
		else {
			test = false;
			var mjesto = $(this).position();
			var height = Number($(this).height());
			var width = Number($(this).width());
			dohv.css('top', (Number(mjesto.top) + height + 3)+ 'px');
			dohv.css('display', 'table-cell');
		}
	});
});