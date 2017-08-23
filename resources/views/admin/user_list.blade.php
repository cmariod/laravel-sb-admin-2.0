@extends('layouts.admin')
@section('page_heading','Manage Users')
@section('section')

@if (Session::has('message'))
<div class="alert alert-info alert-dismissable" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
  <i class="fa fa-info"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  {{ Session::get('message') }}
</div>
@endif

@component('admin.widget.table', [
    'action' => true,
    'headers' => count($users) > 0 ? array_keys($users[0]) : ['&nbsp;'],
    'data' => $users,
    'base_route' => 'user'
  ])
@endcomponent

@stop
