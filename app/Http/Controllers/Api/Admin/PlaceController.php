<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlaceResource;
use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PlaceController extends Controller
{
    public function index()
    {
        // get place dan pencarian 
        $places = Place::with('category')->when(request()->q, function($places){
            $places = $places->where('title', '%' .request()->q. '%');
        })->latest()->paginate(5);

        return new PlaceResource(true, 'List Data Places', $places);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'title'         => 'required|unique:places',
            'category_id'   => 'required',
            'description'   => 'required',
            'phone'         => 'required',
            'website'       => 'required',
            'office_hours'  => 'required',
            'address'       => 'required',
            'latitude'      => 'required',
            'longitude'     => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),422);
        };

        //create place
        $place = Place::create([
            'title'         => $request->title,
            'user_id'       => auth()->guard('api')->user()->id,
            'category_id'   => $request->category_id,
            'description'   => $request->description,
            'phone'         => $request->phone,
            'website'       => $request->website,
            'office_hours'  => $request->office_hours,
            'address'       => $request->address,
            'latitude'      => $request->latitude,
            'longitude'     => $request->longitude,
        ]);

          //check request file
        if($request->hasFile('image')) {
            
            //get request file image
            $images = $request->file('image');
            
            //loop file image
            foreach($images as $image) {
                
                //move to storage folder
                $image->storeAs('places', $image->hashName(), 'public');

                //insert database
                $place->images()->create([
                    'image'     => $image->hashName(),
                    'place_id'  => $place->id
                ]);

            }
        }       

        if($place){
            return new PlaceResource(true, 'Data place Berhasil Disimpan!', $place);
        }
        else {
            return new PlaceResource(false, 'Data place Gagal Disimpan!', null);
        }
    }

    public function show($id)
    {
        $place = Place::with('images')->whereId($id)->first();

        if($place) {
            //return success with Api Resource
            return new PlaceResource(true, 'Detail Data Place!', $place);
        }

        //return failed with Api Resource
        return new PlaceResource(false, 'Detail Data Place Tidak Ditemukan!', null);
    }

    public function update(Request $request, Place $place)
    {
        $validator = Validator::make($request->all(), [
            'title'     => 'required|unique:places,title,'.$place->id,
            'category_id'   => 'required',
            'description'   => 'required',
            'phone'         => 'required',
            'website'       => 'required',
            'office_hours'  => 'required',
            'address'       => 'required',
            'latitude'      => 'required',
            'longitude'     => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //update place
        $place->update([
            'title'         => $request->title,
            'user_id'       => auth()->guard('api')->user()->id,
            'category_id'   => $request->category_id,
            'description'   => $request->description,
            'phone'         => $request->phone,
            'website'       => $request->website,
            'office_hours'  => $request->office_hours,
            'address'       => $request->address,
            'latitude'      => $request->latitude,
            'longitude'     => $request->longitude,
        ]);

        //check request file
        if($request->hasFile('image')) {
            
            //get request file image
            $images = $request->file('image');
            
            //loop file image
            foreach($images as $image) {
                
                //move to storage folder
                $image->storeAs('places', $image->hashName(), 'public');

                //insert database
                $place->images()->create([
                    'image'     => $image->hashName(),
                    'palce_id'  => $place->id
                ]);

            }
        }

        if($place) {
            //return success with Api Resource
            return new PlaceResource(true, 'Data Place Berhasil Diupdate!', $place);
        }

        //return failed with Api Resource
        return new PlaceResource(false, 'Data Place Gagal Diupdate!', null);
    }

    public function destroy($id)
    {
        $place = Place::findOrFail($id);

        foreach($place->images()->get() as $image){
            //remove image
            Storage::disk('public')->delete('places/'.basename($image->image));

            //remove child relation
            $image->delete();
        }

        if($place->delete()) {
            //return success with Api Resource
            return new PlaceResource(true, 'Data Place Berhasil Dihapus!', null);
        }
        else{
            //return failed with Api Resource
            return new PlaceResource(false, 'Data Place Gagal Dihapus!', null);
        }
    }
}