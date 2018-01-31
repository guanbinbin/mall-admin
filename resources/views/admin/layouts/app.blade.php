<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Mosaddek">
    <meta name="keyword" content="psy ">
    <link rel="shortcut icon" href="{{ asset('/static/img/favicon.png') }}">

    <title>FlatLab - 后台管理系统</title>

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('/static/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/static/css/bootstrap-reset.css') }}" rel="stylesheet">
    <!--external css-->
    <link href="{{ asset('/static/assets/font-awesome/css/font-awesome.css') }}" rel="stylesheet"/>
    <link href="{{ asset('/static/assets/jquery-easy-pie-chart/jquery.easy-pie-chart.css') }}" rel="stylesheet"
          type="text/css" media="screen"/>
    <link rel="stylesheet" href="{{ asset('/static/css/owl.carousel.css') }}" type="text/css">
    <!-- Custom styles for this template -->
    <link href="{{ asset('/static/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('/static/css/style-responsive.css') }}" rel="stylesheet"/>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
    <!--[if lt IE 9]>
    <script src="{{ asset('/static/js/html5shiv.js')}}"></script>
    <script src="{{ asset('/static/js/respond.min.js')}}"></script>
    <![endif]-->

    <style type="text/css">

    </style>
    @yield('css')
</head>

<body>

<section id="container" class="">

    <!--header start-->
@include('admin.layouts.start')
<!--header end-->

    <!--sidebar start-->
@include('admin.layouts.sidebar')
<!--sidebar end-->

    <section id="main-content">

        {{--<!-- 面包屑 stsrt -->--}}
        <section class="wrapper col-lg-12" style="margin-bottom: 0;padding-bottom: 0; ">
            <div class="row">

                <div class="panel-body alert-div" style="padding-bottom: 0;display: none;">
                    <div class="alert alert-success alert-block fade in" style="display: none;">
                        <button data-dismiss="alert" class="close close-sm" type="button">
                            <i class="icon-remove"></i>
                        </button>
                        <p>Best check yo self, you're not looking too good...</p>
                    </div>

                    <div class="alert alert-danger alert-block fade in" style="display: none;">
                        <button data-dismiss="alert" class="close close-sm" type="button">
                            <i class="icon-remove"></i>
                        </button>
                        <p>Best check yo self, you're not looking too good...</p>
                    </div>
                </div>

            </div>
        </section>
        <!-- 面包屑 end -->
        <!--main content start-->
            @yield('content')
        <!--main content end-->
    </section>


</section>

<!-- js placed at the end of the document so the pages load faster -->
<script src="{{ asset('/static/js/jquery.js') }}"></script>
<script src="{{ asset('/static/js/jquery-1.8.3.min.js') }}"></script>
<script src="{{ asset('/static/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('/static/js/jquery.scrollTo.min.js') }}"></script>
<script src="{{ asset('/static/js/jquery.nicescroll.js') }}" type="text/javascript"></script>
<script src="{{ asset('/static/js/jquery.sparkline.js') }}" type="text/javascript"></script>
<script src="{{ asset('/static/assets/jquery-easy-pie-chart/jquery.easy-pie-chart.js') }}"></script>
<script src="{{ asset('/static/js/owl.carousel.js') }}"></script>
<script src="{{ asset('/static/js/jquery.customSelect.min.js') }}"></script>

<!--common script for all pages-->
<script src="{{ asset('/static/js/common-scripts.js') }}"></script>

<!--script for this page-->
<script src="{{ asset('/static/js/sparkline-chart.js') }}"></script>
<script src="{{ asset('/static/js/easy-pie-chart.js') }}"></script>

<script src="{{ asset('/static/layer/layer.js') }}"></script>

@yield('js')

<script>

    //owl carousel
    $(document).ready(function () {
        $("#owl-demo").owlCarousel({
            navigation: true,
            slideSpeed: 300,
            paginationSpeed: 400,
            singleItem: true

        });
    });
    //custom select box
    $(function () {
        $('select.styled').customSelect();
    });

    function success_message(message) {
        $('.alert-div').show();
        $('.alert-success').show();
        $('.alert-success').find('p').text(message);


        $('.alert-div').delay(3000).hide('slow');
    }

    function error_message(message) {

        $('.alert-div').show();

        $('.alert-danger').show();
        $('.alert-danger').find('p').text(message);

        $('.alert-div').delay(3000).hide('slow');
    }

    @if(session()->has('success'))
        success_message("{{ session()->get('success') }}");
    @endif

    @if(session()->has('error'))
        error_message("{{ session()->get('error') }}");
    @endif

</script>

</body>
</html>
