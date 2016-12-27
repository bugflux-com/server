$(function() {

	var duration = 300;
	var menu = $('.menu-primary');

	var isMenu = function(selector) {
		if($(selector).closest('.menu').length > 0) {
			return true;
		}

		return $(selector).closest('.nav-menu').length > 0;
	}

	var desktopExpand = function(e) {
		menu.stop().slideDown(duration);
	}

	var desktopCollapse = function(e) {
		if(!isMenu(e.relatedTarget)) {
			menu.stop().slideUp(duration);
		}
	}

	var desktopToggle = function() {
		menu.stop().slideToggle(duration);
	}

	var mobileToggle = function() {
		$('body').toggleClass('menu-visible');
	}

	$('.nav-menu').click(desktopToggle).click(mobileToggle);
	$('.nav-menu').mouseenter(desktopExpand).mouseleave(desktopCollapse);
	$('.menu').mouseleave(desktopCollapse);

});