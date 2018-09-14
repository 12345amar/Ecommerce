<div class="form-group @if ($errors->has('name')) has-error @endif">
    {!! Form::label('name', 'Name', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) !!}
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::text('name', null, ['class' => 'form-control', 'placeholder'=>'Enter Name']) !!}
        @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
    </div>
</div>
<div class="form-group @if ($errors->has('category_id')) has-error @endif" >
    {!! Form::label('category_id', 'Category', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) !!}
    <div class="col-md-6 col-sm-6 col-xs-12"> 
        <select name='category_id' class="form-control" id="category_id">
            <option value="0" selected="">Select Category</option>
            @foreach($categoryLists as $category)           
            <option value="{{ $category->id }}" {{ @$product->category_id == $category->id ? 'selected="selected"' : '' }}>{{ $category->name }}</option>            
            @endforeach            
        </select>
        @if ($errors->has('category_id')) <p class="help-block">{{ $errors->first('category_id') }}</p> @endif
    </div>
</div>
<div class="clearfix"></div>
<div class="form-group @if ($errors->has('brand_id')) has-error @endif" >
    {!! Form::label('brand_id', 'Brand', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) !!}
    <div class="col-md-6 col-sm-6 col-xs-12"> 
        <select name='brand_id' class="form-control" id="brand_id">
            <option value="" selected="">Select Brand</option>
            @foreach($brands as $brand)           
            <option value="{{ $brand->id }}" {{ @$product->brand_id == $brand->id ? 'selected="selected"' : '' }}>{{ $brand->name }}</option>            
            @endforeach            
        </select>
        @if ($errors->has('brand_id')) <p class="help-block">{{ $errors->first('brand_id') }}</p> @endif
    </div>
</div>
<div class="clearfix"></div>
<div class="form-group @if ($errors->has('stock')) has-error @endif">
    {!! Form::label('stock', 'Stock(Number of Product)', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) !!}
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::number('stock', null, ['class' => 'form-control', 'placeholder'=>'Enter number of product available']) !!}
        @if ($errors->has('stock')) <p class="help-block">{{ $errors->first('stock') }}</p> @endif
    </div>
</div>
<div class="form-group @if ($errors->has('short_description')) has-error @endif">
    {!! Form::label('short_description', 'Short Description', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) !!}
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::text('short_description', null, ['class' => 'form-control', 'placeholder'=>'Enter Short Description']) !!}
        @if ($errors->has('short_description')) <p class="help-block">{{ $errors->first('short_description') }}</p> @endif
    </div>
</div>
<div class="form-group @if ($errors->has('description')) has-error @endif">
    {!! Form::label('description', 'Description', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) !!}
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::textarea('description', null, ['class' => 'form-control', 'rows'=>'3', 'placeholder'=>'Enter Description']) !!}
        @if ($errors->has('description')) <p class="help-block">{{ $errors->first('description') }}</p> @endif
    </div>
</div>
<div class="form-group @if ($errors->has('price')) has-error @endif">
    {!! Form::label('price', 'Price', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) !!}
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::number('price', null, ['class' => 'form-control', 'placeholder'=>'Enter Price', 'maxlength'=>'2']) !!}
        @if ($errors->has('price')) <p class="help-block">{{ $errors->first('price') }}</p> @endif
    </div>
</div>
<div class="form-group @if ($errors->has('discount')) has-error @endif">
    {!! Form::label('discount', 'Discount(%)', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) !!}
    <div class="col-md-6 col-sm-6 col-xs-12">
        {!! Form::number('discount', null, ['class' => 'form-control', 'placeholder'=>'Enter Discount']) !!}
        @if ($errors->has('discount')) <p class="help-block">{{ $errors->first('discount') }}</p> @endif
    </div>
</div>
<div class="form-group @if ($errors->has('image')) has-error @endif">
    {!! Form::label('image', 'Image', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) !!}
    <div class="col-md-6 col-sm-6 col-xs-12">
        <?php
        $images = isset($product->image) ? json_decode($product->image, true) : '';
        if (!empty($images)) {
            for ($i = 0; $i < count($images); $i++) {

                if (file_exists(public_path('images/products/' . $images[$i])) && $images[$i] != '') {
                    ?>
                    <div class="col-md-3"><img src="{{ url('images/products/'.$images[$i]) }}" height="100%" width="100%" class="img-thumbnail"></div>
                <?php } else { ?>
                    <div class="col-md-3"><img src="{{ url('images/default.png') }}" height="100%" width="100%" class="img-thumbnail"></div>

                <?php }
            }
        } ?>

        <input type="file" name="image[]" multiple="" class="form-control">  
        @if ($errors->has('image')) <p class="help-block">{{ $errors->first('image') }}</p> @endif
    </div>
</div>
<!-- drop down Form Input -->
<div class="form-group @if ($errors->has('status')) has-error @endif">
    {!! Form::label('status', 'Status', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) !!}
    <div class="col-md-6 col-sm-6 col-xs-12">
        <select name="status" class="form-control">
            <option value="1" {{ @$product->status == "1" ? 'selected="selected"' : '' }} >Activate</option>
            <option value="0" {{ @$product->status == "0" ? 'selected="selected"' : '' }} >Inactive</option>
        </select>
        @if ($errors->has('status')) <p class="help-block">{{ $errors->first('status') }}</p> @endif
    </div>
</div>
<div class="form-group @if ($errors->has('is_new')) has-error @endif">
    {!! Form::label('is_new', 'Add in New List', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) !!}
    <div class="col-md-6 col-sm-6 col-xs-12">   
        @if(isset($product->is_new))
        <input type="checkbox" name="is_new"  {{ @$product->is_new == "1" ? 'checked="checked"' : '' }}> 
        @else
        <input type="checkbox" name="is_new"  checked> 
        @endif
        @if ($errors->has('is_new')) <p class="help-block">{{ $errors->first('is_new') }}</p> @endif
    </div>
</div>
<div class="form-group @if ($errors->has('is_featured')) has-error @endif">
    {!! Form::label('is_featured', 'Add in featured List', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) !!}
    <div class="col-md-6 col-sm-6 col-xs-12">
        @if(isset($product->is_featured))
        <input type="checkbox" name="is_featured" {{ @$product->is_featured == "1" ? 'checked="checked"' : '' }}> 
        @else
        <input type="checkbox" name="is_featured" checked="">
        @endif
        @if ($errors->has('is_featured')) <p class="help-block">{{ $errors->first('is_featured') }}</p> @endif
    </div>
</div>
<!-- Permissions -->
