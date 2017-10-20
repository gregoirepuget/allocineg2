/* global screenReaderText */
/**
 * File navigation.js.
 *
 * Handles toggling the navigation menu for small screens and enables TAB key
 * navigation support for dropdown menus.
 */
(function () {
    'use strict';
    var container, button, menu, links, subMenus, i, len;

    container = document.getElementById('site-navigation');
    if (!container) {
        return;
    }

    button = container.parentNode.getElementsByTagName('button')[0];
    if ('undefined' === typeof button) {
        return;
    }

    menu = container.getElementsByTagName('ul')[0];

    // Hide menu toggle button if menu is empty and return early.
    if ('undefined' === typeof menu) {
        button.style.display = 'none';
        return;
    }

    menu.setAttribute('aria-expanded', 'false');
    if (-1 === menu.className.indexOf('nav-menu')) {
        menu.className += ' nav-menu';
    }

    button.onclick = function () {
        if (-1 !== container.className.indexOf('toggled')) {
            container.className = container.className.replace(' toggled', '');
            button.setAttribute('aria-expanded', 'false');
            menu.setAttribute('aria-expanded', 'false');
        } else {
            container.className += ' toggled';
            button.setAttribute('aria-expanded', 'true');
            menu.setAttribute('aria-expanded', 'true');
        }
    };

    // Get all the link elements within the menu.
    links = menu.getElementsByTagName('a');
    subMenus = menu.getElementsByTagName('ul');

    // Set menu items with submenus to aria-haspopup="true".
    len = subMenus.length;
    for (i = 0; i < len; i++) {
        subMenus[i].parentNode.setAttribute('aria-haspopup', 'true');
    }

    // Each time a menu link is focused or blurred, toggle focus.
    len = links.length;
    for (i = 0; i < len; i++) {
        links[i].addEventListener('focus', toggleFocus, true);
        links[i].addEventListener('blur', toggleFocus, true);
    }

    /**
     * Sets or removes .focus class on an element.
     */
    function toggleFocus(){
        /*jshint validthis: true */
        var self = this;

        // Move up through the ancestors of the current link until we hit .nav-menu.
        while (-1 === self.className.indexOf('nav-menu')) {

            // On li elements toggle the class .focus.
            if ('li' === self.tagName.toLowerCase()) {
                if (-1 !== self.className.indexOf('focus')) {
                    self.className = self.className.replace(' focus', '');
                } else {
                    self.className += ' focus';
                }
            }

            self = self.parentElement;
        }
    }

})();


/**
 * Those two functions are global because they need to be accesible from customizer.js
 */
function theMotion_header_social_icons_width() {
    'use strict';
    var totalWidth = 0;
    jQuery('.header-social-icons li').each(function () {
        totalWidth += jQuery(this).outerWidth();
    });
    jQuery('.header-social-icons').css('width', totalWidth + 10);
}

function theMotion_menu_toggle_height() {
    'use strict';
    var menuToggleBtn = jQuery('button.menu-toggle');
    var siteHeader = jQuery('.site-header');
    if (!menuToggleBtn) {
        return false;
    }
    menuToggleBtn.css('min-height', '1px');
    menuToggleBtn.css('min-height', siteHeader.outerHeight());
}

function theMotion_video_height() {
    'use strict';
    var videoIframe = jQuery('.post-single iframe[src*="youtube"], .post-single iframe[src*="vimeo"]');
    if(typeof videoIframe !== 'undefined') {
        var videoWidth = videoIframe.width();
        if(typeof videoWidth !== 'undefined' ) {
            var necessaryHeight = (videoWidth / 16) * 9;
            if (typeof necessaryHeight !== 'undefined') {
                videoIframe.css({'height': necessaryHeight + 'px'});
            }
        }
    }
}

(function ($) {
    'use strict';
    $(document).ready(function () {
        theMotion_header_social_icons_width();
        theMotion_menu_toggle_height();
        theMotion_video_height();
    });

    $(window).resize(function () {
        theMotion_header_social_icons_width();
        theMotion_menu_toggle_height();
        theMotion_video_height();
    });


    jQuery('.search-opt, .search-quit').click(function () {
        jQuery('.header-search').fadeToggle('fast', 'linear');
    });


    $('html').click(function () {
        $('.search-toggle-open').removeClass('search-toggle-open');
        $('.search-opt').addClass('search-toggle');
    });

})(jQuery);


//ACCESSIBILITY MENU
(function ($) {
    'use strict';
    function initMainNavigation(container) {
        // Add dropdown toggle that display child menu items.
        container.find('.menu-item-has-children > a').after('<button class="dropdown-toggle" aria-expanded="false"><span class="dropdown-toggle-inner">' + screenReaderText.expand + '</span></button>');

        // Toggle buttons and submenu items with active children menu items.
        container.find('.current-menu-ancestor > button').addClass('toggled-on');
        container.find('.current-menu-ancestor > .sub-menu').addClass('toggled-on');

        // Add menu items with submenus to aria-haspopup="true".
        container.find('.menu-item-has-children').attr('aria-haspopup', 'true');

        container.find('.dropdown-toggle').click(function (e) {
            var _this = $(this);
            e.preventDefault();
            _this.toggleClass('toggled-on');
            _this.next('.children, .sub-menu').toggleClass('toggled-on');
            _this.attr('aria-expanded', _this.attr('aria-expanded') === 'false' ? 'true' : 'false');
            _this.find('.dropdown-toggle-inner').html(_this.html() === screenReaderText.expand ? screenReaderText.collapse : screenReaderText.expand);
        });
    }

    initMainNavigation($('.main-navigation'));

    var masthead = $('#masthead');
    var menuToggle = masthead.find('#menu-toggle');
    var siteHeaderMenu = masthead.find('#site-header-menu');
    var siteNavigation = masthead.find('#site-navigation');

    // Enable menuToggle.
    (function () {
        // Return early if menuToggle is missing.
        if (!menuToggle) {
            return;
        }

        // Add an initial values for the attribute.
        menuToggle.click(function () {
            $(this).add(siteHeaderMenu).toggleClass('toggled-on');
        });
    })();


    // Fix sub-menus for touch devices and better focus for hidden submenu items for accessibility.
    (function () {
        if (!siteNavigation || !siteNavigation.children().length) {
            return;
        }

        if ('ontouchstart' in window) {
            siteNavigation.find('.menu-item-has-children > a').on('touchstart.themotion', function (e) {
                var el = $(this).parent('li');
                if (!el.hasClass('focus')) {
                    e.preventDefault();
                    el.toggleClass('focus');
                    el.siblings('.focus').removeClass('focus');
                }
            });
        }

        siteNavigation.find('a').on('focus.themotion blur.themotion', function () {
            $(this).parents('.menu-item').toggleClass('focus');
        });
    })();


    // Add he default ARIA attributes for the menu toggle and the navigations.
    function onResizeARIA() {
        if (910 > window.innerWidth) {
            if (menuToggle.hasClass('toggled-on')) {
                menuToggle.attr('aria-expanded', 'true');
            } else {
                menuToggle.attr('aria-expanded', 'false');
            }

            if (siteHeaderMenu.hasClass('toggled-on')) {
                siteNavigation.attr('aria-expanded', 'true');
            } else {
                siteNavigation.attr('aria-expanded', 'false');
            }

            menuToggle.attr('aria-controls', 'site-navigation social-navigation');
        } else {
            menuToggle.removeAttr('aria-expanded');
            siteNavigation.removeAttr('aria-expanded');
            menuToggle.removeAttr('aria-controls');
        }
    }

    $(document).ready(function () {
        $(window).on('load.themotion', onResizeARIA);

        if ($('footer').find('.widget_siteorigin-panels-builder').length > 0) {
            $('footer').find('.widget-columns').removeClass();
        }
    });


})(jQuery);


jQuery(document).ready(function ($) {

    'use strict';
    $('#myCarousel').carousel({
        interval: false
    });

    //Handles the carousel thumbnails
    var lastItemPlayingId = -1;
    $('[id^=carousel-selector-]').click(function () {
        var id = $(this).attr('data-id');
        $('.themotion-playlist-item').removeClass('themotion-playlist-playing');
        $(this).addClass('themotion-playlist-playing');
        $('.item').removeClass('active');
        var thisItem = $('.slide-number-' + id);
        var lastItem = lastItemPlayingId !== -1 ? $('.slide-number-' + lastItemPlayingId) : false;
        thisItem.addClass('active');
        thisItem.trigger('resize');
        if( lastItem ) {
            var lastItemIframe = lastItem.find('iframe').length > 0 ? lastItem.find('iframe') : 'undefined_item';
            var lastItemVideo = lastItem.find('video').length > 0 ? lastItem.find('video') : 'undefined_item';
            videoStop( lastItemIframe, lastItemVideo );
        }

        var playButton = thisItem.find( '.themotion-video-play-button' );
        if( playButton.length > 0 ){
            playButton.trigger('click');
        } else {
            var itemIframe  = thisItem.find( 'iframe' ).length > 0 ? thisItem.find( 'iframe' ) : 'undefined_item';
            var itemVideo   = thisItem.find( 'video' ).length > 0 ? thisItem.find( 'video' ) : 'undefined_item';
            videoPlay( itemIframe, itemVideo );
        }

        lastItemPlayingId = id;
    });

    $('.themotion-scroll-to-section').click(function () {
        var anchor = $(this).attr('data-anchor');
        var offset = -60;
        $('html, body').animate({
            scrollTop: $(anchor).offset().top + offset
        }, 1200);
    });

    var iframe = 'undefined_item', video = 'undefined_item';
    $('.themotion-video-play-button').click( function() {

        var thisLightbox = $(this).parent().next('.themotion-lightbox');
        thisLightbox.fadeToggle();

        var playButton = thisLightbox.find( '.themotion-video-play-button' );
        if( playButton.length > 0 ){
            playButton.trigger('click');
        } else {
            iframe  = thisLightbox.find( 'iframe' ).length > 0 ? thisLightbox.find( 'iframe' ) : 'undefined_item';
            video   = thisLightbox.find( 'video' ).length > 0 ? thisLightbox.find( 'video' ) : 'undefined_item';
            videoPlay( iframe, video );
        }

        var itemActive = $( '.carousel-inner .item.active' );
        var itemActiveIframe  = itemActive.find( 'iframe' ).length > 0 ? itemActive.find( 'iframe' ) : 'undefined_item';
        var itemActiveVideo   = itemActive.find( 'video' ).length > 0 ? itemActive.find( 'video' ) : 'undefined_item';

        if( 'undefined_item' !== itemActiveIframe ) {
            var url = itemActiveIframe.attr( 'src' );
            var newUrl = url.replace( 'autoplay=1', 'autoplay=0' );
            itemActiveIframe.attr( 'src', newUrl );
        } else if( 'undefined_item' !== itemActiveVideo ) {
            itemActiveVideo.get(0).stop();
            itemActiveVideo.get(0).currentTime = 0;
        }

    } );

    $('.themotion-lightbox').click(function() {
        $(this).fadeOut();
        videoStop( iframe, video );
    } );

    function videoPlay( iframe, video ) {
        if( iframe !== 'undefined_item' ) {
            // youtube or vimeo
            if( iframe.attr('src') !== '' ){
                var iframeLink      = iframe.attr('src');
                var linkAutoplay;
                if( iframeLink.indexOf( 'autoplay' ) === -1 ) {
                    linkAutoplay = iframeLink + ( iframeLink.indexOf('?') === -1 ? '?' : '&' ) + 'autoplay=1';

                } else {
                    linkAutoplay = iframeLink;
                }
                iframe.attr('src', linkAutoplay);
            } else {
                var dataSrc = iframe.attr('data-themotionsrc');
                var dataSrcNew = ( dataSrc.indexOf( 'autoplay=0' ) !== -1 ) ? dataSrc.replace('autoplay=0', 'autoplay=1') : dataSrc;
                iframe.attr('src', dataSrcNew );
            }
        } else if ( video !== 'undefined_item' ){
            // video
            video.get(0).play();
            video.trigger('resize');
        }
    }

    function videoStop( iframe, video ) {
        if( iframe !== 'undefined_item' ) {
            iframe.attr( 'data-themotionsrc', iframe.attr('src') ).attr('src', '');
        } else if ( video !== 'undefined_item' ){
            video.get(0).stop();
            video.get(0).currentTime = 0;
        }
    }

    $('.themotion-lightbox-inner').click(function(event){
        event.stopPropagation();
    });

    $('.themotion-show-on-click').click(function(){
        $(this).next().show();
        $(this).replaceWith(', ');
    });

});


/* Light YouTube Embeds by @labnol */
/* Web: http://labnol.org/?p=27941 */

document.addEventListener('DOMContentLoaded',
    function() {
        var div, n,
            v = document.getElementsByClassName('youtube-player');
        for (n = 0; n < v.length; n++) {
            div = document.createElement('div');
            div.setAttribute('data-id', v[n].dataset.id);
            div.innerHTML = labnolThumb(v[n].dataset.id);
            div.onclick = labnolIframe;
            v[n].appendChild(div);
        }
    });

function labnolThumb(id) {
    var thumb = '<img src="https://img.youtube.com/vi/ID/maxresdefault.jpg">',
        play = '<div class="themotion-video-play-button play"><i class="mejs-overlay-button themotion-play-icon"></i></div>';
    return thumb.replace('ID', id) + play;
}

function labnolIframe() {
    var iframe = document.createElement('iframe');
    var embed = 'https://www.youtube.com/embed/ID?autoplay=1';
    iframe.setAttribute('src', embed.replace('ID', this.dataset.id));
    iframe.setAttribute('frameborder', '0');
    iframe.setAttribute('allowfullscreen', '1');
    if( this.parentNode ){
        this.parentNode.replaceChild(iframe, this);
    }
}