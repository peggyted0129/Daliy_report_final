@extends('layouts/layout') <!--繼承母模板 layouts/layout-->

@section('title', '業務日報表')

@section('main')
  <div class="container-fluid">
    <div id="calendar"></div>
  </div>
@endsection

@section('eventjs')
  @include('eventjs')
@endsection

<!-- 事件 modal -->
@section('eventForm')
  @include('layouts/eventForm')
@endsection
