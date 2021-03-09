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
    <link rel="apple-touch-icon" href="{{ asset('images/ico/apple-icon-120.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/ico/favicon.ico') }}">
    {{-- Include core + vendor Styles --}}
    @include('panels.styles')
</head>
<!-- END: Head-->

<!-- BEGIN: Body-->
<body class="vertical-layout vertical-menu-modern 2-columns
@if($configData['isMenuCollapsed'] == true) {{'menu-collapsed'}} @endif
@if($configData['theme'] === 'dark') {{'dark-layout'}} @elseif($configData['theme'] === 'semi-dark') {{'semi-dark-layout'}} @else {{'light-layout'}} @endif
@if($configData['isContentSidebar'] === true) {{'content-left-sidebar'}} @endif
@if(isset($configData['navbarType'])) {{$configData['navbarType']}} @endif
@if(isset($configData['footerType'])) {{$configData['footerType']}} @endif
{{$configData['bodyCustomClass']}}
@if($configData['mainLayoutType'] === 'vertical-menu-boxicons') {{'boxicon-layout'}} @endif
@if($configData['isCardShadow'] === false) {{'no-card-shadow'}} @endif"
data-open="click" data-menu="vertical-menu-modern" data-col="2-columns" data-framework="laravel"
style="overflow-x: hidden; padding-right: 0 !important;">

<!-- BEGIN: Header-->
@include('panels.navbar')
<!-- END: Header-->

<!-- BEGIN: Main Menu-->
@include('panels.sidebar')
<!-- END: Main Menu-->

<!-- BEGIN: Content-->
<div id="main-content" class="content" style="height: 100%;">
    <iframe id="content-frame" name="content-frame" src="{{ route('backend.dashboard') }}" style="border: 0; width: 100%; height: 100%;"></iframe>
</div>
<!-- END: Content-->

<div class="sidenav-overlay"></div>
<div class="drag-target"></div>

<!-- BEGIN: Footer -->
@include('panels.footer')
<!-- END: Footer -->

@include('panels.scripts')

</body>
</html>
