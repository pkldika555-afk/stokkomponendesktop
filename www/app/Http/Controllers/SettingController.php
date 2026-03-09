<?php
namespace App\Http\Controllers;

use App\Helpers\AppConfig;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $config = AppConfig::all();
        return view('setting.index', compact('config'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'app_name'     => 'required|string|max:100',
            'app_judul'  => 'required|string|max:20',
            'app_judult' => 'nullable|string|max:200',
        ]);

        AppConfig::set([
            'app_name'     => $request->app_name,
            'app_judul'  => $request->app_judul,
            'app_judult' => $request->app_judult,
        ]);

        return back()->with('success', 'Pengaturan berhasil disimpan');
    }
}   