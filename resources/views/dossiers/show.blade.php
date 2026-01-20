<!-- Search and Filters -->
<div class="flex flex-col sm:flex-row gap-4 mb-6">
    <div class="flex-1">
        <div class="search-input">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Rechercher un dossier..." wire:model.debounce.300ms="search">
        </div>
    </div>
    <div class="flex gap-2">
        <button class="btn btn-secondary"><i class="fas fa-filter"></i> Filtern</button>
        <button class="btn btn-secondary"><i class="fas fa-sort"></i> Sortieren</button>
    </div>
</div>

<!-- Dossiers Table -->
<div class="overflow-hidden rounded-lg">
    <table class="table">
        <thead>
            <tr>
                <th>Nom du Dossier</th>
                <th>Documents</th>
                <th>Formations</th>
                <th>Écoles</th>
                <th>Entreprises</th>
                <th>Élèves</th>
                <th>Actions</th>
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
                            {{-- <div class="text-sm text-gray-500">ID: {{ $folder['id'] }}
                        </div> --}}
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