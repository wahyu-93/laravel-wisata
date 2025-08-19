<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Place extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['title', 'user_id', 'category_id', 'description', 'phone', 'website', 'office_hours', 'address', 'longitude', 'latitude'];

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
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(PlaceImage::class);
    }
}
