$(document).ready(function(){
	

});

$(window).load(function(){
	
	if ($('.client-signature').length == 1) {
		$('#step4Modal').modal('show');    
    }
     
    if ($('.client-signature').length == 0) {
		$('#step1Modal').modal('show');    
    }

     
});