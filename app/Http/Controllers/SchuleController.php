<?php

namespace App\Http\Controllers;

use App\Models\Schule;
use Illuminate\Http\Request;

class SchuleController extends Controller
{
    public function index()
    {
        $schulen = Schule::with(['dossier', 'ausbildung'])
            ->orderBy('name_Schule')
            ->paginate(20);

        return view('schulen.index', compact('schulen'));
    }

    public function show($id)
    {
        $schule = Schule::with(['dossier', 'ausbildung', 'schulers'])
            ->findOrFail($id);

        return view('schulen.show', compact('schule'));
    }
}