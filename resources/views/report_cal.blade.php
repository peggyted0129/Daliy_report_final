@extends('layouts/layout') <!--繼承母模板 layouts/layout-->

@section('title', '日曆報表')

@section('main')
  <div id="report_cal"></div>
@endsection

@section('eventjs')
  @include('reportjs')
@endsection

