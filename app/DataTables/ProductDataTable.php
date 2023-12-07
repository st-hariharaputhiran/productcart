<?php

namespace App\DataTables;

use App\Models\Product;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Http\Request;

class ProductDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables($query)
            ->editColumn('product_title', function ($model) {
                return ucwords($model->product_title);
            })
            ->editColumn('product_description', function ($model) {
                return ucwords(@$model->product_description);
            })
            ->editColumn('product_price', function ($model) {
                return (int) $model->product_price;
            })
            ->editColumn('product_slug', function ($model) {
                return $model->product_slug;
            })
            ->editColumn('product_status', function ($model) {
                return ($model->product_status) ? '<span><label class="form-check form-switch form-check-custom form-check-solid"><input type="checkbox" value='.$model->product_status.' data-id='.$model->id.' data-url="updateProductStatus" checked id="toggle" class="form-check-input toggle"></label></span>'
                : '<span><label class="form-check form-switch form-check-custom form-check-solid"><input type="checkbox" value='.$model->product_status.' data-id='.$model->id.' data-url="updateProductStatus" id="toggle" class="form-check-input toggle"></label></span>';
            })
            ->editColumn('action', function ($model) {
                $action = '<a href="'.route('products.edit', $model->id).'" class="badge btn-success btn-sm" title="Edit"><i class="fa fa-edit text-white"></i></a>&nbsp;';
                
                $action .= '<a href="javascript:void(0);" class="badge btn-danger btn-sm btn-delete" data-id="'.$model->id.'" data-model="product" data-loading-text="<i class=\'fa fa-spin fa-spinner\'></i> Please Wait..." title="Delete"><i class="fa fa-trash text-white"></i></a>';

                return $action;
            })
            ->editColumn('created_at', function ($model) {
                return $model->created_at;
            })
            ->rawColumns(['action', 'product_status']);
    }

    public function query(Product $model, Request $request)
    {        
        $query = $model->newQuery()->with('productImages');
        return $query;
    }

    public function html()
    {
        $html = $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addAction()
            ->parameters([
                'dom' => 'Bfrtip',
                'buttons' => ['create'],
                'stateSave'=> true,
                'bInfo' => false,
                'order' => [[5, 'desc']],
            ]);

        return $html;
    }

    protected function getColumns()
    {
        return [
            [
                'data' => 'product_title',
                'title' => ' Product Name',
            ],
            [
                'data' => 'product_description',
                'title' => 'Product Description',
            ],
            [
                'data' => 'product_price',
                'title' => 'Product Price',
            ],
            [
                'data' => 'product_slug',
                'title' => 'Product Slug',
            ],
            'product_status',
            'created_at'
        ];
    }

}
