<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Gestion Établissement</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); min-height: 100vh; color: #334155; }
        .card { background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.04); transition: all 0.3s ease; border: 1px solid #e2e8f0; }
        .card:hover { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08); transform: translateY(-2px); }
        .stat-card { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(37, 99, 235, 0.2); }
        .btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; border-radius: 8px; font-weight: 500; transition: all 0.2s ease; cursor: pointer; }
        .btn-primary { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; border: none; }
        .btn-primary:hover { background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); transform: translateY(-1px); box-shadow: 0 4px 6px rgba(37, 99, 235, 0.2); }
        .btn-secondary { background: white; color: #64748b; border: 1px solid #e2e8f0; }
        .btn-secondary:hover { background: #f8fafc; border-color: #cbd5e1; }
        .badge { display: inline-flex; align-items: center; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem; font-weight: 500; }
        .badge-blue { background: #dbeafe; color: #1e40af; }
        .badge-green { background: #dcfce7; color: #166534; }
        .badge-yellow { background: #fef3c7; color: #92400e; }
        .badge-purple { background: #e9d5ff; color: #7e22ce; }
        .badge-red { background: #fee2e2; color: #b91c1c; }
        .table { width: 100%; border-collapse: separate; border-spacing: 0; }
        .table th { background: #f8fafc; padding: 0.75rem 1.5rem; text-align: left; font-weight: 600; color: #64748b; border-bottom: 1px solid #e2e8f0; }
        .table td { padding: 1rem 1.5rem; border-bottom: 1px solid #e2e8f0; }
        .table tr:last-child td { border-bottom: none; }
        .nav-tab { display: flex; align-items: center; gap: 0.5rem; padding: 1rem 1.5rem; border-bottom: 2px solid transparent; font-weight: 500; color: #64748b; transition: all 0.2s ease; cursor: pointer; }
        .nav-tab.active { color: #2563eb; border-bottom-color: #2563eb; }
        .nav-tab:hover { color: #334155; }
        .search-input { position: relative; }
        .search-input i { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #94a3b8; }
        .search-input input { padding-left: 2.5rem; border-radius: 8px; border: 1px solid #e2e8f0; width: 100%; height: 2.5rem; }
        .search-input input:focus { outline: none; border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15); }
        .pagination-btn { padding: 0.5rem 0.75rem; border-radius: 6px; border: 1px solid #e2e8f0; background: white; color: #64748b; font-weight: 500; cursor: pointer; transition: all 0.2s ease; }
        .pagination-btn:hover { background: #f8fafc; }
        .pagination-btn.active { background: #dbeafe; color: #2563eb; border-color: #dbeafe; }
        .pagination-btn:disabled { opacity: 0.5; cursor: not-allowed; }
    </style>
</head>
<body class="p-6">
    <div>
        <div class="max-w-7xl mx-auto space-y-6">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
                    <p class="text-gray-600">Gestion complète de votre établissement</p>
                </div>
                <div class="flex gap-2">
                    <button class="btn btn-secondary" wire:click="exportData">
                        <i class="fas fa-download"></i> Export
                    </button>
                    <button class="btn btn-primary" wire:click="createNewDossier">
                        <i class="fas fa-plus"></i> Nouveau Dossier
                    </button>
                </div>
            </div>

            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="stat-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm">Total Dossiers</p>
                            <p class="text-2xl font-bold">{{ count($folders) }}</p>
                        </div>
                        <i class="fas fa-folder text-white text-2xl opacity-80"></i>
                    </div>
                </div>

                <div class="card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm">Élèves</p>
                            <p class="text-2xl font-bold text-gray-900">{{ array_sum(array_column(array_column($folders, 'stats'), 'élèves')) }}</p>
                        </div>
                        <i class="fas fa-users text-blue-500 text-2xl"></i>
                    </div>
                </div>

                <div class="card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm">Écoles</p>
                            <p class="text-2xl font-bold text-gray-900">{{ array_sum(array_column(array_column($folders, 'stats'), 'écoles')) }}</p>
                        </div>
                        <i class="fas fa-school text-green-500 text-2xl"></i>
                    </div>
                </div>

                <div class="card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm">Entreprises</p>
                            <p class="text-2xl font-bold text-gray-900">{{ array_sum(array_column(array_column($folders, 'stats'), 'entreprises')) }}</p>
                        </div>
                        <i class="fas fa-building text-purple-500 text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Tabs Navigation -->
            <div class="card">
                <div class="border-b border-gray-200">
                    <nav class="flex -mb-px">
                        @foreach($tabs as $tab)
                            <button wire:click="setActiveTab('{{ $tab }}')" class="nav-tab {{ $activeTab === $tab ? 'active' : '' }}">
                                @if($tab === 'Dossiers')<i class="fas fa-folder"></i>
                                @elseif($tab === 'Élèves')<i class="fas fa-users"></i>
                                @elseif($tab === 'Entreprises')<i class="fas fa-building"></i>
                                @elseif($tab === 'Écoles')<i class="fas fa-school"></i>@endif
                                {{ $tab }}
                            </button>
                        @endforeach
                    </nav>
                </div>

                <!-- Tab Content -->
                <div class="p-6">
                    @if($activeTab === 'Dossiers')
                        <!-- Search and Filters -->
                        <div class="flex flex-col sm:flex-row gap-4 mb-6">
                            <div class="flex-1">
                                <div class="search-input">
                                    <i class="fas fa-search"></i>
                                    <input type="text" placeholder="Rechercher un dossier..." wire:model.debounce.300ms="search">
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button class="btn btn-secondary"><i class="fas fa-filter"></i> Filtres</button>
                                <button class="btn btn-secondary"><i class="fas fa-sort"></i> Trier</button>
                            </div>
                        </div>

                        <!-- Dossiers Table -->
                        <div class="overflow-hidden rounded-lg">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Nom du Dossier</th><th>Documents</th><th>Formations</th><th>Écoles</th><th>Entreprises</th><th>Élèves</th><th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($filteredFolders as $folder)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td>
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-folder text-blue-600"></i>
                                                </div>
                                                <div>
                                                    <div class="font-medium text-gray-900">{{ $folder['name'] }}</div>
                                                    {{-- <div class="text-sm text-gray-500">ID: {{ $folder['id'] }}</div> --}}
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge badge-blue">{{ $folder['stats']['documents'] }}</span></td>
                                        <td><span class="badge badge-green">{{ $folder['stats']['formations'] }}</span></td>
                                        <td><span class="badge badge-yellow">{{ $folder['stats']['écoles'] }}</span></td>
                                        <td><span class="badge badge-purple">{{ $folder['stats']['entreprises'] }}</span></td>
                                        <td><span class="badge badge-red">{{ $folder['stats']['élèves'] }}</span></td>
                                        <td>
                                            <div class="flex gap-2">
                                                <button class="text-blue-600 hover:text-blue-800" wire:click="viewFolder({{ $folder['id'] }})"><i class="fas fa-eye"></i></button>
                                                <button class="text-green-600 hover:text-green-800" wire:click="editFolder({{ $folder['id'] }})"><i class="fas fa-edit"></i></button>
                                                <button class="text-red-600 hover:text-red-800" wire:click="deleteFolder({{ $folder['id'] }})" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce dossier?')"><i class="fas fa-trash"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-8 text-center">
                                            <div class="text-gray-500">
                                                <i class="fas fa-folder-open text-4xl mb-4"></i>
                                                <p class="font-medium">Aucun dossier trouvé</p>
                                                <p class="text-sm mt-1">Commencez par créer votre premier dossier</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($totalPages > 0)
                        <div class="flex justify-between items-center mt-6">
                            <div class="text-sm text-gray-600">
                                Page {{ $currentPage }} sur {{ $totalPages }} • {{ count($filteredFolders) }} dossier(s) affiché(s)
                            </div>
                            <div class="flex gap-2">
                                <button class="pagination-btn" wire:click="previousPage" {{ $currentPage == 1 ? 'disabled' : '' }}>Previous</button>
                                @for($i = 1; $i <= $totalPages; $i++)
                                    <button class="pagination-btn {{ $currentPage == $i ? 'active' : '' }}" wire:click="goToPage({{ $i }})">{{ $i }}</button>
                                @endfor
                                <button class="pagination-btn" wire:click="nextPage" {{ $currentPage == $totalPages ? 'disabled' : '' }}>Next</button>
                            </div>
                        </div>
                        @endif

                    @elseif($activeTab === 'Élèves')
                        <!-- Search and Filters for Students -->
                        <div class="flex flex-col sm:flex-row gap-4 mb-6">
                            <div class="flex-1">
                                <div class="search-input">
                                    <i class="fas fa-search"></i>
                                    <input type="text" placeholder="Rechercher un élève, école ou email..." wire:model.debounce.300ms="studentSearch">
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button class="btn btn-secondary"><i class="fas fa-filter"></i> Filtres</button>
                                <button class="btn btn-secondary"><i class="fas fa-sort"></i> Trier</button>
                            </div>
                        </div>

                        <!-- Students Table -->
                        <div class="overflow-hidden rounded-lg">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Élève</th>
                                        <th>Email</th>
                                        <th>École</th>
                                        <th>Entreprise</th>
                                        <th>Formation</th>
                                        <th>Niveau Allemand</th>
                                        <th>Période</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($filteredSchulers as $schuler)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td>
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-user text-blue-600"></i>
                                                </div>
                                                <div>
                                                    <div class="font-medium text-gray-900">{{ $schuler['prenom'] }} {{ $schuler['nom'] }}</div>
                                                    <div class="text-sm text-gray-500">ID: {{ $schuler['id'] }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-sm text-gray-600">{{ $schuler['email'] }}</td>
                                        <td>
                                            <span class="badge badge-blue">
                                                <i class="fas fa-school mr-1"></i> {{ $schuler['ecole'] }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-green">
                                                <i class="fas fa-building mr-1"></i> {{ $schuler['entreprise'] }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-yellow">
                                                <i class="fas fa-book mr-1"></i> {{ $schuler['formation'] }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-purple">{{ $schuler['niveau_allemand'] }}</span>
                                        </td>
                                        <td class="text-sm text-gray-600">
                                            {{ \Carbon\Carbon::parse($schuler['date_debut'])->format('d/m/Y') }} -
                                            {{ \Carbon\Carbon::parse($schuler['date_fin'])->format('d/m/Y') }}
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-8 text-center">
                                            <div class="text-gray-500">
                                                <i class="fas fa-user-graduate text-4xl mb-4"></i>
                                                <p class="font-medium">Aucun élève trouvé</p>
                                                <p class="text-sm mt-1">Aucun élève n'est enregistré dans le système</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    @elseif($activeTab === 'Entreprises')
                        <!-- Section en développement pour Entreprises -->
                        <div class="text-center py-12">
                            <div class="max-w-md mx-auto">
                                <i class="fas fa-cogs text-4xl text-gray-400 mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Section en développement</h3>
                                <p class="text-gray-600">Cette fonctionnalité sera bientôt disponible.</p>
                            </div>
                        </div>

                    @elseif($activeTab === 'Écoles')
                        <!-- Section en développement pour Écoles -->
                        <div class="text-center py-12">
                            <div class="max-w-md mx-auto">
                                <i class="fas fa-cogs text-4xl text-gray-400 mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Section en développement</h3>
                                <p class="text-gray-600">Cette fonctionnalité sera bientôt disponible.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Script pour les notifications -->
        <script>
            document.addEventListener('livewire:load', function() {
                Livewire.on('notify', (data) => {
                    alert(data.message);
                });
            });
        </script>
    </div>
</body>
</html>