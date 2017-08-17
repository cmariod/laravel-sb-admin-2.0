@extends('layouts.admin')
@section('page_heading','Reports')
@section('section')

<div class="col-lg-6 col-md-9 col-sm-12">

@if($errors->any())
<div class="alert alert-danger alert-dismissable" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
  <i class="fa fa-exclamation"></i>&nbsp; &nbsp; &nbsp; &nbsp; Invalid submission, please try again.
</div>
@endisset

{!! Form::open(['url' => Request::url()]) !!}

<div class="form-group @if ($errors->has('type')) has-error @endif">
{!! Form::label('type', 'Report Type', ['class' => 'control-label']) !!}
{!! Form::select('type', $reportTypes, null, ['placeholder' => 'Pick a report...', 'class' => 'form-control']); !!}
@isset($errors) 
  @if ($errors->has('type'))
  <p class="help-block">{{ $errors->first('type') }}</p>
  @endif
@endisset
</div>

<div class="form-group @if ($errors->has('fromdate')) has-error @endif">
{!! Form::label('fromdate', 'From Date', ['class' => 'control-label']) !!}
{!! Form::date('fromdate', \Carbon\Carbon::now(), ['class' => 'form-control']); !!}
@isset($errors) 
  @if ($errors->has('fromdate'))
  <p class="help-block">{{ $errors->first('fromdate') }}</p>
  @endif
@endisset
</div>

<div class="form-group @if ($errors->has('todate')) has-error @endif">
{!! Form::label('todate', 'To Date', ['class' => 'control-label']) !!}
{!! Form::date('todate', \Carbon\Carbon::now(), ['class' => 'form-control']); !!}
@isset($errors) 
  @if ($errors->has('todate'))
  <p class="help-block">{{ $errors->first('todate') }}</p>
  @endif
@endisset
</div>

{!! Form::submit('Download Report', ['class' => 'btn btn-sm btn-primary']); !!}

{!! Form::close() !!}

</div>

@stop
