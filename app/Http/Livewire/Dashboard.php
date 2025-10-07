<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Dossier;
use App\Models\Schuler;
use App\Models\Formulaire;
use App\Models\SousDossier;
use Illuminate\Support\Facades\Log;

class Dashboard extends Component
{
    use WithPagination, WithFileUploads;

    // Propriété pour chaques méthodes
    public $layout = 'app';
    public $activeTab = 'Dossiers';
    public array $tabs = ['Dossiers', 'Élèves', 'Entreprises', 'Écoles', 'Formulaires'];

    public $search = '';
    public $schulerSearch = '';
    public $formulaireSearch = '';
    public $page = 1;

    // Tri pour les formulaires
    public $formSortField = 'created_at'; // champ par défaut
    public $formSortDirection = 'desc';

    // Tri pour les dossiers
    public $dossierSortField = 'name_Dossier';
    public $dossierSortDirection = 'asc';

    // Tri pour les élèves
    public $schulerSortField = 'familiename';
    public $schulerSortDirection = 'asc';


    public $showFormModal = false;
    public $showFilterDropdown = false;
    public $showSortDropdown = false;
    public $showSchulerModal = false; // Pour le modal des élèves
    public $schuler = []; // Données de l'élève

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

    // Propriété pour chaques méthodes

    //Dossiers

    public function getFoldersProperty()
    {
        $query = Dossier::with(['sousDossiers'])
            ->withCount(['dokumente', 'ausbildungen', 'schulers', 'schulen', 'firmen', 'sousDossiers']);

        if (!empty($this->search)) {
            $query->where('name_Dossier', 'like', '%' . $this->search . '%');
        }

        return $query->orderBy($this->dossierSortField, $this->dossierSortDirection)
            ->paginate(10);
    }


    public function getFilteredFoldersProperty()
    {
        $folders = $this->folders ?? collect();

        if (!empty($this->search)) {
            $folders = $folders->filter(function ($folder) {
                return stripos($folder->name_Dossier, $this->search) !== false;
            });
        }

        return $folders;
    }

    public function sortDossiersByName()
    {
        $this->dossierSortDirection = $this->dossierSortDirection === 'asc' ? 'desc' : 'asc';
    }

    // --- méthode appelée par le dropdown ---
    public function sortDossiers($field)
    {
        if ($this->dossierSortField === $field) {
            $this->dossierSortDirection = $this->dossierSortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->dossierSortField = $field;
            $this->dossierSortDirection = 'asc';
        }

        $this->resetPage();
    }

    public function viewFolder($id)
    {
        $this->dispatch('notify', message: "Voir dossier #{$id}", type: 'info');
    }



    //Dossiers

    //Schulers

    public function getSchulersProperty()
    {
        try {
            Log::info('Récupération des élèves...');

            $query = Schuler::with(['schule', 'firma', 'ausbildung']);

            // Appliquer le tri
            $query->orderBy($this->schulerSortField, $this->schulerSortDirection);

            // Pagination
            $schulers = $query->paginate(10);

            Log::info('Élèves récupérés: ' . $schulers->count());

            return $schulers->map(function ($schuler) {
                return [
                    'id' => $schuler->id_Schuler,
                    'prenom' => $schuler->vorname ?? 'N/A',
                    'nom' => $schuler->familiename ?? 'N/A',
                    'email' => $schuler->email ?? 'N/A',
                    'land_Schuler' => $schuler->land_Schuler ?? 'N/A',
                    'deutschniveau_Schuler' => $schuler->deutschniveau_Schuler ?? 'N/A',
                    'bildungsniveau_Schuler' => $schuler->bildungsniveau_Schuler ?? 'N/A',
                    'date_debut' => $schuler->datum_Anfang_Ausbildung?->format('d/m/Y') ?? 'N/A',
                    'date_fin' => $schuler->datum_Ende_Ausbildung?->format('d/m/Y') ?? 'N/A',
                    'ecole' => $schuler->schule->name_Schule ?? 'Non assigné',
                    'entreprise' => $schuler->firma->name_Firma ?? 'Non assigné',
                    'formation' => $schuler->ausbildung->name_Ausbildung ?? 'Non assigné',
                ];
            });
        } catch (\Exception $e) {
            Log::error('Erreur récupération élèves: ' . $e->getMessage());
            return []; // Toujours retourner un tableau vide en cas d'erreur
        }
    }


    public function getFilteredSchulersProperty()
    {
        return collect($this->schulers)->filter(function ($schuler) {
            $search = strtolower($this->schulerSearch);
            return stripos($schuler['prenom'] . ' ' . $schuler['nom'], $search) !== false ||
                stripos($schuler['email'] ?? '', $search) !== false ||
                stripos($schuler['ecole'] ?? '', $search) !== false ||
                stripos($schuler['entreprise'] ?? '', $search) !== false;
        })->values()->all();
    }

    // Exemple pour schulers (même logique)
    public function sortSchulers($field)
    {
        $allowed = ['vorname', 'familiename', 'id_Schuler'];
        $this->schulerSortField = in_array($field, $allowed) ? $field : 'familiename';
        $this->schulerSortDirection = $this->schulerSortDirection === 'asc' ? 'desc' : 'asc';
        $this->resetPage();
    }

    public function openSchulerModal()
    {
        $this->reset('schuler'); // Réinitialiser les champs du formulaire
        $this->showSchulerModal = true; // Ouvrir le modal
    }

    public function closeSchulerModal()
    {
        $this->showSchulerModal = false; // Fermer le modal
    }

    public function saveSchuler()
    {
        try {
            // Validation
            $this->validate([
                'schuler.vorname' => 'required|string|max:255',
                'schuler.familiename' => 'required|string|max:255',
                'schuler.geburtsdatum_Schuler' => 'required|date',
                'schuler.land_Schuler' => 'required|string|max:255',
                'schuler.deutschniveau_Schuler' => 'required|string|max:255',
                'schuler.bildungsniveau_Schuler' => 'required|string|max:255',
                'schuler.datum_Anfang_Ausbildung' => 'required|date',
                'schuler.datum_Ende_Ausbildung' => 'required|date',
                'schuler.email' => 'required|email|max:255',
            ]);

            // Création de l'élève
            Schuler::create($this->schuler);

            // Message de succès
            session()->flash('message', 'Élève enregistré avec succès !');

            // Réinitialiser le formulaire
            $this->reset('schuler');

            // Fermer le modal
            $this->closeSchulerModal();
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'enregistrement de l\'élève: ' . $e->getMessage());
            session()->flash('error', 'Une erreur est survenue lors de l\'enregistrement de l\'élève.');
        }
    }



    //Schulers

    //Formulaires

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
        // On applique le tri défini dans sortForms()
        $query = Formulaire::orderBy($this->formSortField, $this->formSortDirection);

        $formulaires = $query->get();

        // 🔎 Filtrage par recherche
        if (!empty($this->formulaireSearch)) {
            $search = strtolower($this->formulaireSearch);

            $formulaires = $formulaires->filter(function ($formulaire) use ($search) {
                return stripos($formulaire->name_Schuler, $search) !== false
                    || stripos($formulaire->name_Firma, $search) !== false;
            });
        }

        return $formulaires->values(); // remet les index à plat
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

    //Formulaires

    //Fonctionnalités partagées

    public function getStatsProperty()
    {
        $folders = $this->folders ?? collect();
        $schulers = $this->schulers ?? collect();

        return [
            'total_dossiers' => $folders->count(),
            'total_eleves' => $schulers->count(),
            'total_ecoles' => count(array_unique(array_column($schulers->toArray(), 'ecole'))) ?: 0,
            'total_entreprises' => count(array_unique(array_column($schulers->toArray(), 'entreprise'))) ?: 0,
        ];
    }

    // Méthodes pour basculer les dropdowns
    public function toggleFilter()
    {
        $this->showFilterDropdown = !$this->showFilterDropdown;
    }

    public function toggleSort()
    {
        $this->showSortDropdown = !$this->showSortDropdown;
    }

    public function getTotalPagesProperty()
    {
        $filtered = $this->filteredFolders ?? [];
        return max(1, ceil(count($filtered) / 10));
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

    public function updatedSearch()
    {
        $this->resetPage(); // Réinitialisez la pagination lorsque la recherche est mise à jour
    }

    //Fonctionnalités partagées

    // Actions simples

    public function exportData()
    {
        $this->dispatch('notify', message: 'Export démarré', type: 'info');
    }

    // Actions simples


    public function render()
    {
        return view('livewire.dashboard', [
            'tabs' => $this->tabs,
            'folders' => $this->folders,
            'totalPages' => $this->totalPages,
            'page' => $this->page,
            'stats' => $this->stats,
            'filteredFormulaires' => $this->filteredFormulaires,
            'filteredSchulers' => $this->filteredSchulers,
            'showSchulerModal' => $this->showSchulerModal,
        ]);
    }
}
