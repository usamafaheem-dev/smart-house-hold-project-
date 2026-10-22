<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Smart Household') — Smart Household System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            --secondary-gradient: linear-gradient(135deg, #3b82f6 0%, #2dd4bf 100%);
            --bg-color: #f8fafc;
            --surface-color: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.05);
            --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03);
            --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.05), 0 4px 6px -2px rgba(0,0,0,0.025);
            --shadow-hover: 0 20px 25px -5px rgba(0,0,0,0.08), 0 10px 10px -5px rgba(0,0,0,0.04);
            --radius-md: 12px;
            --radius-lg: 16px;
        }

        html, body {
            overflow-x: hidden;
            max-width: 100vw;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            background-image: 
                radial-gradient(at 0% 0%, hsla(253,16%,7%,0.03) 0, transparent 50%), 
                radial-gradient(at 50% 0%, hsla(225,39%,30%,0.03) 0, transparent 50%), 
                radial-gradient(at 100% 0%, hsla(339,49%,30%,0.03) 0, transparent 50%);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            -webkit-font-smoothing: antialiased;
        }
        
        /* Enable Modern MPA View Transitions (Chrome 126+) */
        @view-transition {
            navigation: auto;
        }

        /* Fallback animations for elements */
        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeSlideInRight {
            from { opacity: 0; transform: translateX(30px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes fadeSlideInLeft {
            from { opacity: 0; transform: translateX(-30px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .animate-up { animation: fadeSlideUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .animate-right { animation: fadeSlideInRight 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .animate-left { animation: fadeSlideInLeft 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        
        h1, h2, h3, h4, h5, h6, .navbar-brand {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            max-width: 100vw;
            overflow-x: hidden;
        }

        /* Glassmorphism Navbar */
        .navbar-glass {
            background: rgba(255, 255, 255, 0.85) !important;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.03);
        }
        .navbar-glass .navbar-brand {
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 800;
        }
        .navbar-glass .nav-link {
            display: flex;
            align-items: center;
            white-space: nowrap;
            color: var(--text-muted) !important;
            font-weight: 500;
            font-size: 0.88rem;
            transition: all 0.2s ease;
            border-radius: 50rem;
            padding: 0.4rem 0.65rem !important;
            margin: 0 0.1rem;
        }
        .navbar-glass .nav-link:hover, .navbar-glass .nav-link.active {
            color: #4f46e5 !important;
            background: rgba(79, 70, 229, 0.08);
        }

        @media (max-width: 1400px) {
            .navbar-glass .nav-link {
                font-size: 0.82rem;
                padding: 0.35rem 0.5rem !important;
            }
        }
        
        /* Modern Cards */
        .card, .card-custom {
            background: var(--surface-color);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
        }
        .card-custom:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-hover);
        }
        
        /* Premium Glassmorphism */
        .glass-panel {
            background: rgba(255, 255, 255, 0.65);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.05);
        }
        .glass-card-primary {
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.05) 0%, rgba(124, 58, 237, 0.05) 100%);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(79, 70, 229, 0.2);
        }
        .glass-card-success {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.05) 0%, rgba(5, 150, 105, 0.05) 100%);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }
        .glass-banner {
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.85) 0%, rgba(124, 58, 237, 0.85) 100%);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 10px 30px rgba(79, 70, 229, 0.3);
            color: white;
        }
        .card-header {
            background-color: transparent;
            border-bottom: 1px solid var(--border-color);
            padding: 1.25rem 1.5rem;
            font-weight: 600;
            font-family: 'Outfit', sans-serif;
        }
        .card-body {
            padding: 1.5rem;
        }

        /* Buttons */
        .btn {
            border-radius: 8px;
            font-weight: 500;
            font-family: 'Outfit', sans-serif;
            padding: 0.5rem 1.25rem;
            transition: all 0.2s ease;
            letter-spacing: 0.01em;
        }
        .btn-primary {
            background: var(--primary-gradient);
            border: none;
            color: white;
            box-shadow: 0 4px 6px rgba(79, 70, 229, 0.25);
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #4338ca 0%, #6d28d9 100%);
            box-shadow: 0 6px 12px rgba(79, 70, 229, 0.35);
            transform: translateY(-1px);
            color: white;
        }
        .btn-outline-primary {
            color: #4f46e5;
            border-color: #4f46e5;
        }
        .btn-outline-primary:hover {
            background: #4f46e5;
            color: white;
            transform: translateY(-1px);
        }

        /* Badges */
        .badge {
            font-family: 'Inter', sans-serif;
            font-weight: 500;
            padding: 0.5em 0.8em;
            border-radius: 6px;
        }
        .role-badge-customer { background-color: rgba(13, 110, 253, 0.1); color: #0d6efd; border: 1px solid rgba(13, 110, 253, 0.2); }
        .role-badge-provider { background-color: rgba(13, 202, 240, 0.1); color: #087990; border: 1px solid rgba(13, 202, 240, 0.2); }
        .role-badge-admin { background-color: rgba(220, 53, 69, 0.1); color: #dc3545; border: 1px solid rgba(220, 53, 69, 0.2); }

        /* Tables */
        .table {
            vertical-align: middle;
        }
        .table th {
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            color: var(--text-muted);
            border-bottom: 2px solid var(--border-color);
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.05em;
        }
        .table td {
            color: var(--text-main);
            border-bottom: 1px solid var(--border-color);
        }

        /* Footer */
        footer {
            background-color: var(--surface-color);
            border-top: 1px solid var(--border-color) !important;
            color: var(--text-muted);
            font-family: 'Outfit', sans-serif;
            margin-top: auto;
        }
    </style>
    @stack('styles')
</head>
<body class="font-sans antialiased" style="min-height: 100vh; display: flex; flex-direction: column;">

    @if(!request()->routeIs('login') && !request()->routeIs('register'))
        <!-- Shared Dynamic Navigation Bar -->
        @include('layouts.navigation')
    @endif

    <!-- Main Body Container -->
    <main class="main-content">
        @yield('full-width-content')
        @if(!request()->routeIs('welcome') && !request()->is('/') && !request()->routeIs('login') && !request()->routeIs('register'))
        <div class="{{ request()->routeIs('admin.*') ? 'container-fluid px-lg-4' : 'container' }} py-4">
            <!-- Flash Alert Notifications -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <strong><i class="fas fa-exclamation-circle me-2"></i>Validation Error:</strong>
                    <ul class="mb-0 mt-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
        @else
            @yield('content')
        @endif
    </main>

    <!-- Footer -->
    @if(!request()->routeIs('login') && !request()->routeIs('register'))
    <footer class="text-center py-2 border-top">
        <div class="container">
            <small style="font-size: 0.85rem;">&copy; {{ date('Y') }} Smart Household and Home Services Management System. All rights reserved.</small>
        </div>
    </footer>
    @endif

    <!-- jQuery & Bootstrap 5 JS Bundle -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/auth-validation.js') }}"></script>
    
    <script>
        $(document).ready(function() {
            // Auto-dismiss notification popups after 2.5 seconds with smooth slideUp and fadeOut
            function autoDismissAlerts(container) {
                const target = container ? $(container).find('.alert-dismissible') : $('.alert-dismissible');
                setTimeout(function() {
                    target.fadeOut(500, function() {
                        $(this).slideUp(300, function() {
                            $(this).remove();
                        });
                    });
                }, 2500);
            }

            // Trigger for initial page load flash alerts
            autoDismissAlerts();

            // Auto-dismiss dynamic AJAX alerts whenever they are inserted
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.addedNodes.length) {
                        mutation.addedNodes.forEach(function(node) {
                            if ($(node).hasClass('alert-dismissible') || $(node).find('.alert-dismissible').length) {
                                autoDismissAlerts(node);
                            }
                        });
                    }
                });
            });

            const mainContent = document.querySelector('main');
            if (mainContent) {
                observer.observe(mainContent, { childList: true, subtree: true });
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
