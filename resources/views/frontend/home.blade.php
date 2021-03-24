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
    <!-- faq search start -->
    <section class="faq-search">
        <div class="row">
            <div class="col-12">
                <div class="card faq-bg bg-transparent box-shadow-0 p-1 p-md-5">
                    <div class="card-body p-0">
                        <h1 class="faq-title text-center mb-3">Hello, how can we help?</h1>
                        <form>
                            <fieldset class="faq-search-width form-group position-relative w-50 mx-auto">
                                <input type="text" class="form-control round form-control-lg shadow pl-2"
                                       id="searchbar" placeholder="Ask a question...">
                                <button class="btn btn-primary round position-absolute d-none d-sm-block" type="button">Search</button>
                                <button class="btn btn-primary round position-absolute d-block d-sm-none" type="button"><i
                                        class="bx bx-search"></i></button>
                            </fieldset>
                        </form>
                        <p class="card-text text-center mt-3 font-medium-1 text-muted">
                            or choose a category to quickly find the help you need</p>
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
                        <div class="swiper-centered-slides swiper-container p-1">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide rounded swiper-shadow" id="getting-text"><i
                                        class="bx bx-flag mb-1 font-large-1"></i>
                                    <div class="cent-text1">Getting Started</div>
                                </div>
                                <div class="swiper-slide rounded swiper-shadow" id="pricing-text"><i
                                        class="bx bx-dollar-circle mb-1 font-large-1"></i>
                                    <div class="cent-text1">Pricing & Plans</div>
                                </div>
                                <div class="swiper-slide rounded swiper-shadow" id="sales-text"><i
                                        class="bx bx-shopping-bag mb-1 font-large-1"></i>
                                    <div class="cent-text1">Sales Question</div>
                                </div>
                                <div class="swiper-slide rounded swiper-shadow" id="usage-text"><i
                                        class="bx bx-book-open mb-1 font-large-1"></i>
                                    <div class="cent-text1">Usage Guides</div>
                                </div>
                                <div class="swiper-slide rounded swiper-shadow" id="general-text"><i
                                        class="bx bx-info-circle mb-1 font-large-1"></i>
                                    <div class="cent-text1">General Guide</div>
                                </div>
                            </div>
                            <!-- Add Arrows -->
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                        </div>
                        <div class="main-wrapper-content">
                            <div class="wrapper-content" data-faq="getting-text">
                                <div class="text-center p-md-4 p-sm-1 py-1 p-0">
                                    <h1 class="faq-title">Getting Started</h1>
                                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Nam reprehenderit alias voluptas aspernatur
                                        maiores
                                        quis molestiae totam deserunt exercitationem ipsam officiis nisi, labore magni, commodi quaerat quia
                                        earum
                                        quas illo ea amet minus ad dolor?</p>
                                </div>
                                <!-- accordion start -->
                                <div id="accordion-icon-wrapper1" class="collapse-icon accordion-icon-rotate">
                                    <div class="accordion" id="accordionWrapar2">
                                        <div class="card collapse-header">
                                            <div id="heading5" class="card-header" data-toggle="collapse" role="button"
                                                 data-target="#accordion5" aria-expanded="false" aria-controls="accordion5">
                      <span class="collapse-title d-flex align-items-center"><i class="bx bxs-circle font-small-1"></i>
                        Purchasing process?
                      </span>
                                            </div>
                                            <div id="accordion5" role="tabpanel" data-parent="#accordionWrapar2" aria-labelledby="heading5"
                                                 class="collapse">
                                                <div class="card-body">
                                                    Cheesecake cotton candy bonbon muffin cupcake tiramisu croissant. Tootsie roll sweet candy
                                                    bear
                                                    claw chupa chups lollipop toffee. Macaroon donut liquorice powder candy carrot cake macaroon
                                                    fruitcake. Cookie toffee lollipop cotton candy ice cream dragée soufflé.
                                                    Cake tiramisu lollipop wafer pie soufflé dessert tart. Biscuit ice cream pie apple pie topping
                                                    oat cake dessert. Soufflé icing caramels. Chocolate cake icing ice cream macaroon pie
                                                    cheesecake
                                                    liquorice apple pie. <br> <br> Lorem, ipsum dolor sit amet consectetur adipisicing elit.
                                                    Voluptates
                                                    alias
                                                    architecto ullam? Ratione, vitae, amet corrupti non unde praesentium laborum incidunt fugit
                                                    vel illo
                                                    debitis
                                                    dicta illum fugiat, at consequatur! Voluptatum sunt dolorem at deleniti dolor quis a nam
                                                    facilis.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card collapse-header">
                                            <div id="heading6" class="card-header" data-toggle="collapse" role="button"
                                                 data-target="#accordion66" aria-expanded="false" aria-controls="accordion66">
                      <span class=" collapse-title d-flex align-items-center"><i class="bx bxs-circle font-small-1"></i>
                        How secure are your payment?
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
                        How to apply for a prepaid card?
                      </span>
                                            </div>
                                            <div id="accordion71" role="tabpanel" data-parent="#accordionWrapar2" aria-labelledby="heading17"
                                                 class="collapse" aria-expanded="false">
                                                <div class="card-body">
                                                    Gingerbread liquorice liquorice cake muffin lollipop powder chocolate cake. Gummi bears lemon
                                                    drops toffee liquorice pastry cake caramels chocolate bar brownie. Sweet biscuit chupa chups
                                                    sweet.
                                                    Halvah fruitcake gingerbread croissant dessert cupcake. Chupa chups chocolate bar donut tart.
                                                    Donut cake dessert cookie. Ice cream tootsie roll powder chupa chups pastry cupcake soufflé.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card collapse-header">
                                            <div id="heading81" class="card-header" data-toggle="collapse" role="button"
                                                 data-target="#accordion801" aria-expanded="false" aria-controls="accordion801">
                      <span class=" collapse-title d-flex align-items-center"><i class="bx bxs-circle font-small-1"></i>
                        How secure is my data in App?
                      </span>
                                            </div>
                                            <div id="accordion801" role="tabpanel" data-parent="#accordionWrapar2" aria-labelledby="heading81"
                                                 class="collapse" aria-expanded="false">
                                                <div class="card-body">
                                                    Icing sweet roll cotton candy brownie candy canes candy canes. Pie jelly dragée pie. Ice cream
                                                    jujubes wafer. Wafer croissant carrot cake wafer gummies gummies chupa chups halvah bonbon.
                                                    Gummi bears cotton candy jelly-o halvah. Macaroon apple pie dragée bonbon marzipan cheesecake.
                                                    Jelly jelly beans marshmallow.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card collapse-header">
                                            <div id="heading91" class="card-header" data-toggle="collapse" role="button"
                                                 data-target="#accordion125" aria-expanded="false" aria-controls="accordion125">
                      <span class=" collapse-title d-flex align-items-center"><i class="bx bxs-circle font-small-1"></i>
                        How do I know latest version?
                      </span>
                                            </div>
                                            <div id="accordion125" role="tabpanel" data-parent="#accordionWrapar2" aria-labelledby="heading91"
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
                                            <div id="heading103" class="card-header" data-toggle="collapse" role="button"
                                                 data-target="#accordion142" aria-expanded="false" aria-controls="accordion142">
                      <span class=" collapse-title d-flex align-items-center"><i class="bx bxs-circle font-small-1"></i>
                        Download documentation
                      </span>
                                            </div>
                                            <div id="accordion142" role="tabpanel" data-parent="#accordionWrapar2" aria-labelledby="heading103"
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
                                            <div id="heading113" class="card-header" data-toggle="collapse" role="button"
                                                 data-target="#accordion91" aria-expanded="false" aria-controls="accordion91">
                      <span class=" collapse-title d-flex align-items-center"><i class="bx bxs-circle font-small-1"></i>
                        How secure my personal info?
                      </span>
                                            </div>
                                            <div id="accordion91" role="tabpanel" data-parent="#accordionWrapar2" aria-labelledby="heading113"
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
                                    </div>
                                </div>
                                <!-- Accordion end -->
                            </div>
                            <div class="wrapper-content" data-faq="pricing-text">
                                <div class="text-center p-md-4 p-sm-1 py-1 p-0">
                                    <h1 class="faq-title">Pricing & Planes</h1>
                                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Nam reprehenderit alias voluptas aspernatur
                                        maiores
                                        quis molestiae totam deserunt exercitationem ipsam officiis nisi, labore magni, commodi quaerat quia
                                        earum
                                        quas illo ea amet minus ad dolor?</p>
                                </div>
                                <!-- accordion start -->
                                <div id="accordion-icon-wrapper2" class="collapse-icon accordion-icon-rotate">

                                    <div class="accordion" id="accordionWrapar3">
                                        <div class="card collapse-header">
                                            <div id="heading27" class="card-header" data-toggle="collapse" role="button"
                                                 data-target="#accordion16" aria-expanded="false" aria-controls="accordion16">
                      <span class="collapse-title d-flex align-items-center"><i class="bx bxs-circle font-small-1"></i>
                        What are your Purchasing process?
                      </span>
                                            </div>
                                            <div id="accordion16" role="tabpanel" data-parent="#accordionWrapar3" aria-labelledby="heading27"
                                                 class="collapse">
                                                <div class="card-body">
                                                    Cheesecake cotton candy bonbon muffin cupcake tiramisu croissant. Tootsie roll sweet candy
                                                    bear
                                                    claw chupa chups lollipop toffee. Macaroon donut liquorice powder candy carrot cake macaroon
                                                    fruitcake. Cookie toffee lollipop cotton candy ice cream dragée soufflé.
                                                    Cake tiramisu lollipop wafer pie soufflé dessert tart. Biscuit ice cream pie apple pie topping
                                                    oat cake dessert. Soufflé icing caramels. Chocolate cake icing ice cream macaroon pie
                                                    cheesecake
                                                    liquorice apple pie. <br> <br> Lorem, ipsum dolor sit amet consectetur adipisicing elit.
                                                    Voluptates
                                                    alias
                                                    architecto ullam? Ratione, vitae, amet corrupti non unde praesentium laborum incidunt fugit
                                                    vel illo
                                                    debitis
                                                    dicta illum fugiat, at consequatur! Voluptatum sunt dolorem at deleniti dolor quis a nam
                                                    facilis.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card collapse-header">
                                            <div id="heading82" class="card-header" data-toggle="collapse" role="button"
                                                 data-target="#accordion26" aria-expanded="false" aria-controls="accordion26">
                      <span class=" collapse-title d-flex align-items-center"><i class="bx bxs-circle font-small-1"></i>
                        How rarely our information gets?
                      </span>
                                            </div>
                                            <div id="accordion26" role="tabpanel" data-parent="#accordionWrapar3" aria-labelledby="heading82"
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
                                            <div id="heading37" class="card-header" data-toggle="collapse" role="button"
                                                 data-target="#accordion72" aria-expanded="false" aria-controls="accordion72">
                      <span class=" collapse-title d-flex align-items-center"><i class="bx bxs-circle font-small-1"></i>
                        How to apply for a unknown card?
                      </span>
                                            </div>
                                            <div id="accordion72" role="tabpanel" data-parent="#accordionWrapar3" aria-labelledby="heading37"
                                                 class="collapse" aria-expanded="false">
                                                <div class="card-body">
                                                    Gingerbread liquorice liquorice cake muffin lollipop powder chocolate cake. Gummi bears lemon
                                                    drops toffee liquorice pastry cake caramels chocolate bar brownie. Sweet biscuit chupa chups
                                                    sweet.

                                                    Halvah fruitcake gingerbread croissant dessert cupcake. Chupa chups chocolate bar donut tart.
                                                    Donut cake dessert cookie. Ice cream tootsie roll powder chupa chups pastry cupcake soufflé.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card collapse-header">
                                            <div id="heading83" class="card-header" data-toggle="collapse" role="button"
                                                 data-target="#accordion802" aria-expanded="false" aria-controls="accordion802">
                      <span class=" collapse-title d-flex align-items-center"><i class="bx bxs-circle font-small-1"></i>
                        How secure is data and info in App?
                      </span>
                                            </div>
                                            <div id="accordion802" role="tabpanel" data-parent="#accordionWrapar3" aria-labelledby="heading83"
                                                 class="collapse" aria-expanded="false">
                                                <div class="card-body">
                                                    Icing sweet roll cotton candy brownie candy canes candy canes. Pie jelly dragée pie. Ice cream
                                                    jujubes wafer. Wafer croissant carrot cake wafer gummies gummies chupa chups halvah bonbon.

                                                    Gummi bears cotton candy jelly-o halvah. Macaroon apple pie dragée bonbon marzipan cheesecake.
                                                    Jelly jelly beans marshmallow.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card collapse-header">
                                            <div id="heading92" class="card-header" data-toggle="collapse" role="button"
                                                 data-target="#accordion121" aria-expanded="false" aria-controls="accordion121">
                      <span class=" collapse-title d-flex align-items-center"><i class="bx bxs-circle font-small-1"></i>
                        How do I know about midest version?
                      </span>
                                            </div>
                                            <div id="accordion121" role="tabpanel" data-parent="#accordionWrapar3" aria-labelledby="heading92"
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
                                            <div id="heading104" class="card-header" data-toggle="collapse" role="button"
                                                 data-target="#accordion143" aria-expanded="false" aria-controls="accordion143">
                      <span class=" collapse-title d-flex align-items-center"><i class="bx bxs-circle font-small-1"></i>
                        Download offline documentation works?
                      </span>
                                            </div>
                                            <div id="accordion143" role="tabpanel" data-parent="#accordionWrapar3" aria-labelledby="heading104"
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
                                            <div id="heading114" class="card-header" data-toggle="collapse" role="button"
                                                 data-target="#accordion92" aria-expanded="false" aria-controls="accordion92">
                      <span class=" collapse-title d-flex align-items-center"><i class="bx bxs-circle font-small-1"></i>
                        Secure my personal data?
                      </span>
                                            </div>
                                            <div id="accordion92" role="tabpanel" data-parent="#accordionWrapar3" aria-labelledby="heading114"
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
                                    </div>
                                </div>
                                <!-- Accordion end -->
                            </div>
                            <div class="wrapper-content" data-faq="sales-text">
                                <div class="text-center p-md-4 p-sm-1 py-1 p-0">
                                    <h1 class="faq-title">Sales Question</h1>
                                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Nam reprehenderit alias voluptas aspernatur
                                        maiores
                                        quis molestiae totam deserunt exercitationem ipsam officiis nisi, labore magni, commodi quaerat quia
                                        earum
                                        quas illo ea amet minus ad dolor?</p>
                                </div>
                                <!-- accordion start -->
                                <div id="accordion-icon-wrapper3" class="collapse-icon accordion-icon-rotate">

                                    <div class="accordion" id="accordionWrapar4">
                                        <div class="card collapse-header">
                                            <div id="heading47" class="card-header" data-toggle="collapse" role="button"
                                                 data-target="#accordion73" aria-expanded="false" aria-controls="accordion73">
                      <span class="collapse-title d-flex align-items-center"><i class="bx bxs-circle font-small-1"></i>
                        Detailed sales process?
                      </span>
                                            </div>
                                            <div id="accordion73" role="tabpanel" data-parent="#accordionWrapar4" aria-labelledby="heading47"
                                                 class="collapse">
                                                <div class="card-body">
                                                    Cheesecake cotton candy bonbon muffin cupcake tiramisu croissant. Tootsie roll sweet candy
                                                    bear
                                                    claw chupa chups lollipop toffee. Macaroon donut liquorice powder candy carrot cake macaroon
                                                    fruitcake. Cookie toffee lollipop cotton candy ice cream dragée soufflé.
                                                    Cake tiramisu lollipop wafer pie soufflé dessert tart. Biscuit ice cream pie apple pie topping
                                                    oat cake dessert. Soufflé icing caramels. Chocolate cake icing ice cream macaroon pie
                                                    cheesecake
                                                    liquorice apple pie. <br> <br> Lorem, ipsum dolor sit amet consectetur adipisicing elit.
                                                    Voluptates
                                                    alias
                                                    architecto ullam? Ratione, vitae, amet corrupti non unde praesentium laborum incidunt fugit
                                                    vel illo
                                                    debitis
                                                    dicta illum fugiat, at consequatur! Voluptatum sunt dolorem at deleniti dolor quis a nam
                                                    facilis.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card collapse-header">
                                            <div id="heading93" class="card-header" data-toggle="collapse" role="button"
                                                 data-target="#accordion36" aria-expanded="false" aria-controls="accordion36">
                      <span class=" collapse-title d-flex align-items-center"><i class="bx bxs-circle font-small-1"></i>
                        What you sales ?
                      </span>
                                            </div>
                                            <div id="accordion36" role="tabpanel" data-parent="#accordionWrapar4" aria-labelledby="heading93"
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
                                            <div id="heading57" class="card-header" data-toggle="collapse" role="button"
                                                 data-target="#accordion74" aria-expanded="false" aria-controls="accordion74">
                      <span class=" collapse-title d-flex align-items-center"><i class="bx bxs-circle font-small-1"></i>
                        How to apply for a sales card?
                      </span>
                                            </div>
                                            <div id="accordion74" role="tabpanel" data-parent="#accordionWrapar4" aria-labelledby="heading57"
                                                 class="collapse" aria-expanded="false">
                                                <div class="card-body">
                                                    Gingerbread liquorice liquorice cake muffin lollipop powder chocolate cake. Gummi bears lemon
                                                    drops toffee liquorice pastry cake caramels chocolate bar brownie. Sweet biscuit chupa chups
                                                    sweet.

                                                    Halvah fruitcake gingerbread croissant dessert cupcake. Chupa chups chocolate bar donut tart.
                                                    Donut cake dessert cookie. Ice cream tootsie roll powder chupa chups pastry cupcake soufflé.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card collapse-header">
                                            <div id="heading84" class="card-header" data-toggle="collapse" role="button"
                                                 data-target="#accordion803" aria-expanded="false" aria-controls="accordion803">
                      <span class=" collapse-title d-flex align-items-center"><i class="bx bxs-circle font-small-1"></i>
                        How secure is my data in sales App?
                      </span>
                                            </div>
                                            <div id="accordion803" role="tabpanel" data-parent="#accordionWrapar4" aria-labelledby="heading84"
                                                 class="collapse" aria-expanded="false">
                                                <div class="card-body">
                                                    Icing sweet roll cotton candy brownie candy canes candy canes. Pie jelly dragée pie. Ice cream
                                                    jujubes wafer. Wafer croissant carrot cake wafer gummies gummies chupa chups halvah bonbon.

                                                    Gummi bears cotton candy jelly-o halvah. Macaroon apple pie dragée bonbon marzipan cheesecake.
                                                    Jelly jelly beans marshmallow.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card collapse-header">
                                            <div id="heading94" class="card-header" data-toggle="collapse" role="button"
                                                 data-target="#accordion122" aria-expanded="false" aria-controls="accordion122">
                      <span class=" collapse-title d-flex align-items-center"><i class="bx bxs-circle font-small-1"></i>
                        How do I know sales?
                      </span>
                                            </div>
                                            <div id="accordion122" role="tabpanel" data-parent="#accordionWrapar4" aria-labelledby="heading94"
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
                                            <div id="heading105" class="card-header" data-toggle="collapse" role="button"
                                                 data-target="#accordion144" aria-expanded="false" aria-controls="accordion144">
                      <span class=" collapse-title d-flex align-items-center"><i class="bx bxs-circle font-small-1"></i>
                        Download offline sales doc
                      </span>
                                            </div>
                                            <div id="accordion144" role="tabpanel" data-parent="#accordionWrapar4" aria-labelledby="heading105"
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
                                            <div id="heading115" class="card-header" data-toggle="collapse" role="button"
                                                 data-target="#accordion93" aria-expanded="false" aria-controls="accordion93">
                      <span class=" collapse-title d-flex align-items-center"><i class="bx bxs-circle font-small-1"></i>
                        How secure my sales information?
                      </span>
                                            </div>
                                            <div id="accordion93" role="tabpanel" data-parent="#accordionWrapar4" aria-labelledby="heading115"
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
                                    </div>
                                </div>
                                <!-- Accordion end -->
                            </div>
                            <div class="wrapper-content" data-faq="usage-text">
                                <div class="text-center p-md-4 p-sm-1 py-1 p-0">
                                    <h1 class="faq-title">Usage Guides</h1>
                                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Nam reprehenderit alias voluptas aspernatur
                                        maiores
                                        quis molestiae totam deserunt exercitationem ipsam officiis nisi, labore magni, commodi quaerat quia
                                        earum
                                        quas illo ea amet minus ad dolor? Lorem1 </p>
                                </div>
                                <!-- accordion start -->
                                <div id="accordion-icon-wrapper4" class="collapse-icon accordion-icon-rotate">
                                    <div class="accordion" id="accordionWrapar5">
                                        <div class="card collapse-header">
                                            <div id="heading85" class="card-header" data-toggle="collapse" role="button"
                                                 data-target="#accordion804" aria-expanded="false" aria-controls="accordion804">
                      <span class="collapse-title d-flex align-items-center"><i class="bx bxs-circle font-small-1"></i>
                        Purchasing user process?
                      </span>
                                            </div>
                                            <div id="accordion804" role="tabpanel" data-parent="#accordionWrapar5" aria-labelledby="heading85"
                                                 class="collapse">
                                                <div class="card-body">
                                                    Cheesecake cotton candy bonbon muffin cupcake tiramisu croissant. Tootsie roll sweet candy
                                                    bear
                                                    claw chupa chups lollipop toffee. Macaroon donut liquorice powder candy carrot cake macaroon
                                                    fruitcake. Cookie toffee lollipop cotton candy ice cream dragée soufflé.
                                                    Cake tiramisu lollipop wafer pie soufflé dessert tart. Biscuit ice cream pie apple pie topping
                                                    oat cake dessert. Soufflé icing caramels. Chocolate cake icing ice cream macaroon pie
                                                    cheesecake
                                                    liquorice apple pie. <br> <br> Lorem, ipsum dolor sit amet consectetur adipisicing elit.
                                                    Voluptates
                                                    alias
                                                    architecto ullam? Ratione, vitae, amet corrupti non unde praesentium laborum incidunt fugit
                                                    vel illo
                                                    debitis
                                                    dicta illum fugiat, at consequatur! Voluptatum sunt dolorem at deleniti dolor quis a nam
                                                    facilis.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card collapse-header">
                                            <div id="heading106" class="card-header" data-toggle="collapse" role="button"
                                                 data-target="#accordion46" aria-expanded="false" aria-controls="accordion46">
                      <span class=" collapse-title d-flex align-items-center"><i class="bx bxs-circle font-small-1"></i>
                        How secure are and legal rights?
                      </span>
                                            </div>
                                            <div id="accordion46" role="tabpanel" data-parent="#accordionWrapar5" aria-labelledby="heading106"
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
                                            <div id="heading67" class="card-header" data-toggle="collapse" role="button"
                                                 data-target="#accordion75" aria-expanded="false" aria-controls="accordion75">
                      <span class=" collapse-title d-flex align-items-center"><i class="bx bxs-circle font-small-1"></i>
                        How to write for a postpaid card?
                      </span>
                                            </div>
                                            <div id="accordion75" role="tabpanel" data-parent="#accordionWrapar5" aria-labelledby="heading67"
                                                 class="collapse" aria-expanded="false">
                                                <div class="card-body">
                                                    Gingerbread liquorice liquorice cake muffin lollipop powder chocolate cake. Gummi bears lemon
                                                    drops toffee liquorice pastry cake caramels chocolate bar brownie. Sweet biscuit chupa chups
                                                    sweet.

                                                    Halvah fruitcake gingerbread croissant dessert cupcake. Chupa chups chocolate bar donut tart.
                                                    Donut cake dessert cookie. Ice cream tootsie roll powder chupa chups pastry cupcake soufflé.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card collapse-header">
                                            <div id="heading86" class="card-header" data-toggle="collapse" role="button"
                                                 data-target="#accordion805" aria-expanded="false" aria-controls="accordion805">
                      <span class=" collapse-title d-flex align-items-center"><i class="bx bxs-circle font-small-1"></i>
                        How refresh is my info in App?
                      </span>
                                            </div>
                                            <div id="accordion805" role="tabpanel" data-parent="#accordionWrapar5" aria-labelledby="heading86"
                                                 class="collapse" aria-expanded="false">
                                                <div class="card-body">
                                                    Icing sweet roll cotton candy brownie candy canes candy canes. Pie jelly dragée pie. Ice cream
                                                    jujubes wafer. Wafer croissant carrot cake wafer gummies gummies chupa chups halvah bonbon.

                                                    Gummi bears cotton candy jelly-o halvah. Macaroon apple pie dragée bonbon marzipan cheesecake.
                                                    Jelly jelly beans marshmallow.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card collapse-header">
                                            <div id="heading95" class="card-header" data-toggle="collapse" role="button"
                                                 data-target="#accordion123" aria-expanded="false" aria-controls="accordion123">
                      <span class=" collapse-title d-flex align-items-center"><i class="bx bxs-circle font-small-1"></i>
                        How do write about about latest ?
                      </span>
                                            </div>
                                            <div id="accordion123" role="tabpanel" data-parent="#accordionWrapar5" aria-labelledby="heading95"
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
                                            <div id="heading101" class="card-header" data-toggle="collapse" role="button"
                                                 data-target="#accordion145" aria-expanded="false" aria-controls="accordion145">
                      <span class=" collapse-title d-flex align-items-center"><i class="bx bxs-circle font-small-1"></i>
                        Download offline sales doc
                      </span>
                                            </div>
                                            <div id="accordion145" role="tabpanel" data-parent="#accordionWrapar5" aria-labelledby="heading101"
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
                                            <div id="heading116" class="card-header" data-toggle="collapse" role="button"
                                                 data-target="#accordion94" aria-expanded="false" aria-controls="accordion94">
                      <span class=" collapse-title d-flex align-items-center"><i class="bx bxs-circle font-small-1"></i>
                        How secure is your personal information?
                      </span>
                                            </div>
                                            <div id="accordion94" role="tabpanel" data-parent="#accordionWrapar5" aria-labelledby="heading116"
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
                                    </div>
                                </div>
                                <!-- Accordion end -->
                            </div>
                            <div class="wrapper-content" data-faq="general-text">
                                <div class="text-center p-md-4 p-sm-1 py-1 p-0">
                                    <h1 class="faq-title">General Guide</h1>
                                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Nam reprehenderit alias voluptas aspernatur
                                        maiores
                                        quis molestiae totam deserunt exercitationem ipsam officiis nisi, labore magni, commodi quaerat quia
                                        earum
                                        quas illo ea amet minus ad dolor?</p>
                                </div>
                                <!-- accordion start -->
                                <div id="accordion-icon-wrapper5" class="collapse-icon accordion-icon-rotate">
                                    <div class="accordion" id="accordionWrapar6">
                                        <div class="card collapse-header">
                                            <div id="heading96" class="card-header" data-toggle="collapse" role="button"
                                                 data-target="#accordion95" aria-expanded="false" aria-controls="accordion95">
                      <span class="collapse-title d-flex align-items-center"><i class="bx bxs-circle font-small-1"></i>
                        I wanna write process?
                      </span>
                                            </div>
                                            <div id="accordion95" role="tabpanel" data-parent="#accordionWrapar6" aria-labelledby="heading96"
                                                 class="collapse">
                                                <div class="card-body">
                                                    Cheesecake cotton candy bonbon muffin cupcake tiramisu croissant. Tootsie roll sweet candy
                                                    bear
                                                    claw chupa chups lollipop toffee. Macaroon donut liquorice powder candy carrot cake macaroon
                                                    fruitcake. Cookie toffee lollipop cotton candy ice cream dragée soufflé.
                                                    Cake tiramisu lollipop wafer pie soufflé dessert tart. Biscuit ice cream pie apple pie topping
                                                    oat cake dessert. Soufflé icing caramels. Chocolate cake icing ice cream macaroon pie
                                                    cheesecake
                                                    liquorice apple pie. <br> <br> Lorem, ipsum dolor sit amet consectetur adipisicing elit.
                                                    Voluptates
                                                    alias
                                                    architecto ullam? Ratione, vitae, amet corrupti non unde praesentium laborum incidunt fugit
                                                    vel illo
                                                    debitis
                                                    dicta illum fugiat, at consequatur! Voluptatum sunt dolorem at deleniti dolor quis a nam
                                                    facilis.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card collapse-header">
                                            <div id="heading111" class="card-header" data-toggle="collapse" role="button"
                                                 data-target="#accordion56" aria-expanded="false" aria-controls="accordion56">
                      <span class=" collapse-title d-flex align-items-center"><i class="bx bxs-circle font-small-1"></i>
                        How untoched your gateway information?
                      </span>
                                            </div>
                                            <div id="accordion56" role="tabpanel" data-parent="#accordionWrapar6" aria-labelledby="heading111"
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
                                            <div id="heading77" class="card-header" data-toggle="collapse" role="button"
                                                 data-target="#accordion76" aria-expanded="false" aria-controls="accordion76">
                      <span class=" collapse-title d-flex align-items-center"><i class="bx bxs-circle font-small-1"></i>
                        How to apply for a details?
                      </span>
                                            </div>
                                            <div id="accordion76" role="tabpanel" data-parent="#accordionWrapar6" aria-labelledby="heading77"
                                                 class="collapse" aria-expanded="false">
                                                <div class="card-body">
                                                    Gingerbread liquorice liquorice cake muffin lollipop powder chocolate cake. Gummi bears lemon
                                                    drops toffee liquorice pastry cake caramels chocolate bar brownie. Sweet biscuit chupa chups
                                                    sweet.

                                                    Halvah fruitcake gingerbread croissant dessert cupcake. Chupa chups chocolate bar donut tart.
                                                    Donut cake dessert cookie. Ice cream tootsie roll powder chupa chups pastry cupcake soufflé.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card collapse-header">
                                            <div id="heading87" class="card-header" data-toggle="collapse" role="button"
                                                 data-target="#accordion800" aria-expanded="false" aria-controls="accordion800">
                      <span class=" collapse-title d-flex align-items-center"><i class="bx bxs-circle font-small-1"></i>
                        How secure is opensource ?
                      </span>
                                            </div>
                                            <div id="accordion800" role="tabpanel" data-parent="#accordionWrapar6" aria-labelledby="heading87"
                                                 class="collapse" aria-expanded="false">
                                                <div class="card-body">
                                                    Icing sweet roll cotton candy brownie candy canes candy canes. Pie jelly dragée pie. Ice cream
                                                    jujubes wafer. Wafer croissant carrot cake wafer gummies gummies chupa chups halvah bonbon.

                                                    Gummi bears cotton candy jelly-o halvah. Macaroon apple pie dragée bonbon marzipan cheesecake.
                                                    Jelly jelly beans marshmallow.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card collapse-header">
                                            <div id="heading97" class="card-header" data-toggle="collapse" role="button"
                                                 data-target="#accordion124" aria-expanded="false" aria-controls="accordion124">
                      <span class=" collapse-title d-flex align-items-center"><i class="bx bxs-circle font-small-1"></i>
                        How do I know about oldest version?
                      </span>
                                            </div>
                                            <div id="accordion124" role="tabpanel" data-parent="#accordionWrapar6" aria-labelledby="heading97"
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
                                            <div id="heading102" class="card-header" data-toggle="collapse" role="button"
                                                 data-target="#accordion141" aria-expanded="false" aria-controls="accordion141">
                      <span class=" collapse-title d-flex align-items-center"><i class="bx bxs-circle font-small-1"></i>
                        Download offline sales doc
                      </span>
                                            </div>
                                            <div id="accordion141" role="tabpanel" data-parent="#accordionWrapar6" aria-labelledby="heading102"
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
                                            <div id="heading112" class="card-header" data-toggle="collapse" role="button"
                                                 data-target="#accordion96" aria-expanded="false" aria-controls="accordion96">
                      <span class=" collapse-title d-flex align-items-center"><i class="bx bxs-circle font-small-1"></i>
                        How secure personal data ?
                      </span>
                                            </div>
                                            <div id="accordion96" role="tabpanel" data-parent="#accordionWrapar6" aria-labelledby="heading112"
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
        <div class="row mb-2">
            <div class="col-12 text-center">
                <h2 class="faq-subtitle">You still have a question?</h2>
                <p class="p-2 text-muted font-medium-1">If you cannot find a question in our FAQ, you can always contact us. We
                    will answer to you shortly!</p>
            </div>
        </div>
        <div class="row d-flex justify-content-center mb-5">
            <div class="col-sm-12 col-md-4 text-center border rounded p-2 mr-md-2 m-1">
                <i class="bx bx-phone-call primary font-large-1 text-muted p-50"></i>
                <h5>+ (810) 2548 2568</h5>
                <p class="text-muted font-medium-1"> we are always happy to help!</p>
            </div>
            <div class="col-sm-12 col-md-4 text-center border color-gray-faq rounded p-2 m-1">
                <i class="bx bx-mail-send primary font-large-1 p-50"></i>
                <h5><a href="mailto:hello@help.com">hello@help.com</a></h5>
                <p class="text-muted font-medium-1">Best way to get answer faster!</p>
            </div>
        </div>
    </section>
    <!-- fab bottom ends -->
@endsection

{{-- vendor scripts --}}
@section('vendor-scripts')
    <script src="{{asset('vendors/js/extensions/swiper.min.js')}}"></script>
@endsection
{{-- page scripts --}}
@section('page-scripts')
    <script src="{{asset('js/scripts/pages/faq.js')}}"></script>
@endsection
