<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $user = User::query()
            ->orderBy('name', 'asc')
            ->when($request->id_user, fn($q) => $q->where('id', $request->id_user))
            ->paginate(10);

        $alluser = User::orderBy('name', 'asc')->get();
        return view('user.index', compact('user', 'alluser'));
    }
    public function create()
    {
        return view('user.create');
    }
    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'nrp' => 'required|unique:users,nrp',
            'role' => 'required|in:admin,user',
        ]);
        $validate['password'] = bcrypt($validate['password']);
        $user = User::create($validate);
        return redirect()->route(route: 'user.index')->with('success', 'Data berhasil ditambahkan');
    }
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('user.edit', compact('user'));
    }
    public function update(Request $request, $id)
    {
        $validate = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'required|min:6',
            'nrp' => 'required|unique:users,nrp,' . $id,
            'role' => 'required|in:admin,user',
        ]);
        if ($request->filled('password')) {
            $validate['password'] = bcrypt($validate['password']);
        } else {
            unset($validate['password']);
        }
        $user = User::findOrFail($id);
        $user->update($validate);
        return redirect()->route(route: 'user.index')->with('success', 'Data berhasil diubah');
    }
}
