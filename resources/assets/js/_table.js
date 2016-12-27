$(function() {

	// @see http://stackoverflow.com/questions/7522565/how-to-stay-on-current-window-when-the-link-opens-in-new-tab
	var openTabInBackground = function(address){    
	    var a = $('<a>').attr('href', address);  
	    var evt = document.createEvent("MouseEvents");    

	    // The tenth parameter of initMouseEvent sets ctrl key    
	    evt.initMouseEvent("click", true, true, window, 0, 0, 0, 0, 0,true, false, false, false, 0, null);    
	    a[0].dispatchEvent(evt);
	}

	// @see http://stackoverflow.com/questions/890743/click-entire-row-preserving-middle-click-and-ctrlclick
	var trClicked = function(e) {
		var address = $(this).data('href');
		
		var leftMouseBtn = 0;
		var middleMouseBtn = 1;
		if((e.ctrlKey && e.button == leftMouseBtn) || e.button == middleMouseBtn) {
			// Middle mouse button or ctrl+click
			openTabInBackground(address);
		} else {
			// Normal left click
			window.location = address;
		}
	}

	$('.table tr[data-href]').click(trClicked);

})