
    <div class="flex flex-col sm:flex-row gap-4 mb-6">
        <div class="flex-1">
            <div class="search-input">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Rechercher un élève, école ou email..." wire:model.debounce.300ms="studentSearch">
            </div>
        </div>
        <div class="flex gap-2">
            <button class="btn btn-secondary"><i class="fas fa-filter"></i> Filtern</button>
            <button class="btn btn-secondary"><i class="fas fa-sort"></i> Sortieren</button>
        </div>
    </div>

    <!-- Students Table -->
    <div class="overflow-hidden rounded-lg">
        <table class="table">
            <thead>
                <tr>
                    <th>Scüler</th>
                    <th>E-Mail</th>
                    <th>Schule</th>
                    <th>Unternehmen</th>
                    <th>Ausbildung</th>
                    <th>Deutschkenntnisse</th>
                    <th>Zeitraum</th>
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
                            <p class="font-medium">Keine Schüler gefunden</p>
                            <p class="text-sm mt-1">Es sind keine Schüler im System registriert</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
