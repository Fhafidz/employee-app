<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>HRIS System - Employee Management</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { 
                extend: { 
                    colors: { 
                        primary: '#13a19c',      // Teal - Main brand color
                        accent: '#fc6b3f',       // Orange - Accent color
                        secondary: '#1f2937',    // Dark gray
                        light: '#f9fafb',        // Very light gray (background)
                        border: '#e5e7eb'        // Light border
                    },
                    fontFamily: {
                        sans: ['Mona Sans', 'Inter', 'system-ui', 'sans-serif'],
                        geist: ['Geist', 'sans-serif'],
                    },
                    boxShadow: {
                        soft: '0 1px 3px rgba(0, 0, 0, 0.1)',
                        card: '0 4px 6px rgba(0, 0, 0, 0.07)',
                    }
                } 
            }
        }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@100..900&family=Mona+Sans:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link href="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.2/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html {
            height: 100%;
            width: 100%;
        }
        body { 
            background-color: #f9fafb;
            color: #1f2937;
            font-family: 'Mona Sans', 'Inter', system-ui, sans-serif;
            line-height: 1.5;
            height: 100%;
            width: 100%;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }
        main {
            flex: 1 1 auto;
            width: 100%;
        }
        footer {
            flex-shrink: 0;
            width: 100%;
        }

        /* ===== FORM INPUTS ===== */
        .form-input, .form-input:focus {
            width: 100%; 
            padding: 0.625rem 0.75rem; 
            border: 1.5px solid #e5e7eb; 
            border-radius: 0.5rem;
            outline: none; 
            transition: all 0.2s ease;
            background-color: #ffffff;
            color: #1f2937;
            font-size: 0.95rem;
        }
        .form-input:focus { 
            border-color: #13a19c; 
            box-shadow: 0 0 0 3px rgba(19, 161, 156, 0.1);
        }
        .form-input::placeholder {
            color: #9ca3af;
        }

        /* ===== SELECT2 CUSTOMIZATION ===== */
        .select2-container--default .select2-selection--single {
            height: 42px; 
            border: 1.5px solid #e5e7eb; 
            border-radius: 0.5rem; 
            padding: 0.5rem;
            background-color: #ffffff;
        }
        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #13a19c;
            box-shadow: 0 0 0 3px rgba(19, 161, 156, 0.1);
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow { 
            height: 40px;
            color: #13a19c;
        }
        .select2-results__option--highlighted { 
            background-color: #13a19c !important; 
        }
        .select2-results__option--selected { 
            background-color: #e0f2f1 !important;
            color: #13a19c !important;
        }

        /* ===== FILTER IN HEADER COLUMN STYLING ===== */
        thead th .filter-select,
        thead th .select2-container {
            display: block;
            width: 100%;
            margin-top: 4px;
        }
        thead th .select2-container--default .select2-selection--single {
            height: 32px !important;
            border: 1.5px solid #d1d5db !important;
            border-radius: 0.375rem !important;
            padding: 0.2rem 0.4rem !important;
            background-color: rgba(255,255,255,0.9) !important;
            font-weight: 400 !important;
            text-transform: none !important;
            letter-spacing: 0 !important;
        }
        thead th .select2-container--default .select2-selection--single .select2-selection__rendered {
            font-size: 0.8rem !important;
            color: #4b5563 !important;
            line-height: 30px !important;
            padding-left: 8px !important;
        }
        thead th .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 30px !important;
        }
        thead th .select2-container--default.select2-container--focus .select2-selection--single,
        thead th .select2-container--default.select2-container--open .select2-selection--single {
            border-color: #13a19c !important;
            box-shadow: 0 0 0 2px rgba(19, 161, 156, 0.15) !important;
        }
        thead th .select2-container--default .select2-selection--single .select2-selection__clear {
            margin-right: 16px;
            font-size: 0.8rem;
            color: #9ca3af;
        }

        /* ===== DATERANGEPICKER FILTER IN HEADER ===== */
        .filter-daterange {
            display: block;
            width: 100%;
            padding: 0.35rem 1.6rem 0.35rem 0.6rem;
            font-size: 0.8rem;
            font-weight: 400;
            text-transform: none;
            letter-spacing: 0;
            color: #4b5563;
            background-color: rgba(255,255,255,0.9);
            border: 1.5px solid #d1d5db;
            border-radius: 0.375rem;
            cursor: pointer;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            outline: none;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
            min-width: 130px;
        }
        .filter-daterange:focus,
        .filter-daterange:hover {
            border-color: #13a19c;
            box-shadow: 0 0 0 2px rgba(19, 161, 156, 0.15);
        }
        .filter-daterange-clear {
            position: absolute;
            right: 4px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #9ca3af;
            font-size: 0.85rem;
            line-height: 1;
            cursor: pointer;
            padding: 0 2px;
            display: none;
        }
        .filter-daterange-clear:hover { color: #ef4444; }
        /* Show clear btn only when input has value */
        .filter-daterange:not(:placeholder-shown) + .filter-daterange-clear,
        .filter-daterange[value]:not([value=""]) + .filter-daterange-clear {
            display: block;
        }
        /* Daterangepicker dropdown z-index fix */
        .daterangepicker { z-index: 9999 !important; }
        .daterangepicker .ranges li.active,
        .daterangepicker td.active,
        .daterangepicker td.active:hover {
            background-color: #13a19c !important;
            border-color: #13a19c !important;
        }
        .daterangepicker td.in-range {
            background-color: #e0f2f1 !important;
            color: #1f2937 !important;
        }
        .daterangepicker .btn-primary,
        .daterangepicker .applyBtn {
            background-color: #13a19c !important;
            border-color: #13a19c !important;
        }
        .daterangepicker .btn-primary:hover,
        .daterangepicker .applyBtn:hover {
            background-color: #0e8a85 !important;
            border-color: #0e8a85 !important;
        }

        /* ===== DROPZONE CUSTOMIZATION ===== */
        #documentDropzone {
            pointer-events: auto !important;
            transition: border-color 0.2s, background-color 0.2s;
        }
        #documentDropzone.dz-drag-hover {
            border-color: #13a19c !important;
            background-color: rgba(19, 161, 156, 0.05) !important;
        }
        #documentDropzone.dz-started .dz-message {
            display: none;
        }

        /* ── Preview card ───────────────────────── */
        .dz-preview {
            position: relative;
            display: flex !important;
            align-items: center;
            gap: 0.75rem;
            width: 100%;
            margin: 6px 0 !important;
            padding: 0.7rem 1rem;
            background: #ffffff;
            border: 1.5px solid #e5e7eb;
            border-radius: 0.6rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .dz-preview:hover {
            border-color: #13a19c;
            box-shadow: 0 2px 8px rgba(19,161,156,0.12);
        }
        .dz-preview.dz-success { border-color: #10b981; }
        .dz-preview.dz-error   { border-color: #ef4444; }

        /* ── File icon ──────────────────────────── */
        .dz-preview .dz-image {
            width: 36px !important;
            height: 36px !important;
            flex-shrink: 0;
            border-radius: 0.375rem !important;
            overflow: hidden !important;
            background: #fef3c7;
            display: flex !important;
            align-items: center;
            justify-content: center;
        }
        .dz-preview .dz-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .dz-preview .dz-image::before {
            content: "\f1c1";
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            font-size: 1.1rem;
            color: #f59e0b;
        }

        /* ── File details ───────────────────────── */
        .dz-preview .dz-details {
            flex: 1;
            min-width: 0;
            padding: 0 !important;
            opacity: 1 !important;
            display: flex !important;
            flex-direction: column;
            gap: 1px;
        }
        .dz-preview .dz-filename {
            font-size: 0.82rem;
            font-weight: 600;
            color: #1f2937;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .dz-preview .dz-filename span {
            background: none !important;
            padding: 0 !important;
            border-radius: 0 !important;
        }
        .dz-preview .dz-size {
            font-size: 0.72rem;
            color: #9ca3af;
        }
        .dz-preview .dz-size strong { font-weight: 400; }

        /* ── Progress bar ───────────────────────── */
        .dz-preview .dz-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: #e5e7eb;
            border-radius: 0 0 0.6rem 0.6rem;
            overflow: hidden;
            opacity: 1 !important;
        }
        .dz-preview .dz-upload {
            display: block;
            height: 100%;
            background: linear-gradient(90deg, #13a19c, #0e8a85);
            transition: width 0.3s ease;
        }
        .dz-preview.dz-complete .dz-progress { opacity: 0 !important; transition: opacity 0.5s 0.5s; }

        /* ── Success / Error marks ──────────────── */
        .dz-preview .dz-success-mark,
        .dz-preview .dz-error-mark {
            display: none;
            flex-shrink: 0;
        }
        .dz-preview.dz-success .dz-success-mark { display: flex !important; }
        .dz-preview.dz-error   .dz-error-mark   { display: flex !important; }
        .dz-preview .dz-success-mark svg { width: 20px; color: #10b981; }
        .dz-preview .dz-error-mark   svg { width: 20px; color: #ef4444; }
        .dz-preview .dz-error-message { display: none !important; }

        /* ── Remove button ──────────────────────── */
        .dz-preview .dz-remove {
            flex-shrink: 0;
            font-size: 0;
            width: 28px;
            height: 28px;
            display: flex !important;
            align-items: center;
            justify-content: center;
            background: #fee2e2;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            text-decoration: none !important;
            transition: background 0.15s;
        }
        .dz-preview .dz-remove::before {
            content: "\f00d";
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            font-size: 0.7rem;
            color: #ef4444;
        }
        .dz-preview .dz-remove:hover { background: #fca5a5; }

        /* ── File type icon colours ─────────────── */
        .dz-preview[data-type="application/pdf"] .dz-image { background: #fee2e2; }
        .dz-preview[data-type="application/pdf"] .dz-image::before { content: "\f1c1"; color: #ef4444; }
        .dz-preview[data-type*="word"]            .dz-image { background: #dbeafe; }
        .dz-preview[data-type*="word"]            .dz-image::before { content: "\f1c2"; color: #3b82f6; }

        /* ===== FILEINPUTJS FIXES ===== */
        .kv-zoom-cache, .kv-zoom-body, .file-zoom-dialog {
            display: none !important;
        }
        .file-loading:before {
            content: none !important;
        }
        .file-input {
            width: 100% !important;
        }
        .file-preview {
            border: 1px solid #e5e7eb !important;
            border-radius: 0.5rem !important;
            margin-bottom: 0.5rem !important;
            padding: 5px !important;
            min-height: auto !important;
            max-height: 250px !important;
            overflow-y: auto !important;
        }
        /* Make preview image and frame much smaller */
        .krajee-default.file-preview-frame, 
        .krajee-default.file-preview-frame:hover {
            margin: 8px !important;
            padding: 10px !important;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05) !important;
            width: 130px !important;
            height: auto !important;
            border: 1.5px solid #e5e7eb !important;
            border-radius: 12px !important;
            float: none !important;
            display: inline-block !important;
            background: #ffffff !important;
        }
        .file-preview-image {
            width: 100% !important;
            height: 110px !important;
            object-fit: cover !important;
            border-radius: 8px !important;
        }
        .file-actions {
            margin-top: 10px !important;
            display: flex !important;
            justify-content: center !important;
        }
        /* Style for the delete/remove button in thumbnail */
        .kv-file-remove {
            color: #ffffff !important;
            background-color: #ef4444 !important; /* Solid red for better visibility */
            border: none !important;
            border-radius: 50% !important;
            width: 36px !important;
            height: 36px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            transition: all 0.2s ease !important;
            padding: 0 !important;
            box-shadow: 0 4px 6px rgba(239, 68, 68, 0.3) !important;
        }
        .kv-file-remove:hover {
            background-color: #dc2626 !important;
            transform: scale(1.15) rotate(90deg);
            box-shadow: 0 6px 12px rgba(239, 68, 68, 0.4) !important;
        }
        .kv-file-remove i, .kv-file-remove .fas {
            font-size: 16px !important;
        }
        .file-footer-caption {
            display: none !important;
        }
        .file-upload-indicator {
            display: none !important;
        }


        /* ===== ERROR MESSAGES ===== */
        .error { 
            color: #ef4444; 
            font-size: 0.875rem; 
            margin-top: 0.375rem; 
            display: block;
            font-weight: 500;
        }

        /* ===== BUTTON STYLES ===== */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.625rem 1.25rem;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: all 0.2s ease;
            cursor: pointer;
            border: none;
            font-size: 0.95rem;
        }
        .btn-primary {
            background-color: #13a19c;
            color: #ffffff;
        }
        .btn-primary:hover {
            background-color: #0d8378;
            box-shadow: 0 4px 6px rgba(19, 161, 156, 0.2);
            transform: translateY(-1px);
        }
        .btn-accent {
            background-color: #fc6b3f;
            color: #ffffff;
        }
        .btn-accent:hover {
            background-color: #e85a2c;
            box-shadow: 0 4px 6px rgba(252, 107, 63, 0.2);
        }

        /* ===== TABLES & CARDS ===== */
        .card {
            background-color: #ffffff;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            border: 1px solid #f0f0f0;
        }
        
        table thead {
            background-color: #f8fafc;
            color: #475569;
            font-weight: 600;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        table thead th {
            font-size: 0.75rem !important;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            border-bottom: 2px solid #f1f5f9 !important;
        }

        table tbody tr {
            border-bottom: 1px solid #f8fafc;
            transition: all 0.1s ease;
        }

        table tbody tr:hover {
            background-color: #fdfdfd !important;
            box-shadow: inset 4px 0 0 #13a19c;
        }

        /* Sticky Header Utility */
        .sticky-header thead {
            position: sticky;
            top: 0;
            z-index: 20;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .max-w-7xl { padding-left: 1rem; padding-right: 1rem; }
            .grid { grid-template-columns: 1fr; }
            .md\:col-span-2 { grid-column: span 1; }
        }
    </style>
</head>
<body class="bg-light text-secondary">

    <!-- ===== NAVBAR ===== -->
    <nav class="bg-white border-b border-border shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <a href="{{ route('employees.index') }}" class="flex items-center space-x-2 hover:opacity-80 transition">
                    <img src="/assets/biiscorp_logo.png" alt="BIIS Corp" class="h-8 w-auto">
                    <span class="text-xl font-bold text-primary tracking-tight">HRIS</span>
                </a>

                <!-- Desktop Nav Links -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('employees.index') }}" class="text-secondary hover:text-primary font-medium transition text-sm {{ request()->routeIs('employees.index') ? 'text-primary' : '' }}">
                        <i class="fas fa-users mr-1.5"></i>Pegawai
                    </a>
                    <a href="{{ route('employees.trashed') }}" class="text-secondary hover:text-primary font-medium transition text-sm {{ request()->routeIs('employees.trashed') ? 'text-primary' : '' }}">
                        <i class="fas fa-trash-alt mr-1.5"></i>Recycle Bin
                    </a>
                    <a href="#" class="text-secondary hover:text-primary font-medium transition text-sm">
                        <i class="fas fa-cog mr-1.5"></i>Pengaturan
                    </a>
                </div>

                <!-- Mobile Menu Toggle -->
                <button id="mobileMenuBtn" class="md:hidden text-secondary hover:text-primary p-2 focus:outline-none">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Nav Menu -->
        <div id="mobileMenu" class="hidden md:hidden bg-white border-t border-border px-4 pt-2 pb-4 space-y-1 shadow-lg">
            <a href="{{ route('employees.index') }}" class="block px-3 py-2.5 rounded-md text-base font-medium text-secondary hover:bg-light hover:text-primary {{ request()->routeIs('employees.index') ? 'bg-teal-50 text-primary' : '' }}">
                <i class="fas fa-users mr-3 w-5 text-center"></i>Pegawai
            </a>
            <a href="{{ route('employees.trashed') }}" class="block px-3 py-2.5 rounded-md text-base font-medium text-secondary hover:bg-light hover:text-primary {{ request()->routeIs('employees.trashed') ? 'bg-teal-50 text-primary' : '' }}">
                <i class="fas fa-trash-alt mr-3 w-5 text-center"></i>Recycle Bin
            </a>
            <a href="#" class="block px-3 py-2.5 rounded-md text-base font-medium text-secondary hover:bg-light hover:text-primary">
                <i class="fas fa-cog mr-3 w-5 text-center"></i>Pengaturan
            </a>
        </div>
    </nav>

    <!-- ===== MAIN CONTENT ===== -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-0 flex-1">
        @yield('content')
    </main>

    <!-- ===== FOOTER ===== -->
    <footer class="bg-secondary text-white py-6 border-t border-border">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm">
            <p>&copy; 2026 BIIS Corporation. All rights reserved. | HRIS System v1.0</p>
        </div>
    </footer>

    <!-- ===== SCRIPTS ===== -->
    <!-- Load jQuery first (synchronously) -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    <!-- Then load jQuery plugins -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.2/js/fileinput.min.js"></script>
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>

    <!-- Global Utilities -->
    <script src="{{ asset('assets/js/app-utils.js') }}"></script>
    
    <!-- Page-specific scripts -->
    @yield('scripts')
</body>
</html>