<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\SliderResource;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::latest()->paginate(5);

        return new SliderResource(true, 'List Data Slider', $sliders);
    }

     public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image'    => 'required|image|mimes:jpeg,jpg,png|max:2000',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //upload image
        $image = $request->file('image');
        $image->storeAs('sliders', $image->hashName(), 'public');

        //create slider
        $slider = Slider::create([
            'image'=> $image->hashName(),
            'user_id'   => auth()->guard('api')->user()->id,
        ]);

        if($slider) {
            //return success with Api Resource
            return new SliderResource(true, 'Data Slider Berhasil Disimpan!', $slider);
        }

        //return failed with Api Resource
        return new SliderResource(false, 'Data Slider Gagal Disimpan!', null);
    }

    public function destroy(Slider $slider)
    {
        //remove image
        Storage::disk('public')->delete('sliders/'.basename($slider->image));

        if($slider->delete()) {
            //return success with Api Resource
            return new SliderResource(true, 'Data Slider Berhasil Dihapus!', null);
        }

        //return failed with Api Resource
        return new SliderResource(false, 'Data Slider Gagal Dihapus!', null);
    }


}
