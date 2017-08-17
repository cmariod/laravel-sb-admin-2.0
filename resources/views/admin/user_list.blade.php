@extends('layouts.admin')
@section('page_heading','Manage User')
@section('section')

@if (Session::has('message'))
<div class="alert alert-info alert-dismissable" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
  <i class="fa fa-info"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  {{ Session::get('message') }}
</div>
@endif

@component('admin.widget.table', [
    'headers' => array_keys($users[0]),
    'data' => $users,
    'base_route' => 'user'
  ])
@endcomponent

@stop
