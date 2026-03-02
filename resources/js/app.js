import "./bootstrap";
import "../css/app.css";

/**
 * Initialize all modules based on page context
 */
document.addEventListener("DOMContentLoaded", () => {
    // Dynamically import modules only when needed
    const currentPath = window.location.pathname;

    // Employee list page
    if (
        currentPath.includes("/employees") &&
        !currentPath.includes("create") &&
        !currentPath.includes("edit")
    ) {
        import("./modules/datatable.js").then(
            ({ initializeEmployeeDataTable }) => {
                initializeEmployeeDataTable(
                    '{{ route("employees.data") }}',
                    '{{ route("employees.destroy", ":id") }}',
                );
            },
        );
    }

    // Employee create/edit page
    if (currentPath.includes("create") || currentPath.includes("edit")) {
        import("./modules/fields.js").then(({ initializeAllFields }) => {
            initializeAllFields();
        });

        import("./modules/dropzone.js").then(({ initializeDropzone }) => {
            const url = currentPath.includes("create")
                ? '{{ route("employees.store") }}'
                : '{{ route("employees.update", ":id") }}';
            initializeDropzone(url);
        });

        import("./modules/form-handler.js").then(
            ({ initializeFormHandler }) => {
                const submitUrl = currentPath.includes("create")
                    ? '{{ route("employees.store") }}'
                    : '{{ route("employees.update", ":id") }}';
                const redirectUrl = '{{ route("employees.index") }}';

                initializeFormHandler("#employeeForm", submitUrl, redirectUrl);
            },
        );

        import("./modules/validation.js").then(
            ({ formRules, formMessages }) => {
                $("#employeeForm").validate({
                    rules: formRules,
                    messages: formMessages,
                });
            },
        );
    }
});
