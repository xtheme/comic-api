@extends('layouts.fullLayoutMaster')

{{-- page title --}}
@section('title', 'Error 404')

@section('content')
    <!-- errors 404 -->
    <section class="row flexbox-container">
        <div class="col-xl-6 col-md-7 col-9">
            <div class="card bg-transparent shadow-none">
                <div class="card-content">
                    <div class="card-body text-center bg-transparent miscellaneous">
                        <h1 class="error-title">页面不存在</h1>
                        <p class="mt-1 pb-3">很抱歉，您所访问的页面似乎还未完善，如有任何疑问请洽询开发人员．</p>
                        <img class="img-fluid" src="{{ asset('images/pages/404.png') }}" alt="404 error">
                        <a href="{{ asset('/dashboard') }}" class="btn btn-primary round glow mt-3">返回控制台</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- errors 404 end -->
@endsection
