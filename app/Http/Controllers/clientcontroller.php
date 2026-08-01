<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\client;

class clientcontroller extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search ?? '' ;
        $client = client::where('nom','LIKE',"%$search%")
        ->orWhere('numtel','LIKE',"%$search%")
        ->get();
        return view('client', compact('client','search'));
    }
    public function store(Request $request)
    {
        client::create([
            'nom'=> $request->nom,
            'numtel'=> $request->numtel,
        ]);
        return back()->with('success','Client ajouté');
    }
    public function update(Request $request,$id)
    {
        $clients = client::findOrFail($id);
        $clients ->update([
            'nom'=> $request->nom,
            'numtel'=> $request->numtel
            ]);
            return redirect()->back()
            -> with('success','Client modifié');
    }
}
