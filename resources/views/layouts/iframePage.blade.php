<!DOCTYPE html>
{{-- pageConfigs variable pass to Helper's updatePageConfig function to update page configuration  --}}
@isset($pageConfigs)
    {!! App\Helpers\Helper::updatePageConfig($pageConfigs) !!}
@endisset
@php
    // $configData variable layoutClasses array in Helper.php file.
    $configData = App\Helpers\Helper::applClasses();
@endphp

<html class="loading" lang="@if(session()->has('locale')){{session()->get('locale') }}@else{{$configData['defaultLanguage']}}@endif"
      data-textdirection="{{$configData['direction'] == 'rtl' ? 'rtl' : 'ltr' }}">
<!-- BEGIN: Head -->
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
<!-- END: Head -->

<!-- BEGIN: Body -->
<body class="vertical-layout vertical-menu-modern 1-column
@if($configData['isMenuCollapsed'] == true) {{'menu-collapsed'}} @endif
@if($configData['theme'] === 'dark') {{'dark-layout'}} @elseif($configData['theme'] === 'semi-dark') {{'semi-dark-layout'}} @else {{'light-layout'}} @endif
@if(isset($configData['footerType'])) {{$configData['footerType']}} @endif
{{$configData['bodyCustomClass']}}
@if($configData['mainLayoutType'] === 'vertical-menu-boxicons') {{'boxicon-layout'}} @endif
@if($configData['isCardShadow'] === false) {{'no-card-shadow'}} @endif"
data-open="click" data-menu="vertical-menu-modern" data-col="1-column" data-framework="laravel">

<!-- BEGIN: Content-->
<div class="app-content content">
    {{-- Application page structure --}}
    @if($configData['isContentSidebar'] === true)
        <div class="content-area-wrapper">
            <div class="sidebar-left">
                <div class="sidebar">
                    @yield('sidebar-content')
                </div>
            </div>
            <div class="content-right">
                <div class="content-overlay"></div>
                <div class="content-wrapper">
                    <div class="content-header row">
                    </div>
                    <div class="content-body">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- others page structures --}}
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                @if($configData['pageHeader'] === true && isset($breadcrumbs))
                    @include('panels.breadcrumbs')
                @endif
            </div>
            <div class="content-body">
                @yield('content')
            </div>
        </div>
    @endif
</div>
<!-- END: Content -->

<!-- BEGIN: Customizer-->
@if($configData['hasSearchForm'] === true)
<div class="customizer d-none d-md-block">
    <a class="customizer-close" href="#"><i class="bx bx-x"></i></a>
    <a class="customizer-toggle" href="#"><i class="bx bx-search-alt white"></i></a>
    <div class="customizer-content p-2">
        @yield('search-form')
    </div>
</div>
@endif
<!-- End: Customizer-->

@include('panels.modal')

<!-- BEGIN: Footer -->
@include('panels.footer')
<!-- END: Footer -->

{{-- scripts --}}
@include('panels.scripts')
</body>
<!-- END: Body -->
</html>
