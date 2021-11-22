<!DOCTYPE html>
{{-- pageConfigs variable pass to Helper's updatePageConfig function to update page configuration  --}}
@isset($pageConfigs)
    {!! Helper::updatePageConfig($pageConfigs) !!}
@endisset
@php
    // configData variable layoutClasses array in Helper.php file.
    $configData = Helper::applClasses();
@endphp

<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>
    <link rel="apple-touch-icon" href="{{asset('images/ico/apple-icon-120.png')}}">
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('images/ico/favicon.ico')}}">

    {{-- Include core + vendor Styles --}}
    @include('panels.styles')
</head>
<!-- END: Head-->

<!-- BEGIN: Body-->
<body class="vertical-layout vertical-menu-modern 1-column navbar-sticky {{$configData['bodyCustomClass']}} footer-static blank-page
  @if($configData['theme'] === 'dark'){{'dark-layout'}} @elseif($configData['theme'] === 'semi-dark'){{'semi-dark-layout'}} @else {{'light-layout'}} @endif @if($configData['isCardShadow'] === false){{'no-card-shadow'}}@endif" data-open="click" data-menu="vertical-menu-modern" data-col="1-column" data-framework="laravel">


<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">
            @yield('content')
        </div>
    </div>
</div>
<!-- END: Content-->

{{-- scripts --}}
@include('panels.scripts')

</body>
<!-- END: Body-->
</html>
