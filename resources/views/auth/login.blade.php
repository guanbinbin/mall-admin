<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Mosaddek">
    <meta name="keyword" content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
    <link rel="shortcut icon" href="img/favicon.html">

    <title>FlatLab - 登录</title>

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('/static/css/bootstrap.min.css')  }}" rel="stylesheet">
    <link href="{{ asset('/static/css/bootstrap-reset.css') }}" rel="stylesheet">
    <link href="{{ asset('/static/css/smartadmin-production-plugins.min.css') }}" rel="stylesheet">
    <!--external css-->
    <link href="{{ asset('/static/assets/font-awesome/css/font-awesome.css') }}" rel="stylesheet"/>
    <!-- Custom styles for this template -->
    <link href="{{ asset('/static/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('/static/css/style-responsive.css') }}" rel="stylesheet"/>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
    <!--[if lt IE 9]>
    <script src="{{ asset('/static/js/html5shiv.js') }}"></script>
    <script src="{{ asset('/static/js/respond.min.js') }}">
    <![endif]-->

    <style type="text/css">
        .form-signin p {
            font-size: 14px;
            color: red;
        }
    </style>

</head>

<body class="login-body">

<div class="container">

    <form class="form-signin" action="{{ route('login') }}" method="post">
        <h2 class="form-signin-heading">欢迎使用后台管理系统</h2>
        <div class="login-wrap">
            {{ csrf_field() }}
            <input type="text" name="email" class="form-control" placeholder="请输入账号" autofocus>
            <input type="password" name="password" class="form-control" placeholder="请输入密码">

            <label class="checkbox">
                <input type="checkbox" name="remember"> 记录登录
                <span class="pull-right"> <a href="{{ route('password.request') }}"> 忘记密码?</a></span>
            </label>
            <button class="btn btn-lg btn-login btn-block" type="submit">登&nbsp;&nbsp;&nbsp;录</button>

            @if ($errors->has('email') || $errors->has('password'))
                <p>您的凭证与我们的记录不匹配。</p>
            @endif
        </div>
    </form>

</div>

</body>
</html>
