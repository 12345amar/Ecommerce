<!-- Name Form Input -->
<div class="form-group @if ($errors->has('name')) has-error @endif">
    {!! Form::label('name', 'Name', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) !!}
    <div class="col-md-6 col-sm-6 col-xs-12">
    {!! Form::text('name', null, ['class' => 'form-control', 'placeholder'=>'Enter Name']) !!}
    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
    </div>
</div>

<div class="form-group @if ($errors->has('status')) has-error @endif">
    {!! Form::label('status', 'Status', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) !!}
    <div class="col-md-6 col-sm-6 col-xs-12">
        <select name="status" class="form-control">
            <option value="1" {{ @$brand->status == "1" ? 'selected="selected"' : '' }} >Activate</option>
            <option value="0" {{ @$brand->status == "0" ? 'selected="selected"' : '' }} >Inactive</option>
        </select>
    @if ($errors->has('status')) <p class="help-block">{{ $errors->first('status') }}</p> @endif
    </div>
</div>

<div class="form-group @if ($errors->has('description')) has-error @endif">
    {!! Form::label('description', 'Description', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) !!}
    <div class="col-md-6 col-sm-6 col-xs-12">
    {!! Form::textarea('description', null, ['class' => 'form-control', 'rows'=>'3', 'placeholder'=>'Enter Description']) !!}
    @if ($errors->has('description')) <p class="help-block">{{ $errors->first('description') }}</p> @endif
    </div>
</div>