<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Place;
use App\Models\Slider;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $categories = Category::count();
        $places = Place::count();
        $sliders = Slider::count();
        $users = User::count();

        return response()->json([
            'success'   => true,
            'message'   => 'Statistik Data',
            'data'      => [
                'categories'    => $categories,
                'places'        => $places,
                'sliders'       => $sliders,
                'users'         => $users,
            ],
        ]);
    }
}
