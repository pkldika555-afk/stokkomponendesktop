<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use Illuminate\Http\Request;

class DepartemenController extends Controller
{
    public function index(Request $request)
    {
    $departemen = Departemen::query()
        ->orderBy('nama_departemen', 'asc')
        ->when($request->id_departemen, fn($q) => $q->where('id', $request->id_departemen))
        ->paginate(10);

    $allDepartemen = Departemen::orderBy('nama_departemen', 'asc')->get(); 

        return view('departemen.index', compact('departemen', 'allDepartemen'));
    }
    public function create()
    {
        return view('departemen.create');
    }
    public function store(Request $request)
    {
        $validate = $request->validate([
            'nama_departemen' => 'required',
        ]);
        $departemen = departemen::create($validate);
        return redirect()->route(route: 'departemen.index')->with('success', 'Data berhasil ditambahkan');
    }
    public function edit($id)
    {
        $departemen = departemen::findOrFail($id);
        return view('departemen.edit', compact('departemen'));
    }
    public function update(Request $request, $id)
    {
        $validate = $request->validate([
            'nama_departemen' => 'required',
        ]);
        $departemen = departemen::findOrFail($id);
        $departemen->update($validate);
        return redirect()->route(route: 'departemen.index')->with('success', 'Data berhasil diperbarui');
    }
    public function destroy($id)
    {
        $departemen = departemen::findOrFail($id);
        if ($departemen->komponen()->exists()){
            return redirect()->route('departemen.index')->with('error', 'Departemen tidak bisa dihapus karena masih digunakan oleh ' . $departemen->komponen()->count() . ' komponen.');
        }
        $departemen->delete();
        return redirect()->route(route: 'departemen.index')->with('success', 'Data berhasil dihapus');
    }
}
