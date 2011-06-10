/* <![CDATA[ */
$(function() {
	var startPos = $('#cart').position().top;
	var divHeight = $('#cart').outerHeight();
	$(window).scroll(function (e) {
		var winWidth = $(window).width();
		var rightWidth = (winWidth-780)/2 - 95;
		scrTop = $(window).scrollTop() + 25;
		if ((startPos-5) < scrTop) {
			if ($.browser.msie && $.browser.version <= 6 ) {
				topPos = startPos + (scrTop - startPos) + 5;
				$('#cart').css("position", "absolute").css("top", topPos +"px").css("right",rightWidth+"px").css('zIndex', '500');
			} else {
				$('#cart').css("position", "fixed").css("top", "28px").css("right",rightWidth+"px").css("zIndex", "500");
			}
		} else {
			$('#cart').css("position", "static");
		}
	});
});
/* ]]> */