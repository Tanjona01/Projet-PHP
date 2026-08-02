<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\voiture;
use App\Models\place;

class voiturecontroller extends Controller
{
    public function index()
    {
        $voitures = voiture::all();
        return view("voiture", compact("voitures"));
    }

    public function store(Request $request)
    {
        $last = voiture::orderBy('idvoit', 'desc')->first();
        if (!$last) {
            $number = 1;
        } else {
            $number = (int) str_replace('V', '', $last->idvoit) + 1;
        }
        $idvoit = 'V' . $number;

        voiture::create([
            'idvoit'   => $idvoit,
            'design'   => $request->design,
            'type'     => $request->type,
            'nbrplace' => $request->nbrplace,
            'frais'    => $request->frais,
        ]);

        for ($i = 1; $i <= $request->nbrplace; $i++) {
            place::create([
                'idvoit'     => $idvoit,
                'place'      => $i,
                'occupation' => 'non',
            ]);
        }

        return back()->with('success', 'Voiture ajoutée avec succès !');
    }

    public function update(Request $request, $id)
    {
        $voiture = voiture::findOrFail($id);
        $ancienNbrPlace = $voiture->nbrplace;
        $nouveauNbrPlace = (int) $request->nbrplace;

        $voiture->update([
            'design'   => $request->design,
            'type'     => $request->type,
            'nbrplace' => $nouveauNbrPlace,
            'frais'    => $request->frais,
        ]);
        
        if ($nouveauNbrPlace > $ancienNbrPlace) {
            
            for ($i = $ancienNbrPlace + 1; $i <= $nouveauNbrPlace; $i++) {
                place::firstOrCreate(
                    ['idvoit' => $id, 'place' => $i],
                    ['occupation' => 'non']
                );
            }
        } elseif ($nouveauNbrPlace < $ancienNbrPlace) {
            
           place::where('idvoit', $id)
                  ->where('place', '>', $nouveauNbrPlace)
                  ->where('occupation', 'non')
                  ->delete();
        }

        return redirect()->back()->with('success', 'Voiture mise à jour !');
    }

    public function destroy($id)
    {
        $voiture = voiture::findOrFail($id);

        \App\Models\reservation::where('idvoit', $id)->delete();
        place::where('idvoit', $id)->delete();
        $voiture->delete();

        return redirect()->back()->with('success', 'Voiture supprimée !');
    }
}
