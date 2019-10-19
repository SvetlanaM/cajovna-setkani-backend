jQuery( document ).ready(function() {
    /**
     * history sidebar functionality
     */
    jQuery('[lang="sk"] #history-hidden .history-sidebar-inner').before('<div class="slideout-tab">História<span class="caret"></span></div>');
    jQuery('[lang="en"] #history-hidden .history-sidebar-inner').before('<div class="slideout-tab">History<span class="caret"></span></div>');
    jQuery('#history-hidden .history-sidebar-inner').prepend('<div class="sidebar-footer-close__top clearfix"><button type="button" class="close btn-sidebar-footer-close" aria-label="Close"> <span aria-hidden="true" class="btn-close-icon"></span></button></div>');
    jQuery('[lang="sk"] #history-hidden .history-sidebar-inner').append('<div class="sidebar-footer-close clearfix"><button type="button" class="close btn-sidebar-footer-close" aria-label="Close"><span class="btn-close-text">Zavrieť</span> <span aria-hidden="true" class="btn-close-icon"></span></button></div>');
    jQuery('[lang="en"] #history-hidden .history-sidebar-inner').append('<div class="sidebar-footer-close clearfix"><button type="button" class="close btn-sidebar-footer-close" aria-label="Close"><span class="btn-close-text">Close</span> <span aria-hidden="true" class="btn-close-icon"></span></button></div>');
    jQuery('[lang="sk"] #history-hidden .history-sidebar-inner').append('<div class="sidebar-footer-close__bar clearfix"><a href="#history-hidden" class="btn-close-history">Späť na históriu</a></div>');
    jQuery('[lang="en"] #history-hidden .history-sidebar-inner').append('<div class="sidebar-footer-close__bar clearfix"><a href="#history-hidden" class="btn-close-history">Back to History</a></div>');


    /**
     * JTRE sidebar functionality
     */
    jQuery('[lang="sk"] #jtre .jtre-sidebar-inner').before('<div class="slideout-tab">Kto je JTRE<span class="caret"></span></div>');
    jQuery('[lang="en"] #jtre .jtre-sidebar-inner').before('<div class="slideout-tab">Who is JTRE<span class="caret"></span></div>');
    jQuery('#jtre .jtre-sidebar-inner').prepend('<div class="sidebar-footer-close__top clearfix"><button type="button" class="close btn-sidebar-footer-close" aria-label="Close"> <span aria-hidden="true" class="btn-close-icon"></span></button></div>');
    jQuery('[lang="sk"] #jtre .jtre-sidebar-inner').append('<div class="sidebar-footer-close clearfix"><button type="button" class="close btn-sidebar-footer-close" aria-label="Close"><span class="btn-close-text">Zavrieť</span> <span aria-hidden="true" class="btn-close-icon"></span></button></div>');
    jQuery('[lang="en"] #jtre .jtre-sidebar-inner').append('<div class="sidebar-footer-close clearfix"><button type="button" class="close btn-sidebar-footer-close" aria-label="Close"><span class="btn-close-text">Close</span> <span aria-hidden="true" class="btn-close-icon"></span></button></div>');
    jQuery('[lang="sk"] #jtre .jtre-sidebar-inner').append('<div class="sidebar-footer-close__bar clearfix"><a href="#jtre" class="btn-close-history">Späť na Kto je JTRE</a></div>');
    jQuery('[lang="en"] #jtre .jtre-sidebar-inner').append('<div class="sidebar-footer-close__bar clearfix"><a href="#jtre" class="btn-close-history">Back to Who is JTRE</a></div>');


    /**
     * Slide out content first aimation
     * pulse slide-Out
     */
    if(jQuery(window).width() > 1199) {

        function isElementVisible($elementToBeChecked) {
            let TopView = jQuery(window).scrollTop();
            let BotView = TopView + jQuery(window).height();
            let TopElement = $elementToBeChecked.offset().top;
            let BotElement = TopElement + 200;
            return ((TopElement <= BotView) && (BotElement >= TopView));
        }

        jQuery(window).scroll(function () {
            jQuery( "#history-hidden" ).each(function() {
                let isOpen = jQuery(this).hasClass('open-modal');
                let isOnView = isElementVisible(jQuery(this));
                if(isOnView && !isOpen && !jQuery(this).is(':animated')){
                    let view = jQuery(this);
                    view.animate({"margin-right": '+='+30}, 400);
                    view.animate({"margin-right": '-='+30}, 400);
                }
            });
            jQuery( "#jtre" ).each(function() {
                let isOnView = isElementVisible(jQuery(this));
                let isOpen = jQuery(this).hasClass('open-modal');
                if(isOnView && !isOpen && !jQuery(this).is(':animated')){
                    let view = jQuery(this);
                    view.animate({"margin-right": '+='+30}, 400);
                    view.animate({"margin-right": '-='+30}, 400);
                }
            });
        });
    }


    jQuery('.slideout-tab').on('click', function(){
        if(jQuery(window).width() < 1200) {
            jQuery(this).toggleClass('open');
            jQuery(this).next().slideToggle();
        }
    });

    // history    
    var slider_width = 700;
    jQuery('#history-hidden .slideout-tab').click(function() {
        if(jQuery(window).width() > 1199) {
            if(jQuery(this).css("margin-right") == slider_width+"px" && !jQuery(this).is(':animated')) {
                jQuery(this).parents('.group-sec').removeClass('overflow-visible');
                jQuery('#history-hidden,#history-hidden .slideout-tab').animate({"margin-right": '-='+slider_width});
                jQuery(this).parents("#history-hidden").removeClass('open-modal');
            } else {
                if(!jQuery(this).is(':animated')) {//perevent double click to double margin
                    jQuery(this).parents('.group-sec').addClass('overflow-visible');
                    jQuery('#history-hidden,#history-hidden .slideout-tab').animate({"margin-right": '+='+slider_width});
                    jQuery(this).parents("#history-hidden").addClass('open-modal');
                }
            }
        }
    });

    jQuery('#history-hidden').mouseenter(function () {
        if(jQuery(window).width() > 1199 && !jQuery(this).is(':animated') && !jQuery(this).hasClass('open-modal')) {
            jQuery(this).parents('.group-sec').addClass('overflow-visible');
            jQuery('#history-hidden, #history-hidden .slideout-tab').animate({"margin-right": "+=" + slider_width});
            jQuery(this).addClass('open-modal');
        }
    });

    jQuery('#history-hidden .btn-sidebar-footer-close').on('click', function(){
        jQuery(this).parents('.group-sec').removeClass('overflow-visible');
        jQuery('#history-hidden,#history-hidden .slideout-tab').animate({"margin-right": '-='+slider_width});
        jQuery(this).parents("#history-hidden").removeClass('open-modal');
    });

    //JTRE    
    var slider_widthJTRE = 700;
    jQuery('#jtre .slideout-tab').click(function() {
        if(jQuery(window).width() > 1199) {
            if (jQuery(this).css("margin-right") == slider_widthJTRE + "px" && !jQuery(this).is(':animated')) {
                jQuery(this).parents('.group-sec').removeClass('overflow-visible');
                jQuery('#jtre,#jtre .slideout-tab').animate({"margin-right": '-=' + slider_widthJTRE});
                jQuery(this).parents("#jtre").removeClass('open-modal');
            } else {
                if (!jQuery(this).is(':animated'))//perevent double click to double margin
                {
                    jQuery(this).parents('.group-sec').addClass('overflow-visible');
                    jQuery('#jtre,#jtre .slideout-tab').animate({"margin-right": '+=' + slider_widthJTRE});
                    jQuery(this).parents("#jtre").addClass('open-modal');
                }
            }
        }

    });

    jQuery('#jtre').mouseenter(function () {
        if(jQuery(window).width() > 1199 && !jQuery(this).is(':animated') && !jQuery(this).hasClass('open-modal')) {
            jQuery(this).parents('.group-sec').addClass('overflow-visible');
            jQuery('#jtre,#jtre .slideout-tab').animate({"margin-right": "+=" + slider_widthJTRE});
            jQuery(this).addClass('open-modal');
        }
    });

    jQuery('#jtre .btn-sidebar-footer-close').on('click', function(){
        jQuery(this).parents('.group-sec').removeClass('overflow-visible');
        jQuery('#jtre,#jtre .slideout-tab').animate({"margin-right": '-='+slider_widthJTRE});
        jQuery(this).parents("#jtre").removeClass('open-modal');
    });


    /**
     * image map building
     */
    jQuery('[name="image-map"] area').on({
        click: function (e) {
            e.preventDefault();
            jQuery('.image-maps > img').addClass('floor-active');
            var textIndex = jQuery(this).index();
            floorIndex = textIndex;
            textIndex = textIndex + 1;

            jQuery('.image-maps').removeClass('klingerka-floor0');
            jQuery('.image-maps').removeClass('klingerka-floor1');
            jQuery('.image-maps').removeClass('klingerka-floor2');
            jQuery('.image-maps').removeClass('klingerka-floor3');
            jQuery('.image-maps').removeClass('klingerka-floor4');
            jQuery('.image-maps').removeClass('klingerka-floor5');
            jQuery('.image-maps').removeClass('klingerka-floor6');
            jQuery('.image-maps').removeClass('klingerka-floor7');
            jQuery('.image-maps').removeClass('klingerka-floor8');
            jQuery('.image-maps').removeClass('klingerka-floor9');
            jQuery('.image-maps').removeClass('klingerka-floor10');
            jQuery('.image-maps').addClass("klingerka-floor" + floorIndex );

            jQuery('.field--name-field-floors > .field__item').hide();
            jQuery('.field--name-field-floors > .field__item:nth-child(' + textIndex + ')').show();
        },
        mouseenter: function (e) {
            e.preventDefault();
            jQuery('.image-maps > img').addClass('floor-active-hover');
            var textIndex = jQuery(this).index();
            floorIndex = textIndex;
            textIndex = textIndex + 1;

            jQuery('.image-maps').removeClass('klingerka-floor0-hover');
            jQuery('.image-maps').removeClass('klingerka-floor1-hover');
            jQuery('.image-maps').removeClass('klingerka-floor2-hover');
            jQuery('.image-maps').removeClass('klingerka-floor3-hover');
            jQuery('.image-maps').removeClass('klingerka-floor4-hover');
            jQuery('.image-maps').removeClass('klingerka-floor5-hover');
            jQuery('.image-maps').removeClass('klingerka-floor6-hover');
            jQuery('.image-maps').removeClass('klingerka-floor7-hover');
            jQuery('.image-maps').removeClass('klingerka-floor8-hover');
            jQuery('.image-maps').removeClass('klingerka-floor9-hover');
            jQuery('.image-maps').removeClass('klingerka-floor10-hover');
            jQuery('.image-maps').addClass("klingerka-floor" + floorIndex + '-hover');
        }
    });

    jQuery('.image-maps').on({
        mouseleave: function (e) {
            e.preventDefault();
            jQuery('.image-maps > img').removeClass('floor-active-hover');
            var textIndex = jQuery(this).index();
            floorIndex = textIndex;
            textIndex = textIndex + 1;

            jQuery('.image-maps').removeClass('klingerka-floor0-hover');
            jQuery('.image-maps').removeClass('klingerka-floor1-hover');
            jQuery('.image-maps').removeClass('klingerka-floor2-hover');
            jQuery('.image-maps').removeClass('klingerka-floor3-hover');
            jQuery('.image-maps').removeClass('klingerka-floor4-hover');
            jQuery('.image-maps').removeClass('klingerka-floor5-hover');
            jQuery('.image-maps').removeClass('klingerka-floor6-hover');
            jQuery('.image-maps').removeClass('klingerka-floor7-hover');
            jQuery('.image-maps').removeClass('klingerka-floor8-hover');
            jQuery('.image-maps').removeClass('klingerka-floor9-hover');
            jQuery('.image-maps').removeClass('klingerka-floor10-hover');
        }
    });

    jQuery('[name="image-map"] area:first-child').trigger('click');


    /**
     * Floors button to show modal
     */
    // Floors modal navigation
    jQuery('.paragraph--type--building-floor-data .modal').each(function(i){
        jQuery(this).attr('data-modalID', i);
        jQuery(this).attr('id', 'floor-modal-' + i);
        jQuery(this).find('.field--name-field-download-pdf').prepend('<div class="floors-nav"></div>');
    });

    jQuery('[lang="sk"] .field--name-field-floors > .field__item .modal').each(function(){
        jQuery(this).before('<a href="javascript(void);" class="btn btn-primary">Detail</a>');
    });

    jQuery('[lang="en"] .field--name-field-floors > .field__item .modal').each(function(){
        jQuery(this).before('<a href="javascript(void);" class="btn btn-primary">Show detail</a>');
    });


    /* Floors modal navigation */
    jQuery('.paragraph--type--building-floor-data .modal').each(function(i){
        var prevModal = '';
        var prevModalId = '';
        var prevModalTitle = '';
        var nextModal = '';
        var nextModalId = '';
        var nextModalTitle = '';

        if(jQuery(this).closest('.field__item').next().length > 0) {
            nextModalTitle = jQuery(this).closest('.field__item').next().find('.modal .field--name-field-title-floor-detail').text();
            nextModal = jQuery(this).closest('.field__item').next().find('.modal').attr('data-modalid');
            nextModalId = jQuery(this).closest('.field__item').next().find('.modal').attr('id');
            jQuery(this).find('.field--name-field-download-pdf .floors-nav').prepend('<a href="#' + nextModalId + '" class="btn btn-floors-nav btn-floors-nav-next" data-targetModal="' + nextModal + '">' + nextModalTitle + '</a>');
        }

        if(jQuery(this).closest('.field__item').prev().length > 0) {
            prevModalTitle = jQuery(this).closest('.field__item').prev().find('.modal .field--name-field-title-floor-detail').text();
            prevModal = jQuery(this).closest('.field__item').prev().find('.modal').attr('data-modalid');
            prevModalId = jQuery(this).closest('.field__item').prev().find('.modal').attr('id');
            jQuery(this).find('.field--name-field-download-pdf .floors-nav').prepend('<a href="#' + prevModalId + '" class="btn btn-floors-nav btn-floors-nav-prev" data-targetModal="' + prevModal + '">' + prevModalTitle + '</a>');
        }
    });

    /**
     * Mobile content
     */
    jQuery('#floors .image-map-container').before('<ul class="image-map-mobile list-unstyled"></ul>');
    jQuery('.field--name-field-floors > .field__item').each(function(){
        jQuery('[lang="sk"] .image-map-mobile').prepend('<li class="row"><div class="buttons col-auto"><button type="button" data-target="' + jQuery(this).find('.modal').attr('id') + '" class="btn btn-primary">Detail</button></div><div class="title col-auto">' + jQuery(this).find('.field--name-field-title').text() + '</div></li>');
        jQuery('[lang="en"] .image-map-mobile').prepend('<li class="row"><div class="buttons col-auto"><button type="button" data-target="' + jQuery(this).find('.modal').attr('id') + '" class="btn btn-primary">Show</button></div><div class="title col-auto">' + jQuery(this).find('.field--name-field-title').text() + '</div></li>');
    });
    //open modal
    jQuery('.image-map-mobile .btn').on('click', function(e){
        if(jQuery('#floors-modal').length == 0 ){
            jQuery('body').append('<div id="floors-modal"></div>');
        }
        jQuery('body').addClass('modal-open');
        jQuery('#floors-modal').show();
        jQuery('#floors-modal').append(jQuery('#' + jQuery(this).attr('data-target')).clone());
        jQuery('#contact').appendTo(jQuery('#floors-modal'));
    });
    /*********************************************************************/


    jQuery('.field--name-field-floors > .field__item .btn').on('click', function(event){
        event.preventDefault();
        if(jQuery('#floors-modal').length == 0 ){
            jQuery('body').append('<div id="floors-modal"></div>');
        }

        jQuery('body').addClass('modal-open');
        jQuery('#floors-modal').show();
        jQuery('#floors-modal').append(jQuery(this).next().clone());
        jQuery('#contact').appendTo(jQuery('#floors-modal'));
    });
    /* Add anchor to mailchimp form to floors detail */
    jQuery('[lang="sk"] .paragraph--type--building-floor-data .modal .field--name-field-download-pdf').append('<div class="mailchimp-cta"><a href="#contact" class="btn btn-primary">Poďme sa rozprávať</a></div>');
    jQuery('[lang="en"] .paragraph--type--building-floor-data .modal .field--name-field-download-pdf').append('<div class="mailchimp-cta"><a href="#contact" class="btn btn-primary">Let\'s talk</a></div>');
    /* Add close icon to floors detail */
    jQuery('.paragraph--type--building-floor-data .modal .container').prepend('<div class="modal-close-wrapper clearfix"><button type="button" class="modal-close" aria-label="Close"><img src="/themes/klingerka/img/close.svg" width="30" height="30" /></button></div>');
    /* Close modal window */
    jQuery(document).on('click', '#floors-modal .modal .modal-close', function(){
        jQuery('#floors-modal').hide();
        jQuery('#floors-modal .modal').remove();
        jQuery('body').removeClass('modal-open');
        jQuery('#contact').insertAfter('#project');
    });

    jQuery(document).on('click', '#floors-modal .btn-floors-nav', function(){
        jQuery('#floors-modal .modal').remove();
        jQuery('#floors-modal').prepend(jQuery('#floors .modal[data-modalid="' + jQuery(this).data('targetmodal') + '"]').clone());
    });


    /**
     * Look inside EN translation
     */
    jQuery('[lang="en"] .image-hotspots-wrapper .labels .label[data-hid="10"]').attr('title', 'Fire alarm system');
    jQuery('[lang="en"] .image-hotspots-wrapper .labels .label[data-hid="11"]').attr('title', 'Cooling and heating with two-pipe fan coil units and parapet radiators');
    jQuery('[lang="en"] .image-hotspots-wrapper .labels .label[data-hid="12"]').attr('title', 'Forced ventilation with fresh air exchange');
    jQuery('[lang="en"] .image-hotspots-wrapper .labels .label[data-hid="13"]').attr('title', 'Openable ventilation flaps, natural ventilation and interior blinds');
    jQuery('[lang="en"] .image-hotspots-wrapper .labels .label[data-hid="14"]').attr('title', 'Office module 2.7m');
    jQuery('[lang="en"] .image-hotspots-wrapper .labels .label[data-hid="15"]').attr('title', 'Double floor');
    jQuery('[lang="en"] .image-hotspots-wrapper .labels .label[data-hid="16"]').attr('title', 'Data and heavy current cabling contained within floor boxes');

    // default active
    jQuery('.node .field--name-field-img-look-inside').before('<div class="field--name-field-text-look-inside"></div>');
    jQuery('.sec-look-inside .image-wrapper a').on('click', function(e){
        e.preventDefault();
        jQuery('.sec-look-inside .image-wrapper a').not(this).removeClass('active');
        jQuery(this).addClass('active');
        jQuery('.field--name-field-text-look-inside').html('<p>' + jQuery('.sec-look-inside .labels .label[data-hid="' + jQuery(this).find('.hotspot-box').attr('data-hid') + '"]').attr('title') + '</p>');

    });
    jQuery('.image-wrapper .hotspot-box[data-hid="11"]').parent().trigger('click');



    /**
     * webform mailchimp text translate
     */
    jQuery('[lang="en"] .webform-submission-leave-us-a-mesaage-add-form .form-item-field-subscribe label.form-item-field-subscribe').text('I am interested in receiving a newsletter. I agree to the processing of personal data for the marketing purposes. ');


    /**
     * Image map init
     */
    jQuery('map').imageMapResize();


    /**
     * Form newsletter checkbox add pdf link
     */
    jQuery('[lang="sk"] .form-item-field-subscribe label').html('Mám záujem o zasielanie newslettra. Súhlasím so <a href="/sk/suhlas-so-spracovanim-osobnych-udajov-newsletter-sk" target="_blank">spracovaním osobných údajov</a> na marketingové účely.');
    jQuery('[lang="en"] .form-item-field-subscribe label').html('I am interested in receiving a newsletter. I agree to the <a href="/suhlas-so-spracovanim-osobnych-udajov-newsletter-en" target="_blank">processing of personal data</a> for the marketing purposes.');

    jQuery('[lang="sk"] .form-item-terms-of-service .form-item-terms-of-service').html('Súhlasím so <a href="/sk/suhlas-so-spracovanim-osobnych-udajov-kontaktny-formular-sk" target="_blank">spracovaním osobných údajov</a>  na účely kontaktovania.');
    jQuery('[lang="en"] .form-item-terms-of-service .form-item-terms-of-service').html('I agree to the processing of <a href="/suhlas-so-spracovanim-osobnych-udajov-kontaktny-formular-en" target="_blank">personal data</a> for the purpose of contacting.');


    /**
     * Menu Mobile
     */
    jQuery('#header .navbar-nav .nav-link, .language-switcher-language-url .links > li > a').on('click', function(){
        if(jQuery(window).width() < 768) {
            jQuery('.navbar-toggler').trigger('click');
        }
    });


    /**
     * PDF target="_blank"
     */
    jQuery('.file--mime-application-pdf a').each(function(){
        jQuery(this).attr('target', '_blank');
    });


});


/**************************************** Cookie consent ****************************************/
// Creare's 'Implied Consent' EU Cookie Law Banner v:2.4
// Conceived by Robert Kent, James Bavington & Tom Foyster

var dropCookie = true;                      // false disables the Cookie, allowing you to style the banner
var cookieDuration = 14;                    // Number of days before the cookie expires, and the banner reappears
var cookieName = 'complianceCookie';        // Name of our cookie
var cookieValue = 'on';                     // Value of cookie

function createDiv(){
    var bodytag = document.getElementsByTagName('body')[0];
    var div = document.createElement('div');
    div.setAttribute('id','cookie-law');
    div.innerHTML = '<p class="sk-only"><a class="close-cookie-banner" href="javascript:void(0);" onclick="removeMe();"><span>Súhlasím</span></a>Na tejto stránke používame cookies na&nbsp;účely analýz a&nbsp;štatistík návštevnosti a&nbsp;využívania našich webstránok ako aj&nbsp;na&nbsp;prispôsobenie zobrazenia obsahu a&nbsp;reklamy. Táto stránka môže obsahovať aj&nbsp;cookies tretích strán. Viac informácií o&nbsp;cookies a&nbsp;možnostiach ich&nbsp;nastavenia nájdete v&nbsp;<a href="/sk/informacie-o-pouzivani-cookies" target="_blank">Informáciách o&nbsp;používaní cookies.</a> Kliknutím na „Súhlasím“ alebo využívaním našich stránok súhlasíte s&nbsp;používaním cookies, pokiaľ si ich používanie nenastavíte prostredníctvom vášho prehliadača.</p><p class="en-only"><a class="close-cookie-banner" href="javascript:void(0);" onclick="removeMe();"><span>I agree</span></a>This page uses cookies to&nbsp;analyse our website’s traffic and usage statistics, and to&nbsp;customize content and advertising to&nbsp;enhance your user experience. This site may also contain third party cookies. For more information about cookies and how they’re used, check out <a href="/information-about-cookie-usage" target="_blank">Information about Cookie Usage.</a> By clicking "I Agree" or by using our site, you agree to the use of cookies - unless disabled through your browser.</p>';
 // Be advised the Close Banner 'X' link requires jQuery

    // bodytag.appendChild(div); // Adds the Cookie Law Banner just before the closing </body> tag
    // or
    bodytag.insertBefore(div,bodytag.firstChild); // Adds the Cookie Law Banner just after the opening <body> tag

    document.getElementsByTagName('body')[0].className+=' cookiebanner'; //Adds a class tothe <body> tag when the banner is visible

    createCookie(window.cookieName,window.cookieValue, window.cookieDuration); // Create the cookie
}


function createCookie(name,value,days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        var expires = "; expires="+date.toGMTString();
    }
    else var expires = "";
    if(window.dropCookie) {
        document.cookie = name+"="+value+expires+"; path=/";
    }
}

function checkCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

function eraseCookie(name) {
    createCookie(name,"",-1);
}

window.onload = function(){
    if(checkCookie(window.cookieName) != window.cookieValue){
        createDiv();
    }
}

function removeMe(){
	var element = document.getElementById('cookie-law');
	element.parentNode.removeChild(element);
}
/**************************************** END OF Cookie consent ****************************************/

/**
 * Smooth scroll
 */

jQuery( document ).ready(function() {
    // Select all links with hashes
    jQuery('a[href*="#"]')
      // Remove links that don't actually link to anything
      .not('[href="#"]')
      .not('[href="#0"]')
      .not('.modal a')
      //.click(function(event) {
      .on('click', function(event) {
        // On-page links
        if (
          location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '')
          &&
          location.hostname == this.hostname
        ) {
          // Figure out element to scroll to
          var target = jQuery(this.hash);
          target = target.length ? target : jQuery('[name=' + this.hash.slice(1) + ']');
          // Does a scroll target exist?
          if (target.length) {
            // Only prevent default if animation is actually gonna happen
            //event.preventDefault();
            jQuery('html, body').animate({
              scrollTop: target.offset().top
            }, 1000, function() {
              // Callback after animation
              // Must change focus!
              var $target = jQuery(target);
              $target.focus();
              if ($target.is(":focus")) { // Checking if the target was focused
                return false;
              } else {
                $target.attr('tabindex','-1'); // Adding tabindex for elements not focusable
                $target.focus(); // Set focus again
              };
            });
          }
        }
      });
});
