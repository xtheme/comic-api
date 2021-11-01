@extends('layouts.iframePage')

{{-- page Title --}}
@section('title','Dashboard')

{{-- vendor css --}}
@section('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/charts/apexcharts.css') }}">
@endsection

@section('content')
    <!-- Dashboard Ecommerce Starts -->
    <section id="dashboard-ecommerce">
        <div class="row">
            <!-- 总用户数 -->
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card text-center">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="badge-circle badge-circle-lg badge-circle-light-primary mx-auto my-1">
                                <i class="bx bxs-group font-medium-5"></i>
                            </div>
                            <p class="text-muted mb-0 line-ellipsis">总用户数</p>
                            <h2 class="mb-0">{{ number_format($statistics['total_users'])}}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <!-- 今日注册人数 -->
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card text-center">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="badge-circle badge-circle-lg badge-circle-light-info mx-auto my-1">
                                <i class="bx bxs-user-plus font-medium-5"></i>
                            </div>
                            <p class="text-muted mb-0 line-ellipsis">今日注册</p>
                            <h2 class="mb-0">{{ number_format($statistics['today_register']) }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <!-- 今日活跃人数 -->
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card text-center">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="badge-circle badge-circle-lg badge-circle-light-warning mx-auto my-1">
                                <i class="bx bxs-user font-medium-5"></i>
                            </div>
                            <p class="text-muted mb-0 line-ellipsis">日活人数</p>
                            <h2 class="mb-0">{{ number_format($statistics['today_login']) }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <!-- 今日订单数 -->
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card text-center">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="badge-circle badge-circle-lg badge-circle-light-danger mx-auto my-1">
                                <i class="bx bxs-user font-medium-5"></i>
                            </div>
                            <p class="text-muted mb-0 line-ellipsis">今日订单数</p>
                            <h2 class="mb-0">{{ number_format($statistics['today_orders_count']) }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <!-- 今日订单金额 -->
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card text-center">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="badge-circle badge-circle-lg badge-circle-light-success mx-auto my-1">
                                <i class="bx bxs-user font-medium-5"></i>
                            </div>
                            <p class="text-muted mb-0 line-ellipsis">今日订单金额</p>
                            <h2 class="mb-0">{{ number_format($statistics['today_orders_amount']) }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <!-- 有效漫画数 -->
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card text-center">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="badge-circle badge-circle-lg badge-circle-light-primary mx-auto my-1">
                                <i class="bx bxs-user font-medium-5"></i>
                            </div>
                            <p class="text-muted mb-0 line-ellipsis">有效漫画数</p>
                            <h2 class="mb-0">{{ number_format($statistics['total_books']) }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <!-- 用户成长走势 -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">用户成长走势</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div id="analytics-bar-chart" class="d-flex justify-content-center"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Dashboard Ecommerce ends -->
@endsection

@section('vendor-scripts')
    <script src="{{ asset('vendors/js/charts/apexcharts.min.js') }}"></script>
@endsection

@section('page-scripts')
    <script>
        $(window).on("load", function () {
            var $primary = '#5A8DEE';
            var $success = '#39DA8A';
            var $danger = '#FF5B5C';
            var $warning = '#FDAC41';
            var $info = '#00CFDD';
            var $label_color = '#475f7b';
            var $primary_light = '#E2ECFF';
            var $danger_light = '#ffeed9';
            var $gray_light = '#828D99';
            var $sub_label_color = "#596778";
            var $radial_bg = "#e7edf3";

            var themeColors = [$primary, $warning, $danger, $success, $info, $label_color];

            // 用戶成長走勢
            // ---------
            var analyticsBarChartOptions = {
                chart: {
                    height: 260,
                    type: 'bar',
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '20%',
                        endingShape: 'rounded'
                    },
                },
                dataLabels: {
                    enabled: false
                },
                colors: [$primary, $primary_light],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'light',
                        type: "vertical",
                        inverseColors: true,
                        opacityFrom: 1,
                        opacityTo: 1,
                        stops: [0, 70, 100]
                    },
                },
                series: [],
                noData: {
                    text: '数据加载中...'
                },
                xaxis: {
                    categories: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'],
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    },
                    labels: {
                        style: {
                            colors: $gray_light
                        }
                    }
                },
                yaxis: {
                    min: 0,
                    max: 5000,
                    tickAmount: 5,
                    labels: {
                        style: {
                            color: $gray_light
                        }
                    }
                },
                legend: {
                    show: false
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return val
                        }
                    }
                }
            }

            var analyticsBarChart = new ApexCharts(
                document.querySelector("#analytics-bar-chart"),
                analyticsBarChartOptions
            );

            $.getJSON('{{ route('backend.dashboard.user.growth') }}', function(response) {
                analyticsBarChart.updateSeries(response);
            });

            analyticsBarChart.render();
        });
    </script>
@endsection

