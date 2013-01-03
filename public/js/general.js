$(document).ready(function(){
	animationSpeed = 400;
	
	settingsFB = {'padding': 0, 'overlayOpacity': 0.4, 'overlayColor': '#ffffff', titlePosition : 'over'};
						   
	jQuery('ul.menu li:first-child, .slidingBlockLangMenu ul li:first-child').addClass("first");
	
	/* Map */
	if (jQuery('.map').length != 0) {

		if(jQuery.browser.msie){
			var browserVersion = parseInt(jQuery.browser.version);
			
			if(browserVersion <= 8) {
				animationSpeed = 50;
			}
		}
		// Map Backlighting
		k = 0;
		jQuery('#mapRArea, #mapRussia, #mapChArea, #mapChina').hover(function(){
			var ID = jQuery(this).attr('id');
			if ((ID == 'mapRArea') || (ID == 'mapRussia')) {
				var mapBaking = 'mapBackingR'; var mapBackingImg = 'mapBackingCh';
			}
			else {
				var mapBaking = 'mapBackingCh'; var mapBackingImg = 'mapBackingR';
			}

			k--;
			jQuery('.'+mapBaking).stop().fadeIn(animationSpeed);
			jQuery('.'+mapBackingImg).css('z-index', k).next('img').css('z-index', k);
			jQuery('.overlay').detach().prependTo('.main').stop().fadeTo(animationSpeed, 0.3);
			
		}, function(){
			var ID = jQuery(this).attr('id');
			var mapBaking = ((ID == 'mapRArea') || (ID == 'mapRussia')) ? 'mapBackingR' : 'mapBackingCh';	
			jQuery('.'+mapBaking).stop(true, true).fadeOut(animationSpeed);
			jQuery('.overlay').stop(true, true).fadeTo(animationSpeed, 0);
		});
		//jQuery('#map, #mapRussia, #mapChina').hover(function(){
			//jQuery('.mapBacking').stop().fadeIn(animationSpeed);
			//jQuery('.overlay').stop().fadeTo(animationSpeed, 0.3);
			
		//}, function(){
		//jQuery('.mapBacking').stop(true, true).fadeOut(animationSpeed);
			//jQuery('.overlay').stop(true, true).fadeTo(animationSpeed, 0);
		//});
		
	}

	
	var windowH = jQuery(window).height();
	var popUpWindowH = 0;
	
	/* Pop Up */	
	jQuery('.popUpW a.popUpCloseBtn').click(function() {
		var popUpMethods = new PopUpMethods();
		popUpMethods.popUpClose();
		$('.overlayPopUp').hide();
		if($(this).hasClass('reload')) location = location;
	});
	/* END Pop Up */
	
	
	/* Repeatable Fields */

	
	
	if (jQuery(".uniformF").length != 0) {
		jQuery(".uniformF input[type!=radio], .uniformF textarea, .uniformF select, .uniformF button").uniform({
			fileDefaultText: '',
			fileBtnText: 'Обзор'
		});
	}
	
	$('.profileInfo select').uniform();

	// IE 6
	if (jQuery.browser.msie) {
		ieVersion = parseInt(jQuery.browser.version);
		
		if (ieVersion == 6) {
			// IF ie6
			jQuery('input[type=radio]').addClass('radio');
		}
	}
	
	/* Profile Form */
	jQuery('select#childrens').bind('change', function() {
		if (jQuery(this).attr('value') == 0)
			jQuery('div.profileFChildrens').css('display', 'none');
		else
			jQuery('div.profileFChildrens').css('display', 'block');
	});
	
	// Sliding Authorization Form & Languages Menu
	var popUpClicked = false;
	jQuery('a[rel=slidingBlockFAuth], a[rel=slidingBlockLangMenu]').click(function() {
		var popUp = jQuery('.'+jQuery(this).attr('rel'));
		popUpOpenedL = (popUpClicked && (popUpL != jQuery(this).attr('rel'))) ? popUpL : 'z';
		popUpL = jQuery(this).attr('rel');
		popUpClicked = true;
		
		if ((jQuery.browser.msie) && (parseInt(jQuery.browser.version) == 8))
			popUp.slideDown(300);
		else
			popUp.fadeIn(300);
	});
	
	jQuery(document).click(function(event) {
		if (jQuery(event.target).parents('div.slidingBlock, a[rel=slidingBlockFAuth], a[rel=slidingBlockLangMenu], ul.actRMenu').length == 0) {	
			if ((jQuery.browser.msie) && (parseInt(jQuery.browser.version) == 6))
				jQuery('.slidingBlock').slideUp(200);
			else
				jQuery('.slidingBlock').fadeOut(200);
		}
		else if ((popUpClicked) && (jQuery(event.target).parents('a[rel=' + popUpOpenedL + ']').length)) return;
		
		if (popUpClicked) {
			if ((jQuery.browser.msie) && (parseInt(jQuery.browser.version) == 6))
				jQuery('.' + popUpOpenedL).slideUp(200);
			else
				jQuery('.' + popUpOpenedL).fadeOut(200);
		}
	});
	
	// All in One Carousel
	if (jQuery('.allinone_carousel_sweet').length != 0) {	
		jQuery('.allinone_carousel_sweet').allinone_carousel({
			skin: 'sweet',
			width: 738,
			height: 215,
			autoPlay: 3,
			resizeImages:true,
			autoHideBottomNav:false,
			easing:'easeOutQuad',
			numberOfVisibleItems:5,
			elementsHorizontalSpacing:115,
			elementsVerticalSpacing:20,
			verticalAdjustment:0,
			animationTime:0.5,
			showElementTitle: false,
			showBottomNav: false,
			showPreviewThumbs: false,
			autoHideNavArrows: false
		});
	}
	
	// Profile Photos
	if (jQuery("#pikame").length != 0) {
			
		var a = function(self){
			self.anchor.fancybox(settingsFB);
		};
		
		jQuery("#pikame").PikaChoose({carousel:true, autoPlay: false, thumbOpacity: 1, buildFinished:a, transition:[1], minW: 271, minH:271});
	}
	
	// Accordion Profile Info
	if (jQuery('.acc').length != 0) {
		parentAccordion=new TINY.accordion.slider("parentAccordion");
		parentAccordion.init("acc","h3",1,0,"acc-selected");
	}
	
	// Search
	if (jQuery('div.drop-down-list').length != 0) {
		// Drop-Down Lists
		jQuery('.drop-down-list .select-link a').click(function() {
			//jQuery(".drop-down-list ul").fadeOut(200);
			//jQuery('.drop-downActive').removeClass('drop-downActive');
			//jQuery(this).parents('div.drop-down-list').addClass('drop-downActive').find('ul').fadeIn(300);
			jQuery(".drop-down-list ul").fadeOut(200);
 jQuery('.drop-downActive').removeClass('drop-downActive');

 ul = jQuery(this).parents('div.drop-down-list').addClass('drop-downActive').find('ul');
 h = ul.parent().offset();
 h = jQuery(window).height() - h.top - 95 + jQuery(window).scrollTop();
 h = Math.max(h, 110);

 var attr = ul.attr('data-height');
 if (typeof attr === 'undefined' || attr === false)
 ul.attr('data-height', ul.height());

 if (ul.attr('data-height') > h) {
 ul.height(h);
 }

 ul.fadeIn(300);
		})

		
		jQuery(document).click(function(event) {
			if (jQuery(event.target).parents('div.drop-downActive').length) return;
			jQuery(".drop-down-list ul").fadeOut(200);
			jQuery('.drop-downActive').removeClass('drop-downActive');
		});
	}
	
	// Messages
	if (jQuery('.msgPartnerInfo').length != 0) {
		jQuery('.msgPartnerInfo a.closeBtn').click(function() {
			jQuery(this).parents('.msgPartnerInfo').detach();
			
			return false;
		});
	}
	
	// Messages Controls
	
	jQuery('.msgItem').hover(function() {
		jQuery(this).find('div.msgActions').fadeIn(200);
	}, function(){
		jQuery(this).find('div.msgActions').fadeOut(200);
	});
	
	
	// Inputs Values
	jQuery('div.searchMsgPartnerBlock input[type=text]')
	.each(function() {
		jQuery(this)
			.attr('rel', jQuery(this).attr('value'))
			.bind({
				focus: function() {
					if (jQuery(this).attr('value') == jQuery(this).attr('rel'))
						jQuery(this).attr('value', '');
				},
				blur: function() {
					if (jQuery(this).attr('value') == '') 
						jQuery(this).attr('value', jQuery(this).attr('rel'));
				}
			});
	});
	
	// Fancybox pop-up
	jQuery("a.fancybox").fancybox(settingsFB);
	
	// IE 7
	if (jQuery.browser.msie) {
		ieVersion = parseInt(jQuery.browser.version);
		
		if (ieVersion == 7) {
			// IF ie7
			var zIndex = 100;
			jQuery('div.drop-down-list').each(function() {
				jQuery(this).css('z-index', zIndex);
				zIndex--;
			});
		}
	}
	
	// Pop Up Methods
	PopUpMethods = function(){
			
		this.popUpOpen = function(popUp) {
			jQuery('.overlayPopUp').css('z-index', '20').stop().fadeTo(300, 0.64);
			
			jQuery('.mapBacking').stop().fadeOut(0);
			jQuery('.overlay').stop().fadeTo(300, 0);
				
			if ((jQuery.browser.msie) && (parseInt(jQuery.browser.version) == 6))
				popUp.slideDown(300);
			else
				popUp.fadeIn(300);
				
				
			popUpWindowH = popUp.height() + 165 + 100;
			/*if (popUpWindowH > windowH)
				jQuery('.main').height(popUpWindowH + 'px');*/
		};
		
		this.popUpClose = function() {
			if ((jQuery.browser.msie) && (parseInt(jQuery.browser.version) == 6))
				jQuery('.popUpW').slideUp(200);
			else
				jQuery('.popUpW').fadeOut(200);
					
			jQuery('.overlayPopUp').css('z-index', '1').stop().fadeTo(200, 0);
			
			if(jQuery.browser.msie){
				var browserVersion = parseInt(jQuery.browser.version);
				
				if(browserVersion <= 8) {
					jQuery('.overlayPopUp').css('display', 'none');
				}
			}
			
			/*if ((popUpWindowH != 0) && (popUpWindowH > windowH))
				jQuery('.main').height(windowH + 'px');*/
		};
	};
	
	if($('#children-childs').val() == 0) {
		$('.hide_area').hide();
	}
	
	$('#children-childs').change(function() {
		$('.hide_area').toggle();
	});
});

/* Registration PopUp Form for Gentlemens */
function registerGentlemenForm() {
	$.ajax({
		url: '/register/getgentlemenform',
		beforeSend: function() {
			$('.onclick_register').attr('onclick', '');
		},
		success: function(output) {
			$('.popUpRegFG .regF').html(output + '<div class="clear"></div>');
			var popUpMethods = new PopUpMethods();
			popUpMethods.popUpClose();
			popUpMethods.popUpOpen(jQuery('div.popUpRegFG'));
			$('.onclick_register_men').attr('onclick', 'registerGentlemenForm();');
		}
	});
};

/* Registration PopUp Form for Gentlemens */
function registerWomenForm() {
	$.ajax({
		url: '/register/getwomenform',
		beforeSend: function() {
			$('.onclick_register').attr('onclick', '');
		},
		success: function(output) {
			$('.popUpRegFW .regF').html(output + '<div class="clear"></div>');
			var popUpMethods = new PopUpMethods();
			popUpMethods.popUpClose();
			popUpMethods.popUpOpen(jQuery('div.popUpRegFW'));
			$('.onclick_register_women').attr('onclick', 'registerWomenForm();');
		}
	});
};

function reloadCaptcha(url) {
	$.ajax({
		url: url,
		dataType: 'json',
		beforeSend: function() {
			
		},
		success: function(output) {
			$('#captcha-id').val(output['id']);
			$('.captcha img').attr('src', output['img']);
		}
	});
}

function addLanguage(language_id) {
	prevLang = language_id - 1;
	options = $('[name="languages[language' + prevLang + ']"]').html();
	skills = $('[name="languages[skill' + prevLang + ']"]').html();
	label = $('[for="language0"]').html();
	$('.repeatableF .button').before('<div class="repeatableFItem"><dl><dt><label for="language' + language_id + '">' + label + '</label></dt><dd><div class="width164"><select id="languages-language' + language_id + '" name="languages[language' + language_id + ']">' + options + '</select></div><div class="width164"><select id="languages-skill' + language_id + '" name="languages[skill' + language_id + ']">' + skills + '</select></div></dd></dl><div class="clear"></div></div>');
	
	$('[name="languages[language' + language_id + ']"]').uniform();
	$('[name="languages[skill' + language_id + ']"]').uniform();
	
	language_id = language_id + 1;
	$('.add_language').attr('onclick', 'addLanguage(' + language_id + ');')
}

function addLanguageProfile(language_id) {
	prevLang = language_id - 1;
	options = $('[name="step1[languages][language' + prevLang + ']"]').html();
	skills = $('[name="step1[languages][skill' + prevLang + ']"]').html();
	label = $('[for="language0"]').html();
	$('.language_button_here').before('<tr><td class="pInfoParamT"><label for="language' + language_id + '">' + label + '</label></td><td><div class="repeatableFItem"><dl><dt></dt><dd><div class="m_width164"><select id="step1-languages-language' + language_id + '" name="step1[languages][language' + language_id + ']">' + options + '</select></div><div class="m_width164"><select id="step1-languages-skill' + language_id + '" name="step1[languages][skill' + language_id + ']">' + skills + '</select></div></dd></dl><div class="clear"></div></div></td></tr>');
	
	$('[name="step1[languages][language' + language_id + ']"]').uniform();
	$('[name="step1[languages][skill' + language_id + ']"]').uniform();
	
	language_id = language_id + 1;
	$('.add_language').attr('onclick', 'addLanguageProfile(' + language_id + ');')
}

function addChild(child_id) {
	prevChild = child_id - 1;
	name = $('[for="children-child' + prevChild + '-name"]').html();
	age = $('[for="children-child' + prevChild + '-child_age"]').html();
	together = $('[for="children-child' + prevChild + '-together"]').html();
	together_values = $('#children-child' + prevChild + '-together').html();
	$('#children_here .button').before('<div class="repeatableF"><dl><dt><label for="children-child' + child_id + '-name">' + name + '</label></dt><dd><input id="children-child' + child_id + '-name" class="text" type="text" value="" name="children[child' + child_id + '][name]"></dd></dl><dl><dt><label for="children-child' + child_id + '-child_age">' + age + '</label></dt><dd><input id="children-child' + child_id + '-child_age" class="text" type="text" value="" name="children[child' + child_id + '][child_age]"></dd></dl><dl><dt><label for="children-child' + child_id + '-together">' + together + '</label></dt><dd><select id="children-child' + child_id + '-together" name="children[child' + child_id + '][together]">' + together_values + '</select></dd></dl><div class="clear"></div></div>');
	
	$('[name="children[child' + child_id + '][together]"]').uniform();
	
	child_id = child_id + 1;
	$('.add_child').attr('onclick', 'addChild(' + child_id + ');')
}


function showWG() {
	var popUpMethods = new PopUpMethods();
	popUpMethods.popUpClose();
	popUpMethods.popUpOpen(jQuery('div.popUpWelcomeG'));
}

function showWW() {
	var popUpMethods = new PopUpMethods();
	popUpMethods.popUpClose();
	popUpMethods.popUpOpen(jQuery('div.popUpWelcomeW'));
}

jQuery.fn.extend({
    insertAtCaret: function(myValue){
        return this.each(function(i) {
            if (document.selection) {
                // Для браузеров типа Internet Explorer
                this.focus();
                var sel = document.selection.createRange();
                sel.text = myValue;
                this.focus();
            }
            else if (this.selectionStart || this.selectionStart == '0') {
                // Для браузеров типа Firefox и других Webkit-ов
                var startPos = this.selectionStart;
                var endPos = this.selectionEnd;
                var scrollTop = this.scrollTop;
                this.value = this.value.substring(0, startPos)+myValue+this.value.substring(endPos,this.value.length);
                this.focus();
                this.selectionStart = startPos + myValue.length;
                this.selectionEnd = startPos + myValue.length;
                this.scrollTop = scrollTop;
            } else {
                this.value += myValue;
                this.focus();
            }
        })
    }
});

function showReqSend() {
	var popUpMethods = new PopUpMethods();
	popUpMethods.popUpClose();
	popUpMethods.popUpOpen(jQuery('div.popUpRequestSent'));
}
