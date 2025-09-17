<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Dossier;
use App\Models\Schuler;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    use WithPagination;

    public $activeTab = 'Dossiers';
    public $openFolders = [];
    public $tabs = ['Dossiers', 'Élèves', 'Entreprises', 'Écoles'];
    public $search = '';
    public $filteredFolders = [];
    public $currentPage = 1;
    public $perPage = 10;
    public $totalPages = 0;
    public $folders = [];
    public $schulers = [];
    public $filteredSchulers = [];
    public $schulerSearch = '';

    public function mount()
    {
        $this->folders = $this->getFolders();
        $this->filterFolders();
        $this->schulers = $this->getSchulers();
        $this->filterSchulers();
    }

    protected function getSchulers()
    {
        if (!class_exists('App\Models\Schuler') || !Schema::hasTable('schuler')) {
            Log::error('Model Schuler ou table schuler non trouvés');
            return [];
        }

        try {
            $schulers = Schuler::with(['schule', 'firma', 'ausbildung'])
                ->get();

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
                    'date_debut' => $schuler->datum_Anfang_Ausbildung ?? 'N/A',
                    'date_fin' => $schuler->datum_Ende_Ausbildung ?? 'N/A'
                ];
            })->toArray();
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des élèves: ' . $e->getMessage());
            return [];
        }
    }

    public function filterSchulers()
    {
        if (empty($this->schulerSearch)) {
            $this->filteredSchulers = $this->schulers;
        } else {
            $this->filteredSchulers = array_filter($this->schulers, function ($schuler) {
                return stripos($schuler['prenom'] . ' ' . $schuler['nom'], $this->schulerSearch) !== false ||
                    stripos($schuler['email'], $this->schulerSearch) !== false ||
                    stripos($schuler['ecole'], $this->schulerSearch) !== false;
            });
        }
    }

    public function render()
    {
        $this->filterFolders();

        if ($this->activeTab === 'Élèves') {
            $this->filterSchulers();
        }

        return view('livewire.dashboard', [
            'folders' => $this->folders,
            'filteredFolders' => $this->getPaginatedFolders(),
            'totalPages' => $this->totalPages,
            'currentPage' => $this->currentPage,
            'schulers' => $this->schulers,
            'filteredSchulers' => $this->filteredSchulers,
        ]);
    }

    protected function getFolders()
    {
        if (!class_exists('App\Models\Dossier') || !Schema::hasTable('dossier')) {
            Log::error('Model Dossier ou table dossier non trouvés');
            return [];
        }

        try {
            Log::info('Tentative de récupération des dossiers...');

            $dossiers = Dossier::withCount([
                'dokumente',
                'ausbildungen',
                'schulen',
                'firmen',
                'schulers'
            ])
                ->get();

            Log::info('Nombre de dossiers récupérés: ' . $dossiers->count());

            return $dossiers->map(function ($dossier) {
                return [
                    'id' => $dossier->id_Dossier,
                    'name' => $dossier->name_Dossier,
                    'stats' => [
                        'documents' => $dossier->dokumente_count,
                        'formations' => $dossier->ausbildungen_count,
                        'écoles' => $dossier->schulen_count,
                        'entreprises' => $dossier->firmen_count,
                        'élèves' => $dossier->schulers_count
                    ]
                ];
            })->toArray();
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des dossiers: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());
            return [];
        }
    }

    public function filterFolders()
    {
        if (empty($this->search)) {
            $this->filteredFolders = $this->folders;
        } else {
            $this->filteredFolders = array_filter($this->folders, function ($folder) {
                return stripos($folder['name'], $this->search) !== false;
            });
        }

        $this->totalPages = ceil(count($this->filteredFolders) / $this->perPage);
        $this->totalPages = max(1, $this->totalPages);
        $this->currentPage = min($this->currentPage, $this->totalPages);
    }

    protected function getPaginatedFolders()
    {
        if (empty($this->filteredFolders)) {
            return [];
        }

        return array_slice(
            $this->filteredFolders,
            ($this->currentPage - 1) * $this->perPage,
            $this->perPage
        );
    }

    public function updatedSearch()
    {
        $this->filterFolders();
        $this->currentPage = 1;
    }

    public function updatedSchulerSearch()
    {
        $this->filterSchulers();
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        if ($tab === 'Élèves') {
            $this->filterSchulers();
            Log::info('Onglet Élèves cliqué');
            Log::info('Nombre d\'élèves: ' . count($this->schulers));
        }
    }

    public function previousPage()
    {
        if ($this->currentPage > 1) {
            $this->currentPage--;
        }
    }

    public function nextPage()
    {
        if ($this->currentPage < $this->totalPages) {
            $this->currentPage++;
        }
    }

    public function goToPage($page)
    {
        if ($page >= 1 && $page <= $this->totalPages) {
            $this->currentPage = $page;
        }
    }

    public function exportData()
    {
        $this->dispatchBrowserEvent('notify', ['message' => 'Exportation démarrée']);
    }

    public function createNewDossier()
    {
        $this->dispatchBrowserEvent('notify', ['message' => 'Création d\'un nouveau dossier']);
    }

    public function viewFolder($folderId)
    {
        $this->dispatchBrowserEvent('notify', ['message' => 'Visualisation du dossier ' . $folderId]);
    }

    public function editFolder($folderId)
    {
        $this->dispatchBrowserEvent('notify', ['message' => 'Édition du dossier ' . $folderId]);
    }

    public function deleteFolder($folderId)
    {
        $this->dispatchBrowserEvent('notify', ['message' => 'Suppression du dossier ' . $folderId]);
    }
}
