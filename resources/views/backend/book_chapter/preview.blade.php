@extends('layouts.modal')

{{-- page Title --}}
@section('title', $title)

{{-- vendor style --}}
@section('vendor-styles')
@endsection

@section('content')
    <div class="mb-1">
        <h1 class="text-center text-bold-400">@yield('title')</h1>
        <div class="divider">
            <span class="divider-text divider-center">共有 {{ count($images) }} 張圖片</span>
        </div>
        @foreach($images as $image)
        <div class="text-center"><img src="{{ $image }}" alt="" /></div>
        @endforeach
    </div>
@endsection

@section('search-form')
@endsection

{{-- vendor scripts --}}
@section('vendor-scripts')
@endsection

{{-- page scripts --}}
@section('page-scripts')
    <script>
    </script>
@endsection

