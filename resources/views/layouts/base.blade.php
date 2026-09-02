<!doctype html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=10"><!---->
    @hasSection('title')
        <title>@yield('title')｜小鳥のやど</title>
    @else
        <title>小鳥のやど</title>
    @endif

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="{{ asset('css/reception/bootstrap.min.css') }}">

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/b755a4e291.js" crossorigin="anonymous"></script>

    <!-- Custom styles for this template -->
    <link href="{{ asset('css/reception/drawer.css') }}" rel="stylesheet">
    <link href="{{ asset('css/reception/common.css') }}" rel="stylesheet">
{{--    <link href="{{ asset('css/reception/drawer.css') }}" rel="stylesheet">--}}
    @yield('styles')
</head>

<body @if(isset($isPrint) && $isPrint) onload="window.print();" @endif>

<!-- スマホ用メニュー START -->
<nav class="navbar navbar-sp navbar-expand-md navbar-light d-md-none no-print">
    <button class="navbar-toggler" type="button" id="sidr-right">
        <span class="navbar-toggler-icon"></span>
    </button>
</nav>

<div id="sidr" class="d-none no-print">
    <header class="py-1 px-3 border-bottom d-flex justify-content-between">
        <div>&nbsp;</div>
        <div>&nbsp;</div>
        <button type="button" class="dwrapper-close btn btn-light btn-sm bg-white"><i class="fa fa-times"></i> 閉じる</button>
    </header>
    <ul class="list-group" style="margin: 10px;">
        <li class="list-group-item"><a href="https://kotori-yado.com/"><i class="fa fa-home"></i> HOME <i class="fa fa-angle-right"></i></a></li>
        <li class="list-group-item"><a href="https://kotori-yado.com/calendar/"><i class="fa fa-calendar"></i> カレンダー <i class="fa fa-angle-right"></i></a></li>
        <li class="list-group-item"><a href="https://kotori-yado.com/scheduler/"><i class="fa fa-calendar"></i> スケジュール <i class="fa fa-angle-right"></i></a></li>
        <li class="list-group-item"><a href="https://kotori-yado.com/scheduler_stay/"><i class="fa fa-bed"></i> 予約リスト <i class="fa fa-angle-right"></i></a></li>
        <li class="list-group-item"><a href="https://ops.kotori-yado.com/reception/"><i class="fa fa-feather"></i> 予定表 <i class="fa fa-angle-right"></i></a></li>
    </ul>
    <footer class="py-1 px-3 border-top d-flex justify-content-between">
        <div>&nbsp;</div>
        <div>&nbsp;</div>
        <button type="button" class="dwrapper-close btn btn-light btn-sm bg-white"><i class="fa fa-times"></i> 閉じる</button>
    </footer>
</div>
<div class="d-none" id="dwrapper"></div><!-- ドロワーオーバーラップ用 -->
<!-- スマホ用メニュー END -->

<!-- PC用メニュー START -->
<nav class="navbar navbar-pc navbar-expand-md d-none d-md-block no-print">
    <a class="navbar-brand" href="#"><img src="https://kotori-yado.com/scheduler/images/header_no_btn.png"></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsE1" aria-controls="navbarsE1" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarsE1">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item"><a class="nav-link text-dark" href="https://kotori-yado.com/">Home</a></li>
            <li class="nav-item active"><a class="nav-link text-dark" href="https://kotori-yado.com/calendar/">カレンダー</a></li>
            <li class="nav-item active"><a class="nav-link text-dark" href="https://kotori-yado.com/scheduler/">スケジュール</a></li>
            <li class="nav-item"><a class="nav-link text-dark" href="https://kotori-yado.com/scheduler_stay/">予約リスト</a></li>
            <li class="nav-item"><a class="nav-link text-dark" href="https://ops.kotori-yado.com/reception/">予定表</a></li>
        </ul>
    </div>
</nav>
<!-- PC用メニュー END -->

<main role="main" class="mb-5">
    @yield('content')
</main><!-- /.container -->

<footer class="mt-auto py-3 bg-white no-print">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                Copyright(C)　小鳥のやど　All Rights Reserved.
            </div>
        </div>
    </div>
</footer><!-- /footer -->

<p id="page-top"><i class="fa fa-4x fa-chevron-circle-up" aria-hidden="true"></i></p>

<!-- Bootstrap JS (bundle contains popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>

<!-- main.js -->
<script src="{{ asset('js/main.js') }}"></script>
<script src="{{ asset('js/jquery.sidr.js') }}"></script>
<script src="{{ asset('js/drawer.js') }}"></script>


@yield('javascript')

</body>
</html>
