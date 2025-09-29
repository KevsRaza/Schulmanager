<div class="space-y-6">

    <!-- Actions et header tab -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Dashboard</h2>
            <p class="text-gray-600">Gestion complète de votre établissement</p>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Dossiers -->
        <div class="card p-6 flex items-center justify-between 
        {{ $activeTab === 'Dossiers' ? 'bg-blue-300' : '' }}">
            <div>
                <p class="text-sm">Total Dossiers</p>
                <p class="text-2xl font-bold">{{ $stats['total_dossiers'] ?? 0 }}</p>
            </div>
            <i class="fas fa-folder text-2xl opacity-80"></i>
        </div>

        <!-- Élèves -->
        <div class="card p-6 flex items-center justify-between 
        {{ $activeTab === 'Élèves' ? 'bg-blue-300' : '' }}">
            <div>
                <p class="text-gray-600 text-sm">Élèves</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_eleves'] ?? 0 }}</p>
            </div>
            <i class="fas fa-users text-blue-500 text-2xl"></i>
        </div>

        <!-- Entreprises -->
        <div class="card p-6 flex items-center justify-between 
                    {{ $activeTab === 'Entreprises' ? 'bg-blue-300' : '' }}">
            <div>
                <p class="text-gray-600 text-sm">Entreprises</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_entreprises'] ?? 0 }}</p>
            </div>
            <i class="fas fa-building text-purple-500 text-2xl"></i>
        </div>

        <!-- Écoles -->
        <div class="card p-6 flex items-center justify-between 
                    {{ $activeTab === 'Écoles' ? 'bg-blue-300' : '' }}">
            <div>
                <p class="text-gray-600 text-sm">Écoles</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_ecoles'] ?? 0 }}</p>
            </div>
            <i class="fas fa-school text-green-500 text-2xl"></i>
        </div>
    </div>


    <!-- Tabs -->
    <div class="card">
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
                @foreach($tabs as $tab)
                <button wire:click="setActiveTab('{{ $tab }}')"
                    class="flex items-center gap-2 px-6 py-4 border-b-2 font-medium text-sm transition-colors
                                    {{ $activeTab === $tab ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                    @if($tab==='Dossiers') <i class="fas fa-folder"></i>
                    @elseif($tab==='Élèves') <i class="fas fa-users"></i>
                    @elseif($tab==='Écoles') <i class="fas fa-school"></i>
                    @elseif($tab==='Entreprises') <i class="fas fa-building"></i>
                    @elseif($tab==='Formulaires') <i class="fas fa-file"></i>
                    @endif
                    {{ $tab }}
                </button>
                @endforeach
            </nav>
        </div>

        <div class="p-6">
            {{-- Dossiers Tab --}}
            @if($activeTab==='Dossiers')
            <div class="space-y-6">
                <div class="flex flex-col sm:flex-row gap-4 mb-6">
                    <!-- Recherche -->
                    <div class="relative flex-1">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" wire:model.live="search"
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Rechercher un dossier...">
                    </div>

                    <!-- Filtres / Tri -->
                    <div class="flex gap-2">

                        <!-- Dropdown Filtres -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="btn btn-secondary flex items-center gap-1">
                                <i class="fas fa-filter"></i> Filtres
                            </button>

                            <div x-show="open"
                                @click.away="open = false"
                                class="absolute mt-2 bg-white border rounded shadow-lg w-48 z-50">
                                <ul>
                                    <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Filtre 1</li>
                                    <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Filtre 2</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Dropdown Trier -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="btn btn-secondary flex items-center gap-1">
                                <i class="fas fa-sort"></i> Trier
                            </button>

                            <div x-show="open" @click.away="open = false"
                                class="absolute mt-2 bg-white border rounded shadow-lg w-56 z-50">
                                <ul>
                                    <li wire:click="sortDossiers('name_Dossier', 'asc')" class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Nom (A → Z)</li>
                                    <li wire:click="sortDossiers('name_Dossier', 'desc')" class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Nom (Z → A)</li>
                                    <li wire:click="sortDossiers('created_at', 'asc')" class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Date (Ancien → Récent)</li>
                                    <li wire:click="sortDossiers('created_at', 'desc')" class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Date (Récent → Ancien)</li>
                                </ul>
                            </div>
                        </div>

                    </div>

                </div>

                @php $foldersToDisplay = $folders ?? []; @endphp

                @if(count($foldersToDisplay))
                <div class="grid gap-4">
                    @foreach($foldersToDisplay as $folder)
                    <div class="card p-6 hover:shadow-md transition-shadow flex justify-between">
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $folder['name'] ?? 'Sans nom' }}</h3>
                            <div class="flex flex-wrap gap-2 mt-3">
                                <span class="badge badge-blue"><i class="fas fa-file mr-1"></i>{{ $folder['stats']['documents'] ?? 0 }} docs</span>
                                <span class="badge badge-green"><i class="fas fa-users mr-1"></i>{{ $folder['stats']['élèves'] ?? 0 }} élèves</span>
                                <span class="badge badge-purple"><i class="fas fa-school mr-1"></i>{{ $folder['stats']['écoles'] ?? 0 }} écoles</span>
                                <span class="badge badge-yellow"><i class="fas fa-building mr-1"></i>{{ $folder['stats']['entreprises'] ?? 0 }} entreprises</span>
                            </div>
                        </div>
                        <div class="flex gap-2 ml-4">
                            <button class="btn btn-secondary" wire:click="viewFolder({{ $folder['id'] }})"><i class="fas fa-eye"></i></button>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if(($totalPages ?? 1) > 1)
                <div class="flex justify-center items-center space-x-2 mt-6">
                    <button class="px-3 py-1 border rounded disabled:opacity-50" wire:click="previousPage" {{ ($page ?? 1)<=1?'disabled':'' }}>
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    @for($i=1;$i<=($totalPages ??1);$i++)
                        <button class="px-3 py-1 border rounded {{ ($page ?? 1)==$i?'bg-blue-100 border-blue-500':'' }}" wire:click="goToPage({{ $i }})">{{ $i }}</button>
                        @endfor
                        <button class="px-3 py-1 border rounded disabled:opacity-50" wire:click="nextPage" {{ ($page ?? 1)>=($totalPages ??1)?'disabled':'' }}>
                            <i class="fas fa-chevron-right"></i>
                        </button>
                </div>
                @endif
                @else
                <div class="text-center py-12">
                    <i class="fas fa-folder-open text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg">Aucun dossier trouvé</p>
                    @if($search)<p class="text-gray-400 mt-2">Essayez de modifier vos critères de recherche</p>@endif
                </div>
                @endif
            </div>

            {{-- Élèves Tab --}}
            @elseif($activeTab==='Élèves')
            <div class="space-y-6">
                <div class="flex flex-col sm:flex-row gap-4 mb-6">
                    <!-- Recherche -->
                    <div class="relative flex-1">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" wire:model.live="search"
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Rechercher un élève...">
                    </div>

                    <!-- Filtres / Tri -->
                    <div class="flex gap-2">

                        <!-- Dropdown Filtres -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="btn btn-secondary flex items-center gap-1">
                                <i class="fas fa-filter"></i> Filtres
                            </button>

                            <div x-show="open"
                                @click.away="open = false"
                                class="absolute mt-2 bg-white border rounded shadow-lg w-48 z-50">
                                <ul>
                                    <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Filtre 1</li>
                                    <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Filtre 2</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Dropdown Trier -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="btn btn-secondary flex items-center gap-1">
                                <i class="fas fa-sort"></i> Trier
                            </button>

                            <div x-show="open"
                                @click.away="open = false"
                                class="absolute mt-2 bg-white border rounded shadow-lg w-48 z-50">
                                <ul>
                                    <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Trier par Nom</li>
                                    <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Trier par Date</li>
                                </ul>
                            </div>
                        </div>

                    </div>

                </div>

                @php $studentsToDisplay = $filteredSchulers ?? []; @endphp

                @if(count($studentsToDisplay))
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">École</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Entreprise</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Formation</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($studentsToDisplay as $schuler)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $schuler['prenom'] ?? '' }} {{ $schuler['nom'] ?? '' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $schuler['email'] ?? '' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $schuler['ecole'] ?? '' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $schuler['entreprise'] ?? '' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $schuler['formation'] ?? '' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-12">
                    <i class="fas fa-users text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg">Aucun élève trouvé</p>
                    @if($schulerSearch)<p class="text-gray-400 mt-2">Essayez de modifier vos critères de recherche</p>@endif
                </div>
                @endif
            </div>

            {{-- Formulaires Tab --}}
            @elseif($activeTab === 'Formulaires')
            <div class="space-y-6">
                <div class="flex flex-col sm:flex-row gap-4 mb-6">
                    <!-- Recherche -->
                    <div class="relative flex-1">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" wire:model.live="formulaireSearch"
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Rechercher un formulaire...">
                    </div>

                    <!-- Filtres / Tri -->
                    <div class="flex gap-2">

                        <!-- Dropdown Filtres -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="btn btn-secondary flex items-center gap-1">
                                <i class="fas fa-filter"></i> Filtres
                            </button>

                            <div x-show="open"
                                @click.away="open = false"
                                class="absolute mt-2 bg-white border rounded shadow-lg w-48 z-50">
                                <ul>
                                    <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Filtre 1</li>
                                    <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Filtre 2</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Dropdown Trier -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="btn btn-secondary flex items-center gap-1">
                                <i class="fas fa-sort"></i> Trier
                            </button>

                            <div x-show="open"
                                @click.away="open = false"
                                class="absolute mt-2 bg-white border rounded shadow-lg w-56 z-50">
                                <ul>
                                    <!-- Tri par Nom A-Z et Z-A -->
                                    <li wire:click="sortForms('name_Schuler', 'asc')"
                                        class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
                                        Nom
                                    </li>

                                    <!-- Tri par Date ASC -->
                                    <li wire:click="sortForms('created_at', 'asc')"
                                        class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
                                        Date
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <button wire:click="openFormModal" class="btn btn-primary"><i class="fas fa-plus"></i>Nouveau</button>
                    </div>



                </div>

                @if (session()->has('message'))
                <div class="p-2 bg-green-100 text-green-800 rounded">{{ session('message') }}</div>
                @endif

                <!-- Modal -->
                @if($showFormModal)
                <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg">
                        <!-- Header -->
                        <div class="flex justify-between items-center border-b px-6 py-4">
                            <h5 class="text-lg font-semibold">Nouveau Formulaire</h5>
                            <button wire:click="closeFormModal" class="text-gray-500 hover:text-gray-700">
                                &times;
                            </button>
                        </div>

                        <!-- Body -->
                        <div class="px-6 py-4 space-y-3">
                            <input type="text" wire:model.defer="form.name_Firma" placeholder="Nom de la société" class="border p-2 rounded w-full">
                            <input type="text" wire:model.defer="form.name_Manager" placeholder="Nom du manager" class="border p-2 rounded w-full">
                            <input type="text" wire:model.defer="form.land_Firma" placeholder="Pays de la société" class="border p-2 rounded w-full">
                            <input type="text" wire:model.defer="form.name_Schuler" placeholder="Nom de l'élève" class="border p-2 rounded w-full">
                            <input type="text" wire:model.defer="form.land_Schuler" placeholder="Pays de l'élève" class="border p-2 rounded w-full">
                            <input type="date" wire:model.defer="form.date_in" class="border p-2 rounded w-full">
                            <input type="date" wire:model.defer="form.date_out" class="border p-2 rounded w-full">
                            <input type="file" wire:model="form.sign_Manager" class="border p-2 rounded w-full">
                            <input type="file" wire:model="form.sign_Schuler" class="border p-2 rounded w-full">
                            <input type="file" wire:model="form.image_Schuler" class="border p-2 rounded w-full">
                        </div>

                        <!-- Footer -->
                        <div class="flex justify-end gap-2 border-t px-6 py-4">
                            <button wire:click="closeFormModal" class="btn btn-secondary px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">
                                Annuler
                            </button>
                            <button wire:click="saveFormulaire" class="btn btn-primary px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">
                                Enregistrer
                            </button>
                        </div>
                    </div>
                </div>
                @endif

                <div class="grid gap-4">
                    <h3 class="font-semibold">Formulaires récents</h3>
                    @foreach($formulaires as $f)
                    <div class="card p-4 hover:shadow-md transition-shadow flex justify-between">
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $f->name_Schuler }}</h3>
                        </div>
                        <div class="flex gap-2 ml-4">
                            <button class="btn btn-secondary" wire:click="viewFolder({{ $f['id'] }})"><i class="fas fa-eye"></i></button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>



            {{-- Autres Tabs --}}
            @else
            <div class="text-center py-12">
                <i class="fas fa-cogs text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Section en développement</h3>
                <p class="text-gray-600">Cette fonctionnalité sera bientôt disponible.</p>
            </div>
            @endif
        </div>


    </div>
</div>