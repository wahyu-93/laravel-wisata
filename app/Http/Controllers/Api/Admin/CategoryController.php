<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::when(request()->q, function($categories){
            $categories = $categories->where('name', 'like', '%'.request()->q.'%');
        })->latest()->paginate(5);

        // return category resource
        return new CategoryResource(true, 'List Data Categories', $categories);
    }

    public function store(Request $request)
    {
        // validasi
        $validator = Validator::make($request->all(), [
            'image'    => 'required|image|mimes:jpeg,jpg,png|max:2000',
            'name'     => 'required|unique:categories',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        };

        // image upload
        $image = $request->file('image');
        $image->storeAs('categories', $image->hashName(), 'public');

        // create category
        $category = Category::create([
            'name'  => $request->name,
            'image' => $image->hashName(),
        ]);

        // return resource
        if(!$category){
            return new CategoryResource(false, 'Data Category Berhasil Disimpan', null);
        };
        return new CategoryResource(true, 'Data Category Berhasil Disimpan', $category);
    }

    public function show($id)
    {
        $category = Category::whereId($id)->first();

        if($category){
            return new CategoryResource(true, 'Detail Data Category', $category);
        }
        else {
            return new CategoryResource(false, 'Detail Data Category Tidak Ditemukan', null);
        }
    }

    public function update(Request $request, Category $category)
    {
        // validasi
        $validator = Validator::make($request->all(),[
            'name'  => 'required|unique:categories,name,'.$category->id,
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        };

        // cek image update jika ada uploatan image
        if($request->file('image')){
            // hapus foto lama
            Storage::disk('public')->delete('categories/'.basename($category->image));

            // upload foto baru
            $image = $request->file('image');
            $image->storeAs('categories', $image->hashName(), 'public');

            // update data
            $category->update([
                'image' => $image->hashName(),
                'name'  => $request->name,
            ]);
        }

        // jika tidak ada uplotan imgage
       $category->update([
            'image' => $image->hashName(),
            'name'  => $request->name,
        ]);

        if($category){
            return new CategoryResource(true, 'Data Category Berhasil Diupdate!', $category);
        }
        else {
            return new CategoryResource(false, 'Data Category Gagal Diupdate!', null);
        }
    }

    public function delete(Category $category)
    {
        Storage::disk('publick')->delete('categories/'.basename($category->image));

        if($category->delete()){
            return new CategoryResource(true, 'Data Category Berhasil Dihapus!', null);
        }
        else{
            return new CategoryResource(false, 'Data Category Gagal Dihapus!', null);
        }
    }
}
