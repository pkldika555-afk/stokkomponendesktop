<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use App\Models\MasterKomponen;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index() {
        $komponen = MasterKomponen::count();
        $departemen = Departemen::count();
        
        return view('welcome', compact('komponen', 'departemen'));
    }
}
