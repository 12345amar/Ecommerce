<!-- Name Form Input -->
<div class="form-group @if ($errors->has('name')) has-error @endif">
    {!! Form::label('name', 'Name', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) !!}
    <div class="col-md-6 col-sm-6 col-xs-12">
    {!! Form::text('name', null, ['class' => 'form-control', 'placeholder'=>'Enter Name']) !!}
    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
    </div>
</div>

<!-- Name Form Input -->
<div class="form-group @if ($errors->has('parent_cate')) has-error @endif" >
    {!! Form::label('parent_id', 'Parent Category', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) !!}
    <div class="col-md-6 col-sm-6 col-xs-12"> 
        <select name='parent_id' class="form-control">
            <option value="0" selected="">Parent Category</option>
            @foreach($categoryLists as $categoryList)           
                <option value="{{ $categoryList->id }}" {{ @$category->parent_id == $categoryList->id ? 'selected="selected"' : '' }}>{{ $categoryList->name }}</option>            
            @endforeach            
        </select>
    @if ($errors->has('parent_id')) <p class="help-block">{{ $errors->first('parent_id') }}</p> @endif
    </div>
</div>
<div class="clearfix"></div>

<div class="form-group @if ($errors->has('parent_cate')) has-error @endif" >
    {!! Form::label('brands', 'Available Brands', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) !!}
    <div class="col-md-6 col-sm-6 col-xs-12"> 
                    
            @foreach($brands as $brand)
            <?php
            
                $brand_found = null;
                $options['class'] = 'flat';
            ?>
            
            
            <div class="col-md-4">
                <div class="checkbox">
                    <?php
                    if(isset($selectedBrands)) {
                        $brand_found = in_array($brand->id, $selectedBrands);
                    }
                                
                    ?>
                    
                    {!! Form::checkbox("brands[]", $brand->id, $brand_found, isset($options) ? $options : []) !!} {{ $brand->name }}
                </div>
            </div>
            @endforeach            
    @if ($errors->has('brands')) <p class="help-block">{{ $errors->first('brands') }}</p> @endif
    </div>
</div>

<!-- Name Form Input -->
<div class="form-group @if ($errors->has('description')) has-error @endif">
    {!! Form::label('description', 'Description', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) !!}
    <div class="col-md-6 col-sm-6 col-xs-12">
    {!! Form::textarea('description', null, ['class' => 'form-control', 'rows'=>'3', 'placeholder'=>'Enter Description']) !!}
    @if ($errors->has('description')) <p class="help-block">{{ $errors->first('description') }}</p> @endif
    </div>
</div>
<!-- Name Form Input -->
<div class="form-group @if ($errors->has('image')) has-error @endif">
    {!! Form::label('image', 'Image', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) !!}
    <div class="col-md-6 col-sm-6 col-xs-12">
    {!! Form::file('image', null, ['class' => 'form-control', 'placeholder'=>'Enter Image']) !!}
    @if ($errors->has('image')) <p class="help-block">{{ $errors->first('image') }}</p> @endif
    </div>
</div>
<!-- drop down Form Input -->
<div class="form-group @if ($errors->has('status')) has-error @endif">
    {!! Form::label('status', 'Status', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) !!}
    <div class="col-md-6 col-sm-6 col-xs-12">
        <select name="status" class="form-control">
            <option value="1" {{ @$category->status == "1" ? 'selected="selected"' : '' }} >Activate</option>
            <option value="0" {{ @$category->status == "0" ? 'selected="selected"' : '' }} >Inactive</option>
        </select>
    @if ($errors->has('status')) <p class="help-block">{{ $errors->first('status') }}</p> @endif
    </div>
</div>
<!-- Permissions -->
