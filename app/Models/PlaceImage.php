<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlaceImage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['place_id', 'image'];

    public function getImageAttribute($image)
    {
        return url('storage/places/' . $image);
    }
}
