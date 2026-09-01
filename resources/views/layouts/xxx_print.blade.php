<!doctype html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>予約表印刷</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('css/reception/bootstrap.min.css') }}">
    <link href="{{ asset('css/reception/reception.css') }}" rel="stylesheet">
    <link href="{{ asset('css/reception/calendar.css') }}" rel="stylesheet">
    <style>
        @page {
            size: A4 landscape;
            margin: 10mm;
        }
        @media print {
            .no-print { display: none !important; }
            .container { width: 100% !important; max-width: 100% !important; margin: 0; padding: 0; }
            .table-calendar { min-width: 100% !important; width: 100% !important; table-layout: fixed; }
            .table-calendar th, .table-calendar td { font-size: 10px; padding: 2px !important; }
            .table-calendar .day { font-size: 12px; }
            .reception-counts dt { width: 25px; }
            .reception-counts dd { font-size: 10px; }
            .reception-counts .count-number { font-size: 12px; }
        }
    </style>
</head>
<body onload="window.print();">
    <div class="container-fluid">
        @yield('content')
    </div>
</body>
</html>
