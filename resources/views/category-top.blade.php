@extends('layouts.app')

@php
    $image = new App\Models\Image();
@endphp

@section('content')
<div class="category category-top">

    <div id="splash" style="background-image: url('{!! $image->getFull($category->tn_image_id) !!}">    </div>

    <div id="content">

        <div id="intro">

            <h2 id="title">{{ $category->title }}</h2>
            <div id="description">

            @if (!isset($category->description))
                <p><i>Category description forthcoming.</i></p>
            @endif

            {!! $category->description !!}

            </div>

        </div>

        <div id="categories">
            @foreach ($children as $child)
                <div class="item">
                    <a href="{!! route('category', $child->id) !!}">
                        <img src="{!! $image->getSquareThumbnail($child->tn_image_id) !!}" />
                        <p>{{ $child->title }}</p>
                    </a>
                </div>
            @endforeach
        </div>

    </div>

</div>
@endsection
