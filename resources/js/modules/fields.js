/**
 * Form Field Initializers
 * Handles Select2, DatePicker, and File Input initialization
 */

export const initializeSelect2 = () => {
    $(".select2").select2({
        placeholder: function () {
            return $(this).data("placeholder") || "Pilih opsi...";
        },
        width: "100%",
        allowClear: true,
        language: {
            noResults: () => "Tidak ada hasil",
        },
    });
};

export const initializeSelect2Tags = () => {
    $(".select2-tags").select2({
        placeholder: "Ketik atau pilih...",
        width: "100%",
        tags: true,
        tokenSeparators: [","],
        language: {
            noResults: () => "Tidak ada hasil",
        },
    });
};

export const initializeDatePicker = () => {
    $(".datepicker").daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        locale: {
            format: "DD-MM-YYYY",
        },
    });
};

export const initializeFileInputs = () => {
    $(".file-input").on("change", function () {
        if (this.files && this.files[0]) {
            const fileName = this.files[0].name;
            $(`#${this.id}-name`).text(fileName);
        }
    });
};

export const initializeAllFields = () => {
    initializeSelect2();
    initializeSelect2Tags();
    initializeDatePicker();
    initializeFileInputs();
};
