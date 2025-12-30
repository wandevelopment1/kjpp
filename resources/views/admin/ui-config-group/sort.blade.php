<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Urutan</title>
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@iconify/iconify@3.0.0/dist/iconify.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css" rel="stylesheet">
    <style>
        :root {
            --bg-color: #ffffff;
            --text-color: #111827;
            --text-color-secondary: #374151;
            --text-color-muted: #6B7280;
            --card-bg: #ffffff;
            --border-color: #e5e7eb;
            --hover-bg: #f3f4f6;
            --table-bg: #ffffff;
            --table-hover: #f9fafb;
            --table-border: #e5e7eb;
            --badge-bg: #f3f4f6;
            --badge-text: #374151;
            --icon-color: #6B7280;
            --link-color: #2563EB;
            --link-hover: #1D4ED8;
            --danger-color: #DC2626;
            --danger-hover: #B91C1C;
        }

        [data-theme="dark"] {
            --bg-color: #1f2937;
            --text-color: #f3f4f6;
            --text-color-secondary: #d1d5db;
            --text-color-muted: #9ca3af;
            --card-bg: #374151;
            --border-color: #4b5563;
            --hover-bg: #4b5563;
            --table-bg: #374151;
            --table-hover: #4b5563;
            --table-border: #4b5563;
            --badge-bg: #4b5563;
            --badge-text: #f3f4f6;
            --icon-color: #9ca3af;
            --link-color: #60A5FA;
            --link-hover: #3B82F6;
            --danger-color: #EF4444;
            --danger-hover: #DC2626;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            transition: background-color 0.3s, color 0.3s;
        }

        .card {
            background-color: var(--card-bg);
            border-color: var(--border-color);
        }

        .card-title, h4, h5, h6 {
            color: var(--text-color);
        }

        .table {
            color: var(--text-color);
            background-color: var(--table-bg);
        }

        .table thead th {
            border-bottom-color: var(--table-border);
            background-color: var(--table-bg);
            color: var(--text-color);
        }

        .table td, .table th {
            border-top-color: var(--table-border);
            background-color: var(--table-bg);
            color: var(--text-color);
        }

        .table tbody tr:hover {
            background-color: var(--table-hover);
        }

        .alert-info {
            background-color: var(--hover-bg);
            border-color: var(--border-color);
            color: var(--text-color);
        }

        .badge {
            background-color: var(--badge-bg);
            color: var(--badge-text);
        }

        .badge.bg-success {
            background-color: #10B981 !important;
            color: white !important;
        }

        .badge.bg-danger {
            background-color: #EF4444 !important;
            color: white !important;
        }

        .text-neutral-900 { color: var(--text-color) !important; }
        .text-neutral-700 { color: var(--text-color-secondary) !important; }
        .text-neutral-500 { color: var(--text-color-muted) !important; }
        .text-primary-600 { color: var(--link-color) !important; }
        .cursor-move { cursor: move; }
        .radius-8 { border-radius: 8px; }
        .mt-24 { margin-top: 24px; }
        .p-24 { padding: 24px; }
        .gap-20 { gap: 20px; }
        .gap-16 { gap: 16px; }
        .gap-4 { gap: 4px; }
        .gap-3 { gap: 3px; }
        .gap-2 { gap: 2px; }
        .text-neutral-900 { color: var(--text-color); }
        .text-neutral-700 { color: var(--text-color); }
        .text-neutral-500 { color: var(--text-color); }
        .text-primary-600 { color: #2563EB; }
        .text-danger-600 { color: #DC2626; }
        .border-danger-600 { border-color: #DC2626; }
        .bg-hover-danger-200:hover { background-color: #FECACA; }
        .text-md { font-size: 14px; }
        .px-56 { padding-left: 56px; padding-right: 56px; }
        .py-11 { padding-top: 11px; padding-bottom: 11px; }
        .py-12 { padding-top: 12px; padding-bottom: 12px; }
        .w-100 { width: 100%; }
        .h-100 { height: 100%; }
        .object-fit-cover { object-fit: cover; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row gy-4">
            <div class="col-lg-12">
                <div class="card mt-24">
                    <div class="card-body p-24">
                        <div class="d-flex flex-column gap-20">
                            <!-- Header Section -->
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="fw-bold text-neutral-900">Urutan</h4>
                                <div>
                                    <a href="{{ route('admin.ui-config-group.index') }}" 
                                       class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-56 py-11 radius-8 text-decoration-none">
                                        Kembali
                                    </a>
                                </div>
                            </div>

                            <!-- Info Section -->
                            <div class="alert alert-info">
                                <div class="d-flex align-items-center gap-2">
                                    <iconify-icon icon="mdi:information" class="text-xl"></iconify-icon>
                                    <span>Drag and drop untuk mengubah urutan tampilan</span>
                                </div>
                            </div>

                            <!-- Sortable Table -->
                            <div class="table-responsive">
                                <table class="table bordered-table mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col" style="width: 50px">No</th>
                                            <th scope="col">Judul</th>
                                        </tr>
                                    </thead>
                                    <tbody id="sortable">
                                        @foreach($uiConfigGroups as $item)
                                        <tr data-id="{{ $item->id }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->title }}</td>
                                            <td>
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <iconify-icon icon="mdi:drag" class="text-xl text-neutral-500 cursor-move"></iconify-icon>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@iconify/iconify@3.0.0/dist/iconify.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>
    <script>
        // Dark/Light Mode Detection
        function setTheme() {
            if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                document.documentElement.setAttribute('data-theme', 'dark');
            } else {
                document.documentElement.setAttribute('data-theme', 'light');
            }
        }

        // Initial theme setup
        setTheme();

        // Listen for theme changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
            setTheme();
        });

        // Sortable functionality
        $(document).ready(function() {
            $("#sortable").sortable({
                update: function(event, ui) {
                    var order = [];
                    $("#sortable tr").each(function(index) {
                        order.push({
                            id: $(this).data("id"),
                            order: index + 1
                        });
                    });

                    $.ajax({
                        url: "{{ route('admin.ui-config-group.updateOrder') }}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            order: order
                        },
                        success: function(response) {
                            if(response.success) {
                                toastr.success("Urutan berhasil diperbarui");
                            } else {
                                toastr.error("Gagal memperbarui urutan");
                            }
                        },
                        error: function() {
                            toastr.error("Terjadi kesalahan saat memperbarui urutan");
                        }
                    });
                }
            });
        });
    </script>
</body>
</html> 