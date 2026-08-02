<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des voitures</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
     @vite(['resources/css/app.css','resources/js/app.js'])
    
</head>
<body>
<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container"><a class="navbar-brand">Gestion de réservation</a></div>
        <div class="container flex-grow-1 d-flex flex-column">
            <div class="d-flex gap-5" id="menu">
                <a class="navbar-link text-white text-decoration-none" href="/">Accueil</a>
                <a class="navbar-link text-white text-decoration-none" href="/client">Clients</a>
                <a class="navbar-link text-white text-decoration-none" href="/reservation">Réservation</a>
            </div>
        </div>
    </nav>
</header>

<button class="btn btn-primary" onclick="history.back()" id="retour">&leftarrow; Retour</button>

<div class="container mt-4">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card mb-4">
        <div class="card-header"><b>Ajouter une voiture</b></div>
        <div class="card-body">
            <form method="POST" action="{{ route('voiture.store') }}">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Désignation</label>
                        <input type="text" name="design" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Type</label>
                        <select name="type" class="form-control" required>
                            <option value="simple">Simple</option>
                            <option value="premium">Premium</option>
                            <option value="VIP">VIP</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Nombre de places</label>
                        <input type="number" name="nbrplace" class="form-control" min="1" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Frais (Ar)</label>
                        <input type="number" name="frais" class="form-control" min="0" required>
                    </div>
                </div>
                <button class="btn btn-success">Ajouter</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><b>Liste des voitures</b></div>
        <div class="card-body">
            <table class="table table-bordered table-sm">
                <thead >
                    <tr>
                        <th>ID</th>
                        <th>Désignation</th>
                        <th>Type</th>
                        <th>Nb Places</th>
                        <th>Frais (Ar)</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($voitures as $voiture)
                    <tr>
                        <td>{{ $voiture->idvoit }}</td>
                        <td>{{ $voiture->design }}</td>
                        <td>
                            <span class="badge 
                                @if($voiture->type=='VIP') bg-warning text-dark
                                @elseif($voiture->type=='premium') bg-info text-dark
                                @else bg-secondary @endif">
                                {{ ucfirst($voiture->type) }}
                            </span>
                        </td>
                        <td>{{ $voiture->nbrplace }}</td>
                        <td>{{ number_format($voiture->frais, 0, ',', '.') }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#editModal{{ $voiture->idvoit }}">Modifier</button>
                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal{{ $voiture->idvoit }}">Supprimer</button>
                            </div>
                        </td>
                    </tr>
                    <div class="modal fade" id="editModal{{ $voiture->idvoit }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Modifier voiture {{ $voiture->idvoit }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form method="POST" action="{{ route('voiture.update', $voiture->idvoit) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label>Désignation</label>
                                            <input type="text" name="design" class="form-control"
                                                value="{{ $voiture->design }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label>Type</label>
                                            <select name="type" class="form-control" required>
                                                <option value="simple"   {{ $voiture->type=='simple'  ? 'selected' : '' }}>Simple</option>
                                                <option value="premium"  {{ $voiture->type=='premium' ? 'selected' : '' }}>Premium</option>
                                                <option value="VIP"      {{ $voiture->type=='VIP'     ? 'selected' : '' }}>VIP</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label>Nombre de places</label>
                                            <input type="number" name="nbrplace" class="form-control"
                                                value="{{ $voiture->nbrplace }}" min="1" required>
                                        </div>
                                        <div class="mb-3">
                                            <label>Frais (Ar)</label>
                                            <input type="number" name="frais" class="form-control"
                                                value="{{ $voiture->frais }}" min="0" required>
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
                    <div class="modal fade" id="deleteModal{{ $voiture->idvoit }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Supprimer voiture {{ $voiture->idvoit }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    Voulez-vous vraiment supprimer la voiture <b>{{ $voiture->design }}</b> ?
                                    Ses places et ses réservations seront supprimées définitivement.
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                    <form method="POST" action="{{ route('voiture.destroy', $voiture->idvoit) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Supprimer</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted">Aucune voiture enregistrée.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.js"></script>
<footer class="bg-dark text-white text-center py-3 mt-5">
    <p class="mb-0">Auteur : Tanjona</p>
</footer>
</body>
</html>
