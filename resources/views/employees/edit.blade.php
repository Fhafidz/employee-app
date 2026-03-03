@extends('layouts.app')

@section('content')
<div class="card max-w-4xl mx-auto">
    <div class="px-6 py-5 border-b border-border bg-white flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <h3 class="text-xl font-bold text-secondary flex items-center">
            <i class="fas fa-user-edit mr-3 text-primary text-2xl"></i>Edit Pegawai
        </h3>
        <a href="{{ route('employees.index') }}" class="inline-flex items-center text-primary hover:opacity-80 transition font-medium text-sm">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>
    
    <div class="p-6">
        <form id="employeeForm" enctype="multipart/form-data">
            <input type="hidden" name="_method" value="PUT">
            
            <!-- Personal Info Section -->
            <div class="mb-8">
                <h4 class="text-lg font-bold text-secondary mb-4 pb-3 border-b-2 border-primary flex items-center">
                    <i class="fas fa-id-card mr-2 text-primary"></i> Informasi Pribadi
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-form-input name="identity_number" label="NIK" placeholder="Masukkan nomor identitas" maxlength="16" :value="$employee->identity_number" required />
                    <x-form-input name="full_name" label="Nama Lengkap" placeholder="Masukkan nama lengkap" :value="$employee->full_name" required />
                    <x-form-input name="email" label="Email" type="email" placeholder="Masukkan email" :value="$employee->email" required />
                    <x-form-input name="phone_number" label="No. Telepon" placeholder="Masukkan nomor telepon" :value="$employee->phone_number" required />
                    <x-form-select name="gender" label="Jenis Kelamin" :options="$genders" :value="$employee->gender" required />
                    <x-form-input name="date_of_birth" label="Tanggal Lahir" type="text" class="datepicker" placeholder="DD-MM-YYYY" :value="$employee->date_of_birth" required />
                    <div class="md:col-span-2">
                        <x-form-textarea name="address" label="Alamat" placeholder="Masukkan alamat lengkap" :value="$employee->address" required />
                    </div>
                    <x-form-select name="religion" label="Agama" :options="$religions" :value="$employee->religion" required />
                    <x-form-select name="marital_status" label="Status Pernikahan" :options="$maritalStatuses" :value="$employee->marital_status" required />
                </div>
            </div>

            <!-- Work Info Section -->
            <div class="mb-8">
                <h4 class="text-lg font-bold text-secondary mb-4 pb-3 border-b-2 border-primary flex items-center">
                    <i class="fas fa-briefcase mr-2 text-primary"></i> Informasi Pekerjaan
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-form-select name="department" label="Departemen" :options="$departments" :value="$employee->department" taggable required />
                    <x-form-select name="position" label="Jabatan" :options="$positions" :value="$employee->position" taggable required />
                    <x-form-select name="working_status" label="Status Pekerjaan" :options="$workingStatuses" :value="$employee->working_status" required />
                    <x-form-select name="status" label="Status Karyawan" :options="$statuses" :value="$employee->status" required />
                    <x-form-input name="hired_date" label="Tgl Bergabung" type="text" class="datepicker" placeholder="DD-MM-YYYY" :value="$employee->hired_date" required />
                </div>
            </div>

            <!-- Media Section -->
            <div class="mb-6">
                <h4 class="text-lg font-bold text-secondary mb-4 pb-3 border-b-2 border-primary flex items-center">
                    <i class="fas fa-images mr-2 text-primary"></i> Media & Dokumen
                </h4>
                <div class="grid grid-cols-1 gap-6">
                    <x-form-file name="photo" label="Foto Profil" accept=".jpg,.jpeg,.png" help="Format: JPG, PNG (Maks 5MB)" :value="$employee->photo" />
                    <div class="form-group">
                        <label class="block text-sm font-semibold text-secondary mb-2">Dokumen Lampiran</label>
                        <div id="documentDropzone" class="dz-container border-2 border-dashed border-primary rounded-lg bg-light hover:bg-gray-50 flex items-center justify-center min-h-[160px] cursor-pointer transition p-6 text-center">
                            <div class="dz-message text-center">
                                <i class="fas fa-cloud-upload-alt text-3xl text-primary mb-2 block"></i>
                                <p class="text-secondary font-semibold">Tarik & Lepas dokumen di sini</p>
                                <p class="text-sm text-gray-500">atau klik untuk memilih (PDF, DOC, DOCX)</p>
                            </div>
                        </div>
                        <span class="error hidden" data-field="documents"></span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 pt-6 border-t border-border">
                <a href="{{ route('employees.index') }}" class="btn border-2 border-border text-secondary hover:bg-light w-full sm:w-auto">
                    <i class="fas fa-times mr-2"></i> Batal
                </a>
                <button type="submit" id="btnSimpan" class="btn btn-primary w-full sm:w-auto">
                    <span id="spinner" class="hidden"><i class="fas fa-spinner fa-spin mr-2"></i></span>
                    <i class="fas fa-save mr-2"></i> Perbarui Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    let employee = @json($employee);

    // Populate selects (AppUtils handles Select2 init globally)
    $('select[name="gender"]').val(employee.gender).trigger('change');
    $('select[name="religion"]').val(employee.religion).trigger('change');
    $('select[name="marital_status"]').val(employee.marital_status).trigger('change');
    $('select[name="department"]').val(employee.department).trigger('change');
    $('select[name="position"]').val(employee.position).trigger('change');
    $('select[name="working_status"]').val(employee.working_status).trigger('change');
    $('select[name="status"]').val(employee.status).trigger('change');

    // Initialize Dropzone
    Dropzone.autoDiscover = false;
    let documentDropzone;
    try {
        documentDropzone = new Dropzone("#documentDropzone", {
            url: "/employees/" + employee.id,
            paramName: "documents",
            maxFiles: 5,
            maxFilesize: 5,
            acceptedFiles: ".pdf,.doc,.docx",
            autoProcessQueue: false,
            uploadMultiple: true,
            parallelUploads: 5,
            addRemoveLinks: true,
            dictRemoveFile: "Hapus"
        });
    } catch(e) {}

    // Form Validation
    $('#employeeForm').validate({
        rules: {
            identity_number: { required: true, minlength: 16 },
            full_name: { required: true },
            email: { required: true, email: true },
            phone_number: { required: true },
            gender: { required: true },
            date_of_birth: { required: true },
            address: { required: true },
            religion: { required: true },
            marital_status: { required: true },
            department: { required: true },
            position: { required: true },
            working_status: { required: true },
            status: { required: true },
            hired_date: { required: true }
        },
        messages: {
            identity_number: { required: "NIK harus diisi", minlength: "NIK minimal 16 karakter" },
            full_name: "Nama lengkap harus diisi",
            email: { required: "Email harus diisi", email: "Format email tidak valid" },
            phone_number: "No. telepon harus diisi",
            gender: "Jenis kelamin harus dipilih",
            date_of_birth: "Tanggal lahir harus diisi",
            address: "Alamat harus diisi",
            religion: "Agama harus dipilih",
            marital_status: "Status pernikahan harus dipilih",
            department: "Departemen harus diisi",
            position: "Jabatan harus diisi",
            working_status: "Status pekerjaan harus dipilih",
            status: "Status karyawan harus dipilih",
            hired_date: "Tanggal bergabung harus diisi"
        },
        submitHandler: function(form, event) {
            event.preventDefault();
            let formData = new FormData(form);
            
            if (documentDropzone) {
                documentDropzone.getAcceptedFiles().forEach(file => formData.append('documents[]', file));
            }

            AppUtils.submitForm({
                formId: '#employeeForm',
                url: '/employees/' + employee.id,
                formData: formData,
                redirectUrl: '{{ route('employees.index') }}',
                successMessage: 'Data pegawai berhasil diperbarui!'
            });
        }
    });
});
</script>
@endsection