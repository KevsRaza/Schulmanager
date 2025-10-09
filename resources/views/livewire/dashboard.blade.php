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
                        <input type="text" wire:model.live="folderSearch"
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
                                    <li wire:click="sortDossiersByName"
                                        class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
                                        Nom
                                    </li>
                                </ul>
                            </div>
                        </div>

                    </div>

                </div>

                @php $foldersToDisplay = $folders ?? collect(); @endphp

                @if($foldersToDisplay->count())
                <div class="grid gap-4">
                    @foreach($foldersToDisplay as $folder)
                    <div x-data="{ open: false }"
                        class="card p-6 hover:shadow-md transition-shadow relative">

                        <!-- En-tête du dossier -->
                        <div class="flex justify-between items-center">
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ $folder['name_Dossier'] }}
                                </h3>

                                <div class="flex flex-wrap gap-2 mt-3">
                                    <span class="badge badge-blue">
                                        <i class="fas fa-file mr-1"></i>
                                        {{ $folder->sous_dossiers_count ?? 0 }} docs
                                    </span>
                                    <span class="badge badge-green">
                                        <i class="fas fa-users mr-1"></i>
                                        {{ $folder->schulers_count ?? 0 }} élèves
                                    </span>
                                    <span class="badge badge-purple">
                                        <i class="fas fa-school mr-1"></i>
                                        {{ $folder->schulen_count ?? 0 }} écoles
                                    </span>
                                    <span class="badge badge-yellow">
                                        <i class="fas fa-building mr-1"></i>
                                        {{ $folder->firmen_count ?? 0 }} entreprises
                                    </span>
                                </div>
                            </div>

                            <!-- Chevron -->
                            <button @click="open = !open"
                                class="btn btn-secondary border-0 flex items-center justify-center">
                                <i class="fas fa-chevron-down transform transition-transform duration-300"
                                    :class="{'rotate-180': open}"></i>
                            </button>
                        </div>

                        <!-- Contenu déroulant -->
                        <div x-show="open"
                            x-transition
                            class="mt-4 pl-6 border-l-4 border-blue-200 bg-gray-50 rounded-lg p-4 shadow-inner">
                            @if($folder->sousDossiers->count())
                            <ul class="space-y-2">
                                @foreach($folder->sousDossiers as $sous)
                                <li class="p-2 pl-4 bg-white rounded shadow-sm flex items-center justify-between border-l-4 hover:bg-blue-100 border-transparent hover:border-blue-400 transition-all duration-200">
                                    <div>
                                        <i class="fa-regular fa-folder-open text-gray-600 mr-1"></i>
                                        {{ $sous->name_SousDossier ?? 'Sous-dossier' }}
                                    </div>
                                </li>

                                @endforeach
                            </ul>
                            @else
                            <p class="text-gray-500 italic">Aucun sous-dossier</p>
                            @endif
                        </div>
                    </div>
                    @endforeach

                </div>

                {{ $folders->links() }}

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
                    @if($folderSearch)<p class="text-gray-400 mt-2">Essayez de modifier vos critères de recherche</p>@endif
                </div>
                @endif
            </div>

            {{-- Élèves Tab --}}
            @elseif($activeTab === 'Élèves')
            <div class="space-y-6">
                <div class="flex flex-col sm:flex-row gap-4 mb-6">
                    <!-- Recherche -->
                    <div class="relative flex-1">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" wire:model="schulerSearch"
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
                                    <li wire:click="sortSchulers('vorname')" class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Trier par Prénom</li>
                                    <li wire:click="sortSchulers('familiename')" class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Trier par Nom</li>
                                    <li wire:click="sortSchulers('created_at')" class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Trier par Date</li>
                                </ul>
                            </div>
                        </div>

                        <button wire:click="openSchulerModal" class="btn btn-primary"><i class="fas fa-plus"></i> Ajouter Élève</button>
                    </div>
                </div>

                @if (session()->has('message'))
                <div class="p-2 bg-green-100 text-green-800 rounded">{{ session('message') }}</div>
                @endif

                <!-- Modal pour ajouter un élève -->
                @if($showSchulerModal)
                <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                    <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl h-auto sm:h-[90vh] overflow-y-auto">
                        <!-- Header -->
                        <div class="flex justify-between items-center border-b px-6 py-4">
                            <h5 class="text-lg font-semibold">Nouvel Élève</h5>
                            <button wire:click="closeSchulerModal" class="text-gray-500 hover:text-gray-700">&times;</button>
                        </div>

                        <div class="space-y-6 px-6 py-4">

                            {{-- Bloc Nom --}}
                            <div class="bg-gray-50 p-4 rounded-lg shadow-sm space-y-4">
                                <h3 class="text-sm font-bold uppercase text-gray-700">Élève</h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Prénom</label>
                                        <input type="text" wire:model.defer="schuler.vorname"
                                            class="border border-gray-300 rounded-md p-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Nom</label>
                                        <input type="text" wire:model.defer="schuler.familiename"
                                            class="border border-gray-300 rounded-md p-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Pays de l'élève</label>
                                        <input type="text" wire:model.defer="schuler.land_Schuler"
                                            class="border border-gray-300 rounded-md p-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Date de naissance</label>
                                        <input type="date" wire:model.defer="schuler.geburtsdatum_Schuler"
                                            class="border border-gray-300 rounded-md p-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                </div>
                            </div>

                            {{-- Bloc A propos --}}
                            <div class="bg-gray-50 p-4 rounded-lg shadow-sm space-y-4">
                                <h3 class="text-sm font-bold uppercase text-gray-700">Détails</h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label for="deutschniveau_Schuler" class="block">Niveau d'allemand:</label>
                                        <select id="deutschniveau_Schuler" wire:model.defer="schuler.deutschniveau_Schuler" class="border p-2 rounded w-full">
                                            <option value="" style="display: none;">Open...</option>
                                            <option value="A1">A1</option>
                                            <option value="A2">A2</option>
                                            <option value="B1">B1</option>
                                            <option value="B2">B2</option>
                                            <option value="C1">C1</option>
                                            <option value="C2">C2</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="bildungsniveau_Schuler" class="block">Niveau d'éducation:</label>
                                        <select id="bildungsniveau_Schuler" wire:model.defer="schuler.bildungsniveau_Schuler" class="border p-2 rounded w-full">
                                            <option value="" style="display: none;">Open...</option>
                                            <option value="Primaire">Primaire</option>
                                            <option value="Secondaire">Secondaire</option>
                                            <option value="Universitaire">Universitaire</option>
                                            <option value="Professionnel">Professionnel</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- Bloc Dates --}}
                            <div class="bg-gray-50 p-4 rounded-lg shadow-sm space-y-4">
                                <h3 class="text-sm font-bold uppercase text-gray-700">Dates</h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Date de début</label>
                                        <input type="date" wire:model.defer="schuler.datum_Anfang_Ausbildung"
                                            class="border border-gray-300 rounded-md p-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Date de fin</label>
                                        <input type="date" wire:model.defer="schuler.datum_Ende_Ausbildung"
                                            class="border border-gray-300 rounded-md p-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                </div>
                            </div>

                            {{-- Bloc Email --}}
                            <div class="bg-gray-50 p-4 rounded-lg shadow-sm space-y-4">
                                <h3 class="text-sm font-bold uppercase text-gray-700">Email</h3>
                                <div class="sm:col-span-2">
                                    <div>
                                        <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Email</label>
                                        <input type="email" wire:model.defer="schuler.email"
                                            class="border border-gray-300 rounded-md p-2 w-full">
                                    </div>
                                </div>
                            </div>

                            {{-- Boutons --}}
                            <div class="flex justify-end gap-3">
                                <button wire:click="closeSchulerModal"
                                    class="px-4 py-2 rounded bg-gray-300 text-gray-700 font-bold hover:bg-gray-400 transition">Annuler</button>
                                <button wire:click="saveSchuler" wire:loading.attr="disabled"
                                    class="px-4 py-2 rounded bg-blue-500 text-white font-bold hover:bg-blue-600 transition">
                                    <span wire:loading.remove>Enregistrer</span>
                                    <span wire:loading>Enregistrement...</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <div class="grid gap-4">
                    <h3 class="font-semibold">Élèves récents</h3>

                    @forelse($schulers as $schuler)
                    <div x-data="{ open: false }" class="card p-4 hover:shadow-md transition-shadow flex flex-col">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ $schuler['vorname'] }}
                                    {{ $schuler['familiename'] }}
                                </h3>
                                <p class="text-sm text-gray-500">{{ $schuler['email'] }}</p>
                            </div>
                            <button @click="open = !open" class="btn btn-secondary border-0 flex items-center justify-center">
                                <i class="fas fa-chevron-down transform transition-transform duration-300"
                                    :class="{'rotate-180': open}"></i>
                            </button>
                        </div>

                        <div x-show="open" x-transition class="mt-3 bg-gray-50 border-l-4 border-blue-400 pl-4 rounded-lg shadow-sm text-gray-700 text-sm space-y-2 p-4">
                            <p><strong>Pays :</strong> {{ $schuler['land_Schuler'] }}</p>
                            <p><strong>Niveau d'allemand :</strong> {{ $schuler['deutschniveau_Schuler'] }}</p>
                            <p><strong>Niveau d'éducation :</strong> {{ $schuler['bildungsniveau_Schuler'] }}</p>
                            <p><strong>Date début :</strong> {{ $schuler['datum_Anfang_Ausbildung'] }}</p>
                            <p><strong>Date fin :</strong> {{ $schuler['datum_Ende_Ausbildung'] }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-12">
                        <i class="fas fa-user-graduate text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500 text-lg">Aucun élève trouvé</p>
                    </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                <div class="mt-4">
                    {{ $schulers->links() }}
                </div>


            </div>
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
                        <div x-show="open" @click.away="open = false" class="absolute mt-2 bg-white border rounded shadow-lg w-48 z-50">
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
                        <div x-show="open" @click.away="open = false" class="absolute mt-2 bg-white border rounded shadow-lg w-56 z-50">
                            <ul>
                                <li wire:click="sortForms('name_Schuler', 'asc')" class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Nom</li>
                                <li wire:click="sortForms('created_at', 'asc')" class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Date</li>
                            </ul>
                        </div>
                    </div>

                    <button wire:click="openFormModal" class="btn btn-primary"><i class="fas fa-plus"></i>Ajouter Formulaire</button>
                </div>
            </div>

            @if (session()->has('message'))
            <div class="p-2 bg-green-100 text-green-800 rounded">{{ session('message') }}</div>
            @endif

            @if($showFormModal)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl h-auto sm:h-[90vh] overflow-y-auto">
                    <!-- Header -->
                    <div class="flex justify-between items-center border-b px-6 py-4">
                        <h5 class="text-lg font-semibold">Nouveau Formulaire</h5>
                        <button wire:click="closeFormModal" class="text-gray-500 hover:text-gray-700">&times;</button>
                    </div>

                    <div class="space-y-6 px-6 py-4">

                        {{-- Bloc Société --}}
                        <div class="bg-gray-50 p-4 rounded-lg shadow-sm space-y-4">
                            <h3 class="text-sm font-bold uppercase text-gray-700">Société</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Nom de la société</label>
                                    <input type="text" wire:model.defer="form.name_Firma"
                                        class="border border-gray-300 rounded-md p-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Pays de la société</label>
                                    <input type="text" wire:model.defer="form.land_Firma"
                                        class="border border-gray-300 rounded-md p-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Nom du manager</label>
                                    <input type="text" wire:model.defer="form.name_Manager"
                                        class="border border-gray-300 rounded-md p-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                        </div>

                        {{-- Bloc Élève --}}
                        <div class="bg-gray-50 p-4 rounded-lg shadow-sm space-y-4">
                            <h3 class="text-sm font-bold uppercase text-gray-700">Élève</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Nom de l'élève</label>
                                    <input type="text" wire:model.defer="form.name_Schuler"
                                        class="border border-gray-300 rounded-md p-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Pays de l'élève</label>
                                    <input type="text" wire:model.defer="form.land_Schuler"
                                        class="border border-gray-300 rounded-md p-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Photo élève</label>
                                <input type="file" wire:model="form.image_Schuler"
                                    class="border border-gray-300 rounded-md p-2 w-full">
                            </div>
                        </div>

                        {{-- Bloc Dates --}}
                        <div class="bg-gray-50 p-4 rounded-lg shadow-sm space-y-4">
                            <h3 class="text-sm font-bold uppercase text-gray-700">Dates</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Date de début</label>
                                    <input type="date" wire:model.defer="form.date_in"
                                        class="border border-gray-300 rounded-md p-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Date de fin</label>
                                    <input type="date" wire:model.defer="form.date_out"
                                        class="border border-gray-300 rounded-md p-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                        </div>

                        {{-- Bloc Fichiers --}}
                        <div class="bg-gray-50 p-4 rounded-lg shadow-sm space-y-4">
                            <h3 class="text-sm font-bold uppercase text-gray-700">Signatures et image</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Signature manager</label>
                                    <input type="file" wire:model="form.sign_Manager"
                                        class="border border-gray-300 rounded-md p-2 w-full">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Signature élève</label>
                                    <input type="file" wire:model="form.sign_Schuler"
                                        class="border border-gray-300 rounded-md p-2 w-full">
                                </div>
                            </div>
                        </div>

                        {{-- Boutons --}}
                        <div class="flex justify-end gap-3">
                            <button wire:click="closeFormModal"
                                class="px-4 py-2 rounded bg-gray-300 text-gray-700 font-bold hover:bg-gray-400 transition">Annuler</button>
                            <button wire:click="saveFormulaire" wire:loading.attr="disabled"
                                class="px-4 py-2 rounded bg-blue-500 text-white font-bold hover:bg-blue-600 transition">
                                <span wire:loading.remove>Enregistrer</span>
                                <span wire:loading>Enregistrement...</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endif


            <!-- Formulaires récents avec dropdown détails -->
            @if($formulaires->count())
            <div class="grid gap-4">
                <h3 class="font-semibold">Formulaires récents</h3>
                @foreach($formulaires as $f)
                <div x-data="{ open: false }" class="card p-4 hover:shadow-md transition-shadow flex flex-col">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $f->name_Schuler }}</h3>
                        <button @click="open = !open" class="btn btn-secondary border-0">
                            <i class="fa-solid fa-chevron-down" :class="{'rotate-180': open}"></i>
                        </button>
                    </div>

                    <!-- Dropdown détails -->
                    <div x-show="open"
                        x-transition
                        class="mt-3 bg-gray-50 border-l-4 border-blue-400 pl-4 rounded-lg shadow-sm text-gray-700 text-sm space-y-2 p-4">
                        <p><strong>Entreprise :</strong> {{ $f->name_Firma }}</p>
                        <p><strong>Manager :</strong> {{ $f->name_Manager }}</p>
                        <p><strong>Pays Élève :</strong> {{ $f->land_Schuler }}</p>
                        <p><strong>Pays Entreprise :</strong> {{ $f->land_Firma }}</p>
                        <p><strong>Date début :</strong> {{ $f->date_in }}</p>
                        <p><strong>Date fin :</strong> {{ $f->date_out }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            {{-- Pagination --}}
            {{ $formulaires->links() }}

            @else
            <div class="text-center py-12">
                <i class="fas fa-folder-open text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">Aucune entreprise trouvée</p>
                @if($formulaireSearch)<p class="text-gray-400 mt-2">Essayez de modifier vos critères de recherche</p>@endif
            </div>
            @endif
        </div>






        {{-- Entreprises Tab --}}
        @elseif($activeTab==='Entreprises')
        <div class="space-y-6">
            <div class="flex flex-col sm:flex-row gap-4 mb-6">
                <!-- Recherche -->
                <div class="relative flex-1">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" wire:model.live="firmaSearch"
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Rechercher une entreprise...">
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
                                <li wire:click="sortFirmen"
                                    class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
                                    Nom
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            @php $firmen = $this->firmen; @endphp

            @if($firmen->count())
            <div class="grid gap-4">
                @foreach($firmen as $firma)
                <div x-data="{ open: false }"
                    class="card p-6 hover:shadow-md transition-shadow relative">

                    <!-- En-tête -->
                    <div class="flex justify-between items-center">
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900">
                                {{ $firma->name_Firma }}
                            </h3>
                        </div>

                        <!-- Chevron -->
                        <button @click="open = !open"
                            class="btn btn-secondary border-0 flex items-center justify-center">
                            <i class="fas fa-chevron-down transform transition-transform duration-300"
                                :class="{'rotate-180': open}"></i>
                        </button>
                    </div>

                    <!-- Contenu déroulant -->
                    <div x-show="open"
                        x-transition
                        class="mt-4 pl-6 border-l-4 border-blue-200 bg-gray-50 rounded-lg p-4 shadow-inner">
                        <ul class="space-y-2">
                            <li class="p-2 pl-4 bg-white rounded shadow-sm flex items-center justify-between border-l-4 hover:bg-blue-100 border-transparent hover:border-blue-400 transition-all duration-200">
                                <div>
                                    <i class="fa-solid fa-user-tie text-gray-600 mr-1"></i>
                                    {{ $firma->manager_Firma }}
                                </div>
                                <div>
                                    <i class="fas fa-globe text-gray-600 mr-1"></i>
                                    {{ $firma->land_Firma }}
                                </div>
                                @if($firma->id_Dossier)
                                <div>
                                    <i class="fa-regular fa-folder-open text-gray-600 mr-1"></i>
                                    {{ $firma->id_Dossier }}
                                </div>
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>
                @endforeach

            </div>

            {{-- Pagination --}}
            {{ $firmen->links() }}

            @else
            <div class="text-center py-12">
                <i class="fas fa-folder-open text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">Aucune entreprise trouvée</p>
                @if($firmaSearch)<p class="text-gray-400 mt-2">Essayez de modifier vos critères de recherche</p>@endif
            </div>
            @endif
        </div>







        {{-- Écoles Tab --}}
        @elseif($activeTab==='Écoles')
        <div class="space-y-6">
            <div class="flex flex-col sm:flex-row gap-4 mb-6">
                <!-- Recherche -->
                <div class="relative flex-1">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" wire:model.live="schuleSearch"
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Rechercher une école...">
                </div>

                <!-- Filtres / Tri -->
                <div class="flex gap-2">
                    <!-- Dropdown Filtres -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="btn btn-secondary flex items-center gap-1">
                            <i class="fas fa-filter"></i> Filtres
                        </button>
                        <div x-show="open" @click.away="open = false"
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
                                <li wire:click="sortSchulen('name_Schule')"
                                    class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
                                    Nom
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            @php $schulen = $this->schulen; @endphp

            @if($schulen->count())
            <div class="grid gap-4">
                @foreach($schulen as $schule)
                <div x-data="{ open: false }"
                    class="card p-6 hover:shadow-md transition-shadow relative">
                    <div class="flex justify-between items-center">
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900">
                                {{ $schule->name_Schule }}
                            </h3>
                        </div>
                        <button @click="open = !open"
                            class="btn btn-secondary border-0 flex items-center justify-center">
                            <i class="fas fa-chevron-down transform transition-transform duration-300"
                                :class="{'rotate-180': open}"></i>
                        </button>
                    </div>

                    <div x-show="open" x-transition
                        class="mt-4 pl-6 border-l-4 border-blue-200 bg-gray-50 rounded-lg p-4 shadow-inner">
                        <ul class="space-y-2">
                            <li
                                class="p-2 pl-4 bg-white rounded shadow-sm flex items-center justify-between border-l-4 hover:bg-blue-100 border-transparent hover:border-blue-400 transition-all duration-200">
                                <div>
                                    <i class="fa-solid fa-user-tie text-gray-600 mr-1"></i>
                                    {{ $schule->name_Schulleiter }}
                                </div>
                                <div>
                                    <i class="fas fa-globe text-gray-600 mr-1"></i>
                                    {{ $schule->land_Schule }}
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            {{ $schulen->links() }}

            @else
            <div class="text-center py-12">
                <i class="fas fa-folder-open text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">Aucune école trouvée</p>
                @if($schuleSearch)
                <p class="text-gray-400 mt-2">Essayez de modifier vos critères de recherche</p>
                @endif
            </div>
            @endif
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
</div>