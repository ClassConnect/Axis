(function($) {
  var methods = {
    init : function(options) {
    	// set up our initial settings
	    settings = {
	      'headTitle': 'Choose a folder', // default title bar
	      'width': 450, // default width of box
	      'height': 200, // default height of box
	      'defaultLocation': '0', // default to root directory
	      'showFolders': true, // show folders by default
	      'showFiles': true, // show files by default
	      'multiSelect': false, // don't allow multiple select by default
	      'fileTypes': 'all', // all, whitelist, blacklist
	      'typeList': '', // CSV list of either white/black list
	      'fileList': '', // CSV of file types (if 1 in typelist) ex: .doc
	      'contentType': '', // determine if this is able to be used as a "version"
	      'onAdd': false, // function to fire when an item is added
	      'onRemove': false, // function to fire when an item is removed
	      'onPageSwap': false // function to fire when we refresh the picker window
	    };
	    this.each(function() {
			if (options) { 
				$.extend(settings, options);
			}
	  	});
	  	// set the height and width
	  	this.height(settings['height']);
	  	this.width(settings['width']);
	  	// add styling information
    	this.html('<div class="titleBar"><span style="font-weight:bolder">' + settings['headTitle'] + '</span></div><div class="fboxResults"></div><div class="bottomBar"></div>');
	  	// make this accessible by ajax request
    	orig = this;
    	resultBox = this.find('.fboxResults');
    	resultBox.height(settings['height'] - 41);
    	this.addClass('fboxPicker');
	  	methods['show'].apply(this, Array(settings['defaultLocation']));
    },
    show : function(newLoc) {
    	if (settings['onPageSwap'] != false) {
    		catchPageSwap(newLoc);
    	}
    	// show the loading icon
    	resultBox.html('<center><br /><br /><img src="/assets/app/img/box/loading.gif" /></center>');
		// setup for showing the picker 
		$.ajax({
			type: "GET",
			url: "/app/common/picker/" + newLoc,
			success: function(data) {
				// put the return data in our box
				resultBox.html(data);
				// disable selection
				orig.find(".pickClick").disableSelection();
				// activate click func
				orig.find(".pickClick").click(function() {
					var newID = $(this).find('.identity').html();
		    		methods['show'].apply(this, Array(newID));
		    		return false;
		    	});
		    	// disable on optarea
		    	orig.find('.optarea').click(function() {
			    	return false;
				});
				// disable on optarea
		    	orig.find('.checkBoxer').click(function() {
		    		// if this is being removed
		    		if ($(this).hasClass('checkBoxered')) {
		    			$(this).removeClass('checkBoxered');
		    			$(this).parent().parent().parent().removeClass('fbpickSel');
		    			if (settings['onRemove'] != false) {
		    				catchRemove($(this).parent().parent().parent().find('.identity').html());
		    			}
		    		// otherwise, this is being added
		    		} else {
		    			// no multi? remove current
		    			if (settings['multiSelect'] == false) {
		    				$('.checkBoxered').removeClass('checkBoxered');
		    				$('.fbpickSel').removeClass('fbpickSel');
		    			}
		    			$(this).addClass('checkBoxered');
		    			$(this).parent().parent().parent().addClass('fbpickSel');
		    			if (settings['onAdd'] != false) {
		    				catchAdd($(this).parent().parent().parent().find('.identity').html());
		    			}
		    		}
			    	
				});

				orig.find(".itemEl").hover(
				   function() {
				      $(this).addClass('hoverPick');
				   },
				   function() {
				      $(this).removeClass('hoverPick');
				   }
				  );
			}
		});

    }
  };
  $.fn.boxPicker = function(method) {
  	// Method calling logic
    if ( methods[method] ) {
      return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
    } else if ( typeof method === 'object' || ! method ) {
      return methods.init.apply( this, arguments );
    } else {
      $.error( 'Method ' +  method + ' does not exist on jQuery.tooltip' );
    }    
    
  };
})(jQuery);