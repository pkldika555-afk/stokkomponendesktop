<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $user = User::query()
            ->orderBy('nama_departemen', 'asc')
            ->when($request->id_user, fn($q) => $q->where('id', $request->id_user))
            ->paginate(10);

        $alluser = User::orderBy('nama_departemen', 'asc')->get();
        return view('user.index', compact('user', 'alluser'));
    }
}
