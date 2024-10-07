<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\Sections;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductsController extends Controller
{
    // Display all products
    public function index()
    {
        $products = Products::all();
        $sections = Sections::all();
        return view('products.products', compact('products', 'sections'));
    }

    // Show form for creating a new product
    public function create()
    {
        $sections = Sections::all();
        return view('products.create', compact('sections'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'Product_name' => 'required|max:255',
            'section_id' => 'required',
        ], [
            'Product_name.required' => 'يرجى إدخال اسم المنتج',
            'Product_name.unique' => 'اسم المنتج مسجل مسبقًا',
            'section_id.required' => 'يرجى اختيار القسم',
        ]);

        Products::create([
            'Product_name' => $request->Product_name,
            'section_id' => $request->section_id,
            'description' => $request->description,
        ]);

        $notification = [
            'message' => 'تم إضافة المنتج بنجاح',
            'alert-type' => 'success',
        ];

        return redirect()->route('products.index')->with($notification);
    }

    // Show form for editing a product
    public function edit($id)
    {
        $product = Products::find($id);
        $sections = Sections::all();
        return view('products.edit', compact('product', 'sections'));
    }

    // Update the product in the database
    public function update(Request $request)
    {
        $id = $request->pro_id;

        // Validate input
        $this->validate($request, [
            'Product_name' => 'required|max:255|unique:products,Product_name,' . $id,
            'section_id' => 'required',
            'description' => 'required',
        ], [
            'Product_name.required' => 'يرجى إدخال اسم المنتج',
            'Product_name.unique' => 'اسم المنتج مسجل مسبقًا',
            'section_id.required' => 'يرجى اختيار القسم',
            'description.required' => 'يرجى إدخال الملاحظات',
        ]);

        // Update the product
        $product = Products::find($id);
        $product->update([
            'Product_name' => $request->Product_name,
            'section_id' => $request->section_id,
            'description' => $request->description,
        ]);

        // Success notification
        $notification = [
            'message' => 'تم تعديل المنتج بنجاح',
            'alert-type' => 'success',
        ];

        return redirect()->route('products.index')->with($notification);
    }

    // Delete a product
    public function destroy(Request $request)
    {
        $id = $request->pro_id;
        Products::find($id)->delete();

        // Success notification
        $notification = [
            'message' => 'تم حذف المنتج بنجاح',
            'alert-type' => 'success',
        ];

        return redirect()->route('products.index')->with($notification);
    }
}
