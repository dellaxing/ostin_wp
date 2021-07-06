/**
 *	Custom Front-end JS code
 */
 
jQuery(document).ready(function() {
	
	var	searchScreen = jQuery('#search-screen'),
		searchInput	 = jQuery('.top_search_field'),
		cancelSearch = jQuery('.cancel_search');
	
	jQuery('.search-btn').on('click', function() {
		searchScreen.fadeIn(200, () => {
			jQuery('body').hasClass('search-toggled') ?  '' : jQuery('body').addClass('search-toggled');
		});
		searchInput.focus();
	});
	
	cancelSearch.on('click', function(e) {
		searchScreen.fadeOut(200, () => {
			jQuery('body').hasClass('search-toggled') ?  jQuery('body').removeClass('search-toggled') : '';
		});
		jQuery('#search-btn').focus();
	});
	
	jQuery('#go-to-field').on('focus', function() {
		jQuery(this).siblings('input[type="text"]').focus();
	});
	
	jQuery('#go-to-close').on('focus', function() {
		jQuery(this).siblings('button.cancel_search').focus();
	});
	
	
	var clickedBtn;
	// Navigation
	jQuery('.menu-link').bigSlide({
		easyClose	: true,
		width		: '25em',
		side		: 'right',
		saveState	: true,
		afterOpen	: function(e) {
				    	jQuery('#close-menu').focus();
				    	clickedBtn = jQuery(e.target).parent();
			    	},
		afterClose: function(e) {
				    	clickedBtn.focus();
			    }
    });
  
  	jQuery('.go-to-top').on('focus', function() {
		jQuery('#close-menu').focus();
	});
	
	jQuery('.go-to-bottom').on('focus', function() {
		jQuery('ul#menu-main > li:last-child > a').focus();
	});
	
	var parentElement =	jQuery('.panel li.menu-item-has-children'),
      dropdown		=	jQuery('.panel li.menu-item-has-children span');
	  
	parentElement.children('ul').hide();
	dropdown.on({
		'click': function(e) {
			e.target.style.transform == 'rotate(0deg)' ? 'rotate(180deg)' : 'rotate(0deg)';
			jQuery(this).siblings('ul').slideToggle().toggleClass('expanded');
			e.stopPropagation();
		},
		'keydown': function(e) {
			if( e.keyCode == 32 || e.keyCode == 13 ) {
				e.preventDefault();
				jQuery(this).siblings('ul').slideToggle().toggleClass('expanded');
				e.stopPropagation();
			}
		}
	});
	
	
	// Magnific Popup Lightbox
	jQuery('.blocks-gallery-grid, .gallery').magnificPopup({
		delegate: 'a',
		type: 'image',
		gallery: {
			enabled: true
		}
	})
	
	
	// Owl Slider
	var catSliders = [];
	
	for (catSlider in window) {
	    if ( catSlider.indexOf("cat_slider") != -1 ) {
		    catSliders.push( window[catSlider] );
	    }
    };
    catSliders.forEach( function( item ) {
	    var slider = jQuery("#" + item.id).find('.cat-slider');
	    slider.owlCarousel({
		    items: 1,
		    loop: true,
		    autoplay: true,
		    dots: false,
		    nav: true,
		    navText: ['<i class="fa fa-angle-left" aria-hidden="true"></i>', '<i class="fa fa-angle-right" aria-hidden="true"></i>']
	    });
    });
	
	
	// Tab Widget
	var tabWidgets = [];
    
    for (tabWidget in window) {
	    if ( tabWidget.indexOf("tab_widget") != -1 ) {
		    tabWidgets.push( window[tabWidget] );
	    }
    };
    tabWidgets.forEach( function( item ) {
	    
	    var widget 			=	jQuery("#tab-category-wrapper-" + item.number),
	    	containerLeft	=	widget.find('ul').offset().left,
    		currentArrow	=	widget.find('.tabs-slider'),
    		arrowWidth		=	currentArrow.width();
    		
	    widget.tabs({
		    create: function( event, ui ) {
				
				var initialTab = ui.tab,
					initialTabLeft	=	initialTab.offset().left;
					initialTabWidth	=	initialTab.width();
					currentArrow.css('left', initialTabLeft - containerLeft + initialTabWidth/2 -10 + 'px');
		    },
		    beforeActivate: function( event, ui ) {
			    jQuery(ui.oldPanel[0]).fadeOut()
			    jQuery(ui.newPanel[0]).fadeIn()
		    },
		    activate: function( event, ui ) {
			    
		    	var currentTabLeft		=	ui.newTab.offset().left,
		    		currentTabWidth		=	ui.newTab.width();
		    		
				currentArrow.animate({
									    left: currentTabLeft - containerLeft + currentTabWidth/2 - 10 + 'px'
									},
									{
										duration: 300
									});
	    	}
	    });
	});
	
	
	//Sticky Navigation
	if (oro.stickyNav !== "") {
		var stickyNav = jQuery('#sticky-navigation');
		stickyNav.css({
			"opacity": "0",
			"transform": "translateY(-100%)"
		});
		function oroStickyMenu() {
			var height = jQuery(this).scrollTop();
			if (height > 500) {
				jQuery('body').addClass('has-sticky-menu');
				stickyNav.css({
					"transform": "translateY(0)",
					"opacity": "1"
				});
			} else {
				jQuery('body').removeClass('has-sticky-menu');
				stickyNav.css({
			"transform": "translateY(-100%)",
			"opacity": "0"
		});
			}
		}
		jQuery(window).scroll(function() {
			oroStickyMenu()
		})
		oroStickyMenu()
	}
});