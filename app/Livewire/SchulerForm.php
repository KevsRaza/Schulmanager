<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Schuler;

class SchulerForm extends Component
{
    public $showSchulerModal = false; // Pour contrôler l'affichage du modal
    public $schuler = []; // Pour stocker les données de l'élève
    public $search = ''; // Pour la recherche d'élèves
    public $filteredSchuler; // Pour stocker les élèves filtrés

        public function mount()
    {
        $this->filteredSchuler = Schuler::all(); // Charger tous les élèves au démarrage
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
        // Validation
        $this->validate([
            'schuler.vorname' => 'required|string|max:255',
            'schuler.familiename' => 'required|string|max:255',
            'schuler.geburtsdatum_Schuler' => 'required|date',
            'schuler.land_Shuler' => 'required|string|max:255',
            'schuler.deutschniveau_Schuler' => 'required|string|max:255',
            'schuler.bildungsniveau_Schuler' => 'required|string|max:255',
            'schuler.datum_Anfang_Ausbildung' => 'required|date',
            'schuler.datum_Ende_Ausbildung' => 'required|date',
            'schuler.id_Firma' => 'required|integer',
            'schuler.id_Schule' => 'required|integer',
            'schuler.id_Ausbildung' => 'required|integer',
            'schuler.id_Dokument' => 'required|integer',
            'schuler.id_Dossier' => 'required|integer',
            'schuler.email' => 'required|email|max:255',
        ]);

        // Création de l'élève
        Schuler::create($this->schuler);

        session()->flash('message', 'Élève enregistré avec succès !');

        // Réinitialiser le formulaire
        $this->closeSchulerModal();
    }

    public function render()
    {
        return view('livewire.schuler-form');
    }
}

