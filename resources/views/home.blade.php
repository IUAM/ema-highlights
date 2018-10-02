@extends('layouts.app')

@section('content')
<div class="index">

    <div id="splash" style="background-image: url('{!! $background !!}')"></div>

    <div id="content">

        <div id="intro">

            <h2 id="title">{{ $category->title }}</h2>

            <div id="description">
                {!! $category->description !!}

                <h3>At a Glance</h3>
                <p>Please find below a sample of works from our collection. Scroll to see more or use the navigation menu to explore our curatorial areas.</p>

            </div>

        </div>

        <div id="objects"></div>

        <div id="loader"><div class="loading-indicator"></div></div>

    </div>

</div>

<script>

$(document).ready(function() {

    var $content = $('#content');
    var $loader = $('#loader');
    var loading = false;
    var finished = false;
    var screens = 2;
    var page = 0;

    function get_objects( ) {

        // Exit if already loading
        if( loading || finished ) {
            return false;
        }

        // Start the load process
        loading = true;

        // Add page indicator
        $('<div/>').attr('data-page', page).appendTo('#objects');

        // Show loading indicator
        $loader.stop().show();

        // Remember current page
        var current = page;

        $.ajax({
            url: 'random/' + page,
            success: function(data) {

                // Find the right page divider
                var $divider = $('[data-page=' + current + ']');

                // Make sure we are inserting in the right order
                var items = data.reverse();

                for( i=0; i < items.length; i++ ) {

                    var $block = $('<div class="block"/>');
                    var $link = $('<a/>').attr('href', items[i]['page'] );
                    var $img = $('<img/>').attr('src', items[i]['tn'] );

                    $block.css('background-image', 'url(' + items[i]['b64'] + ')' );

                    $img.hide().on('load', function() {
                        $(this).fadeIn(200);
                    });


                    $img.appendTo( $link );
                    $link.appendTo( $block );
                    $block.insertAfter( $divider );

                }

                // $divider.remove();
                $loader.stop().hide();

                // Get ready to load again
                loading = false;

                // Make sure to fill out the full page
                if( $content.height() < $(window).height() * screens ) {
                    get_objects();
                }

                // Check to see if the end has been reached
                if( items.length < 1 ) {
                    finished = true;
                    console.log( finished );
                }

            }

        });

        page++;
    }

    var $window = $(window);
    var $document = $(document);

    $window.scroll( function() {

        if( $window.scrollTop() + $window.height() * screens > $document.height() ) {
            get_objects();
        }

    });

    // Initial object pull...
    get_objects();

});

</script>
@endsection
