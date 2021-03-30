@extends('layouts.frontendLayout')
{{-- title --}}
@section('title','FAQ')
{{-- vendor style --}}
@section('vendor-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/extensions/swiper.min.css')}}">
@endsection
{{-- page-styles --}}
@section('page-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('css/plugins/extensions/swiper.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/pages/faq.css')}}">
@endsection

@section('content')
    <div style="width: 1040px; margin: 0 auto;">
    <!-- faq search start -->
    <section class="faq-search">
        <div class="row">
            <div class="col-12">
                <div class="card faq-bg bg-transparent box-shadow-0 p-1 p-md-5">
                    <div class="card-body p-0">
                        <h1 class="faq-title text-center mb-3">您好，查詢寄送進度嗎?</h1>
                        <form>
                            <fieldset class="faq-search-width form-group position-relative w-75 mx-auto">
                                <input type="text" class="form-control round form-control-lg shadow pl-2"
                                       id="searchbar" placeholder="請輸入您的物流單號">
                                <button class="btn btn-primary round position-absolute d-none d-sm-block" type="button">查詢</button>
                                <button class="btn btn-primary round position-absolute d-block d-sm-none" type="button"><i class="bx bx-search"></i></button>
                            </fieldset>
                        </form>
                        <p class="card-text text-center mt-3 text-muted">
                            <button type="button" class="btn btn-primary round" data-toggle="modal" data-target="#default">
                                託運委託請按此
                            </button>
                            或參考下面的說明了解更多我們的服務
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- faq search ends -->

    <!-- faq start -->
    <section class="faq">
        <div class="row">
            <div class="col-12">
                <!-- swiper start -->
                <div class="card bg-transparent shadow-none">
                    <div class="card-body">
                        <div class="main-wrapper-content">
                            <div class="wrapper-content" data-faq="getting-text">
                                <div class="text-center p-md-2 p-sm-1 py-1 p-0">
                                    <h1 class="faq-title">常見問題</h1>
                                    <p></p>
                                </div>
                                <!-- accordion start -->
                                <div id="accordion-icon-wrapper1" class="collapse-icon accordion-icon-rotate">
                                    <div class="accordion" id="accordionWrapar2">
                                        <div class="card collapse-header">
                                            <div id="heading5" class="card-header" data-toggle="collapse" role="button" data-target="#accordion5" aria-expanded="false" aria-controls="accordion5">
                                                  <span class="collapse-title d-flex align-items-center"><i class="bx bxs-circle font-small-1"></i>
                                                    寄送範圍與服務品項?
                                                  </span>
                                            </div>
                                            <div id="accordion5" role="tabpanel" data-parent="#accordionWrapar2" aria-labelledby="heading5"
                                                 class="collapse">
                                                <div class="card-body">
                                                    Cheesecake cotton candy bonbon muffin cupcake tiramisu croissant. Tootsie roll sweet candy
                                                    bear
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card collapse-header">
                                            <div id="heading6" class="card-header" data-toggle="collapse" role="button"
                                                 data-target="#accordion66" aria-expanded="false" aria-controls="accordion66">
                                                  <span class=" collapse-title d-flex align-items-center"><i class="bx bxs-circle font-small-1"></i>
                                                    收件地點與運送方式?
                                                  </span>
                                            </div>
                                            <div id="accordion66" role="tabpanel" data-parent="#accordionWrapar2" aria-labelledby="heading6"
                                                 class="collapse" aria-expanded="false">
                                                <div class="card-body">
                                                    Pie pudding candy. Oat cake jelly beans bear claw lollipop. Ice cream candy canes tootsie roll
                                                    muffin powder donut powder. Topping candy canes chocolate bar lemon drops candy canes.
                                                    Halvah muffin marzipan powder sugar plum donut donut cotton candy biscuit. Wafer jujubes apple
                                                    pie sweet lemon drops jelly cupcake. Caramels dessert halvah marshmallow. Candy topping cotton
                                                    candy oat cake croissant halvah gummi bears toffee powder.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card collapse-header">
                                            <div id="heading17" class="card-header" data-toggle="collapse" role="button"
                                                 data-target="#accordion71" aria-expanded="false" aria-controls="accordion71">
                                                  <span class=" collapse-title d-flex align-items-center"><i class="bx bxs-circle font-small-1"></i>
                                                    收費方式與特定商品說明?
                                                  </span>
                                            </div>
                                            <div id="accordion71" role="tabpanel" data-parent="#accordionWrapar2" aria-labelledby="heading17"
                                                 class="collapse" aria-expanded="false">
                                                <div class="card-body">
                                                    Gingerbread liquorice liquorice cake muffin lollipop powder chocolate cake. Gummi bears lemon
                                                    drops toffee liquorice pastry cake caramels chocolate bar brownie. Sweet biscuit chupa chups
                                                    sweet.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Accordion end -->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- swiper ends -->
            </div>
        </div>
    </section>
    <!-- faq ends -->

    <!-- fab bottom start -->
    <section class="faq-bottom">
        <div class="row">
            <div class="col-12 text-center">
                <h2 class="faq-subtitle">還有問題嗎？</h2>
                <p class="p-2 text-muted font-medium-1">假如 FAQ 沒辦法解決您的疑問，隨時歡迎您透過以下方式聯繫我們，相信我們一定能解決您的問題！</p>
            </div>
        </div>
        <div class="row d-flex justify-content-center mb-5">
            <div class="col-sm-12 col-md-4 text-center border rounded p-2 mr-md-2 m-1">
                <i class="bx bx-phone-call primary font-large-1 text-muted p-50"></i>
                <h5 class="m-1">+ (852) 5500 0715</h5>
                <p class="text-muted font-small-3"> 歡迎來電由專人為您服務！</p>
            </div>
            <div class="col-sm-12 col-md-4 text-center border color-gray-faq rounded p-2 m-1">
                <a href="https://www.facebook.com/%E9%A6%B9%E9%8B%92%E7%89%A9%E6%B5%81-Forward-Logistics-588163535183613/?comment_id=Y29tbWVudDo1ODkyODE1ODUwNzE4MDhfNTg5NjgzNTI1MDMxNjE0"><i class="bx bxl-facebook-circle primary font-large-1 p-50"></i></a>
                <h5 class="m-1">Forward Logistics</h5>
                <p class="text-muted font-small-3">馹鋒物流 - 專業香港寄大陸清關快件！</p>
            </div>
        </div>
    </section>
    <!-- fab bottom ends -->

    <!-- 彈窗表單 -->
    <div class="modal fade text-left" id="default" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1"
         aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="myModalLabel1">請填寫託運委託資料</h3>
                    <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form" class="form" method="post" action="" novalidate>
                        <div class="form-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="input-name"><span class="danger">*</span> 寄貨地點</label>
                                        <div class="controls">
                                            <select id="select-type" class="form-control" name="delivery_location">
                                                @foreach($locations as $location)
                                                <option value="{{ $location->id }}" selected>{{ $location->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="input-key"><span class="danger">*</span> 運輸方式</label>
                                        <div class="controls">
                                            <select id="select-type" class="form-control" name="delivery_location">
                                                @foreach($shipments as $shipment)
                                                    <option value="{{ $shipment->id }}" selected>{{ $shipment->name }} ({{ $shipment->description }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="input-value"><span class="danger">*</span> 寄件人姓名</label>
                                        <div class="controls">
                                            <input type="text" class="form-control" name="receiver_name" placeholder="請填寫真實姓名">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="input-value"><span class="danger">*</span> 收件人電話</label>
                                        <div class="controls">
                                            <input type="text" class="form-control" name="receiver_phone" placeholder="">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="input-value"><span class="danger">*</span> 收件人地址</label>
                                        <div class="controls">
                                            <input type="text" class="form-control" name="receiver_address" placeholder="請填寫完整地址">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="input-value"><span class="danger">*</span> 寄貨清單</label>
                                        <fieldset class="repeater-default">
                                            <div data-repeater-list="shipping-list">
                                                    <div class="row justify-content-between">
                                                        <div class="col-md-5 col-sm-12 form-group">
                                                            <label>品項</label>
                                                        </div>
                                                        <div class="col-md-2 col-sm-12 form-group">
                                                            <label>件數</label>
                                                        </div>
                                                        <div class="col-md-3 col-sm-12 form-group">
                                                            <label>重量</label>
                                                        </div>
                                                        <div class="col-md-2 col-sm-12 form-group d-flex align-items-center pt-2">
                                                        </div>
                                                    </div>
                                                    <div class="row justify-content-between" data-repeater-item>
                                                        <div class="col-md-5 col-sm-12 form-group">
                                                            <input type="text" class="form-control" name="item">
                                                        </div>
                                                        <div class="col-md-2 col-sm-12 form-group">
                                                            <input type="number" class="form-control" name="pieces">
                                                        </div>
                                                        <div class="col-md-3 col-sm-12 form-group">
                                                            <input type="number" class="form-control" name="weight" placeholder="單位公克">
                                                        </div>
                                                        <div class="col-md-2 col-sm-12 form-group d-flex align-items-center">
                                                            <button class="btn btn-danger text-nowrap px-1" data-repeater-delete type="button">
                                                                <i class="bx bx-x"></i> 移除
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <hr>
                                            </div>
                                            <div class="form-group">
                                                <div class="col p-0">
                                                    <button class="btn btn-primary px-1" data-repeater-create type="button">
                                                        <i class="bx bx-plus"></i> 增加品項
                                                    </button>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="input-value">總單價值</label>
                                        <div class="controls">
                                            <input type="number" class="form-control" name="total_value">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="input-value">預估運費</label>
                                        <div class="controls">
                                            <input type="number" class="form-control" name="total_value">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">關閉</span>
                    </button>
                    <button type="button" class="btn btn-primary ml-1" data-dismiss="modal">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">送出</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

{{-- vendor scripts --}}
@section('vendor-scripts')
    <script src="{{asset('vendors/js/forms/repeater/jquery.repeater.min.js')}}"></script>
@endsection
{{-- page scripts --}}
@section('page-scripts')
    <script>
        $(document).ready(function () {
            // form repeater jquery
            $('.file-repeater, .contact-repeater, .repeater-default').repeater({
                show: function () {
                    $(this).slideDown();
                },
                hide: function (deleteElement) {
                    if (confirm('Are you sure you want to delete this element?')) {
                        $(this).slideUp(deleteElement);
                    }
                }
            });

        });
    </script>
@endsection
