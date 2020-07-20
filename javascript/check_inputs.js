$(document).ready(function() {
    
    for( var i=0; i<4; i++){

        var error = JSON.parse(localStorage.getItem(i));
        
        if (error.value === '1')
            $('[name="new_' + error.key + '"]').css('border-bottom', '2px solid #d13232');
        else{
        
            $('[name="new_' + error.key + '"]').val(error.text);
            $('[name="new_' + error.key + '"]').css('border-bottom', '1px solid black');
        }
    }
});