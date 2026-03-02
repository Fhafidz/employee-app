/**
 * Form Validation & Error Handling
 * Provides validation rules and error display
 */

export const formRules = {
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
    hired_date: { required: true },
};

export const formMessages = {
    identity_number: {
        required: "NIK harus diisi",
        minlength: "NIK minimal 16 karakter",
    },
    full_name: "Nama lengkap harus diisi",
    email: {
        required: "Email harus diisi",
        email: "Format email tidak valid",
    },
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
    hired_date: "Tanggal bergabung harus diisi",
};

/**
 * Display server-side validation errors per field
 */
export const displayFieldErrors = (errors) => {
    // Clear previous errors
    $(".error").addClass("hidden").text("");
    $(".form-input, .select2, select, textarea").removeClass("border-red-500");

    // Display new errors
    Object.keys(errors).forEach((field) => {
        const errorElement = $(`.error[data-field="${field}"]`);
        const input = $(`[name="${field}"]`);

        if (errorElement.length) {
            errorElement.text(errors[field][0]).removeClass("hidden");
            input.addClass("border-red-500");
        }
    });
};

/**
 * Clear all field errors
 */
export const clearErrors = () => {
    $(".error").addClass("hidden").text("");
    $(".form-input, .select2, select, textarea").removeClass("border-red-500");
};
