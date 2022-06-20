<?php

namespace App\Http\Controllers\Api\Web;

use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;

use function PHPUnit\Framework\assertNotNull;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // get all parent category
        $categories = Category::with('subCategory')->where('parent_id', null)->orderby('name', 'asc')->get();
        //return with Api Resource
        return new CategoryResource(true, 'List Data Categories', $categories);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $category = Category::with('subCategory')->where('parent_id', null)
            //get count review and average review
            ->with('subCategory.products', function ($query) {
                $query->withCount('reviews');
                $query->withAvg('reviews', 'rating');
            })
            ->where('slug', $slug)->first();  
            //return success with Api Resource
            if($category) {
                return new CategoryResource(true, 'Data Product By Category : '.$category->name.'', $category);
            }
        //return failed with Api Resource
        return new CategoryResource(false, 'Detail Data Category Tidak DItemukan!', null);
    }
}