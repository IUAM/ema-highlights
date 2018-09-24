@extends('layouts.app')

@section('content')

<div id="entry">

    <div id="breadcrumbs">

        @if (isset($prev))
            <div id="previous">
                <a href="{!! $prev['href'] !!}">
                    <img src="{!! $prev['image'] !!}">
                    <span class="text">{{ $prev['accession'] }}</span>
                </a>
            </div>
        @endif

        <ol class="breadcrumb">
            @foreach ($breadcrumbs as $breadcrumb)
                <li><a href="{!! $breadcrumb['href'] !!}">{{ $breadcrumb['title'] }}</a></li>
            @endforeach
        </ol>

        @if (isset($next))
            <div id="next">
                <a href="{!! $next['href'] !!}">
                    <span class="text">{{ $next['accession'] }}</span>
                    <img src="{!! $next['image'] !!}">
                </a>
            </div>
        @endif

    </div>

    <div class="image-main" data-ratio="{{ $images->first()->aspect }}">
        <img src="{!! $images->shift()->getFull(); !!}">
    </div>
    <div class="image-gallery">
        @foreach ($images as $image)
            <a class="fancybox" rel="group" href="{!! $image->getFull() !!}">
                <img style="max-width: 100%;" src="{!! $image->getSquareThumbnail() !!}">
            </a>
        @endforeach
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2 col-md-8 col-md-offset-2 col-lg-8 col-lg-offset-2">

                <h2 class="title">
                    {{ $entry->title }}
                </h2>

                <div class="description">
                    <div>
                        {!! !empty($entry->description) ? $entry->description : '<p>Description forthcoming.</p>' !!}
                    </div>

                    <div class="text-muted">

                        {!! !empty($entry->tombstone) ? $entry->tombstone : '<p>Tombstone forthcoming.</p>' !!}

                        @if ($entry->is_copyrighted)
                            <p class="text-warning">Large image not available.</p>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>


</div>
@endsection
