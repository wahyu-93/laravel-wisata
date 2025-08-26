<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'image'];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function($model){
            $model->slug = Str::slug($model->name);
        });

        static::updating(function($model){
            $model->slug = Str::slug($model->name);
        });
    }

    public function places()
    {
        return $this->hasMany(Place::class);
    }

    public function getImageAttribute($image)
    {
        return url('storage/categories/' . $image);
    }
}
