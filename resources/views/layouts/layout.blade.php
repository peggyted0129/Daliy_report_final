<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="_token" content="{!! csrf_token() !!}">
  <title>@yield('title')</title>
  <!-- fullcalendar V5.9版 -->
  <script src="{{ asset('scripts/moment.min.js') }}"></script>
  <script src="{{ asset('scripts/fullcalendar/main.js') }}"></script>
  <script src="{{ asset('scripts/fullcalendar/locales-all.js') }}"></script>
  <!-- select2 -->
  <link rel="stylesheet" href="{{ asset('styles/select2/select2.min.css') }}">
  <!-- jquery-datetimepicker -->
  <link rel="stylesheet" href="{{ asset('styles/jquery-datetimepicker/jquery.datetimepicker.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ mix('css/app.css') }}">
  <script src="{{ mix('/js/app.js') }}"></script>
</head>
<body>
  <ul class="nav nav-tabs mb-8" style="font-size:14px">
    <li class="nav-item">
      <a class="nav-link {{ Request::path() === '/' ? 'active' : '' }}" href="/">目錄清單</a>
    </li>
    <li class="nav-item">
      <a class="nav-link {{ Request::path() === 'calendar' ? 'active' : '' }}" href="/calendar">日曆</a>
    </li>
    <li class="nav-item">
      <a class="nav-link {{ Request::path() === 'report' ? 'active' : '' }}" href="/report">管理報表</a>
    </li>
    <li class="nav-item">
      <a class="nav-link {{ Request::path() === 'summary' ? 'active' : '' }}" href="/summary">彙整總表</a>
    </li>
    <li class="nav-item">
      <a class="nav-link {{ Request::path() === 'report_cal' ? 'active' : '' }}" href="/report_cal">日曆報表</a>
    </li>
  </ul>

  {{-- 引入 calendar 套件 : calendar.blade.php --}}
  {{-- 引入篩選表格 : report.blade.php --}}
  @yield('main')

  {{-- 引入 calendar modal --}}
  @yield('eventForm')

  <script src="{{ asset('scripts/select2/select2.min.js') }}"></script>
  <script src="{{ asset('scripts/jquery-datetimepicker/jquery.datetimepicker.full.min.js') }}"></script>
  @yield('eventjs')



</body>
</html>