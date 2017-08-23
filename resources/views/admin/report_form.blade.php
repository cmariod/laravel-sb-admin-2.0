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

<div class="form-group @if ($errors->has('id')) has-error @endif">
{!! Form::label('id', 'Report Type', ['class' => 'control-label']) !!}
{!! Form::select('id', $reportTypes, null, ['placeholder' => 'Pick a report...', 'class' => 'form-control']); !!}
@isset($errors) 
  @if ($errors->has('id'))
  <p class="help-block">{{ $errors->first('id') }}</p>
  @endif
@endisset
</div>

<div class="form-group @if ($errors->has('daterange')) has-error @endif">
{!! Form::label('daterange', 'Date Range', ['class' => 'control-label']) !!}
{!! Form::text('daterange', '', ['class' => 'form-control']); !!}
@isset($errors) 
  @if ($errors->has('daterange'))
  <p class="help-block">{{ $errors->first('daterange') }}</p>
  @endif
@endisset
</div>

<div class="form-group @if ($errors->has('type')) has-error @endif">
{!! Form::label('type', 'Type', ['class' => 'control-label']) !!}
<br />
{!! Form::radio('type', 'xlsx', true); !!} Download Excel
{!! Form::radio('type', 'csv'); !!} Download CSV
{!! Form::radio('type', 'preview'); !!} Preview
@isset($errors) 
  @if ($errors->has('type'))
  <p class="help-block">{{ $errors->first('type') }}</p>
  @endif
@endisset
</div>

{!! Form::submit('Submit', ['class' => 'btn btn-sm btn-primary']); !!}

{!! Form::close() !!}

@isset($report)
<br />
<br />
@component('admin.widget.table', [
  'action' => false,
  'data' => $report,
])
@endcomponent
@endisset

</div>

@stop
