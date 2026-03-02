/**
 * Global Employee App Utilities
 * Handle common UI initializations and AJAX wrappers
 */

const AppUtils = {
    // ─── UI Init Helpers ───────────────────────────────────────────────────

    /**
     * Initialize Select2 for standard and tagged selects
     */
    initSelect2: function () {
        if ($.fn.select2) {
            $(".select2").select2({
                placeholder: "Pilih opsi...",
                width: "100%",
                allowClear: true,
            });

            $(".select2-tags").select2({
                placeholder: "Ketik atau pilih...",
                width: "100%",
                tags: true,
                tokenSeparators: [","],
            });

            // Header table filters
            $(".filter-select").each(function () {
                if ($(this).hasClass("select2-hidden-accessible")) {
                    $(this).select2("destroy");
                }
                $(this).select2({
                    placeholder: $(this).data("placeholder") || "Pilih...",
                    allowClear: true,
                    width: "100%",
                    minimumResultsForSearch:
                        $(this).data("minimum-results-for-search") || -1,
                    dropdownParent: $("body"),
                });
            });
        }
    },

    /**
     * Initialize standard Datepicker
     */
    initDatepicker: function () {
        if ($.fn.daterangepicker) {
            $(".datepicker").daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                autoUpdateInput: true,
                locale: { format: "DD-MM-YYYY" },
            });
        }
    },

    /**
     * Initialize Bootstrap FileInput (FileInputJS)
     */
    initFileInput: function () {
        if ($.fn.fileinput) {
            $(".file-input-js").each(function () {
                const initialPreview = $(this).data("initial-preview");
                const config = {
                    theme: "fas",
                    showUpload: false,
                    showRemove: false,
                    browseClass: "btn btn-primary",
                    browseLabel: "Pilih Foto",
                    browseIcon: '<i class="fas fa-camera mr-2"></i>',
                    previewFileType: "any",
                    allowedFileExtensions: ["jpg", "jpeg", "png"],
                    maxFileSize: 5120, // 5MB
                };

                if (initialPreview) {
                    config.initialPreview = [initialPreview];
                    config.initialPreviewAsData = true;
                }

                config.fileActionSettings = {
                    showRemove: true,
                    showUpload: false,
                    showDownload: false,
                    showZoom: false,
                    removeIcon: '<i class="fas fa-times"></i>',
                    removeClass: "btn btn-sm btn-kv kv-file-remove",
                    removeTitle: "Hapus foto",
                };

                $(this).fileinput(config);
            });
        }
    },

    // ─── Shared AJAX Handlers ──────────────────────────────────────────────

    /**
     * Submit form via AJAX with SweetAlert feedback
     */
    submitForm: function (options) {
        const {
            formId,
            url,
            method = "POST",
            formData = null,
            btnSubmitId = "#btnSimpan",
            spinnerId = "#spinner",
            redirectUrl = null,
            successMessage = "Data berhasil disimpan!",
            onSuccess = null,
        } = options;

        const form = $(formId);
        const data = formData || new FormData(form[0]);
        const btnSubmit = $(btnSubmitId);
        const spinner = $(spinnerId);

        btnSubmit.prop("disabled", true);
        spinner.removeClass("hidden");

        $.ajax({
            url: url,
            type: method,
            data: data,
            processData: false,
            contentType: false,
            success: function (response) {
                Swal.fire({
                    icon: "success",
                    title: "Berhasil!",
                    text: response.message || successMessage,
                    timer: 1500,
                    showConfirmButton: false,
                }).then(() => {
                    if (onSuccess) {
                        onSuccess(response);
                    } else if (redirectUrl) {
                        window.location.href = redirectUrl;
                    }
                });
            },
            error: function (xhr) {
                btnSubmit.prop("disabled", false);
                spinner.addClass("hidden");

                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    let msg = Object.values(errors)[0][0];
                    Swal.fire({
                        icon: "error",
                        title: "Validasi Gagal",
                        text: msg,
                    });
                } else {
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
    },
};

// Initialize common elements on load
$(document).ready(function () {
    AppUtils.initSelect2();
    AppUtils.initDatepicker();
    AppUtils.initFileInput();

    // Mobile menu toggle logic
    $("#mobileMenuBtn").on("click", function () {
        $("#mobileMenu").toggleClass("hidden");
    });

    // Auto-setup CSRF for all AJAX
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
});
