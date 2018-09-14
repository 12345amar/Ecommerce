
<!-- Name Form Input -->
<div class="form-group @if ($errors->has('name')) has-error @endif">
    {!! Form::label('name', 'Name', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) !!}
    <div class="col-md-6 col-sm-6 col-xs-12">
    {!! Form::text('name', null, ['class' => 'form-control', 'placeholder'=>'Enter Name']) !!}
    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
    </div>
</div>
<!-- Name Form Input -->

 

<div class="form-group @if ($errors->has('image')) has-error @endif">
 
    {!! Form::label('image', 'Image', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) !!}
    <div class="col-md-6 col-sm-6 col-xs-12">
           @if(isset($offer->image) && file_exists( public_path().'/images/offers/'.$offer->image ) && $offer->image != '')
    <img src="{{ url('images/offers').'/'.$offer->image }}" class="img-thumbnail" width="100" height="100"><br/>
@else
    <img src="{{ url('images/default.png') }}" alt="" class="img-thumbnail" width="100" height="100"><br/>
@endif 
    {!! Form::file('image', null, ['class' => 'form-control', 'placeholder'=>'Enter Image']) !!}
    @if ($errors->has('image')) <p class="help-block">{{ $errors->first('image') }}</p> @endif
    </div>
</div>
<!-- drop down Form Input -->
<div class="form-group @if ($errors->has('status')) has-error @endif">
    {!! Form::label('status', 'Status', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) !!}
    <div class="col-md-6 col-sm-6 col-xs-12">
        <select name="status" class="form-control">
            <option value="1" {{ @$offer->status == "1" ? 'selected="selected"' : '' }} >Activate</option>
            <option value="0" {{ @$offer->status == "0" ? 'selected="selected"' : '' }} >Inactive</option>
        </select>
    @if ($errors->has('status')) <p class="help-block">{{ $errors->first('status') }}</p> @endif
    </div>
</div>
<!-- Permissions -->
