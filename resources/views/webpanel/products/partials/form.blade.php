
<div class="row mb-4">
    <div class="col-12 col-md-6 fv-row fv-plugins-icon-container mb-4">
        {{ Form::label('product_title', 'Product Title', ['class' => 'form-label required','enctype' => 'multipart/form-data']) }}
        {{ Form::text('product_title', old('product_title'), ['class' => 'form-control form-control-lg form-control', 'placeholder' => 'Product Title']) }}
    </div>
</div>
<div class="row mb-4">
    <div class="col-12 col-md-6 fv-row fv-plugins-icon-container mb-4">
        {{ Form::label('product_description', 'Description', ['class' => 'form-label']) }}
        {{ Form::textarea('product_description', old('product_description'), ['class' => 'form-control form-control-lg', 'rows' => '5', 'placeholder' => 'Product Description']) }}
    </div>
</div>

<div class="row mb-4">
    <div class="col-12 col-md-6 fv-row fv-plugins-icon-container mb-4">
        {{ Form::label('product_slug', 'Product Slug', ['class' => 'form-label required','enctype' => 'multipart/form-data']) }}
        {{ Form::text('product_slug', old('product_slug'), ['class' => 'form-control form-control-lg form-control', 'placeholder' => 'Product Slug']) }}
    </div>
</div>

<div class="row mb-4">
    <div class="col-12 col-md-6 fv-row fv-plugins-icon-container mb-4">
        {{ Form::label('product_price', 'Product Price', ['class' => 'form-label required','enctype' => 'multipart/form-data']) }}
        {{ Form::text('product_price', old('product_price'), ['class' => 'form-control form-control-lg form-control', 'placeholder' => 'Product Price']) }}
    </div>
</div>
<div class="row mb-4">
    <div class="col-12 col-md-6 fv-row fv-plugins-icon-container mb-4">
        {{ Form::label('image_url', 'Product Image', ['class' => 'form-label','enctype' => 'multipart/form-data']) }}
        {{ Form::file('image_url[]', ['class' => 'form-control form-select form-control-lg','id' => 'product_image','multiple'=>'multiple']) }}
        <br>
        <img src="#" alt="Preview Image" id="footerpreviewImage" style="display: none; max-width: 200px; max-height: 200px;">
    </div>
</div>
<div class="row mb-4">
    <input type="hidden" name="status" value="0">
    <div class="col-12 col-md-6 fv-row fv-plugins-icon-container mb-4">
        <label class="form-check form-switch form-check-custom form-check-solid">
            {{ Form::label('product_status', 'Product Status', ['class' => 'form-label fw-bold fs-6']) }}
        </label>
        <label class="form-check form-switch form-check-custom form-check-solid">
            {{ Form::checkbox('product_status', 1 , @$model->id ? old('product_status') : true, ['class' => 'form-check-input']) }}
        </label>
    </div>
</div>
<div class="card-footer d-flex justify-content-end py-6 px-9">
    <a href="{{ route('products.index') }}" class="btn btn-light me-2" style="border: 1px solid black;">Back</a>
    {{ Form::submit('Save',['class' => 'btn btn-primary']) }}
</div>
