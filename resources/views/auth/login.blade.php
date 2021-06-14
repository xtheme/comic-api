@extends('layouts.fullLayoutMaster')

{{-- page title --}}
@section('title','登录')

{{-- page scripts --}}
@section('page-styles')
@endsection

@section('content')
    <!-- login page start -->
    <section class="row flexbox-container">
        <div class="col-xl-4 col-md-6 col-11">
            <div class="card bg-authentication mb-0">
                <div class="row m-0">
                    <!-- left section-login -->
                    <div class="col-md-6 col-12 px-0">
                        <div class="card disable-rounded-right mb-0 p-0 h-100 d-flex justify-content-center">
                            <div class="card-content">
                                <div class="card-body">
                                    <form id="login-form" class="form form-vertical" action="{{ route('login') }}" novalidate>
                                        <div class="form-group mb-2">
                                            <div class="controls">
                                                <input type="text" class="form-control" name="username" placeholder="请输入账号">
                                            </div>
                                        </div>
                                        <div class="form-group mb-2">
                                            <div class="controls">
                                                <input type="password" class="form-control" name="password" placeholder="请输入密码" autocomplete="on">
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary glow w-100 position-relative">登录
                                            <i id="icon-arrow" class="bx bx-right-arrow-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- right section image -->
                    <div class="col-md-6 d-md-block d-none text-center align-self-center p-3">
                        <div class="card-content">
                            <img class="img-fluid" src="{{ asset('images/pages/login.png') }}" alt="branding logo">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- login page ends -->
@endsection

{{-- vendor scripts --}}
@section('vendor-scripts')
@endsection

{{-- page scripts --}}
@section('page-scripts')
    <script>
        $(document).ready(function () {
            let $form = $('#login-form');

            document.onkeydown = keyListener;

            function keyListener(e) {
                if (e.keyCode == 13) {
                    $form.submit();
                }
            }

            $form.submit(function (e) {
                e.preventDefault();
                $.request({
                    url     : '{{ route('login') }}',
                    type    : 'post',
                    data    : $(this).serialize(),
                    callback: function (res) {
                        console.log(res);
                        if (res.code == 200) {
                            $.toast({
                                title  : '登录成功',
                                message: '正在转跳控制台'
                            });

                            setTimeout(function () {
                                location.href = '/backend';
                            }, 1500);
                        } else {
                            $.toast({
                                title  : '登录失败',
                                type  : 'error',
                                message: res.msg
                            });
                        }
                    }
                });
            });
        });
    </script>
@endsection
