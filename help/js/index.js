$(function(){
	$('#ulMenu > li ul').css('display', 'none');
	$('#ulMenu > li ul.open').css('display', 'block');
	
    SyntaxHighlighter.all();
	
	$('#ulMenu > li a').click(
		function(){
			//$(this).addClass('sub_menu_open');
			var el = $(this).parent().find('ul');
			if(el.is(':hidden')){
				$(this).parent().parent().find('li > ul').hide();
				el.show();
			}else{
				el.hide();
			}
		}
	);

    $('#ulMenu li a').click(function(){
	    $('li.curli').removeClass('curli');
		$(this).parent().addClass('curli');
		return true;
	});

	//lay_aside bar
	function slider_bar(){
	    var slider = $('.lay_aside');
		if($.browser.version == '6.0'){
		    $('.lay_main').css({position: 'relative'});
			$('.lay_cont').css({margin:'0 0 0 170px'});
		    slider.css({position:'absolute', top:'0px', left:'0px'});
			$(window).scroll(function(){
			    var st = $(window).scrollTop();
				slider[0].style.top = st;
			});
		}
	}
	slider_bar();

	function returnTop(){
		var returnTop = $('#returnTop').addClass('to_top');
		if($.browser.version == '6.0'){
			returnTop.hover(
				function(){
					returnTop.css('backgroundPosition', '-106px -119px');
				}, 
				function(){
					returnTop.css('backgroundPosition', '-54px -119px');
				}
			);
			returnTop.css({'position':'absolute', 'top':getPageHeight()-41+'px', 'right':'0px'});
			$(window).scroll(function(){
				var st = $(window).scrollTop();
				returnTop[0].style.top = getPageHeight() + st - 41 + 'px';
			});
		}else{
			returnTop.css({position:'fixed', top:'auto', left:'auto', bottom:'0px', right:'0px'});
		}
		returnTop.click(function(){
			$(window).scrollTop(0);
		});
	}
	
	function getPageHeight(){
		if($.browser.msie){
			return document.compatMode == "CSS1Compat" ? document.documentElement.clientHeight : document.body.clientHeight;
		}else{
			return self.innerHeight;
		}
	}
	returnTop();
});
