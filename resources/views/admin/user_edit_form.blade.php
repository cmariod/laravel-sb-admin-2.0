@extends('layouts.admin')
@section('page_heading','Edit User')
@section('section')

<div class="col-lg-6 col-md-9 col-sm-12">

@if($errors->any())
<div class="alert alert-danger alert-dismissable" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
  <i class="fa fa-exclamation"></i>&nbsp; &nbsp; &nbsp; &nbsp; Invalid submission, please try again.
</div>
@endisset

{!! Form::open(['url' => Request::url()]) !!}

<div class="form-group @if ($errors->has('name')) has-error @endif">
{!! Form::label('name', 'Name', ['class' => 'control-label']) !!}
{!! Form::text('name', $name, ['class' => 'form-control']); !!}
@isset($errors) 
  @if ($errors->has('name'))
  <p class="help-block">{{ $errors->first('name') }}</p>
  @endif
@endisset
</div>

<div class="form-group @if ($errors->has('email')) has-error @endif">
{!! Form::label('email', 'E-Mail Address', ['class' => 'control-label']) !!}
{!! Form::email('email', $email, ['class' => 'form-control']); !!}
@isset($errors) 
  @if ($errors->has('email'))
  <p class="help-block">{{ $errors->first('email') }}</p>
  @endif
@endisset
</div>

<div class="form-group @if ($errors->has('password')) has-error @endif">
{!! Form::label('password', 'Password', ['class' => 'control-label']) !!}
{!! Form::password('password', ['class' => 'form-control']); !!}
<p class="help-block">Leave empty if you do not intend to change password.</p>
@isset($errors) 
  @if ($errors->has('password'))
  <p class="help-block">{{ $errors->first('password') }}</p>
  @endif
@endisset
</div>

<div class="form-group @if ($errors->has('confirmpassword')) has-error @endif">
{!! Form::label('confirmpassword', 'Confirm Password', ['class' => 'control-label']) !!}
{!! Form::password('confirmpassword', ['class' => 'form-control']); !!}
<p class="help-block">Leave empty if you do not intend to change password.</p>
@isset($errors) 
  @if ($errors->has('confirmpassword'))
  <p class="help-block">{{ $errors->first('confirmpassword') }}</p>
  @endif
@endisset
</div>

<div class="form-group @if ($errors->has('level')) has-error @endif">
{!! Form::label('level', 'Level', ['class' => 'control-label']) !!}
{!! Form::select('level', ['Admin' => 'Admin', 'CSO' => 'CSO'], $level, ['placeholder' => 'Choose a level...', 'class' => 'form-control']); !!}
@isset($errors) 
  @if ($errors->has('level'))
  <p class="help-block">{{ $errors->first('level') }}</p>
  @endif
@endisset
</div>

{!! Form::submit('Update User', ['class' => 'btn btn-sm btn-primary']); !!}
<a href="{{ route('user') }}" class="btn btn-sm btn-danger">Cancel</a>

{!! Form::close() !!}

</div>

@stop
