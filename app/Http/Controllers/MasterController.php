<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use App\Models\MasterKomponen;
use Illuminate\Http\Request;
use Storage;
use Str;

class MasterController extends Controller
{
    public function index(Request $request)
    {
        $komponen = MasterKomponen::with('departemen')
            ->orderBy('nama_komponen', 'asc')
            ->when($request->id_komponen, fn($q) => $q->where('id', $request->id_komponen))
            ->paginate(7);

        $allKomponen = MasterKomponen::orderBy('nama_komponen', 'asc')->get();
        $departemen = Departemen::all();

        return view('komponen.index', compact('komponen', 'allKomponen', 'departemen'));
    }
    public function create()
    {
        $departemen = Departemen::all();
        return view('komponen.create', compact('departemen'));
    }
    public function store(Request $request)
    {
        $validate = $request->validate([
            'kode_komponen' => 'required|max:255',
            'nama_komponen' => 'required',
            'tipe' => 'required',
            'satuan' => 'required',
            'stok_minimal' => 'required',
            'rak' => 'required',
            'lokasi' => 'required',
            'departemen_id' => 'required',
            'harga' => 'required|numeric',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:baru,bekas',
        ]);

        // ensure 'gambar' key exists so DB insert doesn't fail
        if ($request->hasFile('gambar')) {
            $filename = Str::slug($validate['kode_komponen']) . '.' . $request->gambar->extension();
            $request->gambar->storeAs('', $filename, 'app_data_images');
            $validate['gambar'] = $filename;
        } else {
            $validate['gambar'] = null;
        }

        $komponen = MasterKomponen::create($validate);

        return redirect()->route('komponen.index')->with('success', 'Data berhasil ditambahkan');
    }
    public function edit($id)
    {
        $komponen = MasterKomponen::findOrFail($id);
        $departemen = Departemen::all();
        return view('komponen.edit', compact('komponen', 'departemen'));
    }
    public function update(Request $request, $id)
    {
        $komponen = MasterKomponen::findOrFail($id);
        $validate = $request->validate([
            'kode_komponen' => 'required|unique:master_komponen,kode_komponen,' . $komponen->id,
            'nama_komponen' => 'required',
            'tipe' => 'required',
            'satuan' => 'required',
            'stok_minimal' => 'required',
            'rak' => 'required',
            'lokasi' => 'required',
            'id_departemen' => 'required',
            'harga' => 'required|numeric',
            'status' => 'required|in:baru,bekas',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $komponen->update($validate);

        if ($request->hasFile('gambar')) {
            if ($komponen->gambar) {
                Storage::disk('app_data_images')->delete($komponen->gambar);
            }

             $filename = Str::slug($komponen->kode_komponen) . '-' . time() . '.' . $request->gambar->extension();'.' . $request->gambar->extension();
            $request->gambar->storeAs('', $filename, 'app_data_images');
            $komponen->update(['gambar' => $filename]);
        }
        return redirect()->route('komponen.index')->with('success', 'Data berhasil diubah');
    }
    public function destroy($id)
    {
        $komponen = MasterKomponen::findOrFail($id);
        $komponen->delete();
        return redirect()->route('komponen.index')->with('success', 'Data berhasil dihapus');
    }
}
