<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Daftar Pengguna') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                
                {{-- SEARCH & TAMBAH --}}
                <div class="p-6 flex flex-col md:flex-row justify-between items-center gap-4">
                    <form action="{{ route('admin.user.index') }}" method="GET" class="flex gap-2 w-full md:w-auto">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..." 
                               class="border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 w-full md:w-64 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white p-2.5 rounded-lg transition" title="Cari">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                        <a href="{{ route('admin.user.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white p-2.5 rounded-lg transition" title="Reset">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </a>
                    </form>

                    <button onclick="document.getElementById('modalTambah').classList.remove('hidden')" 
                            class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2.5 rounded-lg font-bold shadow transition whitespace-nowrap">
                        + Tambah Pengguna
                    </button>
                </div>

                {{-- TABEL --}}
                <div class="px-6 pb-6 overflow-x-auto">
                    <table class="w-full text-left border-collapse border border-gray-200 dark:border-gray-700">
                        <thead class="bg-blue-600 text-white uppercase text-xs">
                            <tr>
                                <th class="py-4 px-2 border text-center w-16">No</th>
                                <th class="py-4 px-6 border">Nama</th>
                                <th class="py-4 px-6 border">Email</th>
                                <th class="py-4 px-6 border text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($users as $user)
                            <tr class="odd:bg-white even:bg-gray-50 dark:odd:bg-gray-800 dark:even:bg-gray-700 hover:bg-blue-50 dark:hover:bg-gray-600 transition">
                                <td class="py-4 px-2 border text-center text-sm">{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</td>
                                <td class="py-4 px-6 border text-sm">{{ $user->name }}</td>
                                <td class="py-4 px-6 border text-sm">{{ $user->email }}</td>
                                <td class="py-4 px-6 border text-center">
                                    <form action="{{ route('admin.user.destroy', $user->id) }}" method="POST" class="inline" id="deleteForm{{ $user->id }}">
                                        @csrf @method('DELETE')
                                        <button type="button" onclick="confirmDelete({{ $user->id }}, '{{ $user->name }}')" 
                                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-1.5 rounded font-bold text-sm transition">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center py-10 text-gray-500">Data tidak ditemukan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 pb-6">{{ $users->withQueryString()->links() }}</div>
            </div>
        </div>
    </div>

    {{-- MODAL TAMBAH USER --}}
    <div id="modalTambah" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 px-4">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-full max-w-sm">
            <h3 class="font-bold text-lg mb-4 dark:text-white">Tambah Pengguna Baru</h3>
            <form action="{{ route('admin.user.store') }}" method="POST" id="formStore">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm dark:text-gray-300 font-medium">Nama Lengkap</label>
                    <input type="text" name="name" placeholder="Contoh: Budi Santoso" class="w-full border-gray-300 rounded-lg dark:bg-gray-700 focus:ring-emerald-500 focus:border-emerald-500" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm dark:text-gray-300 font-medium">Email</label>
                    <input type="email" name="email" placeholder="contoh@gmail.com" class="w-full border-gray-300 rounded-lg dark:bg-gray-700 focus:ring-emerald-500 focus:border-emerald-500" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm dark:text-gray-300 font-medium">Password</label>
                    <input type="password" name="password" placeholder="Minimal 8 karakter" class="w-full border-gray-300 rounded-lg dark:bg-gray-700 focus:ring-emerald-500 focus:border-emerald-500" required>
                </div>
                {{-- Tambahan Role --}}
                <div class="mb-4">
                    <label class="block text-sm dark:text-gray-300 font-medium">Pilih Role</label>
                    <select name="role" class="w-full border-gray-300 rounded-lg dark:bg-gray-700 focus:ring-emerald-500 focus:border-emerald-500" required>
                        <option value="kasir">Kasir</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeModal(this)" class="bg-gray-300 px-4 py-2 rounded-lg">Batal</button>
                    <button type="button" onclick="confirmAction()" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function closeModal(btn) { btn.closest('.fixed').classList.add('hidden'); }

        function confirmAction() {
            Swal.fire({
                title: 'Simpan Pengguna?',
                text: "Pastikan data sudah benar sebelum disimpan.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Simpan'
            }).then((res) => { if (res.isConfirmed) document.getElementById('formStore').submit(); });
        }

        function confirmDelete(id, name) {
            Swal.fire({
                title: 'Hapus ' + name + '?',
                text: "Data tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                confirmButtonText: 'Ya, Hapus'
            }).then((res) => { if (res.isConfirmed) document.getElementById('deleteForm' + id).submit(); });
        }

        @if(session('success'))
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session('success') }}', confirmButtonText: 'OK' });
        @endif
        
        @if($errors->any())
            Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Periksa kembali data Anda.', confirmButtonText: 'OK' });
        @endif
    </script>
</x-app-layout>