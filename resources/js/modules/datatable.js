/**
 * DataTable Employee List Handler
 * Manages employee data display with AJAX
 */

export const initializeEmployeeDataTable = (dataUrl, deleteUrl) => {
    const table = $("#employeeTable").DataTable({
        processing: true,
        serverSide: false,
        ajax: dataUrl,
        columns: [
            {
                data: null,
                className: "p-3 border-b text-center text-xs font-semibold",
                render: (data, type, row, meta) => meta.row + 1,
            },
            {
                data: "photo",
                className: "p-3 border-b text-center",
                orderable: false,
                render: function (data) {
                    if (data) {
                        return `<img src="/storage/employees/${data}" class="w-10 h-10 rounded-full object-cover mx-auto shadow-soft border-2 border-primary">`;
                    }
                    return `<div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary to-accent text-white flex items-center justify-center mx-auto font-bold shadow-soft"><i class="fas fa-user text-sm"></i></div>`;
                },
            },
            {
                data: "identity_number",
                className:
                    "p-3 border-b font-bold text-secondary whitespace-nowrap",
            },
            {
                data: "full_name",
                className: "p-3 border-b whitespace-nowrap",
                render: (data, type, row) => `
                    <div class="font-semibold text-secondary">${data}</div>
                    <div class="text-xs text-gray-500">${row.email}</div>
                `,
            },
            {
                data: "phone_number",
                className: "p-3 border-b whitespace-nowrap",
            },
            {
                data: "gender",
                className: "p-3 border-b whitespace-nowrap",
                render: (data) => {
                    const genderLabel =
                        data === "M" ? "Laki-laki" : "Perempuan";
                    const color =
                        data === "M"
                            ? "bg-blue-100 text-blue-700"
                            : "bg-pink-100 text-pink-700";
                    return `<span class="px-2 py-1 ${color} rounded-full text-xs font-semibold">${genderLabel}</span>`;
                },
            },
            {
                data: "date_of_birth",
                className: "p-3 border-b whitespace-nowrap",
            },
            {
                data: "address",
                className: "p-3 border-b",
                render: (data) =>
                    `<div class="truncate w-40 text-gray-700" title="${data}">${data}</div>`,
            },
            {
                data: "religion",
                className: "p-3 border-b whitespace-nowrap",
                render: (data) => {
                    const religions = {
                        islam: "Islam",
                        christianity_protestant: "Kristen Protestan",
                        catholic: "Katolik",
                        hindu: "Hindu",
                        buddhism: "Buddha",
                        confucianism: "Khonghucu",
                    };
                    return `<span class="text-sm text-gray-700">${religions[data] || data}</span>`;
                },
            },
            {
                data: "marital_status",
                className: "p-3 border-b whitespace-nowrap",
                render: (data) => {
                    const statuses = {
                        single: "Belum Menikah",
                        married: "Menikah",
                        divorced: "Cerai",
                    };
                    return `<span class="text-sm text-gray-700">${statuses[data] || data}</span>`;
                },
            },
            {
                data: "department",
                className:
                    "p-3 border-b whitespace-nowrap font-medium text-secondary",
            },
            {
                data: "position",
                className: "p-3 border-b whitespace-nowrap text-secondary",
            },
            {
                data: "working_status",
                className: "p-3 border-b whitespace-nowrap",
                render: (data) => {
                    const workStatus = {
                        full_time: "Full Time",
                        part_time: "Part Time",
                        contract: "Kontrak",
                        intern: "Magang",
                    };
                    const colors = {
                        full_time: "bg-green-100 text-green-700",
                        part_time: "bg-yellow-100 text-yellow-700",
                        contract: "bg-purple-100 text-purple-700",
                        intern: "bg-blue-100 text-blue-700",
                    };
                    return `<span class="px-2 py-1 ${colors[data] || "bg-gray-100"} rounded-full text-xs font-semibold">${workStatus[data] || data}</span>`;
                },
            },
            { data: "hired_date", className: "p-3 border-b whitespace-nowrap" },
            {
                data: "status",
                className: "p-3 border-b whitespace-nowrap",
                render: (data) =>
                    data === "active"
                        ? '<span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">Aktif</span>'
                        : '<span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold">Non-Aktif</span>',
            },
            {
                data: "id",
                className: "p-3 border-b text-center whitespace-nowrap",
                orderable: false,
                render: function (data) {
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
                },
            }
        ],
        language: {
            emptyTable: "Tidak ada data pegawai",
            info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ pegawai",
            infoEmpty: "Tidak ada pegawai",
            paginate: { previous: "< Sebelumnya", next: "Selanjutnya >" },
        },
    });

    // Delete handler
    $("tbody").on("click", ".btn-delete", function () {
        const id = $(this).data("id");
        handleDeleteEmployee(id, table);
    });

    // Documents handler
    $("tbody").on("click", ".btn-documents", function () {
        const id = $(this).data("id");
        handleViewDocuments(id);
    });

    return table;
};

const handleDeleteEmployee = (id, table) => {
    Swal.fire({
        title: "Hapus Data?",
        text: "Data pegawai akan dihapus secara permanen!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#fc6b3f",
        cancelButtonColor: "#9ca3af",
        confirmButtonText: "Ya, Hapus!",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/employees/${id}`,
                type: "DELETE",
                success: (res) => {
                    table.ajax.reload();
                    Swal.fire({
                        title: "Terhapus!",
                        text: res.message,
                        icon: "success",
                        timer: 1500,
                    });
                },
                error: (xhr) => {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text:
                            xhr.responseJSON?.message || "Gagal menghapus data",
                    });
                },
            });
        }
    });
};

const handleViewDocuments = (id) => {
    $.ajax({
        url: `/employees/${id}/documents`,
        type: "GET",
        dataType: "json",
        success: (response) => {
            let documentHtml = "";
            if (response.documents && response.documents.length > 0) {
                documentHtml = `
                    <div class="space-y-2">
                        ${response.documents.map((doc, idx) => `
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-file-pdf text-red-500 text-lg"></i>
                                    <div>
                                        <p class="font-semibold text-secondary text-sm">${doc.original_name || doc.file_path.split('/').pop()}</p>
                                        <p class="text-xs text-gray-500">${(doc.file_size / 1024).toFixed(2)} KB</p>
                                    </div>
                                </div>
                                <a href="/storage/${doc.file_path}" target="_blank" class="btn btn-primary text-xs px-3 py-2">
                                    <i class="fas fa-download mr-1"></i>Download
                                </a>
                            </div>
                        `).join("")}
                    </div>
                `;
            } else {
                documentHtml = `<p class="text-gray-500 text-center py-8">Tidak ada dokumen yang diupload</p>`;
            }

            Swal.fire({
                title: "Dokumen Pegawai",
                html: documentHtml,
                icon: "info",
                confirmButtonColor: "#13a19c",
                width: 500,
            });
        },
        error: (xhr) => {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: xhr.responseJSON?.message || "Gagal memuat dokumen",
            });
        },
    });
};
