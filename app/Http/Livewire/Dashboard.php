<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Dossier;
use App\Models\Schuler;
use App\Models\Formulaire;
use Illuminate\Support\Facades\Log;

class Dashboard extends Component
{
    use WithPagination, WithFileUploads;

    public $layout = 'app';

    public $activeTab = 'Dossiers';
    public array $tabs = ['Dossiers', 'Élèves', 'Entreprises', 'Écoles', 'Formulaires'];

    public $search = '';
    public $schulerSearch = '';

    public $formulaireSearch = '';
    public $page = 1;

    public $formSortField = 'created_at'; // champ par défaut
    public $formSortDirection = 'desc';

    // Pour les dossiers
    public $dossierSortField = 'name_Dossier';
    public $dossierSortDirection = 'asc';

    // Pour les élèves
    public $schulerSortField = 'familiename';
    public $schulerSortDirection = 'asc';


    public $showFormModal = false;
    public $showFilterDropdown = false;
    public $showSortDropdown = false;


    // Méthodes pour basculer les dropdowns
    public function toggleFilter()
    {
        $this->showFilterDropdown = !$this->showFilterDropdown;
    }

    public function toggleSort()
    {
        $this->showSortDropdown = !$this->showSortDropdown;
    }


    public $form = [
        'name_Firma' => '',
        'name_Manager' => '',
        'land_Firma' => '',
        'name_Schuler' => '',
        'land_Schuler' => '',
        'date_in' => '',
        'date_out' => '',
        'sign_Manager' => null,
        'sign_Schuler' => null,
        'image_Schuler' => null,
    ];



    protected $queryString = ['activeTab'];

    public function mount()
    {
        $this->activeTab = 'Dossiers';
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage(); // reset pagination si nécessaire
    }

    public function getFoldersProperty()
    {
        try {
            Log::info('Récupération des dossiers...');

            $dossiers = Dossier::withCount(['dokumente', 'ausbildungen', 'schulers', 'schulen', 'firmen'])->paginate(10);

            Log::info('Dossiers récupérés: ' . $dossiers->count());

            return $dossiers->map(function ($dossier) {
                return [
                    'id' => $dossier->id,
                    'name' => $dossier->name_Dossier ?? 'Sans nom',
                    'stats' => [
                        'documents' => $dossier->dokumente_count ?? 0,
                        'formations' => $dossier->ausbildungen_count ?? 0,
                        'élèves' => $dossier->schulers_count ?? 0,
                        'écoles' => $dossier->schulen_count ?? 0,
                        'entreprises' => $dossier->firmen_count ?? 0,
                        // 'entreprises' => 0,
                    ],
                    'created_at' => $dossier->created_at?->format('d/m/Y') ?? 'Date inconnue'
                ];
            })->toArray();
        } catch (\Exception $e) {
            Log::error('Erreur récupération dossiers: ' . $e->getMessage());
            return []; // Toujours retourner un tableau vide en cas d'erreur
        }
    }

    public function getSchulersProperty()
    {
        try {
            Log::info('Récupération des élèves...');

            $schulers = Schuler::with(['schule', 'firma', 'ausbildung'])->get();

            Log::info('Élèves récupérés: ' . $schulers->count());

            return $schulers->map(function ($schuler) {
                return [
                    'id' => $schuler->id_Schuler,
                    'prenom' => $schuler->vorname ?? 'N/A',
                    'nom' => $schuler->familiename ?? 'N/A',
                    'email' => $schuler->email ?? 'N/A',
                    'ecole' => $schuler->schule->name_Schule ?? 'Non assigné',
                    'entreprise' => $schuler->firma->name_Firma ?? 'Non assigné',
                    'formation' => $schuler->ausbildung->name_Ausbildung ?? 'Non assigné',
                    'niveau_allemand' => $schuler->deutschniveau_Schuler ?? 'N/A',
                    'date_debut' => $schuler->datum_Anfang_Ausbildung?->format('d/m/Y') ?? 'N/A',
                    'date_fin' => $schuler->datum_Ende_Ausbildung?->format('d/m/Y') ?? 'N/A'
                ];
            })->toArray();
        } catch (\Exception $e) {
            Log::error('Erreur récupération élèves: ' . $e->getMessage());
            return []; // Toujours retourner un tableau vide en cas d'erreur
        }
    }

    public function getFilteredFoldersProperty()
    {
        $folders = $this->folders ?? [];

        // 🔎 Filtrer par recherche
        if (!empty($this->search)) {
            $folders = array_values(array_filter($folders, function ($folder) {
                return stripos($folder['name'] ?? '', $this->search) !== false;
            }));
        }

        // ↕️ Appliquer le tri si défini
        if ($this->dossierSortField && $this->dossierSortDirection) {
            usort($folders, function ($a, $b) {
                $field = $this->dossierSortField;
                $direction = $this->dossierSortDirection;

                $valA = $a[$field] ?? '';
                $valB = $b[$field] ?? '';

                if ($field === 'created_at') {
                    $valA = strtotime($valA) ?: 0;
                    $valB = strtotime($valB) ?: 0;
                }

                if ($valA == $valB) return 0;

                if ($direction === 'asc') {
                    return $valA <=> $valB;
                } else {
                    return $valB <=> $valA;
                }
            });
        }

        Log::info('Filtered + Sorted folders count: ' . count($folders));

        return $folders;
    }



    public function getFilteredSchulersProperty()
    {
        $schulers = $this->schulers ?? [];

        if (empty($this->schulerSearch)) {
            return $schulers;
        }

        $search = strtolower($this->schulerSearch);
        return array_values(array_filter($schulers, function ($schuler) use ($search) {
            return stripos($schuler['prenom'] . ' ' . $schuler['nom'], $search) !== false ||
                stripos($schuler['email'] ?? '', $search) !== false ||
                stripos($schuler['ecole'] ?? '', $search) !== false ||
                stripos($schuler['entreprise'] ?? '', $search) !== false;
        }));
    }

    // --- méthode appelée par le dropdown ---
    public function sortDossiers($field, $direction)
    {
        // Valide les valeurs (sécurité)
        $allowedFields = ['name_Dossier', 'created_at'];
        $direction = strtolower($direction) === 'asc' ? 'asc' : 'desc';
        if (!in_array($field, $allowedFields)) {
            $field = 'created_at';
        }

        $this->dossierSortField = $field;
        $this->dossierSortDirection = $direction;
        $this->resetPage();
    }

    // Exemple pour schulers (même logique)
    public function sortSchulers($field, $direction)
    {
        $allowed = ['vorname', 'familiename', 'id_Schuler'];
        $direction = strtolower($direction) === 'asc' ? 'asc' : 'desc';
        if (!in_array($field, $allowed)) $field = 'familiename';
        $this->schulerSortField = $field;
        $this->schulerSortDirection = $direction;
        $this->resetPage();
    }

    // 📄 Formulaire
    public function openFormModal()
    {
        $this->reset('form'); // réinitialise les champs du formulaire
        $this->showFormModal = true; // ouvre le modal
    }

    public function closeFormModal()
    {
        $this->showFormModal = false;
    }
    public function saveFormulaire()
    {
        // validation
        $this->validate([
            'form.name_Firma' => 'required|string',
            'form.name_Manager' => 'required|string',
            'form.land_Firma' => 'required|string',
            'form.name_Schuler' => 'required|string',
            'form.land_Schuler' => 'required|string',
            'form.date_in' => 'required|date',
            'form.date_out' => 'required|date',
            'form.sign_Manager' => 'required|file|mimes:jpg,png,pdf',
            'form.sign_Schuler' => 'required|file|mimes:jpg,png,pdf',
            'form.image_Schuler' => 'required|image|mimes:jpg,png',
        ]);

        // Upload des fichiers et calcul des hash
        if ($this->form['sign_Manager']) {
            $signManagerPath = $this->form['sign_Manager']->store('signatures', 'public');
            $signManagerHash = md5_file(storage_path('app/public/' . $signManagerPath));
        } else {
            $signManagerPath = null;
            $signManagerHash = null;
        }

        if ($this->form['sign_Schuler']) {
            $signSchulerPath = $this->form['sign_Schuler']->store('signatures', 'public');
            $signSchulerHash = md5_file(storage_path('app/public/' . $signSchulerPath));
        } else {
            $signSchulerPath = null;
            $signSchulerHash = null;
        }

        if ($this->form['image_Schuler']) {
            $imageSchulerPath = $this->form['image_Schuler']->store('images', 'public');
            $imageSchulerHash = md5_file(storage_path('app/public/' . $imageSchulerPath));
        } else {
            $imageSchulerPath = null;
            $imageSchulerHash = null;
        }

        // Création du formulaire
        \App\Models\Formulaire::create([
            'name_Firma' => $this->form['name_Firma'],
            'name_Manager' => $this->form['name_Manager'],
            'land_Firma' => $this->form['land_Firma'],
            'name_Schuler' => $this->form['name_Schuler'],
            'land_Schuler' => $this->form['land_Schuler'],
            'date_in' => $this->form['date_in'],
            'date_out' => $this->form['date_out'],
            'sign_Manager' => $signManagerPath,
            'sign_Manager_hash' => $signManagerHash,
            'sign_Schuler' => $signSchulerPath,
            'sign_Schuler_hash' => $signSchulerHash,
            'image_Schuler' => $imageSchulerPath,
            'image_Schuler_hash' => $imageSchulerHash,
        ]);

        session()->flash('message', 'Formulaire enregistré avec succès !');

        // Réinitialiser le formulaire
        $this->closeFormModal();
    }

    public function getFilteredFormulairesProperty()
    {
        $formulaires = Formulaire::latest()->get();

        if (empty($this->formulaireSearch)) {
            return $formulaires;
        }

        $search = strtolower($this->formulaireSearch);

        return $formulaires->filter(function ($formulaire) use ($search) {
            return stripos($formulaire->name_Schuler, $search) !== false
                || stripos($formulaire->name_Firma, $search) !== false;
        });
    }

    public function sortForms($field)
    {
        // Si on clique sur le même champ, on inverse la direction
        if ($this->formSortField === $field) {
            $this->formSortDirection = $this->formSortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            // Sinon on change de champ et on met asc par défaut
            $this->formSortField = $field;
            $this->formSortDirection = 'asc';
        }
    }



    public function filterOption($option)
    {
        // appliquer le filtre choisi
        $this->dispatchBrowserEvent('notify', ['message' => "Filtré par $option"]);
        $this->showFilterDropdown = false; // fermer le dropdown après sélection
    }

    public function sortBy($field)
    {
        // appliquer le tri
        $this->dispatchBrowserEvent('notify', ['message' => "Trié par $field"]);
        $this->showSortDropdown = false; // fermer le dropdown après sélection
    }


    public function getPaginatedFoldersProperty()
    {
        $filtered = $this->filteredFolders ?? [];
        $perPage = 10;
        $start = ($this->page - 1) * $perPage;

        return array_slice($filtered, $start, $perPage);
    }

    public function getTotalPagesProperty()
    {
        $filtered = $this->filteredFolders ?? [];
        return max(1, ceil(count($filtered) / 10));
    }

    public function getStatsProperty()
    {
        $folders = $this->folders ?? [];
        $schulers = $this->schulers ?? [];

        return [
            'total_dossiers' => count($folders),
            'total_eleves' => count($schulers),
            'total_ecoles' => count(array_unique(array_column($schulers, 'ecole'))) ?: 0,
            'total_entreprises' => count(array_unique(array_column($schulers, 'entreprise'))) ?: 0,
        ];
    }

    public function previousPage()
    {
        if ($this->page > 1) {
            $this->page--;
        }
    }

    public function nextPage()
    {
        if ($this->page < $this->totalPages) {
            $this->page++;
        }
    }

    public function goToPage($page)
    {
        $page = max(1, min($page, $this->totalPages));
        $this->page = $page;
    }

    // Actions simples
    public function exportData()
    {
        $this->dispatch('notify', message: 'Export démarré', type: 'info');
    }

    public function createNewDossier()
    {
        $this->dispatch('notify', message: 'Création nouveau dossier', type: 'info');
    }

    public function viewFolder($id)
    {
        $this->dispatch('notify', message: "Voir dossier #{$id}", type: 'info');
    }

    public function editFolder($id)
    {
        $this->dispatch('notify', message: "Éditer dossier #{$id}", type: 'warning');
    }

    public function deleteFolder($id)
    {
        $this->dispatch('notify', message: "Supprimer dossier #{$id}", type: 'error');
    }

    public function render()
    {
        return view('livewire.dashboard', [
            'tabs' => $this->tabs,
            'folders' => $this->folders,
            'filteredFolders' => $this->filteredFolders,
            'paginatedFolders' => $this->paginatedFolders,
            'totalPages' => $this->totalPages,
            'page' => $this->page,
            'stats' => $this->stats,
            'formulaires' => $this->filteredFormulaires,
            'formulaires' => Formulaire::orderBy($this->formSortField, $this->formSortDirection)->get(),
        ]);
    }
}
