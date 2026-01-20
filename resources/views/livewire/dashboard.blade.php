<div class="space-y-6">

    <!-- Actions et header tab -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Dashboard</h2>
            <p class="text-gray-600">Vollständige erwaltung ihrer einrichtung</p>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Dossiers -->
        <div class="card p-6 flex items-center justify-between 
        {{ $activeTab === 'Land' ? 'bg-blue-300' : '' }}">
            <div>
                <p class="text-sm">Länder</p>
                <p class="text-2xl font-bold">{{ $stats['total_lands'] ?? 0 }}</p>
            </div>
            <i class="fas fa-folder text-2xl opacity-80"></i>
        </div>

        <!-- Élèves -->
        <div class="card p-6 flex items-center justify-between 
        {{ $activeTab === 'Schuler' ? 'bg-blue-300' : '' }}">
            <div>
                <p class="text-gray-600 text-sm">Schulers</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_schulers'] ?? 0 }}</p>
            </div>
            <i class="fas fa-users text-blue-500 text-2xl"></i>
        </div>

        <!-- Entreprises -->
        <div class="card p-6 flex items-center justify-between 
                    {{ $activeTab === 'Firma' ? 'bg-blue-300' : '' }}">
            <div>
                <p class="text-gray-600 text-sm">Firmen</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_firmen'] ?? 0 }}</p>
            </div>
            <i class="fas fa-building text-purple-500 text-2xl"></i>
        </div>

        <!-- Écoles -->
        <div class="card p-6 flex items-center justify-between 
                    {{ $activeTab === 'Schule' ? 'bg-blue-300' : '' }}">
            <div>
                <p class="text-gray-600 text-sm">Schulen</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_schulen'] ?? 0 }}</p>
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
                    @if($tab==='Land') <i class="fas fa-folder"></i>
                    @elseif($tab==='Schuler') <i class="fas fa-users"></i>
                    @elseif($tab==='Schule') <i class="fas fa-school"></i>
                    @elseif($tab==='Firma') <i class="fas fa-building"></i>
                    @elseif($tab==='Formulaires') <i class="fas fa-file"></i>
                    @endif
                    {{ $tab }}
                </button>
                @endforeach
            </nav>
        </div>

        <div class="p-6">
            {{-- Land Tab --}}
            @if($activeTab === 'Land')
            <div class="space-y-6">
                <div class="flex flex-col sm:flex-row gap-4 mb-6">
                    <!-- Recherche -->
                    <div class="relative flex-1">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <!-- CORRECTION: landSearch au lieu de LandSearch -->
                        <input type="text" wire:model.live="landSearch"
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Ein Land suchen...">
                    </div>

                    <!-- Filtres / Tri -->
                    <div class="flex gap-2">

                        <!-- Dropdown Filtres -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="btn btn-secondary flex items-center gap-1">
                                <i class="fas fa-filter"></i> Filtern
                            </button>

                            <div x-show="open"
                                @click.away="open = false"
                                class="absolute mt-2 bg-white border rounded shadow-lg w-48 z-50">
                                <ul>
                                    <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Filter 1</li>
                                    <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Filter 2</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Dropdown Trier -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="btn btn-secondary flex items-center gap-1">
                                <i class="fas fa-sort"></i> Sortieren
                            </button>

                            <div x-show="open" @click.away="open = false"
                                class="absolute mt-2 bg-white border rounded shadow-lg w-56 z-50">
                                <ul>
                                    <li wire:click="sortLands('nameLand')"
                                        class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
                                        Nom
                                    </li>
                                </ul>
                            </div>
                        </div>

                    </div>

                </div>

                @if($lands->count())
                <div class="grid gap-4">
                    @foreach($lands as $land)
                    <div x-data="{ open: false }"
                        class="card p-6 hover:shadow-md transition-shadow relative">

                        <!-- En-tête du dossier -->
                        <div class="flex justify-between items-center">
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ $land->nameLand }}
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
                        <div x-show="open" x-transition class="mt-4 pl-6 border-l-4 border-blue-200 bg-gray-50 rounded-lg p-4 shadow-inner">
                            <p class="text-sm text-gray-600">Anzahl der Schulen: {{ $land->schulen_count ?? 0 }}</p>
                        </div>

                    </div>
                    @endforeach

                </div>

                {{ $lands->links() }}

                @else
                <div class="text-center py-12">
                    <i class="fas fa-folder-open text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg">Kein Land gefunden</p>
                    @if($landSearch)<p class="text-gray-400 mt-2">Versuchen Sie, Ihre Suchkriterien zu ändern</p>@endif
                </div>
                @endif
            </div>

            {{-- Élèves Tab --}}
            @elseif($activeTab === 'Schuler')
            <div class="space-y-6">
                <!-- BOUTON IMPORT CSV MODIFIÉ -->
                <div class="mb-4">
                    <button wire:click="openImportModal"
                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded flex items-center gap-2">
                        <i class="fas fa-file-import"></i>
                        CSV Importieren
                    </button>
                    @if($importMessage)
                    <div class="mt-2 p-2 bg-gray-100 rounded text-sm">
                        {{ $importMessage }}
                    </div>
                    @endif
                </div>

                <!-- Modal d'import CSV -->
                @if($showImportModal)
                <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                    <div class="bg-white rounded-lg shadow-lg w-full max-w-md">
                        <!-- Header -->
                        <div class="flex justify-between items-center border-b px-6 py-4">
                            <h5 class="text-lg font-semibold">CSV Importieren</h5>
                            <button wire:click="closeImportModal" class="text-gray-500 hover:text-gray-700">&times;</button>
                        </div>

                        <div class="p-6 space-y-4">
                            <!-- Sélection manuelle du fichier -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Wählen Sie eine CSV-Datei aus
                                </label>
                                <input type="file"
                                    wire:model="csvFile"
                                    accept=".csv,.txt"
                                    class="block w-full text-sm text-gray-500
                                              file:mr-4 file:py-2 file:px-4
                                              file:rounded-full file:border-0
                                              file:text-sm file:font-semibold
                                              file:bg-blue-50 file:text-blue-700
                                              hover:file:bg-blue-100">
                                @error('csvFile') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Informations sur le format -->
                            <div class="bg-blue-50 p-3 rounded-lg">
                                <p class="text-sm text-blue-800">
                                    <strong>Erforderliches CSV-Format</strong><br>
                                    - Trennzeichen: Semikolon (;)<br>
                                    - Erforderliche Spalten: E-Mail, vorname, familiename<br>
                                    - Kodierung: UTF-8 Empfohlen
                                </p>
                            </div>

                            <!-- Message d'état -->
                            @if($importMessage)
                            <div class="p-3 rounded-lg 
                                    {{ str_contains($importMessage, '✅') ? 'bg-green-100 text-green-800' : 
                                       (str_contains($importMessage, '❌') ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800') }}">
                                {{ $importMessage }}
                            </div>
                            @endif

                            <!-- Boutons d'action -->
                            <div class="flex justify-end gap-3 pt-4">
                                <button wire:click="closeImportModal"
                                    class="px-4 py-2 rounded bg-gray-300 text-gray-700 font-bold hover:bg-gray-400 transition">
                                    Abbrechen
                                </button>
                                <button wire:click="importSchulersFromCsv"
                                    wire:loading.attr="disabled"
                                    class="px-4 py-2 rounded bg-green-500 text-white font-bold hover:bg-green-600 transition flex items-center gap-2">
                                    <span wire:loading.remove>Importieren</span>
                                    <span wire:loading>Import läuft...</span>
                                    <i wire:loading class="fas fa-spinner fa-spin"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <div class="flex flex-col sm:flex-row gap-4 mb-6">
                    <!-- Recherche -->
                    <div class="relative flex-1">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <!-- CORRECTION: wire:model.live au lieu de wire:model -->
                        <input type="text" wire:model.live="schulerSearch"
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Ein Schüler suchen...">
                    </div>

                    <!-- Filtres / Tri -->
                    <div class="flex gap-2">

                        <!-- Dropdown Filtres -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="btn btn-secondary flex items-center gap-1">
                                <i class="fas fa-filter"></i> Filtern
                            </button>

                            <div x-show="open"
                                @click.away="open = false"
                                class="absolute mt-2 bg-white border rounded shadow-lg w-48 z-50">
                                <ul>
                                    <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Filter 1</li>
                                    <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Filter 2</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Dropdown Trier -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="btn btn-secondary flex items-center gap-1">
                                <i class="fas fa-sort"></i> Sortieren
                            </button>

                            <div x-show="open"
                                @click.away="open = false"
                                class="absolute mt-2 bg-white border rounded shadow-lg w-48 z-50">
                                <ul>
                                    <li wire:click="sortSchulers('vorname')" class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Nach Vornamen Sortieren</li>
                                    <li wire:click="sortSchulers('familiename')" class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Nach Name Sortieren</li>
                                    <li wire:click="sortSchulers('created_at')" class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Nach Datum Sortieren</li>
                                </ul>
                            </div>
                        </div>

                        <button wire:click="openSchulerModal" class="btn btn-primary"><i class="fas fa-plus"></i> Schüler Hinzufügen</button>
                    </div>
                </div>

                <!-- MESSAGES DE SESSION AJOUTÉS -->
                @if (session()->has('message'))
                <div class="p-2 bg-green-100 text-green-800 rounded">{{ session('message') }}</div>
                @endif
                @if (session()->has('success'))
                <div class="p-2 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
                @endif
                @if (session()->has('warning'))
                <div class="p-2 bg-yellow-100 text-yellow-800 rounded">{{ session('warning') }}</div>
                @endif
                @if (session()->has('error'))
                <div class="p-2 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
                @endif

                <!-- Modal pour ajouter un élève -->
                @if($showSchulerModal)
                <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                    <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl h-auto sm:h-[90vh] overflow-y-auto">
                        <!-- Header -->
                        <div class="flex justify-between items-center border-b px-6 py-4">
                            <h5 class="text-lg font-semibold">Neuer Schüler</h5>
                            <button wire:click="closeSchulerModal" class="text-gray-500 hover:text-gray-700">&times;</button>
                        </div>

                        <div class="space-y-6 px-6 py-4">

                            {{-- Bloc Nom --}}
                            <div class="bg-gray-50 p-4 rounded-lg shadow-sm space-y-4">
                                <h3 class="text-sm font-bold uppercase text-gray-700">Schüler</h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Vorname</label>
                                        <input type="text" wire:model="schuler.vorname"
                                            class="border border-gray-300 rounded-md p-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Name</label>
                                        <input type="text" wire:model="schuler.familiename"
                                            class="border border-gray-300 rounded-md p-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Land des Schüler</label>
                                        <input type="text" wire:model="schuler.landSchuler"
                                            class="border border-gray-300 rounded-md p-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Geburtsdatum</label>
                                        <input type="date" wire:model="schuler.geburtstag"
                                            class="border border-gray-300 rounded-md p-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                </div>
                            </div>

                            {{-- Bloc A propos --}}
                            <div class="bg-gray-50 p-4 rounded-lg shadow-sm space-y-4">
                                <h3 class="text-sm font-bold uppercase text-gray-700">Details</h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block">Deutschkenntnisse:</label>
                                        <select wire:model="schuler.deutschniveau_Schuler" class="border p-2 rounded w-full">
                                            <option value="" style="display: none;">Offen...</option>
                                            <option value="A1">A1</option>
                                            <option value="A2">A2</option>
                                            <option value="B1">B1</option>
                                            <option value="B2">B2</option>
                                            <option value="C1">C1</option>
                                            <option value="C2">C2</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block">Bildungdniveau:</label>
                                        <select wire:model="schuler.bildungsniveau_Schuler" class="border p-2 rounded w-full">
                                            <option value="" style="display: none;">Offen...</option>
                                            <option value="Primaire">Grundierung</option>
                                            <option value="Secondaire">Sekundär</option>
                                            <option value="Universitaire">Akademiker</option>
                                            <option value="Professionnel">Beruflich</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- Bloc Dates --}}
                            <div class="bg-gray-50 p-4 rounded-lg shadow-sm space-y-4">
                                <h3 class="text-sm font-bold uppercase text-gray-700">Datum</h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Startdatum</label>
                                        <input type="date" wire:model="schuler.datum_Anfang_Ausbildung"
                                            class="border border-gray-300 rounded-md p-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Enddatum</label>
                                        <input type="date" wire:model="schuler.datum_Ende_Ausbildung"
                                            class="border border-gray-300 rounded-md p-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                </div>
                            </div>

                            {{-- Bloc Email --}}
                            <div class="bg-gray-50 p-4 rounded-lg shadow-sm space-y-4">
                                <h3 class="text-sm font-bold uppercase text-gray-700">E-Mail</h3>
                                <div class="sm:col-span-2">
                                    <div>
                                        <label class="block text-xs font-bold uppercase text-gray-600 mb-1">E-Mail</label>
                                        <input type="email" wire:model="schuler.email"
                                            class="border border-gray-300 rounded-md p-2 w-full">
                                    </div>
                                </div>
                            </div>

                            {{-- Boutons --}}
                            <div class="flex justify-end gap-3">
                                <button wire:click="closeSchulerModal"
                                    class="px-4 py-2 rounded bg-gray-300 text-gray-700 font-bold hover:bg-gray-400 transition">Abbrechen</button>
                                <button wire:click="saveSchuler" wire:loading.attr="disabled"
                                    class="px-4 py-2 rounded bg-blue-500 text-white font-bold hover:bg-blue-600 transition">
                                    <span wire:loading.remove>Speichern</span>
                                    <span wire:loading>Anmeldung...</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <div class="grid gap-4">
                    <h3 class="font-semibold">Aktuelle Schüler</h3>

                    @forelse($schulers as $schuler)
                    <div x-data="{ open: false }" class="card p-4 hover:shadow-md transition-shadow flex flex-col">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ $schuler['vorname'] ?? 'N/A' }}
                                    {{ $schuler['familiename'] ?? 'N/A' }}
                                </h3>
                                <p class="text-sm text-gray-500">{{ $schuler['email'] ?? 'N/A' }}</p>
                            </div>
                            <button @click="open = !open" class="btn btn-secondary border-0 flex items-center justify-center">
                                <i class="fas fa-chevron-down transform transition-transform duration-300"
                                    :class="{'rotate-180': open}"></i>
                            </button>
                        </div>

                        <div x-show="open" x-transition
                            class="mt-3 bg-gray-50 border-l-4 border-blue-400 pl-4 rounded-lg shadow-sm text-gray-700 text-sm space-y-2 p-4">
                            <p><strong>Land :</strong> {{ $schuler['landSchuler'] ?? 'N/A' }}</p>
                            <p><strong>Deutschkenntnisse :</strong> {{ $schuler['deutschniveauSchuler'] ?? 'N/A' }}</p>
                            <p><strong>Bildungdniveau :</strong> {{ $schuler['bildungsniveauSchuler'] ?? 'N/A' }}</p>
                            <p><strong>Startdatum :</strong> {{ $schuler['datumAnfangAusbildung'] ?? 'N/A' }}</p>
<p><strong>Enddatum :</strong> {{ $schuler['datumEndeAusbildung'] ?? 'N/A' }}</p>

                        </div>

                    </div>
                    @empty
                    <div class="text-center py-12">
                        <i class="fas fa-user-graduate text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500 text-lg">Kein Schüler Gefunden</p>
                        <p class="text-gray-400 text-sm mt-2">Klicken Sie auf "CSV importieren", um die Schüler hochzuladen.</p>
                    </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                <div class="mt-4">
                    {{ $schulers->links() }}
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
                            placeholder="Formular Suchen...">
                    </div>

                    <!-- Filtres / Tri -->
                    <div class="flex gap-2">
                        <!-- Dropdown Filtres -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="btn btn-secondary flex items-center gap-1">
                                <i class="fas fa-filter"></i> Filtern
                            </button>
                            <div x-show="open" @click.away="open = false" class="absolute mt-2 bg-white border rounded shadow-lg w-48 z-50">
                                <ul>
                                    <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Filter 1</li>
                                    <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Filter 2</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Dropdown Trier -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="btn btn-secondary flex items-center gap-1">
                                <i class="fas fa-sort"></i> Sortieren
                            </button>
                            <div x-show="open" @click.away="open = false" class="absolute mt-2 bg-white border rounded shadow-lg w-56 z-50">
                                <ul>
                                    <li wire:click="sortForms('name_Schuler')" class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Name</li>
                                    <li wire:click="sortForms('created_at')" class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Datum</li>
                                </ul>
                            </div>
                        </div>

                        <button wire:click="openFormModal" class="btn btn-primary"><i class="fas fa-plus"></i>Formulare Hinzufügen</button>
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
                            <h5 class="text-lg font-semibold">Neues Formular</h5>
                            <button wire:click="closeFormModal" class="text-gray-500 hover:text-gray-700">&times;</button>
                        </div>

                        <div class="space-y-6 px-6 py-4">

                            {{-- Bloc Société --}}
                            <div class="bg-gray-50 p-4 rounded-lg shadow-sm space-y-4">
                                <h3 class="text-sm font-bold uppercase text-gray-700">Unternehmen</h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Name des Unternehmen</label>
                                        <input type="text" wire:model="form.name_Firma"
                                            class="border border-gray-300 rounded-md p-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Land des Unternehmen</label>
                                        <input type="text" wire:model="form.land_Firma"
                                            class="border border-gray-300 rounded-md p-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Name des Managers</label>
                                        <input type="text" wire:model="form.name_Manager"
                                            class="border border-gray-300 rounded-md p-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                </div>
                            </div>

                            {{-- Bloc Élève --}}
                            <div class="bg-gray-50 p-4 rounded-lg shadow-sm space-y-4">
                                <h3 class="text-sm font-bold uppercase text-gray-700">Schüler</h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Name des Schülers</label>
                                        <input type="text" wire:model="form.name_Schuler"
                                            class="border border-gray-300 rounded-md p-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Land des Schülers</label>
                                        <input type="text" wire:model="form.land_Schuler"
                                            class="border border-gray-300 rounded-md p-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Foto des Schülers</label>
                                    <input type="file" wire:model="form.image_Schuler"
                                        class="border border-gray-300 rounded-md p-2 w-full">
                                </div>
                            </div>

                            {{-- Bloc Dates --}}
                            <div class="bg-gray-50 p-4 rounded-lg shadow-sm space-y-4">
                                <h3 class="text-sm font-bold uppercase text-gray-700">Datum</h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Startdatum</label>
                                        <input type="date" wire:model="form.date_in"
                                            class="border border-gray-300 rounded-md p-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Enddatum</label>
                                        <input type="date" wire:model="form.date_out"
                                            class="border border-gray-300 rounded-md p-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                </div>
                            </div>

                            {{-- Bloc Fichiers --}}
                            <div class="bg-gray-50 p-4 rounded-lg shadow-sm space-y-4">
                                <h3 class="text-sm font-bold uppercase text-gray-700">Unterschriften und Bilder</h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Unterschrift des Managers</label>
                                        <input type="file" wire:model="form.sign_Manager"
                                            class="border border-gray-300 rounded-md p-2 w-full">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Unterschrift des Schüler</label>
                                        <input type="file" wire:model="form.sign_Schuler"
                                            class="border border-gray-300 rounded-md p-2 w-full">
                                    </div>
                                </div>
                            </div>

                            {{-- Boutons --}}
                            <div class="flex justify-end gap-3">
                                <button wire:click="closeFormModal"
                                    class="px-4 py-2 rounded bg-gray-300 text-gray-700 font-bold hover:bg-gray-400 transition">Abbrechen</button>
                                <button wire:click="saveFormulaire" wire:loading.attr="disabled"
                                    class="px-4 py-2 rounded bg-blue-500 text-white font-bold hover:bg-blue-600 transition">
                                    <span wire:loading.remove>Speichern</span>
                                    <span wire:loading>Anmeldung...</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Formulaires récents avec dropdown détails -->
                @if($formulaires->count())
                <div class="grid gap-4">
                    <h3 class="font-semibold">Aktuelle Formulare</h3>
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
                            <p><strong>Unternehmen :</strong> {{ $f->name_Firma }}</p>
                            <p><strong>Manager :</strong> {{ $f->name_Manager }}</p>
                            <p><strong>Land des schülers :</strong> {{ $f->land_Schuler }}</p>
                            <p><strong>Land des Unternehmens :</strong> {{ $f->land_Firma }}</p>
                            <p><strong>Startdatum :</strong> {{ $f->date_in }}</p>
                            <p><strong>Enddatum :</strong> {{ $f->date_out }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                {{-- Pagination --}}
                {{ $formulaires->links() }}

                @else
                <div class="text-center py-12">
                    <i class="fas fa-folder-open text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg">Kein Formulare Gefunden</p>
                    @if($formulaireSearch)<p class="text-gray-400 mt-2">Versuchen Sie, Ihre Suchkriterien zu ändern</p>@endif
                </div>
                @endif
            </div>

            {{-- Firmen Tab --}}
            @elseif($activeTab === 'Firma')
            <div class="space-y-6">
                <div class="flex flex-col sm:flex-row gap-4 mb-6">
                    <!-- Recherche -->
                    <div class="relative flex-1">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" wire:model.live="firmaSearch"
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Ein Unternehmen suchen...">
                    </div>

                    <!-- Filtres / Tri -->
                    <div class="flex gap-2">

                        <!-- Dropdown Filtres -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="btn btn-secondary flex items-center gap-1">
                                <i class="fas fa-filter"></i> Filtern
                            </button>

                            <div x-show="open"
                                @click.away="open = false"
                                class="absolute mt-2 bg-white border rounded shadow-lg w-48 z-50">
                                <ul>
                                    <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Filter 1</li>
                                    <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Filter 2</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Dropdown Trier -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="btn btn-secondary flex items-center gap-1">
                                <i class="fas fa-sort"></i> Sortieren
                            </button>

                            <div x-show="open" @click.away="open = false"
                                class="absolute mt-2 bg-white border rounded shadow-lg w-56 z-50">
                                <ul>
                                    <li wire:click="sortFirmen('name_Firma')"
                                        class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
                                        Nom
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

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
                                    @if($firma->idLand)
                                    <div>
                                        <i class="fa-regular fa-folder-open text-gray-600 mr-1"></i>
                                        {{ $firma->nameLand }}
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
                    <p class="text-gray-500 text-lg">Kein Unternehmen Gefunden</p>
                    @if($firmaSearch)<p class="text-gray-400 mt-2">Versuchen Sie, Ihre Suchkriterien zu ändern</p>@endif
                </div>
                @endif
            </div>

            {{-- Écoles Tab --}}
            @elseif($activeTab === 'Schule')
            <div class="space-y-6">
                <div class="flex flex-col sm:flex-row gap-4 mb-6">
                    <!-- Recherche -->
                    <div class="relative flex-1">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" wire:model.live="schuleSearch"
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Eine Schule suchen...">
                    </div>

                    <!-- Filtres / Tri -->
                    <div class="flex gap-2">
                        <!-- Dropdown Filtres -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="btn btn-secondary flex items-center gap-1">
                                <i class="fas fa-filter"></i> Filtern
                            </button>
                            <div x-show="open" @click.away="open = false"
                                class="absolute mt-2 bg-white border rounded shadow-lg w-48 z-50">
                                <ul>
                                    <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Filter 1</li>
                                    <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Filter 2</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Dropdown Trier -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="btn btn-secondary flex items-center gap-1">
                                <i class="fas fa-sort"></i> Sortieren
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
                    <p class="text-gray-500 text-lg">Kein Schule Gefunden</p>
                    @if($schuleSearch)
                    <p class="text-gray-400 mt-2">Versuchen Sie, Ihre Suchkriterien zu ändern</p>
                    @endif
                </div>
                @endif
            </div>

            {{-- Autres Tabs --}}
            @else
            <div class="text-center py-12">
                <i class="fas fa-cogs text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Abschnitt in Entwicklung</h3>
                <p class="text-gray-600">Diese Funktion wird bald verfügbar sein.</p>
            </div>
            @endif
        </div>
    </div>
</div>