@extends('layouts.app')

@section('content')
<div class="card">
    <div class="px-6 py-5 border-b border-border flex flex-col sm:flex-row justify-between items-start sm:items-center bg-white gap-4">
        <h3 class="text-xl font-bold text-secondary flex items-center">
            <i class="fas fa-users mr-3 text-primary text-2xl"></i>Direktori Pegawai
        </h3>
        <div class="flex flex-col xs:flex-row items-stretch sm:items-center gap-3 w-full sm:w-auto">
            <!-- Export Section -->
            <div class="flex items-center gap-2">
                <div class="w-full sm:w-32">
                    <select id="exportFormat" class="filter-select" data-placeholder="Format" data-minimum-results-for-search="-1">
                        <option value="excel" selected>Excel</option>
                        <option value="pdf">PDF</option>
                        <option value="csv">CSV</option>
                    </select>
                </div>
                <button id="btnExport" class="btn btn-accent py-2 px-4 text-sm flex items-center justify-center whitespace-nowrap">
                    <i class="fas fa-file-export mr-2"></i> Export
                </button>
            </div>
            
            <a href="{{ route('employees.create') }}" class="btn btn-primary py-2 px-4 text-sm flex items-center justify-center whitespace-nowrap">
                <i class="fas fa-plus mr-2"></i> Tambah Data
            </a>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <div class="p-6">
            <table id="employeeTable" class="w-full text-left border-collapse">
            <thead class="bg-light text-secondary text-xs font-bold uppercase tracking-wider">
                <tr>
                    <th class="p-4 border-b whitespace-nowrap">No</th>
                    <th class="p-4 border-b whitespace-nowrap">Foto</th>
                    <th class="p-4 border-b whitespace-nowrap">NIK</th>
                    <th class="p-4 border-b whitespace-nowrap">Nama & Email</th>
                    <th class="p-4 border-b whitespace-nowrap">No. HP</th>
                    <th class="p-4 border-b whitespace-nowrap">
                        Gender
                        <select class="filter-select text-xs" id="filterGender" data-column="gender" data-placeholder="Semua Gender">
                            <option></option>
                            <option value="M">Laki-laki</option>
                            <option value="F">Perempuan</option>
                        </select>
                    </th>
                    <th class="p-4 border-b whitespace-nowrap">
                        Tgl Lahir
                        <div class="relative mt-1">
                            <input type="text" id="filterDob" class="filter-daterange" placeholder="Pilih rentang..." autocomplete="off" readonly>
                            <button type="button" id="clearDob" class="filter-daterange-clear" title="Hapus filter">&times;</button>
                        </div>
                    </th>
                    <th class="p-4 border-b whitespace-nowrap">Alamat</th>
                    <th class="p-4 border-b whitespace-nowrap">
                        Agama
                        <select class="filter-select text-xs" id="filterReligion" data-column="religion" data-placeholder="Semua Agama">
                            <option></option>
                            <option value="islam">Islam</option>
                            <option value="christianity_protestant">Kristen</option>
                            <option value="catholic">Katolik</option>
                            <option value="hindu">Hindu</option>
                            <option value="buddhism">Buddha</option>
                            <option value="confucianism">Khonghucu</option>
                        </select>
                    </th>
                    <th class="p-4 border-b whitespace-nowrap">
                        Status Nikah
                        <select class="filter-select text-xs" id="filterMaritalStatus" data-column="marital_status" data-placeholder="Semua Status">
                            <option></option>
                            <option value="single">Belum Menikah</option>
                            <option value="married">Menikah</option>
                            <option value="divorced">Cerai</option>
                        </select>
                    </th>
                    <th class="p-4 border-b whitespace-nowrap">
                        Departemen
                        <select class="filter-select text-xs" id="filterDepartment" data-column="department" data-placeholder="Semua Dept">
                            <option></option>
                        </select>
                    </th>
                    <th class="p-4 border-b whitespace-nowrap">
                        Jabatan
                        <select class="filter-select text-xs" id="filterPosition" data-column="position" data-placeholder="Semua Jabatan">
                            <option></option>
                        </select>
                    </th>
                    <th class="p-4 border-b whitespace-nowrap">
                        Tipe Kerja
                        <select class="filter-select text-xs" id="filterWorkingStatus" data-column="working_status" data-placeholder="Semua Tipe">
                            <option></option>
                            <option value="full_time">Full Time</option>
                            <option value="part_time">Part Time</option>
                            <option value="contract">Kontrak</option>
                            <option value="intern">Magang</option>
                        </select>
                    </th>
                    <th class="p-4 border-b whitespace-nowrap">
                        Tgl Gabung
                        <div class="relative mt-1">
                            <input type="text" id="filterHiredDate" class="filter-daterange" placeholder="Pilih rentang..." autocomplete="off" readonly>
                            <button type="button" id="clearHiredDate" class="filter-daterange-clear" title="Hapus filter">&times;</button>
                        </div>
                    </th>
                    <th class="p-4 border-b whitespace-nowrap">
                        Status
                        <select class="filter-select text-xs" id="filterStatus" data-column="status" data-placeholder="Semua Status">
                            <option></option>
                            <option value="active">Aktif</option>
                            <option value="inactive">Non-Aktif</option>
                        </select>
                    </th>
                    <th class="p-4 border-b text-center whitespace-nowrap">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm"></tbody>
        </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<!-- PDF Export Libraries (Stable Versions) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tableexport.jquery.plugin@1.29.0/tableExport.min.js"></script>

<script>
// Required for tableExport to find jsPDF
window.jsPDF = window.jspdf.jsPDF;
$(document).ready(function() {

    // ─── Load dynamic filters (Department & Position from API) ────────────────
    function loadDynamicFilters() {
        $.ajax({
            url: '{{ route('employees.data') }}',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                const departments = new Set();
                const positions   = new Set();

                (response.data || []).forEach(emp => {
                    if (emp.department) departments.add(emp.department);
                    if (emp.position)   positions.add(emp.position);
                });

                $('#filterDepartment').html(
                    '<option></option>' +
                    Array.from(departments).sort().map(d => `<option value="${d}">${d}</option>`).join('')
                );
                $('#filterPosition').html(
                    '<option></option>' +
                    Array.from(positions).sort().map(p => `<option value="${p}">${p}</option>`).join('')
                );

                // Re-init Select2 after populating options
                initSelect2();
            },
            error: function() {
                // silent fail — filters will just be empty
            }
        });
    }

    // ─── DataTable ────────────────────────────────────────────────────────────
    let table = $('#employeeTable').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: '{{ route('employees.data') }}',
            type: 'GET',
            dataType: 'json',
            data: function(d) {
                d.gender         = $('#filterGender').val()        || '';
                d.department     = $('#filterDepartment').val()    || '';
                d.position       = $('#filterPosition').val()      || '';
                d.working_status = $('#filterWorkingStatus').val() || '';
                d.marital_status = $('#filterMaritalStatus').val() || '';
                d.religion       = $('#filterReligion').val()      || '';
                d.status         = $('#filterStatus').val()        || '';

                // Date range filters
                const dobVal = $('#filterDob').val();
                if (dobVal && dobVal.includes(' - ')) {
                    const parts = dobVal.split(' - ');
                    d.dob_from = parts[0] || '';
                    d.dob_to   = parts[1] || '';
                } else {
                    d.dob_from = ''; d.dob_to = '';
                }

                const hiredVal = $('#filterHiredDate').val();
                if (hiredVal && hiredVal.includes(' - ')) {
                    const parts = hiredVal.split(' - ');
                    d.hired_from = parts[0] || '';
                    d.hired_to   = parts[1] || '';
                } else {
                    d.hired_from = ''; d.hired_to = '';
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Memuat Data',
                    text: 'Tidak dapat memuat data pegawai. Silakan refresh halaman.',
                });
            },
            dataSrc: function(json) {
                return json.data || [];
            }
        },
        columns: [
            { data: null, className: 'p-4 border-b text-center text-xs font-semibold', render: (data, type, row, meta) => meta.row + 1 },
            { data: 'photo', className: 'p-4 border-b text-center', orderable: false, render: function(data) {
                if (data) {
                    return `<img src="/storage/employees/${data}" class="w-10 h-10 rounded-full object-cover mx-auto shadow-soft border-2 border-primary">`;
                }
                return `<div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary to-accent text-white flex items-center justify-center mx-auto font-bold shadow-soft"><i class="fas fa-user text-sm"></i></div>`;
            }},
            { data: 'identity_number', className: 'p-4 border-b text-secondary whitespace-nowrap' },
            { data: 'full_name', className: 'p-4 border-b whitespace-nowrap', render: (data, type, row) => `
                <div class="font-bold text-secondary text-base leading-tight">${data}</div>
                <div class="text-xs text-gray-500 mt-0.5">${row.email}</div>
            ` },
            { data: 'phone_number', className: 'p-4 border-b whitespace-nowrap' },
            { data: 'gender', className: 'p-4 border-b whitespace-nowrap', orderable: false, render: data => {
                const label = data === 'M' ? 'Laki-laki' : 'Perempuan';
                const color = data === 'M' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700';
                return `<span class="px-2 py-1 ${color} text-xs font-medium">${label}</span>`;
            }},
            { data: 'date_of_birth', className: 'p-4 border-b whitespace-nowrap', orderable: false },
            { data: 'address', className: 'p-4 border-b', render: data => `<div class="truncate w-40 text-gray-700" title="${data}">${data}</div>` },
            { data: 'religion', className: 'p-4 border-b whitespace-nowrap', orderable: false, render: data => {
                const map = { islam: 'Islam', christianity_protestant: 'Kristen Protestan', catholic: 'Katolik', hindu: 'Hindu', buddhism: 'Buddha', confucianism: 'Khonghucu' };
                return `<span class="text-sm text-gray-700">${map[data] || data}</span>`;
            }},
            { data: 'marital_status', className: 'p-4 border-b whitespace-nowrap', orderable: false, render: data => {
                const map = { single: 'Belum Menikah', married: 'Menikah', divorced: 'Cerai' };
                return `<span class="text-sm text-gray-700">${map[data] || data}</span>`;
            }},
            { data: 'department',     className: 'p-4 border-b whitespace-nowrap text-secondary', orderable: false },
            { data: 'position',       className: 'p-4 border-b whitespace-nowrap text-secondary', orderable: false },
            { data: 'working_status', className: 'p-4 border-b whitespace-nowrap', orderable: false, render: data => {
                const label = { full_time: 'Full Time', part_time: 'Part Time', contract: 'Kontrak', intern: 'Magang' };
                const color = { full_time: 'bg-green-100 text-green-700', part_time: 'bg-yellow-100 text-yellow-700', contract: 'bg-purple-100 text-purple-700', intern: 'bg-blue-100 text-blue-700' };
                return `<span class="px-2 py-1 ${color[data] || 'bg-gray-100'} text-xs font-medium">${label[data] || data}</span>`;
            }},
            { data: 'hired_date', className: 'p-4 border-b whitespace-nowrap', orderable: false },
            { data: 'status', className: 'p-4 border-b whitespace-nowrap', orderable: false, render: data =>
                data === 'active'
                    ? '<span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-medium">Aktif</span>'
                    : '<span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-medium">Non-Aktif</span>'
            },
            { data: 'id', className: 'p-4 border-b text-center whitespace-nowrap', orderable: false, render: function(data) {
                return `
                    <div class="flex justify-center gap-2">
                        <a href="/employees/${data}/edit" class="inline-flex items-center justify-center w-9 h-9 bg-blue-100 hover:bg-blue-200 text-blue-600 rounded-lg transition shadow-soft" title="Edit">
                            <i class="fas fa-edit text-sm"></i>
                        </a>
                        <button class="btn-documents inline-flex items-center justify-center w-9 h-9 bg-amber-100 hover:bg-amber-200 text-amber-600 rounded-lg transition shadow-soft" data-id="${data}" title="Dokumen">
                            <i class="fas fa-file text-sm"></i>
                        </button>
                        <button class="btn-delete inline-flex items-center justify-center w-9 h-9 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg transition shadow-soft" data-id="${data}" title="Hapus">
                            <i class="fas fa-trash text-sm"></i>
                        </button>
                    </div>`;
            }}
        ],
        language: {
            emptyTable: "Tidak ada data pegawai",
            info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ pegawai",
            infoEmpty: "Tidak ada pegawai",
            paginate: { previous: "< Sebelumnya", next: "Selanjutnya >" }
        },
        initComplete: function() {
            initSelect2();
            loadDynamicFilters();

            // ─── Daterangepicker ───────────────────────────────────────────
            const drpLocale = {
                format: 'DD/MM/YYYY',
                separator: ' - ',
                applyLabel: 'Terapkan',
                cancelLabel: 'Reset',
                daysOfWeek: ['Min','Sen','Sel','Rab','Kam','Jum','Sab'],
                monthNames: ['Januari','Februari','Maret','April','Mei','Juni',
                             'Juli','Agustus','September','Oktober','November','Desember'],
                firstDay: 1
            };
            const drpOptions = {
                autoApply: false,
                autoUpdateInput: false,
                showDropdowns: true,
                linkedCalendars: true,
                locale: drpLocale,
                opens: 'auto',
                drops: 'auto',
                ranges: {
                    'Bulan Ini'       : [moment().startOf('month'), moment().endOf('month')],
                    '3 Bulan Terakhir': [moment().subtract(3, 'months').startOf('month'), moment().endOf('month')],
                    'Tahun Ini'       : [moment().startOf('year'), moment().endOf('year')],
                    'Tahun Lalu'      : [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')],
                }
            };

            function initDrp(inputId, clearId) {
                const $input = $('#' + inputId);
                const $clear = $('#' + clearId);

                $input.val('');
                $input.daterangepicker(drpOptions);

                $input.on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                    $clear.show();
                    table.ajax.reload();
                });
                $input.on('cancel.daterangepicker', function() {
                    $(this).val('');
                    $clear.hide();
                    table.ajax.reload();
                });
                $input.on('click', function(e) { e.stopPropagation(); });

                $clear.hide().on('click', function(e) {
                    e.stopPropagation();
                    $input.val('');
                    $(this).hide();
                    table.ajax.reload();
                });
            }

            initDrp('filterDob', 'clearDob');
            initDrp('filterHiredDate', 'clearHiredDate');
        }
    });

    // ─── Reload on Select2 filter change ─────────────────────────────────────
    $(document).on('change', '.filter-select', function() {
        table.ajax.reload();
    });

    // ─── Delete ───────────────────────────────────────────────────────────────
    $('tbody').on('click', '.btn-delete', function() {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Hapus Data?',
            text: 'Data pegawai akan dipindahkan ke recycle bin',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#fc6b3f',
            cancelButtonColor: '#9ca3af',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/employees/${id}`,
                    type: 'DELETE',
                    success: res => {
                        table.ajax.reload();
                        Swal.fire({ title: 'Terhapus!', text: res.message, icon: 'success', timer: 1500 });
                    },
                    error: xhr => {
                        Swal.fire({ icon: 'error', title: 'Error', text: xhr.responseJSON?.message || 'Gagal menghapus data' });
                    }
                });
            }
        });
    });

    // ─── Documents ────────────────────────────────────────────────────────────
    $('tbody').on('click', '.btn-documents', function() {
        const id = $(this).data('id');
        $.ajax({
            url: `/employees/${id}/documents`,
            type: 'GET',
            dataType: 'json',
            success: (response) => {
                let documentHtml = '';
                if (response.documents && response.documents.length > 0) {
                    documentHtml = `
                        <div class="space-y-2">
                            ${response.documents.map(doc => `
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200">
                                    <div class="flex items-center gap-3">
                                        <i class="fas fa-file-pdf text-red-500 text-lg"></i>
                                        <div>
                                            <p class="font-semibold text-secondary text-sm">${doc.original_filename || doc.path.split('/').pop()}</p>
                                            <p class="text-xs text-gray-500">${(doc.size / 1024).toFixed(2)} KB</p>
                                        </div>
                                    </div>
                                    <a href="/storage/${doc.path}" target="_blank" class="btn btn-primary text-xs px-3 py-2">
                                        <i class="fas fa-download mr-1"></i>Download
                                    </a>
                                </div>
                            `).join('')}
                        </div>
                    `;
                } else {
                    documentHtml = `<p class="text-gray-500 text-center py-8">Tidak ada dokumen yang diupload</p>`;
                }
                Swal.fire({
                    title: 'Dokumen Pegawai',
                    html: documentHtml,
                    icon: 'info',
                    confirmButtonColor: '#13a19c',
                    width: 500,
                });
            },
            error: (xhr) => {
                Swal.fire({ icon: 'error', title: 'Error', text: xhr.responseJSON?.message || 'Gagal memuat dokumen' });
            },
        });
    });

    // ─── Export Functionality ───────────────────────────────────────────────
    function handleExport(type) {
        // We target the employeeTable.
        // We ignore the columns: 0 (No), 1 (Foto), and last column (Aksi)
        // Also ignore the header filters (select/input elements)
        $('#employeeTable').tableExport({
            type: type,
            fileName: 'Data_Pegawai_' + moment().format('YYYYMMDD_HHmmss'),
            ignoreColumn: [0, 1, 15], // No, Foto, Aksi
            bootstrap: false,
            exportOptions: {
                onCellHtmlData: function(cell, row, col, data) {
                    // Extract text from cells that have HTML spans/labels
                    if (cell.is('td')) {
                        // For Gender, Status, and Working Status labels
                        if (row > 0) { // skip header
                           return (data && typeof data === 'string') ? data.replace(/<[^>]*>?/gm, '').trim() : data;
                        }
                    }
                    return data;
                }
            },
            jspdf: {
                format: 'a3',
                margins: {left: 10, right: 10, top: 20, bottom: 20},
                autotable: {
                    orientation: 'l',
                    styles: { valign: 'middle' },
                    tableWidth: 'wrap',
                    headerStyles: {fillColor: [19, 161, 156], textColor: 255, fontStyle: 'bold', halign: 'center'},
                    bodyStyles: {textColor: 50},
                    alternateRowStyles: {fillColor: 245}
                }
            }
        });
    }

    $('#btnExport').on('click', function() {
        const type = $('#exportFormat').val();
        if (type) {
            handleExport(type);
        }
    });

});
</script>
@endsection
