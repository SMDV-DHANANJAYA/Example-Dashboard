<?php

namespace App\Http\Controllers\DashboardController;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\User;

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $admins = User::where('type',User::ADMIN)->count();
        $users = User::where('type',User::USER)->count();
        $locations = Location::count();

        $data = [
            'admins' => $admins,
            'users' => $users,
            'locations' => $locations
        ];

        return view('home',$data);
    }
}
