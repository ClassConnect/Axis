jQuery.fn.img_preloader = function(options){
	var defaults = {
		repeatedCheck: 500,
		fadeInSpeed: 500,
		delay:600,
		callback: ''
	};
	var options = jQuery.extend(defaults, options);
	return this.each(function(){
		var imageContainer = jQuery(this),
			images = imageContainer.find('img').css({opacity:0, visibility:'hidden'}),
			imagesToLoad = images.length;				
			imageContainer.operations = {	
				preload: function(){	
					var stopPreloading = true;
					images.each(function(i, event){	
						var image = jQuery(this);
						
						if(event.complete == true){	
							imageContainer.operations.showImage(image);
						}else{
							image.bind('error load',{currentImage: image}, imageContainer.operations.showImage);
						}
						
					});
					return this;
				},showImage: function(image){	
					imagesToLoad --;
					if(image.data.currentImage != undefined) { image = image.data.currentImage;}
											
					if (options.delay <= 0) image.css('visibility','visible').animate({opacity:1}, options.fadeInSpeed);
											 
					if(imagesToLoad == 0){
						if(options.delay > 0){
							images.each(function(i, event){	
								var image = jQuery(this);
								setTimeout(function(){	
									image.css('visibility','visible').animate({opacity:1}, options.fadeInSpeed);
								},
								options.delay*(i+1));
							});
							
							if(options.callback != ''){
								setTimeout(options.callback, options.delay*images.length);
							}
						}else if(options.callback != ''){
							(options.callback)();
						}
					}
				}
			};
			imageContainer.operations.preload();
		});
	}