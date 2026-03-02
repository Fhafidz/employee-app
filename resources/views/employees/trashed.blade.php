@extends('layouts.app')

@section('content')
<div class="card">
    <div class="px-6 py-5 border-b border-border flex flex-col sm:flex-row justify-between items-start sm:items-center bg-white gap-4">
        <h3 class="text-xl font-bold text-secondary flex items-center">
            <i class="fas fa-trash-alt mr-3 text-primary text-2xl"></i>Recycle Bin
        </h3>
        <a href="{{ route('employees.index') }}" class="btn btn-primary py-2 px-4 text-sm flex items-center justify-center w-full sm:w-auto">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>
    <div class="p-6 overflow-x-auto">
        <table id="trashedTable" class="w-full text-left border-collapse">
            <thead class="bg-light text-secondary text-xs font-bold uppercase tracking-wider">
                <tr>
                    <th class="p-4 border-b whitespace-nowrap">No</th>
                    <th class="p-4 border-b whitespace-nowrap">NIK</th>
                    <th class="p-4 border-b whitespace-nowrap">Nama & Email</th>
                    <th class="p-4 border-b whitespace-nowrap">Departemen</th>
                    <th class="p-4 border-b whitespace-nowrap text-center">Tanggal Dihapus</th>
                    <th class="p-4 border-b text-center whitespace-nowrap">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm"></tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    let table = $('#trashedTable').DataTable({
        processing: true, 
        serverSide: false,
        ajax: '{{ route('employees.trashed.data') }}',
        columns: [
            { data: null, className: 'p-4 border-b text-center text-xs font-semibold', render: (data, type, row, meta) => meta.row + 1 },
            { data: 'identity_number', className: 'p-4 border-b text-secondary whitespace-nowrap' },
            { data: 'full_name', className: 'p-4 border-b whitespace-nowrap', render: (data, type, row) => `
                <div class="font-bold text-secondary text-base leading-tight">${data}</div>
                <div class="text-xs text-gray-500 mt-0.5">${row.email}</div>
            ` },
            { data: 'department', className: 'p-4 border-b whitespace-nowrap text-secondary' },
            { data: 'deleted_at', className: 'p-4 border-b text-center whitespace-nowrap text-sm text-gray-600' },
            { data: 'id', className: 'p-4 border-b text-center whitespace-nowrap', orderable: false, render: function(data) {
                return `
                    <div class="flex justify-center gap-2">
                        <button class="btn-restore inline-flex items-center justify-center w-9 h-9 bg-green-100 hover:bg-green-200 text-green-600 rounded-lg transition shadow-soft" data-id="${data}" title="Kembalikan">
                            <i class="fas fa-undo text-sm"></i>
                        </button>
                        <button class="btn-force-delete inline-flex items-center justify-center w-9 h-9 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg transition shadow-soft" data-id="${data}" title="Hapus Permanen">
                            <i class="fas fa-times text-sm"></i>
                        </button>
                    </div>`;
            }}
        ],
        language: {
            emptyTable: "Tidak ada data yang dihapus",
            info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data",
            paginate: { previous: "< Sebelumnya", next: "Selanjutnya >" }
        }
    });

    // Restore handler
    $('tbody').on('click', '.btn-restore', function() {
        let id = $(this).data('id');
        Swal.fire({
            title: 'Kembalikan Data?', 
            text: "Data pegawai akan dikembalikan dari recycle bin", 
            icon: 'question',
            showCancelButton: true, 
            confirmButtonColor: '#13a19c', 
            cancelButtonColor: '#9ca3af',
            confirmButtonText: 'Ya, Kembalikan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/employees/trash/restore/${id}`, 
                    type: 'POST',
                    success: res => { 
                        table.ajax.reload(); 
                        Swal.fire({
                            title: 'Dikembalikan!',
                            text: res.message,
                            icon: 'success',
                            timer: 1500
                        });
                    },
                    error: xhr => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.message || 'Gagal mengembalikan data'
                        });
                    }
                });
            }
        });
    });

    // Force delete handler
    $('tbody').on('click', '.btn-force-delete', function() {
        let id = $(this).data('id');
        Swal.fire({
            title: 'Hapus Permanen?', 
            text: "Data pegawai akan dihapus secara permanen dan tidak bisa dikembalikan!", 
            icon: 'warning',
            showCancelButton: true, 
            confirmButtonColor: '#fc6b3f', 
            cancelButtonColor: '#9ca3af',
            confirmButtonText: 'Ya, Hapus Permanen!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/employees/trash/force/${id}`, 
                    type: 'DELETE',
                    success: res => { 
                        table.ajax.reload(); 
                        Swal.fire({
                            title: 'Terhapus Permanen!',
                            text: res.message,
                            icon: 'success',
                            timer: 1500
                        });
                    },
                    error: xhr => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.message || 'Gagal menghapus data'
                        });
                    }
                });
            }
        });
    });
});
</script>
@endsection
