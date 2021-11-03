@extends('layouts.fullLayoutMaster')

{{-- page title --}}
@section('title', 'Error 500')

@section('content')
    <!-- errors 500 -->
    <section class="row flexbox-container">
        <div class="col-xl-6 col-md-7 col-9">
            <!-- w-100 for IE specific -->
            <div class="card bg-transparent shadow-none">
                <div class="card-content">
                    <div class="card-body text-center bg-transparent miscellaneous">
                        <img src="{{ asset('images/pages/500.png') }}" class="img-fluid my-3" alt="branding logo">
                        <h1 class="error-title mt-1">内部服务错误!</h1>
                        <p class="p-2">
                            出现了一些技术问题导致您看到此信息，请联系开发人员排查．
                        </p>
                        <a href="{{ asset('/backend') }}" class="btn btn-primary round glow">返回控制台</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- errors 500 end -->
@endsection
