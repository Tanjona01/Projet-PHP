<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\client;

class clientcontroller extends Controller
{
    private array $rules = [
        'nom'    => ['required', 'regex:/^[\pL \'-]+$/u'],
        'numtel' => ['required', 'regex:/^03[0-9]{8}$/'],
    ];

    private array $messages = [
        'nom.required'    => 'Le nom est obligatoire.',
        'nom.regex'       => 'Le nom ne doit contenir que des lettres.',
        'numtel.required' => 'Le numéro de téléphone est obligatoire.',
        'numtel.regex'    => 'Le numéro doit commencer par 03 et contenir 10 chiffres.',
    ];

    public function index(Request $request)
    {
        $search = $request->search ?? '';
        $client = client::where('nom', 'LIKE', "%$search%")
            ->orWhere('numtel', 'LIKE', "%$search%")
            ->get();
        return view('client', compact('client', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate($this->rules, $this->messages);

        client::create([
            'nom'    => $request->nom,
            'numtel' => $request->numtel,
        ]);
        return back()->with('success', 'Client ajouté');
    }

    public function update(Request $request, $id)
    {
        $request->validate($this->rules, $this->messages);

        $clients = client::findOrFail($id);
        $clients->update([
            'nom'    => $request->nom,
            'numtel' => $request->numtel,
        ]);
        return redirect()->back()->with('success', 'Client modifié');
    }

    public function destroy($id)
    {
        $clients = client::findOrFail($id);

        $reservations = \App\Models\reservation::where('idcli', $id)->get();
        foreach ($reservations as $r) {
            \App\Models\place::where('idvoit', $r->idvoit)
                ->where('place', $r->place)
                ->update(['occupation' => 'non']);
        }

        $clients->delete();
        return back()->with('success', 'Client supprimé');
    }
}
