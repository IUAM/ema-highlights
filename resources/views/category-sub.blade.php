@extends('layouts.app')

@php
    $image = new App\Models\Image();
@endphp

@section('content')
<div class="category category-sub">

    <div id="splash" style="background-image: url('{!! $image->getFull($category->tn_image_id) !!}">    </div>

    <div id="content">

        <div id="intro">

            <h2 id="title">{!! $category->title !!}</h2>
            <div id="description">

            @if (!isset($category->description))
                <p><i>Category description forthcoming.</i></p>
            @endif

            {!! $category->description !!}

            </div>


            @if ($entries->total() < 1)
                <p><i>No objects currently available for this category.</i></p>
            @endif

        </div>

        <div id="objects">

            @foreach ($entries->items() as $key => $entry)
                <div class="item {{ $key % 2 ? 'alt' : '' }}">
                    <div class="image">
                        <a href="{!! route('entry', $entry) !!}">
                            <img src="{!! $image->getSquareThumbnail($entry->artworks->first()->images->first()->id) !!}" />
                        </a>
                    </div>
                    <div class="tombstone">
                        {!! $entry->tombstone !!}
                    </div>
                </div>
            @endforeach

        </div>

        <div id="pagination">

            <!-- Desktop view -->
            <div id="pagination-desktop" class="col-xs-12">
                <div class="pull-left">
                    <p>{!! $entries->total() !!} <span class="glyphicon glyphicon-search"></span></p>
                    <p>{!! $entries->lastPage() !!} <span class="glyphicon glyphicon-file"></span></p>
                </div>
                <div class="pull-right" >

                    @if ($entries->lastPage() > 1)

                    <ul class="pagination pagination-sm">
                        <li>
                            <a href="{!! $entries->previousPageUrl() ?? 'javascript:;' !!}" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        @for ($i=1; $i <= $entries->lastPage() ; $i++)
                        <li class="{{ $entries->currentPage() === $i ? 'active' : '' }}">
                            {{-- TODO: Add back $q passthrough --}}
                            <a href="{!! $entries->url($i) !!}">{{ $i }}</a>
                        </li>
                        @endfor

                        <li>
                            <a href="{!! $entries->nextPageUrl() ?? 'javascript:;' !!}" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>

                    @endif

                </div>
            </div>

            <!-- Mobile view -->
            <div id="pagination-mobile" class="col-xs-12">
                <form action="{!! url()->current() !!}" role="form">

                    @php
                        $prev_disabled = $entries->currentPage() < 2 ? 'disabled' : '';
                        $select_disabled = $entries->lastPage() < 2 ? 'disabled' : '';
                        $next_disabled = $entries->currentPage() == $entries->lastPage() ? 'disabled' : '';
                    @endphp

                    <a
                        class="btn btn-default btn-sm pull-left"
                        href="{!! $entries->previousPageUrl() !!}"
                        {!! $prev_disabled !!}
                    >Prev</a>

                    <div id="pagination-mobile-select">
                        <div class="input-group">

                            <select
                                class="form-control input-sm"
                                style="width: 60px;"
                                name="page"
                                {!! $select_disabled !!}
                            >

                            @for ($i=1; $i <= $entries->lastPage() ; $i++)
                                <option {!! ($i == $entries->currentPage()) ? 'selected' : '' !!}>{{ $i }}</option>
                            @endfor

                            </select>

                            <span class="input-group-addon">of {{ $entries->lastPage() }}</span>

                        </div>
                    </div>

                    <a
                        class="btn btn-default btn-sm pull-right"
                        href="{!! $entries->nextPageUrl() !!}"
                        {!! $next_disabled !!}
                    >Next</a>

                </form>
            </div>

        </div>

    </div>

</div>
@endsection
