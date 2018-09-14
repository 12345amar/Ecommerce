<!-- Name Form Input -->
<div class="form-group @if ($errors->has('name')) has-error @endif">
    {!! Form::label('name', 'Name', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) !!}
    <div class="col-md-6 col-sm-6 col-xs-12">
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
    </div>
</div>

<!-- email Form Input -->
<div class="form-group @if ($errors->has('email')) has-error @endif">
    {!! Form::label('email', 'Email', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) !!}
    <div class="col-md-6 col-sm-6 col-xs-12">
    {!! Form::text('email', null, ['class' => 'form-control']) !!}
    @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
    </div>
</div>

<!-- password Form Input -->
<div class="form-group @if ($errors->has('password')) has-error @endif">
    {!! Form::label('password', 'Password', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) !!}
    <div class="col-md-6 col-sm-6 col-xs-12">
    {!! Form::password('password', ['class' => 'form-control']) !!}
    @if ($errors->has('password')) <p class="help-block">{{ $errors->first('password') }}</p> @endif
    </div>
</div>

<div class="form-group @if ($errors->has('moblie')) has-error @endif">
    {!! Form::label('name', 'Mobile', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) !!}
    <div class="col-md-6 col-sm-6 col-xs-12">
    {!! Form::text('mobile', null, ['class' => 'form-control']) !!}
    @if ($errors->has('mobile')) <p class="help-block">{{ $errors->first('mobile') }}</p> @endif
    </div>
</div>

<div class="form-group @if ($errors->has('avatar')) has-error @endif">
    {!! Form::label('name', 'Image', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) !!}
    <div class="col-md-6 col-sm-6 col-xs-12">
    {!! Form::file('avatar', null, ['class' => 'form-control', 'placeholder'=>'Enter Image']) !!}
    @if ($errors->has('avatar')) <p class="help-block">{{ $errors->first('avatar') }}</p> @endif

    @if(isset($userImage))
        <img class="img-circle profile_img" src="{{ $userImage }}" width="150" height="150">
    @endif
    </div>
</div

<!-- Roles Form Input -->
<div class="form-group @if ($errors->has('roles')) has-error @endif">
    {!! Form::label('roles[]', 'Roles', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) !!}
    <div class="col-md-6 col-sm-6 col-xs-12">
    {!! Form::select('roles[]', $roles, isset($user) ? $user->roles->pluck('id')->toArray() : null,  ['class' => 'form-control', 'multiple']) !!}
    @if ($errors->has('roles')) <p class="help-block">{{ $errors->first('roles') }}</p> @endif
    </div>
</div>

<!-- Permissions -->
@if(isset($user))
    @include('shared._permissions', ['closed' => 'true', 'model' => $user ])
@endif