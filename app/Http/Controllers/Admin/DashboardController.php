<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // Konstruktor untuk middleware role
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Menampilkan dashboard admin
    public function index()
    {
        return view('admin.dashboard'); // Ganti dengan view yang sesuai
    }
}
