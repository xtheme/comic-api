@extends('layouts.iframePage')

{{-- page Title --}}
@section('title','Dashboard')

{{-- vendor css --}}
@section('vendor-styles')

@endsection

@section('content')
    <!-- Dashboard Ecommerce Starts -->
    <section id="dashboard-ecommerce">
123456
    </section>
    <!-- Dashboard Ecommerce ends -->
@endsection

@section('vendor-scripts')
@endsection

@section('page-scripts')
    <script>
        $(document).ready(function () {
            $.toast({
                type  : 'success',
                message: '请填写要添加的标签',
                debug: true
            });
        });
    </script>
@endsection

