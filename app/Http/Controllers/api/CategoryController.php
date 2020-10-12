<?php

namespace App\Http\Controllers\api;

use App\Category;
use App\Http\Controllers\Controller;
use App\Subcategory;
use App\Vertical;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function getAllCategories()
    {
        $data = Category::all();

        return $this->success($data, "Categories");
    }

    public function allSubcategories()
    {

        //echo json_encode($this->getAuthenticatedUser());die;
        $data = Subcategory::where('category_id', $this->getUserCategoryId())
                ->with('verticals')
                ->get();

        return $this->success($data, "Subcategories");
    }
    
    public function subcategories(int $category_id)
    {

        $data = Subcategory::where('category_id', $category_id)
                ->get();

        return $this->success($data, "Subcategories");
    }
    
    public function verticals(int $subcategory_id)
    {
        $data = Vertical::where('subcategory_id', $subcategory_id)
                ->get();

        return $this->success($data, "Verticals");
    }

    public function allVerticals(int $id)
    {
        $data = Vertical::where('subcategory_id', $id)
                ->get();

        return $this->success($data, "Verticals");
    }
}
