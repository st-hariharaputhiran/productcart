@extends('webpanel.layouts.admin')
        
        <!-- ============================================================== -->
        <!-- end left sidebar -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- wrapper  -->
        <!-- ============================================================== -->
        @section('content')
        <div class="dashboard-wrapper">
            <div class="dashboard-ecommerce">
                <div class="container-fluid dashboard-content ">
                   
                    <div class="card mb-5 mb-xl-10">
        <div class="card-header border-0 cursor-pointer">
            <div class="card-title m-0">
                <h3 class="fw-boldest m-0">Products</h3>
            </div>
        </div>
        <div class="card-body border-top p-9">
            {!! $dataTable->table([
                'class' => 'table align-middle table-row-dashed fs-6 gy-5 dataTable',
                'id' => 'datatable-buttons',
            ]) !!}
        </div>
        </div>
        @include('webpanel.partials.datatable_scripts')
                    
            </div>
            
        </div>
        <!-- ============================================================== -->
        <!-- end wrapper  -->
        <!-- ============================================================== -->
    </div>
    @endsection   
</body>
 
</html>
