<?php

namespace App\Http\Controllers;

use App\Models\Schuler;
use Illuminate\Http\Request;

class SchulerController extends Controller
{
    public function index()
    {
        $schulers = Schuler::with(['schule', 'firma', 'ausbildung', 'dossier'])
            ->orderBy('familiename')
            ->paginate(20);

        return view('schulers.index', compact('schulers'));
    }

    public function show($id)
    {
        $schuler = Schuler::with(['schule', 'firma', 'ausbildung', 'dokument', 'dossier'])
            ->findOrFail($id);

        return view('schulers.show', compact('schuler'));
    }
}