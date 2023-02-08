<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubcategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
//            $subcategories = Subcategory::latest()->paginate(20);
            $subcategories = Subcategory::with('category')->latest()->paginate(20);
            if ($subcategories->count() > 0) {
                return response()->json([
                    'data'    => $subcategories,
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
            'name'        => ['required', 'string', 'max:255']
        ]);

        if ($validator->fails()){
            return response()->json([
                'errors' => $validator->errors()->first()
            ]);
        }

        try {
            $saveSubcategory = Subcategory::create($validator->validated());
            if ($saveSubcategory){
                return response()->json([
                    'message' => 'record created',
                    'status' => 'success',
                    'data'   => $saveSubcategory
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
        $subcategory = Subcategory::with('category')->find($id);
        try {
            if ($subcategory != null){
                return response()->json([
                    'status' => 'success',
                    'data'   => $subcategory
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
            'name'        => ['required', 'string', 'max:255']
        ]);

        if ($validator->fails()){
            return response()->json([
                'errors' => $validator->errors()->first()
            ]);
        }

        try {
            $subcategory = Subcategory::find($id);
            if ($subcategory != null){
                if ($subcategory->update($validator->validated())){
                    return response()->json([
                        'message' => 'record updated',
                        'status' => 'success',
                        'data'   => $subcategory
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
            if (Subcategory::destroy($id)){
                return response()->json([
                    'status'  => 'success',
                    'message' => 'subcategory deleted successfully'
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
