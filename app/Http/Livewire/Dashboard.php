<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Autorites;
use App\Models\Dokumente;
use App\Models\Firma;
use App\Models\Land;
use App\Models\Schule;
use App\Models\Schuler;
use App\Models\Formulaire;
use Illuminate\Support\Facades\Log;

class Dashboard extends Component
{
    use WithPagination, WithFileUploads;

    // Propriété pour chaques méthodes
    public $layout = 'app';
    public $activeTab = 'Land';
    public array $tabs = ['Land', 'Schuler', 'Firma', 'Schule', 'Formulaires'];

    // Tri pour les formulaires
    public $formSortField = 'created_at'; // champ par défaut
    public $formSortDirection = 'desc';
    public $formulaireSearch = '';
    public $formulairesPage = 1;

    // Tri pour les Land
    public $landSortField = 'nameLand';
    public $landSortDirection = 'asc';
    public $landSearch = '';
    public $landPage = 1;

    // Tri pour les schuler
    public $schulerSortField = 'vorname';
    public $schulerSortDirection = 'asc';
    public $schulerSearch = '';
    public $schulersPage = 1;

    // Tri pour les schule
    public $schuleSortField = 'name_Schule';
    public $schuleSortDirection = 'asc';
    public $schuleSearch = '';
    public $schulenPage = 1;

    // Tri pour les firma
    public $firmaSortField = 'name_Firma';
    public $firmaSortDirection = 'asc';
    public $firmaSearch = '';
    public $firmaPage = 1;

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
        $this->activeTab = 'Land';
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage(); // reset pagination si nécessaire
    }

    // Propriété pour chaques méthodes

    //Land

        public function getLandProperty()
        {
            $query = Land::with(['schulen']) // Charger les écoles associées
                ->withCount(['schulen']);    // Compter le nombre d’écoles par pays

            // Filtrage (si tu cherches un pays)
            if (!empty($this->landSearch)) {
                $query->where('nameLand', 'like', '%' . $this->landSearch . '%');
            }

            return $query
                ->orderBy($this->landSortField ?? 'nameLand', $this->landSortDirection ?? 'asc')
                ->paginate(10);
        }

        public function getFilteredLandsProperty()
        {
            $lands = $this->land ?? collect();

            if (!empty($this->search)) {
                $lands = $lands->filter(function ($land) {
                    return stripos($land->nameLand, $this->search) !== false;
                });
            }

            return $lands;
        }

        public function sortLandsByName()
        {
            $this->landSortDirection = $this->landSortDirection === 'asc' ? 'desc' : 'asc';
        }

        public function sortLands($field)
        {
            if ($this->landSortField === $field) {
                $this->landSortDirection = $this->landSortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                $this->landSortField = $field;
                $this->landSortDirection = 'asc';
            }

            $this->resetPage();
        }

        public function viewLand($id)
        {
            $this->dispatch('notify', message: "Voir le pays #{$id}", type: 'info');
        }

    //Land

    //Schulers

    public function getSchulersProperty()
    {
        $query = Schuler::with(['schule', 'firma']);

        if (!empty($this->schulerSearch)) {
            $search = $this->schulerSearch;
            $query->where(function ($q) use ($search) {
                $q->where('vorname', 'like', "%{$search}%")
                    ->orWhere('familiename', 'like', "%{$search}%");
            });
        }

        $query->orderBy($this->schulerSortField ?? 'familiename', $this->schulerSortDirection ?? 'asc');

        return $query->paginate(10)->through(function ($schuler) {
            return [
                'id' => $schuler->id_Schuler,
                'vorname' => $schuler->vorname ?? 'N/A',
                'familiename' => $schuler->familiename ?? 'N/A',
                'email' => $schuler->email ?? 'N/A',
                'land_Schuler' => $schuler->land_Schuler ?? 'N/A',
                'deutschniveau_Schuler' => $schuler->deutschniveau_Schuler ?? 'N/A',
                'bildungsniveau_Schuler' => $schuler->bildungsniveau_Schuler ?? 'N/A',
                'datum_Anfang_Ausbildung' => $schuler->datum_Anfang_Ausbildung?->format('d/m/Y') ?? 'N/A',
                'datum_Ende_Ausbildung' => $schuler->datum_Ende_Ausbildung?->format('d/m/Y') ?? 'N/A',
                'schule' => $schuler->schule->name_Schule ?? 'Non assigné',
                'firma' => $schuler->firma->name_Firma ?? 'Non assigné',
                'pays' => $schuler->land->nameLand ?? 'Non assigné',
            ];
        });
    }

    public function sortSchulers($field)
    {
        $allowed = ['vorname', 'familiename', 'id_Schuler', 'nameLand'];
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

    //Firma

    public function getFirmenProperty()
    {
        $query = Firma::query()
            ->when($this->firmaSearch, function ($q) {
                $q->where('name_Firma', 'like', '%' . $this->firmaSearch . '%')
                    ->orWhere('manager_Firma', 'like', '%' . $this->firmaSearch . '%')
                    ->orWhere('land_Firma', 'like', '%' . $this->firmaSearch . '%')
                    ->orWhere('idLand', 'like', '%' . $this->firmaSearch . '%');
            });

        return $query
            ->orderBy($this->firmaSortField, $this->firmaSortDirection)
            ->paginate(10);
    }

    public function sortFirmen($field = 'name_Firma')
    {
        if ($this->firmaSortField === $field) {
            $this->firmaSortDirection = $this->firmaSortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->firmaSortField = $field;
            $this->firmaSortDirection = 'asc';
        }

        $this->resetPage();
    }

    //Firma

    //Ecoles

    public function getSchulenProperty()
    {
        $query = Schule::with('land')
            ->when($this->schuleSearch, function ($q) {
                $q->where('name_Schule', 'like', '%' . $this->schuleSearch . '%')
                    ->orWhere('land_Schule', 'like', '%' . $this->schuleSearch . '%')
                    ->orWhere('name_Schulleiter', 'like', '%' . $this->schuleSearch . '%');
            });

        return $query
            ->orderBy($this->schuleSortField, $this->schuleSortDirection)
            ->paginate(10);
    }

    public function sortSchulen($field)
    {
        if ($this->schuleSortField === $field) {
            $this->schuleSortDirection = $this->schuleSortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->schuleSortField = $field;
            $this->schuleSortDirection = 'asc';
        }

        $this->resetPage();
    }

    //Ecoles

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

    public function getFormulairesProperty()
    {
        $query = Formulaire::query()
            ->when($this->formulaireSearch, function ($q) {
                $q->where('name_Schuler', 'like', '%' . $this->formulaireSearch . '%')
                    ->orWhere('name_Firma', 'like', '%' . $this->formulaireSearch . '%');
            });

        return $query
            ->orderBy($this->formSortField, $this->formSortDirection)
            ->paginate(5); // Ajustez le nombre de résultats par page si nécessaire
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
        $lands = $this->lands ?? collect();
        $schulers = $this->schulers ?? collect();
        $firmen = $this->firmen ?? collect();

        return [
            'total_lands' => Land::count(),
            'total_schulers' => Schuler::count(),
            'total_firmen' => Firma::count(),
            'total_schulen' => Schule::count(),
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

    public function getTotalPagesLandsProperty()
    {
        $filtered = $this->filteredLands ?? [];
        return max(1, ceil(count($filtered) / 10));
    }

    public function getTotalPagesSchulersProperty()
    {
        $filtered = $this->filteredSchulers ?? [];
        return max(1, ceil(count($filtered) / 10));
    }

    public function getTotalPagesFirmenProperty()
    {
        $filtered = $this->filteredFirmen ?? [];
        return max(1, ceil(count($filtered) / 10));
    }

    public function getTotalPagesSchulenProperty()
    {
        $filtered = $this->filteredSchulen ?? [];
        return max(1, ceil(count($filtered) / 10));
    }

    public function getTotalPagesFormulairesProperty()
    {
        $filtered = $this->filteredFormulaires ?? [];
        return max(1, ceil(count($filtered) / 10));
    }

    public function previousSchulerPage()
    {
        if ($this->schulersPage > 1) {
            $this->schulersPage--;
        }
    }

    public function nextSchulerPage()
    {
        if ($this->schulersPage < $this->totalPagesSchulers) {
            $this->schulersPage++;
        }
    }

    public function gotoSchulerPage($page)
    {
        $page = max(1, min($page, $this->totalPagesSchulers));
        $this->schulersPage = $page;
    }


    public function previousSchulenPage()
    {
        if ($this->schulenPage > 1) {
            $this->schulenPage--;
        }
    }

    public function nextSchulenPage()
    {
        if ($this->schulenPage < $this->totalPagesSchulen) {
            $this->schulenPage++;
        }
    }

    public function gotoSchulenPage($page)
    {
        $page = max(1, min($page, $this->totalPagesSchulen));
        $this->schulenPage = $page;
    }


    public function previousLandsPage()
    {
        if ($this->foldersPage > 1) {
            $this->landPage--;
        }
    }

    public function nextLandsPage()
    {
        if ($this->landPage < $this->totalPagesLands) {
            $this->landPage++;
        }
    }

    public function gotoLandsPage($page)
    {
        $page = max(1, min($page, $this->totalPagesLands));
        $this->landPage = $page;
    }

    public function previousFormulairesPage()
    {
        if ($this->formulairesPage > 1) {
            $this->formulairesPage--;
        }
    }

    public function nextFormulairesPage()
    {
        if ($this->formulairesPage < $this->totalPagesFormulaires) {
            $this->formulairesPage++;
        }
    }

    public function gotoFormulairesPage($page)
    {
        $page = max(1, min($page, $this->totalPagesFormulaires));
        $this->formulairesPage = $page;
    }

    public function previousFirmaPage()
    {
        if ($this->firmaPage > 1) {
            $this->firmaPage--;
        }
    }

    public function nextFirmaPage()
    {
        if ($this->firmaPage < $this->totalPagesFirmen) {
            $this->firmaPage++;
        }
    }

    public function gotoFirmaPage($page)
    {
        $page = max(1, min($page, $this->totalPagesFirmen));
        $this->firmaPage = $page;
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
            'stats' => $this->stats,
            'lands' => $this->land,
            'formulaires' => $this->formulaires,
            'schulers' => $this->schulers,
            'firmen' => $this->firmen,
            'schulen' => $this->schulen,
            'totalPagesSchulers' => $this->totalPagesSchulers,
            'totalPagesSchulen' => $this->totalPagesSchulen,
            'totalPagesLands' => $this->totalPagesLands,
            'totalPagesFormulaires' => $this->totalPagesFormulaires,
            'totalPagesFirmen' => $this->totalPagesFirmen,
            'schulersPage' => $this->schulersPage,
            'schulenPage' => $this->schulenPage,
            'firmaPage' => $this->firmaPage,
            'formulairesPage' => $this->formulairesPage,
            'showSchulerModal' => $this->showSchulerModal,
        ]);
    }
}
