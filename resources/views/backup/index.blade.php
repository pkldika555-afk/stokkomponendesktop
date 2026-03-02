@extends('layouts.app')
@section('content')
    <div>
        <div>
            <div>
                <p>Sistem</p>
                <h2>Backup & Restore</h2>
                <p>Pindahkan atau salin data antar perangkat</p>
            </div>

            @if (session('success'))
                <div>
                    <svg></svg>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div>
                    <svg></svg>
                    <div>
                        <p>Terjadi Kesalahan</p>
                        @foreach ($errors->all() as $e)
                            <p>{{ $e }}</p>
                        @endforeach
                    </div>
                </div>
            @endif
            <div>
                <div>
                    <p>{{$stats['departemen']}}</p>
                    <p>Departemen</p>
                </div>
                <div>
                    <p>{{ $stats['komponen'] }}</p>
                    <p>Komponen</p>
                </div>
                <div>
                    <p>{{ $stats['mutasi'] }}</p>
                    <p>Mutasi</p>
                </div>
            </div>

            <div>
                <div>
                    <div>
                        <div>
                            <svg></svg>
                        </div>
                        <div>
                            <p>Backup Data</p>
                            <p>Unduh semua data</p>
                        </div>
                    </div>
                </div>
                <div>
                    <div>
                        <div>
                            <span>JSON</span>
                        </div>
                        <p>File Backup Lengkap</p>
                        <p>Semua data</p>
                    </div>
                </div>
                <a href="{{ route('backup.download') }}">
                    <svg></svg>
                    Backup.JSON
                </a>
            </div>
            <div>
                <div>
                    <div>
                        <span></span>
                    </div>
                    <div>
                        <p>Laporan Excel</p>
                        <p>sheet: Komponen, Mutasi, Rekap Stok</p>
                    </div>
                </div>
                <a href="{{ route('backup.excel') }}">
                    <svg></svg>
                    Export.xlsx
                </a>
            </div>
        </div>
    </div>
    <div>
        <div>
            <div>
                <div>
                    <div>
                        <svg></svg>
                    </div>
                    <div>
                        <p>Restore Data</p>
                        <p>Pulihkan data dari file backup.json</p>
                    </div>
                </div>
            </div>
            <div>
                <div>
                    <svg></svg>
                </div>
                <p>Perhatian</p>
                <p>Proses ini akan <strong>menghapus semua data</strong> saat ini dan menggantinya dengan data dari file
                    backup.json</p>
            </div>
            <form action="{{ route('backup.restore') }}" method="POST" enctype="multipart/form-data" onsubmit="return confirmRestore()">
                @csrf
                <label for="backup_file" id="dropzone">
                    <svg></svg>
                    <div>
                        <p>
                            Klik untuk pilih file backup
                        </p>
                        <p>Format: .json - Max. 20MB</p>
                    </div>
                    <input type="file" id="backup_file" accept=".json" class="hidden" onchange="updateDropzone(this)">
                </label>
                <button type="submit">
                    <svg></svg>
                    Restore Sekarang 
                </button>
            </form>
        </div>
    </div>
    <script>
        function updateDropzone(input){
            const label = document.getElementById('dropzone-label');
            if(input.files && input.files[0]){
                const file = input.files[0];
                label.textContent = 'File siap diupload: ' + file.name;
                label.classList.add('text-emerald-400');
                label.classList.remove('text-gray-400');
                document.getElementById('dropzone').classList.add('border-emerald-500/40');
                document.getElementById('dropzone').classList.remove('border-gray-700');
            }
        }
        function confirmRestore(){
            const file = document.getElementById('backup_file').files[0];
            if(!file){
                alert('Silakan pilih file backup terlebih dahulu.');
                return false;
            }
            return confirm('Proses restore akan menghapus semua data saat ini dan menggantinya dengan data dari file backup.json. Apakah Anda yakin ingin melanjutkan?');
        }
    </script>
@endsection