<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des réservations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite('resources/js/app.js')
    @vite('resources/css/app.css')
</head>
<body>
<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container"><a class="navbar-brand">Gestion de réservation</a></div>
        <div class="container flex-grow-1 d-flex flex-column">
            <div class="d-flex gap-5" id="menu">
                <a class="navbar-link text-white text-decoration-none" href="/">Accueil</a>
                <a class="navbar-link text-white text-decoration-none" href="/client">Clients</a>
                <a class="navbar-link text-white text-decoration-none" href="/voiture">Voitures</a>
            </div>
        </div>
    </nav>
</header>

<button class="btn btn-primary" onclick="history.back()" id="retour">&leftarrow; Retour</button>

<div class="container mt-4">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header"><b>Ajouter une réservation</b></div>
        <div class="card-body">
            <form method="POST" action="{{ route('reservation.store') }}">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Voiture</label>
                        <select name="idvoit" id="idvoit_add" class="form-control" required
                            onchange="chargerPlaces(this.value, 'places_add'); majFraisAdd(this);">
                            <option value=""> Choisir une voiture</option>
                            @foreach($voitures as $v)
                                <option value="{{ $v->idvoit }}" data-frais="{{ $v->frais }}">
                                    {{ $v->idvoit }} - {{ $v->design }} ({{ ucfirst($v->type) }}) — {{ number_format($v->frais,0,',',' ') }} Ar
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Place libre</label>
                        <select name="place" id="places_add" class="form-control" required>
                            <option value=""> Sélectionner une voiture d'abord</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Client</label>
                        <select name="idcli" class="form-control" required>
                            <option value="">Choisir un(e) client(e)</option>
                            @foreach($clients as $c)
                                <option value="{{ $c->idcli }}">{{ $c->nom }} - {{ $c->numtel }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Date du voyage</label>
                        <input type="date" name="date_voyage" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Paiement</label>
                        <select name="payement" id="payement_add" class="form-control" required
                            onchange="toggleAvance('avance_add', this.value)">
                            <option value="sans avance">Sans avance</option>
                            <option value="avec avance">Avec avance</option>
                            <option value="tout payé">Tout payé</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3" id="avance_add" style="display:none">
                        <label>Montant avance (Ar)</label>
                        <input type="number" name="montant_avance" id="montant_add"
                            class="form-control" value="0" min="0"
                            oninput="verifierAvance('montant_add', fraisAdd, 'msg_add')">
                        <div id="msg_add" class="small mt-1"></div>
                    </div>
                </div>
                <button class="btn btn-success">Ajouter la réservation</button>
            </form>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header"><b>Afficher les places libres d'une voiture</b></div>
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <label>Choisir une voiture</label>
                    <select id="voiture_places" class="form-control" onchange="afficherPlacesLibres(this.value)">
                        <option value="">Appuyer pour choisir</option>
                        @foreach($voitures as $v)
                            <option value="{{ $v->idvoit }}">{{ $v->idvoit }} - {{ $v->design }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-8 mt-3">
                    <div id="places_libres_result"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header"><b>Voyageurs par statut de paiement (par voiture)</b></div>
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <label>Choisir une voiture</label>
                    <select id="voiture_stats" class="form-control" onchange="afficherStats(this.value)">
                        <option value="">Appuyer pour choisir </option>
                        @foreach($voitures as $v)
                            <option value="{{ $v->idvoit }}">{{ $v->idvoit }} - {{ $v->design }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-8 mt-3">
                    <div id="stats_result"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header"><b>Recette totale accumulée par la coopérative</b></div>
        <div class="card-body">
            <button class="btn btn-info" onclick="afficherRecette()">Calculer la recette</button>
            <div id="recette_result" class="mt-3 fs-5 fw-bold text-success"></div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header"><b>Liste des réservations</b></div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-sm align-middle">
                <thead>
                    <tr>
                        <th>N° Reçu</th>
                        <th>Date réservation</th>
                        <th>Date voyage</th>
                        <th>Client</th>
                        <th>Voiture</th>
                        <th>N° Place</th>
                        <th>Frais (Ar)</th>
                        <th>Paiement</th>
                        <th>Avance (Ar)</th>
                        <th>Reste (Ar)</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservations as $r)
                    @php
                        $frais  = $r->voiture ? $r->voiture->frais : 0;
                        $avance = $r->montant_avance;
                        $reste  = ($r->payement == 'tout payé') ? 0 : $frais - $avance;
                    @endphp
                    <tr>
                        <td>{{ $r->idreserv }}</td>
                        <td>{{ \Carbon\Carbon::parse($r->date_reserv)->format('Y-m-d H:i:s') }}</td>
                        <td>{{ \Carbon\Carbon::parse($r->date_voyage)->format('Y-m-d') }}</td>
                        <td>{{ $r->client ? $r->client->nom : '-' }}</td>
                        <td>{{ $r->idvoit }}</td>
                        <td class="text-center">{{ $r->place }}</td>
                        <td>{{ number_format($frais, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge
                                @if($r->payement=='tout payé') bg-success
                                @elseif($r->payement=='avec avance') bg-warning text-dark
                                @else bg-danger @endif">
                                {{ $r->payement }}
                            </span>
                        </td>
                        <td>{{ number_format($avance, 0, ',', '.') }}</td>
                        <td>{{ number_format($reste, 0, ',', '.') }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#editModal{{ $r->idreserv }}">Modifier</button>
                                <a href="{{ route('reservation.recu', $r->idreserv) }}"
                                    class="btn btn-secondary btn-sm" target="_blank">Reçu</a>
                            </div>
                        </td>
                    </tr>

                    <div class="modal fade" id="editModal{{ $r->idreserv }}" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Modifier réservation {{ $r->idreserv }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form method="POST" action="{{ route('reservation.update', $r->idreserv) }}">
                                    @csrf
                                    @method('PUT')
                                    @php
                                        $fraisEdit   = $r->voiture ? $r->voiture->frais : 0;
                                        $displayEdit = $r->payement == 'avec avance' ? 'block' : 'none';
                                    @endphp
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label>Voiture</label>
                                                <select name="idvoit" class="form-control" required>
                                                    @foreach($voitures as $v)
                                                        <option value="{{ $v->idvoit }}" {{ $r->idvoit==$v->idvoit ? 'selected':'' }}>
                                                            {{ $v->idvoit }} - {{ $v->design }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label>Place (N°)</label>
                                                <input type="number" name="place" class="form-control"
                                                    value="{{ $r->place }}" min="1" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label>Client</label>
                                                <select name="idcli" class="form-control" required>
                                                    @foreach($clients as $c)
                                                        <option value="{{ $c->idcli }}" {{ $r->idcli==$c->idcli ? 'selected':'' }}>
                                                            {{ $c->nom }} - {{ $c->numtel }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label>Date du voyage</label>
                                                <input type="date" name="date_voyage" class="form-control"
                                                    value="{{ $r->date_voyage }}" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label>Paiement</label>
                                                <select name="payement" id="payement_edit_{{ $r->idreserv }}"
                                                    class="form-control" required
                                                    onchange="toggleAvance('avance_edit_{{ $r->idreserv }}', this.value)">
                                                    <option value="sans avance" {{ $r->payement=='sans avance' ? 'selected':'' }}>Sans avance</option>
                                                    <option value="avec avance" {{ $r->payement=='avec avance' ? 'selected':'' }}>Avec avance</option>
                                                    <option value="tout payé"   {{ $r->payement=='tout payé'   ? 'selected':'' }}>Tout payé</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3"
                                                id="avance_edit_{{ $r->idreserv }}"style="display:{{ $displayEdit }}">
                                                <label>Montant avance (Ar) <small class="text-muted">/ Frais : {{ number_format($fraisEdit,0,',',' ') }} Ar</small></label>
                                                <input type="number" name="montant_avance"
                                                    id="montant_edit_{{ $r->idreserv }}"
                                                    class="form-control"value="{{ $r->montant_avance }}" min="0" oninput="verifierAvance('montant_edit_{{ $r->idreserv }}',{{ $fraisEdit }},'msg_edit_{{ $r->idreserv }}')">
                                                <div id="msg_edit_{{ $r->idreserv }}" class="small mt-1"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                        <button type="submit" class="btn btn-success">Mettre à jour</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="11" class="text-center text-muted">Aucune réservation enregistrée.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.js"></script>
<script>
  
    let fraisAdd = 0;
    window.majFraisAdd = function(sel) {
        const opt = sel.options[sel.selectedIndex];
        fraisAdd = parseInt(opt.getAttribute('data-frais')) || 0;

        const msg = document.getElementById('msg_add');
        if (msg) msg.innerHTML = '';
    };
</script>

<footer class="bg-dark text-white text-center py-3 mt-5">
    <p class="mb-0">Auteur : Tanjona</p>
</footer>
</body>
</html>
