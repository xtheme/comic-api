<!DOCTYPE html>
{{-- pageConfigs variable pass to Helper's updatePageConfig function to update page configuration  --}}
@isset($pageConfigs)
    {!! App\Helpers\Helper::updatePageConfig($pageConfigs) !!}
@endisset
@php
    // $configData variable layoutClasses array in Helper.php file.
    $configData = App\Helpers\Helper::applClasses();
@endphp

<html class="loading" lang="en" data-textdirection="ltr">
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
</head>
<!-- END: Head -->

<!-- BEGIN: Body -->
<body id="model-iframe" data-col="1-column" data-framework="laravel">

<!-- BEGIN: Content-->
<div class="content">
    <div class="content-wrapper">
        @yield('content')
    </div>
</div>
<!-- END: Content -->

@include('panels.modal')

{{-- scripts --}}
@include('panels.scripts')

</body>
<!-- END: Body -->
</html>
