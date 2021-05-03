@extends('layouts.modal')

@section('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/videojs/video-js.css') }}">
@endsection

@section('page-styles')
@endsection

@section('content')
    <video id="example-video" width="640" height="360" class="video-js vjs-default-skin" controls>
        <source src="{{ $series->url }}" type="application/x-mpegURL">
    </video>
@endsection

{{-- vendor scripts --}}
@section('vendor-scripts')
    <script src="{{ asset('vendors/js/videojs/video.min.js') }}"></script>
    <script src="{{ asset('vendors/js/videojs/videojs-contrib-hls.min.js') }}"></script>
@endsection

{{-- page scripts --}}
@section('page-scripts')
    <script>
        $(document).ready(function () {
            var options = {
                autoplay: true,
                preload: true,
                controls: true,
                controlBar: {
                    'liveDisplay': true,
                    'subsCapsButton': false,
                    'audioTrackButton': false,
                    'playToggle': false,
                    'fullscreenToggle': false,
                    'pictureInPictureToggle': false
                },
                fluid: true
            };
            videojs('example-video', options, function() {
                console.log('播放器初始化完成');    //回调函数
            });
        });
    </script>
@endsection
