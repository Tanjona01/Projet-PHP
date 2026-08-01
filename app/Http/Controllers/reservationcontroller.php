<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\reservation;
use App\Models\place;
use App\Models\voiture;
use App\Models\client;

class reservationcontroller extends Controller
{
    public function index()
    {
        $reservations = reservation::with(['voiture', 'client'])->get();
        $voitures = voiture::all();
        $clients = client::all();
        return view('reservation', compact('reservations', 'voitures', 'clients'));
    }

    private function corrigerPaiement(string $payement, int $montant_avance, int $frais): array
    {
        if ($payement === 'avec avance') {
            if ($montant_avance <= 0) {
                return ['sans avance', 0];
            }
            if ($montant_avance >= $frais) {
                return ['tout payé', $frais];
            }
        }
 
        if ($payement === 'sans avance') {
            return ['sans avance', 0];
        }
        if ($payement === 'tout payé') {
            return ['tout payé', $frais];
        }
        return [$payement, $montant_avance];
    }

    public function store(Request $request)
    {
        $last = reservation::orderBy('idreserv', 'desc')->first();
        $number = $last ? (int) str_replace('R', '', $last->idreserv) + 1 : 1;
        $idreserv = 'R' . $number;

        $voitureObj = voiture::findOrFail($request->idvoit);
        $frais = (int) $voitureObj->frais;
        $montant_avance = (int) ($request->montant_avance ?: 0);

        [$payement, $montant_avance] = $this->corrigerPaiement(
            $request->payement, $montant_avance, $frais
        );

        reservation::create([
            'idreserv'       => $idreserv,
            'idvoit'         => $request->idvoit,
            'idcli'          => $request->idcli,
            'place'          => $request->place,
            'date_reserv'    => now()->format('Y-m-d H:i:s'),
            'date_voyage'    => $request->date_voyage,
            'payement'       => $payement,
            'montant_avance' => $montant_avance,
        ]);

        place::where('idvoit', $request->idvoit)
             ->where('place', $request->place)
             ->update(['occupation' => 'oui']);

        return back()->with('success', 'Réservation créée avec succès !');
    }

    public function update(Request $request, $id)
    {
        $resa = reservation::findOrFail($id);

        if ($resa->place != $request->place || $resa->idvoit != $request->idvoit) {
            place::where('idvoit', $resa->idvoit)
                 ->where('place', $resa->place)
                 ->update(['occupation' => 'non']);

            place::where('idvoit', $request->idvoit)
                 ->where('place', $request->place)
                 ->update(['occupation' => 'oui']);
        }

        $voitureObj = voiture::findOrFail($request->idvoit);
        $frais = (int) $voitureObj->frais;
        $montant_avance = (int) ($request->montant_avance ?: 0);

        [$payement, $montant_avance] = $this->corrigerPaiement(
            $request->payement, $montant_avance, $frais
        );

        $resa->update([
            'idvoit'         => $request->idvoit,
            'idcli'          => $request->idcli,
            'place'          => $request->place,
            'date_voyage'    => $request->date_voyage,
            'payement'       => $payement,
            'montant_avance' => $montant_avance,
        ]);

        return back()->with('success', 'Réservation mise à jour !');
    }

    public function placesLibres($idvoit)
    {
        $places = place::where('idvoit', $idvoit)
                       ->where('occupation', 'non')
                       ->orderBy('place')
                       ->get();
        return response()->json($places);
    }

    public function statsPaiement($idvoit)
    {
        $avecAvance = reservation::where('idvoit', $idvoit)->where('payement', 'avec avance')->count();
        $sansAvance = reservation::where('idvoit', $idvoit)->where('payement', 'sans avance')->count();
        $toutPaye   = reservation::where('idvoit', $idvoit)->where('payement', 'tout payé')->count();

        return response()->json([
            'avec_avance' => $avecAvance,
            'sans_avance' => $sansAvance,
            'tout_paye'   => $toutPaye,
        ]);
    }

    public function recetteTotal()
    {
        $total = reservation::join('voitures', 'reservations.idvoit', '=', 'voitures.idvoit')
                            ->selectRaw('SUM(voitures.frais) as total')
                            ->value('total');
        return response()->json(['total' => $total ?? 0]);
    }

    public function recu($id)
    {
        $reservation = reservation::with(['voiture', 'client'])->findOrFail($id);
        return view('recu', compact('reservation'));
    }
}
