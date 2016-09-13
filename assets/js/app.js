var $_POST = $_POST;

$(document).ready(function(){
	$('button#change-name').on('click', function(){
		
		$(this).next().fadeIn().removeClass('hide').addClass('show');
		$(this).prev().fadeOut().removeClass('show').addClass('hide');
		$(this).fadeOut().removeClass('show').addClass('hide');
		
		$('html, body').animate({scrollTop: ($(this).offset().top)}, 500);
		
		return false;
	});
	
	$('button#confirm-sig').on('click', function(e){
		 e.stopImmediatePropagation();
		 e.preventDefault();
		 $('form#sendsig').trigger('submit');
	});
	
	$('button#scroll-dwn').on('click', function(e){
		$('html, body').animate({scrollTop: ($(this).next().offset().top)}, 500); 
		return false;
	});
	

});

$(window).load(function(){
	
	if ($('.client-signature').length == 1) {
		$('#step3Modal').modal('show');   
		$('html, body').animate({scrollTop: ($('button#confirm-sig').offset().top)}, 500); 
    }
     
    if ($('.client-signature').length == 0  && $_POST == undefined) {
		$('#step1Modal').modal('show');    
    }

    if ($_POST != undefined) {
	 $('html, body').animate({scrollTop: ($('.app-info').offset().top)}, 500)   
    } 
});