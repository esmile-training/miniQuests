$(function(){    
    var headerHeight = $('.headerPosition > img').height();
    var footerHeight = $('.footerPosition > img').height();
    
    var main = document.getElementById( "main" );
    
    var bodyHeight =  $('body').height();
    var mainHeight =  $('#main').height();
    
    bodyHeight = bodyHeight - (headerHeight + footerHeight);
    headerHeight = headerHeight-(headerHeight/8);
    
    if(bodyHeight <= mainHeight){
	main.style.marginTop = headerHeight+'px';
	main.style.height = bodyHeight+'px';
	main.style.marginBottom = footerHeight+'px';
    }else{
	main.style.marginTop = headerHeight+'px';
	main.style.height = bodyHeight+'px';
    }

});
