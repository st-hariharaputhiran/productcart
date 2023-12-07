<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImages;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public $modelClass = Product::class;

    public $messageClass = 'Product';
   
    protected function _save($request, $model)
    {
        //$data = $request->except(['_token']);
        $model->fill([
            'product_title' => $request->product_title,
            'product_description' => $request->product_description,
            'product_slug' => $request->product_slug,
            'product_price' => $request->product_price,
            'product_status' => $request->product_status
        ]);
        $model->save();
        $files = [];
        if($request->hasfile('image_url'))
         {
            foreach($request->file('image_url') as $file)
            {
                $name = time().rand(1,50).'.'.$file->extension();
                $file->move(public_path('images'), $name);  
                $files[] = new ProductImages( [ 'image_url' => $name] ); 
            }
         }
        $model->productImages()->saveMany( $files );
                
        
    }
}
