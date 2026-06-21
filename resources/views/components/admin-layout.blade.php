<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin Dashboard' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        body { margin: 0; padding: 0; overflow-x: hidden; font-family: 'Segoe UI', sans-serif; }
        .wrapper { display: flex; width: 100%; min-height: 100vh; }
        
        #sidebar { width: 260px; flex-shrink: 0; transition: all 0.3s ease; overflow: hidden; }
        #sidebar.minimized { width: 70px; }
        
        #sidebar .nav-link { 
            color: #ffffff !important; 
            transition: all 0.3s ease; 
            margin-bottom: 8px; padding: 10px 20px; border-radius: 6px;
        }
        #sidebar .nav-link i { color: #ffffff !important; transition: all 0.3s ease; }

        #sidebar.minimized .sidebar-text { display: none !important; }
        #sidebar.minimized .nav-link { justify-content: center; padding: 10px 0; }
        
        #sidebar .nav-link:hover, #sidebar .nav-link.active { 
            background-color: #ffffff !important; 
            color: var(--bs-primary) !important; 
            font-weight: 600; 
        }
        
        #content { flex-grow: 1; background: #f8f9fa; display: flex; flex-direction: column; min-height: 100vh; }
        main { padding: 25px; flex-grow: 1; }
    </style>
</head>
<body>
    <div class="wrapper">
        <x-sidebar />
        
        <div id="content">
            <x-navbar />
            <main>{{ $slot }}</main>
            <x-footer />
        </div>
    </div>

    {{-- CDN Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    @stack('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Toggle Sidebar
            document.getElementById('sidebarToggle')?.addEventListener('click', () => {
                document.getElementById('sidebar').classList.toggle('minimized');
            });

            // 2. Active Link Handler
            const navLinks = document.querySelectorAll('#sidebar .nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    navLinks.forEach(item => item.classList.remove('active'));
                    this.classList.add('active');
                });
            });

            // 3. Initialize Tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (el) { return new bootstrap.Tooltip(el); });
        });
    </script>
</body>
</html>