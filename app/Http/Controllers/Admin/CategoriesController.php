<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $categories = Category::all();
            if ($categories->count() > 0){
                return response()->json([
                    'data' => $categories
                ]);
            }else{
                return response()->json([
                    'message' => 'no record found'
                ]);
            }
        }catch (\Exception $e){
            return response()->json([
                'errors' => 'an exceptional error occurred'
            ]);
        }catch (\Error $e){
            return response()->json([
                'errors' => 'an error occurred'
            ]);
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
            'name' => ['required', 'string', 'max:255']
        ]);

        if ($validator->fails()){
            return response()->json([
                'errors' => $validator->errors()->first()
            ], 401);
        }

        try {
            $create_category = Category::create($validator->validated());

            if ($create_category){
                return response()->json([
                    'data' => $create_category
                ]);
            }else{
                return response()->json([
                    'errors' => 'error creating record'
                ], 500);
            }
        }catch (\Exception $e){
            return response()->json([
                'errors' => $e->getMessage()
                #'errors' => 'an exceptional error'
            ], 500);
        }catch (\Error $e){
            return response()->json([
                'errors' => $e->getMessage()
                #'errors' => 'an error occurred'
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
        try {
            $category = Category::find($id);
            if ($category != null){
                return response()->json([
                    'data' => $category
                ]);
            }else{
                return response()->json([
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
            'name' => ['required', 'string', 'max:255']
        ]);

        if ($validator->fails()){
            return response()->json([
                'errors' => $validator->errors()->first()
            ], 401);
        }
        try {
            $update_category = Category::find($id);
            if ($update_category != null){
                if ($update_category->update(['name' => $request->name])){
                    return response()->json([
                        'message'   => 'record updated successfully',
                        'data'      => $update_category
                    ]);
                }else{
                    return response()->json([
                        'errors' => 'an error occurred while updating record.'
                    ], 500);
                }
            }else{
                return response()->json([
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            if (Category::destroy($id)){
                return response()->json([
                    'message' => 'category deleted successfully'
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
