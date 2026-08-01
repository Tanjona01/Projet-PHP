<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Reçu N° {{ $reservation->idreserv }}</title>
    <style>
        body { font-family: Arial, sans-serif;margin: 60px; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px;  margin-top: 20px; }
        td { padding: 7px 10px; }
        td.label { font-weight: bold; width: 200px; }
        label.form-label { font-weight: bold; margin-left: 10px; width: 200px; }
        h3 {  font-size: 18px;margin-bottom: 20px;margin-left: 130px; }
        .montants td { font-size: 15px; }
        .total-row td { font-size: 16px; font-weight: bold; border-top: 2px solid #333; }
         @media print { 
             .no-print { display: none; } 
             body { margin: 20px; } 
         } 
    </style>
</head>
<body>
<div class="no-print" style="margin-bottom:20px;">
    <button onclick="window.print()"
        style="padding:8px 16px;background:#007bff;color:white;border:none;border-radius:4px;cursor:pointer;">
        Générer PDF
    </button>
</div>


<h3>Reçu N° {{ $reservation->idreserv }}</h3>
<br>
<br>
<table id="apropos">
    <tr>
        <td class="label">Date du réservation : </td>
        <td> {{ \Carbon\Carbon::parse($reservation->date_reserv)->locale('fr')->isoFormat('D MMMM YYYY') }}</td>
    </tr>
    <tr>
        <td class="label">Date du voyage : </td>
        <td> {{ \Carbon\Carbon::parse($reservation->date_voyage)->locale('fr')->isoFormat('D MMMM YYYY') }}</td>
    </tr>
    <tr>
        <td class="label">Nom du Client :
        {{ $reservation->client ? $reservation->client->nom : '-' }} 
        </td>
        <td> <label class="form-label"> Contact :</label>{{ $reservation->client ? $reservation->client->numtel : '-' }}</td>
    </tr>    
    <tr>
        <td class="label">Voiture N° :
        {{ $reservation->idvoit }} </td> 
        <td><label class="form-label"> Type de Voiture :</label>
        {{ $reservation->voiture ? ucfirst($reservation->voiture->type) : '-' }}</td>
    </tr>
    <tr>
        <td class="label">Place :
        {{ $reservation->place }}</td>
    </tr>
    <tr>
        <td class="label">Désignation :
        {{ $reservation->voiture ? $reservation->voiture->design : '-' }}</td>
    </tr>
</table>

<br>

@php
    $frais  = $reservation->voiture ? $reservation->voiture->frais : 0;
    $avance = $reservation->montant_avance;
    $reste  = $frais - $avance;
@endphp

<table class="montants">
    <tr>
        <td class="label">Frais :
        {{ number_format($frais, 0, ',', '.') }} Ar</td>
    </tr>
    <tr>
        <td class="label">Payement :
        {{ ucfirst($reservation->payement) }}</td>
    </tr>
    @if($reservation->payement === 'avec avance')
    <tr>
        <td class="label">Montant Avance :
        {{ number_format($avance, 0, ',', '.') }} Ar</td>
    </tr>
    <tr>
        <td class="label">Reste :
        {{ number_format($reste, 0, ',', '.') }} Ar</td>
    </tr>
    @elseif($reservation->payement === 'tout payé')
    <tr>
        <td class="label">Montant payé :
        {{ number_format($frais, 0, ',', '.') }} Ar</td>
        <td ><label class="form-label"> Reste : </label> 0 Ar</td>
    </tr>
    @else
    <tr>
        <td class="label"> Avance : 0 Ar</td>
        <td>
        <label class="form-label">  Reste à payer :</label> {{ number_format($frais, 0, ',', '.') }} Ar</td>       
    </tr>
    @endif
</table>

</body>
</html>
