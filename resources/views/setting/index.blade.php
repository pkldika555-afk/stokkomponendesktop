@extends('layouts.app')
@section('content')
    <form method="POST" action="{{ route('setting.update') }}">
        @csrf
        <div class="mb-4">
            <label class="text-xs text-gray-400">Nama Aplikasi</label>
            <input type="text" name="app_name" value="{{ $config['app_name'] ?? '' }}"
                class="w-full bg-gray-800 border border-gray-700 text-gray-300 rounded-lg px-3 py-2 text-xs mt-1">
        </div>
        <div class="mb-4">
            <label class="text-xs text-gray-400">Versi</label>
            <input type="text" name="app_version" value="{{ $config['app_version'] ?? '' }}"
                class="w-full bg-gray-800 border border-gray-700 text-gray-300 rounded-lg px-3 py-2 text-xs mt-1">
        </div>
        <div class="mb-4">
            <label class="text-xs text-gray-400">Subtitle</label>
            <input type="text" name="app_subtitle" value="{{ $config['app_subtitle'] ?? '' }}"
                class="w-full bg-gray-800 border border-gray-700 text-gray-300 rounded-lg px-3 py-2 text-xs mt-1">
        </div>
        <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg px-4 py-2 text-xs font-medium">
            Simpan
        </button>
    </form>
@endsection