<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pengaturan Toko') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h3 class="text-lg font-bold mb-6 dark:text-white border-b pb-2">Identitas Toko</h3>

                {{-- FORM: Tambahkan id="formSetting" --}}
                <form id="formSetting" action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf @method('PUT')

                    <div class="space-y-6">
                        <div>
                            <label class="block font-medium mb-1 dark:text-gray-300">Nama Toko</label>
                            <input type="text" name="nama_toko" value="{{ $setting->nama_toko ?? '' }}" 
                                   class="w-full border-gray-300 rounded-lg p-2 dark:bg-gray-700 dark:text-white" required>
                        </div>
                        <div>
                            <label class="block font-medium mb-1 dark:text-gray-300">Alamat Lengkap</label>
                            <textarea name="alamat" rows="3" 
                                      class="w-full border-gray-300 rounded-lg p-2 dark:bg-gray-700 dark:text-white" required>{{ $setting->alamat ?? '' }}</textarea>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end">
                        {{-- Button diubah menjadi type="button" untuk memicu alert --}}
                        <button type="button" onclick="konfirmasiSimpan()" 
                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-medium shadow transition">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Script SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // 1. Fungsi Konfirmasi Sebelum Submit
        function konfirmasiSimpan() {
            Swal.fire({
                title: 'Simpan Perubahan?',
                text: "Data toko akan diperbarui.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#4f46e5',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Simpan!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('formSetting').submit();
                }
            });
        }
    </script>

    {{-- 2. Notifikasi Berhasil Setelah Redirect --}}
    @if(session('success'))
        <script>
            Swal.fire({
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonColor: '#4f46e5'
            });
        </script>
    @endif
</x-app-layout>