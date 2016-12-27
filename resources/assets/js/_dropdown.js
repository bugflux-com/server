$(function() {

	var duration = 300; // anim time
	var margin = 10;

	// === SIZING ===

	function positioning(i, dropdown) {
		var menu = $(dropdown).find('.dropdown-menu');
		var link = $(dropdown).find('.dropdown-link');

		// Restore default state.
		// 
		// We set left to 0, because we would like
		// to position element to the left edge of window
		// to get preffered size (not limited by window right edge).
		$(menu).css({
			'left': 0,
			'right': 'auto',
			'top': 'auto',
			'bottom': 'auto',
		});

		// Get current properties
		var windowWidth = $(window).width();
		var linkOffset = $(link).offset();
		var linkWidth = $(link).width();
		var menuWidth = $(menu).width();

		// Center relative to link.
		//
		// Set menu and link center on the same line
		// (position relative to the document).
		var left = linkOffset.left + linkWidth/2 - menuWidth/2;
		$(menu).css('left', left);

		// Size overflow (crop max width).
		if(windowWidth <= menuWidth + 2*margin) {
			return $(menu).css({
				'left': margin,
				'right': margin
			});
		}

		// Position overflow.
		//
		// Move menu to the opposite site by the amount of px
		// that overflow on the other side.
		if(left < margin) {
			// Left overflow
			$(menu).css({
				'left': margin,
				'right': -(left - margin),
			});
		}
		else if(left + menuWidth > windowWidth - margin) {
			// Right overflow
			$(menu).css({
				'left': windowWidth - menuWidth - margin,
				'right': margin,
			});
		}

		// Adjust height of the dropdown
		var windowHeight = $(window).height();
		var linkHeight = $(link).height();
		var menuHeight = $(menu).height();

		var top = linkOffset.top + linkHeight;
		$(menu).css('top', top);

		if(top + menuHeight > windowHeight) {
			$(menu).css('bottom', margin);
		}
	}

	$(window).on('resize', function() {
		$('.dropdown:visible').each(positioning);
	});

	// === BEHAVIOUR ===

	$('.dropdown').each(function(i, elem) {
		$(elem)[0].userData = {};
		$(elem)[0].userData.visible = 0;
	});

	function expand(i, dropdown) {
		positioning(null, dropdown);
		$(dropdown)[0].userData.visible = 1;
		$(dropdown).find('.dropdown-menu').stop()
			.css('z-index', 201)
			.slideDown(duration);
	}

	function collapse(i, dropdown) {
		$(dropdown)[0].userData.visible = 0;
		$(dropdown).find('.dropdown-menu').stop()
			.css('z-index', 200)
			.slideUp(duration);
	}

	function collapseIfOutside(e) {
		$('.dropdown:visible').each(function(i, elem) {
			if($(e.target).closest(elem).length == 0) {
				collapse(null, elem);
			}
		});
	}

	function toggle(i, dropdown) {
		if(dropdown[0].userData.visible) collapse(null, dropdown);
		else expand(null, dropdown);
	}

	$('.dropdown-link').on('click', function() {
		toggle(null, $(this).parent('.dropdown'));
	});

	$('.content, .menu').on('scroll', function() {
		$('.content .dropdown:visible').each(collapse);
		$('.menu .dropdown:visible').each(collapse);
	});

	$(document).on('click', collapseIfOutside);

});