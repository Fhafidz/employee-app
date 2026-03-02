/**
 * Dropzone Document Upload Handler
 * Manages document file uploads with drag-n-drop
 */

let documentDropzone = null;

export const initializeDropzone = (url) => {
    Dropzone.autoDiscover = false;

    try {
        documentDropzone = new Dropzone("#documentDropzone", {
            url: url,
            paramName: "documents",
            maxFiles: 5,
            maxFilesize: 5, // MB
            acceptedFiles: ".pdf,.doc,.docx",
            autoProcessQueue: false,
            uploadMultiple: true,
            parallelUploads: 5,
            addRemoveLinks: true,
            dictRemoveFile: "Hapus",
            dictCancelUpload: "Batal",
        });

        console.log("Dropzone initialized successfully");
    } catch (e) {
        console.error("Dropzone initialization error:", e.message);
    }
};

/**
 * Get all accepted files from Dropzone
 */
export const getDropzoneFiles = () => {
    if (!documentDropzone) return [];
    return documentDropzone.getAcceptedFiles();
};

/**
 * Add Dropzone files to FormData
 */
export const addDropzoneFilesToFormData = (formData) => {
    const files = getDropzoneFiles();
    files.forEach((file) => {
        formData.append("documents[]", file);
    });
    return formData;
};
