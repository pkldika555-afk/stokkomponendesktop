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
            'app_version'  => 'required|string|max:20',
            'app_subtitle' => 'nullable|string|max:200',
        ]);

        AppConfig::set([
            'app_name'     => $request->app_name,
            'app_version'  => $request->app_version,
            'app_subtitle' => $request->app_subtitle,
        ]);

        return back()->with('success', 'Pengaturan berhasil disimpan');
    }
}   