@extends('layouts.fullLayoutMaster')

{{-- page title --}}
@section('title', 'Error 403')

@section('content')
    <!-- errors 500 -->
    <section class="row flexbox-container">
        <div class="col-xl-6 col-md-7 col-9">
            <!-- w-100 for IE specific -->
            <div class="card bg-transparent shadow-none">
                <div class="card-content">
                    <div class="card-body text-center bg-transparent miscellaneous">
                        <img src="{{ asset('images/pages/403.png') }}" class="img-fluid my-3" alt="branding logo">
                        <h1 class="error-title mt-1">权限不足!</h1>
                        <p class="p-2">
                            您没有足够的权限可以访问本页面，如有任何疑问请洽询运营人员．
                        </p>
                        <a href="{{ asset('/backend') }}" class="btn btn-primary round glow">返回控制台</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- errors 500 end -->
@endsection
