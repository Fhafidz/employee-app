/**
 * Form Submission Handler
 * Handles form submission, validation, and AJAX requests
 */

import { displayFieldErrors, clearErrors } from "./validation.js";
import { addDropzoneFilesToFormData } from "./dropzone.js";

export const initializeFormHandler = (
    formSelector,
    submitUrl,
    redirectUrl,
    options = {},
) => {
    const form = $(formSelector);
    const submitBtn = options.submitBtn || "#btnSimpan";
    const spinner = options.spinner || "#spinner";
    const useDropzone = options.useDropzone !== false;

    form.on("submit", function (e) {
        e.preventDefault();

        // Validate form client-side first
        if (!form.valid()) {
            return false;
        }

        clearErrors();
        const formData = new FormData(this);

        // Add Dropzone files if enabled
        if (useDropzone) {
            addDropzoneFilesToFormData(formData);
        }

        // Disable submit button
        $(submitBtn).prop("disabled", true);
        $(spinner).removeClass("hidden");

        // Submit via AJAX
        $.ajax({
            url: submitUrl,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                Swal.fire({
                    icon: "success",
                    title: "Berhasil!",
                    text: response.message,
                    timer: 1500,
                    showConfirmButton: false,
                }).then(() => {
                    window.location.href = redirectUrl;
                });
            },
            error: function (xhr) {
                $(submitBtn).prop("disabled", false);
                $(spinner).addClass("hidden");

                if (xhr.status === 422) {
                    // Validation errors
                    const errors = xhr.responseJSON.errors;
                    displayFieldErrors(errors);

                    // Show first error in toast
                    const firstError = Object.values(errors)[0][0];
                    Swal.fire({
                        icon: "error",
                        title: "Validasi Gagal",
                        text: firstError,
                        position: "top-end",
                        timer: 3000,
                    });
                } else {
                    // Server error
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text:
                            xhr.responseJSON?.message ||
                            "Terjadi kesalahan server",
                    });
                }
            },
        });
    });
};
