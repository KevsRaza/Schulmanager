<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Schulemanager' }}</title>

    <!-- Font Awesome & Tailwind -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>


    @livewireStyles

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .stat-card {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            border-radius: 12px;
            padding: 1.5rem;
        }

        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.04);
            border: 1px solid #e2e8f0;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            border: none;
        }

        .btn-secondary {
            background: white;
            color: #64748b;
            border: 1px solid #e2e8f0;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
        }

        .badge-blue {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-green {
            background: #dcfce7;
            color: #166534;
        }

        .badge-purple {
            background: #e9d5ff;
            color: #7e22ce;
        }

        .badge-yellow {
            background: #fef3c7;
            color: #92400e;
        }

    </style>
</head>

<body class="bg-gray-50 min-h-screen p-6">

    <!-- Header global -->
    <header class="mb-6">
        <div class="max-w-7xl mx-auto flex justify-between items-center bg-gray-200 p-4 rounded-md">
            <h1 class="text-2xl font-bold text-gray-900">{{ $title ?? 'SCHULEMANAGER' }}</h1>
            <nav class="flex gap-4">
                <a href="{{ route('dashboard') }}" class="text-blue-600 font-medium">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-blue-600 font-medium">Logout</button>
                </form>
            </nav>
        </div>
    </header>


    <!-- Contenu principal -->
    <main class="max-w-7xl mx-auto">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="mt-12 text-center text-gray-500 text-sm">
        &copy; {{ date('Y') }} PS-Solutions4You UG • Powered by Innovation
    </footer>

    @livewireScripts
</body>

</html>