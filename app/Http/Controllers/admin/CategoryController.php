<?php

namespace App\Http\Controllers\admin;

use App\Category;
use App\Http\Controllers\Controller;
use App\Product;
use App\Subcategory;
use App\User;
use App\Vertical;
use Exception;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::get();
        return view('layout.category.index', compact('categories'));
    }

    public function subcategory(Request $request, $category_id)
    {

        $subcategories = Subcategory::where('category_id', $category_id)->get();
        // echo "<pre>"; print_r($subcategories); die;
        $category_name = Category::where('id', $category_id)->value('name');
        return view('layout.category.subcategory.index', compact('subcategories', 'category_name', 'category_id'));
    }

    public function vertical(Request $request, $subcategory_id)
    {

        $verticals = Vertical::where('subcategory_id', $subcategory_id)->get();
        // echo "<pre>"; print_r($subcategories); die;
        $sub_category_name = Subcategory::where('id', $subcategory_id)->value('name');
        return view('layout.category.subcategory.vertical.index', compact('verticals', 'sub_category_name', 'subcategory_id'));
    }

    public function eventAdd(Request $request, $event_id, $event_type)
    {
        try {
            if ($event_type == 'C') { //Category
                $add = new Category;
                $add->name = $request->name;
                $add->save();
            } else if ($event_type == 'SC') { //SubCategory
                $add = new Subcategory;
                $add->category_id = $event_id;
                $add->name = $request->name;
                $add->save();
            } else if ($event_type == 'V') { //Vertical
                $add = new Vertical;
                $add->subcategory_id = $event_id;
                $add->name = $request->name;
                $add->save();
            }
            alert()->success('Added Successfully', 'Success');
            return back();
        } catch (Exception $e) {
            alert()->error($e->getMessage(), 'Error');
            return back();
        }
    }

    public function eventEdit(Request $request, $event_id, $event_type)
    {
        try {
            if ($event_type == 'C') { //Category
                $event = Category::findOrFail($event_id);
                $event->name = $request->name;
                $event->save();
            } else if ($event_type == 'SC') { //SubCategory
                $event = Subcategory::findOrFail($event_id);
                $event->name = $request->name;
                $event->save();
            } else if ($event_type == 'V') { //Vertical
                $event = Vertical::findOrFail($event_id);
                $event->name = $request->name;
                $event->save();
            }
            alert()->success('Updated Successfully', 'Success');
            return back();
        } catch (Exception $e) {
            alert()->error($e->getMessage(), 'Error');
            return back();
        }
    }

    public function eventDelete($event_id, $event_type)
    {
        try {
            if ($event_type == 'C') { //Category
                $event = Category::findOrFail($event_id);
                if (User::where('business_category', $event_id)->get()->count() == 0) {
                    $event->delete();
                } else {
                    alert()->error('Cannot delete this category as it is associated with an active vendor.', 'Error');
                    return back();
                }

            } else if ($event_type == 'SC') { //SubCategory
                $event = Subcategory::findOrFail($event_id);
                if (Product::where('subcategory_id', $event_id)->get()->count() == 0) {
                    $event->delete();
                } else {
                    alert()->error('Cannot delete this subcategory as it is associated with a product.', 'Error');
                    return back();
                }
            } else if ($event_type == 'V') { //Vertical
                $event = Vertical::findOrFail($event_id);
                if (Product::where('vertical_id', $event_id)->get()->count() == 0) {
                    $event->delete();
                } else {
                    alert()->error('Cannot delete this vertical as it is associated with a product.', 'Error');
                    return back();
                }
            }
            alert()->success('Deleted Successfully', 'Success');
            return back();

        } catch (Exception $e) {
            alert()->error($e->getMessage(), 'Error');
            return back();
        }
    }
}
