<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
        //  $products = Product::latest()->paginate(20);
            $products = Product::with('category')->latest()->paginate(20);
            if ($products->count() > 0) {
                return response()->json([
                    'data'    => $products,
                    'message' => 'success',
                    'status'  => 'success'
                ]);
            }else{
                return response()->json([
                    'message'   => 'no record found',
                    'status'    => 'success',
                ]);
            }
        }catch (\Exception $e){
            return response()->json([
                'errors' => $e->getMessage()
            ], 500);
        }catch (\Error $e){
            return response()->json([
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => ['required'],
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'price'       => ['required', 'integer']
        ]);

        if ($validator->fails()){
            return response()->json([
                'errors' => $validator->errors()->first()
            ]);
        }

//        return $request->all();

        try {
            $saveProduct = Product::create($validator->validated());
            if ($saveProduct){
                return response()->json([
                    'message' => 'record created',
                    'status' => 'success',
                    'data'   => $saveProduct
                ]);
            }
        }catch (\Exception $e){
            return response()->json([
                'errors' => $e->getMessage()
            ], 500);
        }catch (\Error $e){
            return response()->json([
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::with('category')->find($id);
        try {
            if ($product != null){
                return response()->json([
                    'status' => 'success',
                    'data'   => $product
                ]);
            }else{
                return response()->json([
                    'status'  => 'success',
                    'message' => 'no record found'
                ]);
            }
        }catch (\Exception $e){
            return response()->json([
                'errors' => 'an exceptional error'
            ], 500);
        }catch (\Error $e){
            return response()->json([
                'errors' => 'an error occurred'
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => ['required'],
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'price'       => ['required', 'integer']
        ]);

        if ($validator->fails()){
            return response()->json([
                'errors' => $validator->errors()->first()
            ]);
        }

        try {
            $product = Product::find($id);
            if ($product != null){
                if ($product->update($validator->validated())){
                    return response()->json([
                        'message' => 'record updated',
                        'status' => 'success',
                        'data'   => $product
                    ]);
                }
            }else{
                return response()->json([
                    'message' => 'no record found',
                    'status' => 'success',
                ]);
            }
        }catch (\Exception $e){
            return response()->json([
                'errors' => $e->getMessage()
            ], 500);
        }catch (\Error $e){
            return response()->json([
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            if (Product::destroy($id)){
                return response()->json([
                    'status'  => 'success',
                    'message' => 'product deleted successfully'
                ]);
            } else{
                return response()->json([
                    'errors' => 'error occurred while deleting record.'
                ]);
            }
        }catch (\Exception $e){
            return response()->json([
                'errors' => 'an exceptional error'
            ], 500);
        }catch (\Error $e){
            return response()->json([
                'errors' => 'an error occurred'
            ], 500);
        }
    }
}
