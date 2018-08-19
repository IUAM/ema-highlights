$(document).ready(function() {

    // Remove fancybox from image main
    $(".image-main .fancybox").contents().unwrap();

    // Active any other fancybox links
    $(".fancybox").fancybox({
        'scrolling' : 'no'
    });

    // For sub-categories; change page select
    $('[name=page]').on('change', function() {
        $(this).parents('form').submit();
    });

    // Declaring all our selectors ahead of time for speed

    var $window = $(window);
    var $body = $('html,body');
    var $main = $('#main');

    var $mobile = $('#mobile'); // indicator

    var $sidebar = $('#sidebar');
    var $search = $('#search');
    var $menu = $('#menu');

    var $footer = $('#footer');

    var $pagination = $('#pagination'); // subcategory only
    var $breadcrumbs = $('#breadcrumbs'); // entry only

    var $hamburger = $('#hamburger');

    var $branding = $('#branding-bar');

    // Figure out what to do with the menu and search
    $('#hamburger').click( function(event) {

        // Hide menu if it's visible
        if( $search.is(":visible") ) {

            $search.stop().hide();
            $menu.stop().hide();

            $body.css('overflow-y','');
            $hamburger.css('position','fixed');
            $sidebar.css({
                'overflow-y': 'hidden',
                'height': 'auto',
                'position': '',
                'top': ''
            });

        }else{

            $search.stop().show();
            $menu.stop().show();

            $body.css('overflow-y','hidden');
            $hamburger.css('position','absolute');
            $sidebar.css({
                'overflow-y': 'scroll',
                'height': '100%',
                'position': 'fixed',
                'top': '0'
            });

        }


    });

    // Hide or restore the menu when the screen is resized
    var resizeMenu = function() {

        if( $mobile.is(":hidden") ) {

            $search.stop().hide();
            $menu.stop().hide();

            $sidebar.css('height','auto');

        }else{

            $search.stop().show();
            $menu.stop().show();

            $sidebar.css('height','');

        }

        $body.css('overflow-y','');
        $sidebar.css('overflow-y','hidden');

    };

    resizeMenu();
    $(window).resize( resizeMenu );

    // Constrain object images in height
    var $entry = $('#entry');
    var $plate = $entry.find('.image-main');
    var $description = $entry.find('.description');

    var resizePlate = function() {

        $plate.height(0);

        // Ratios greater than 1 indicate a vertical image
        var ratio = $plate.attr('data-ratio');

        var h;

        // We'd like to leave some of the description showing

        h = $(window).height();
        h -= $description.offset().top;
        h -= parseInt( $description.css('line-height'), 10 ) * 3;

        h = Math.max( h, 370 );
        h = Math.min( h, $plate.width() * ratio );

        // TODO: Account for toolbar menu on mobile?
        $plate.height( h );

    };

    if( $entry.length > 0 ) {
        resizePlate();
        $(window).resize( resizePlate );
    }

    // Add padding to main to offset the absolute footer
    // 30 is an arbitrary number that just kind of makes it look good
    // We'll also account for sub-cat pagination here
    var offsetFooter = function() {

        if( $pagination.length > 0 && $mobile.is(":hidden") ) {
            $footer.css('padding-bottom', 50 );
        }else{
            $footer.css('padding-bottom', '' );
        }

        $main.css( 'padding-bottom', $footer.outerHeight() + 30 );

    };

    offsetFooter();
    $(window).resize( offsetFooter );

    // "Sticky" the navigation when the user scrolls below the header
    // This includes sidebar, sub-category pagination, entry breadcrumbs
    var offsetNavigation;

    var updateNavigationOffset = function() {
        offsetNavigation = $mobile.is(":hidden") ? 50 : 55;
    };

    updateNavigationOffset();
    $(window).resize( updateNavigationOffset );

    var stickyNavigation = function() {
        if( $window.scrollTop() > offsetNavigation ) {
            $sidebar.addClass('sticky');
            $pagination.addClass('sticky');
            $breadcrumbs.addClass('sticky');
        }else{
            $sidebar.removeClass('sticky');
            $pagination.removeClass('sticky');
            $breadcrumbs.removeClass('sticky');
        }

    };

    stickyNavigation();
    $(window).scroll( stickyNavigation );
    $(window).resize( stickyNavigation );

});
