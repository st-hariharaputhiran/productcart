@extends('webpanel.layouts.admin')
@section('content')
<div class="dashboard-wrapper">
            <div class="dashboard-ecommerce">
                <div class="container-fluid dashboard-content ">
    <div class="card mb-5 mb-xl-10" >
        <div class="card-header border-0 cursor-pointer">
            <div class="card-title m-0">
                <h3 class="fw-boldest m-0">Edit Class</h3>
            </div>
        </div>
        <div class="card-body border-top p-9">
            {{ Form::model($model, ['route' =>  [ "products.update", $model->id ], 'method' => 'PUT','class' => 'form-horizontal','id'  => 'classesForm','files'=> true,'autocomplete' => 'off']) }}
                @include('webpanel.products.partials.form')
            {{ Form::close() }}
        </div>
    </div>
</div>
</div>
</div>
@endsection