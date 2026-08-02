<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des clients</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
     @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body>
    
    <header>
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                <div class="container">
                    <a class="navbar-brand">Gestion de réservation</a>
                </div>
                <div class="container flex-grow-1 d-flex flex-column ">
                    <div class="d-flex gap-5" id="menu">
                        <a class="navbar-link text-white text-decoration-none" href="/">Accueil</a>
                        <a class="navbar-link text-white text-decoration-none" href="/voiture">Voitures</a>
                        <a class="navbar-link text-white text-decoration-none" href="/reservation">Réservation</a>
                    </div>
                </div>
            </nav>
        </header>   
    <button class="btn btn-primary" onclick="history.back()" id="retour"> &leftarrow; Retour</button>
    <div class="container mt-4">
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

        @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="card">
            <div class="card-header">
                <b>Ajouter un client</b>
            </div>
            <div class="card-body">
                <form method="post" action="{{ route('client.store') }}" class="needs-check" novalidate>
                    @csrf
                    <div class="mb-3">
                        <label>Nom</label>
                        <input type="text" name="nom" class="form-control champ-nom" pattern="[A-Za-zÀ-ÖØ-öø-ÿ '\-]+" title="Le nom ne doit contenir que des lettres" required>
                        <div class="form-text text-danger msg-nom"></div>
                    </div>
                    <div class="mb-3">
                        <label>Numéro de téléphone</label>
                        <input type="text" name="numtel" class="form-control champ-tel" maxlength="10" pattern="03[0-9]{8}" title="Le numéro doit commencer par 03 et contenir 10 chiffres" required>
                        <div class="form-text text-danger msg-tel"></div>
                    </div>
                    <button class="btn btn-success">Ajouter</button>
                </form>
            </div>
        </div>
        <br>
        <div class="card">
            <div class="card-header">
                <b>Liste des clients</b>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="card-body">
                        <form method="get" action="{{ route('client.index') }}">
                            <div class="row">
                                <div class="col-md-10">
                                    <input type="text"
                                    name="search"
                                     class="form-control"
                                    placeholder="Rechercher par nom ou numéro de téléphone"
                                    value="{{ $search }}">
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">Rechercher</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Numéro de téléphone</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($client as $clients)
                        <tr>
                            <td>{{ $clients->idcli }}</td>
                            <td>{{ $clients->nom }}</td>
                            <td>{{ $clients->numtel }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#editModal{{ $clients->idcli }}">Modifier</button>
                                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal{{ $clients->idcli }}">Supprimer</button>
                                </div>
                            </td>
                        </tr>
                        <div class="modal fade" id="editModal{{ $clients->idcli }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Modifier client</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="post" action="{{ route('client.update',$clients->idcli) }}" class="needs-check" novalidate>
                                        @csrf
                                        @method('put')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label>Nom</label>
                                                <input type="text" name="nom"
                                                class="form-control champ-nom"
                                                pattern="[A-Za-zÀ-ÖØ-öø-ÿ '\-]+" title="Entrer un nom valide"
                                                value="{{ $clients->nom }}" required>
                                                <div class="form-text text-danger msg-nom"></div>
                                            </div>
                                            <div class="mb-3">
                                                <label>Numéro de téléphone</label>
                                                <input type="text"
                                                name="numtel"
                                                class="form-control champ-tel"
                                                maxlength="10" pattern="03[0-9]{8}" title="Entrer un numéro de téléphone valide"
                                                value="{{ $clients->numtel }}" required>
                                                <div class="form-text text-danger msg-tel"></div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                        <button type="submit" class="btn btn-success">Mettre à jour</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="deleteModal{{ $clients->idcli }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Supprimer client</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        Voulez-vous vraiment supprimer le client <b>{{ $clients->nom }}</b> ?
                                        Ses réservations seront supprimées et les places associées libérées.
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                        <form method="post" action="{{ route('client.destroy', $clients->idcli) }}">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-danger">Supprimer</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.js"></script>
    <script>
        const regexNom = /^[A-Za-zÀ-ÖØ-öø-ÿ'\-\s]+$/;
        const regexTel = /^03[0-9]{8}$/;

        function validerChamp(input, regex, msgBox, message) {
            const valeur = input.value.trim();
            if (valeur === '') {
                msgBox.textContent = '';
                input.classList.remove('is-valid', 'is-invalid');
                return false;
            }
            if (regex.test(valeur)) {
                input.classList.remove('is-invalid');
                input.classList.add('is-valid');
                msgBox.textContent = '';
                return true;
            }
            input.classList.remove('is-valid');
            input.classList.add('is-invalid');
            msgBox.textContent = message;
            return false;
        }

        document.querySelectorAll('form.needs-check').forEach(function (form) {
            const champNom = form.querySelector('.champ-nom');
            const champTel = form.querySelector('.champ-tel');
            const msgNom = form.querySelector('.msg-nom');
            const msgTel = form.querySelector('.msg-tel');

            champNom.addEventListener('input', function () {
                validerChamp(champNom, regexNom, msgNom, 'Entrer un nom valide.');
            });
            champTel.addEventListener('input', function () {
                champTel.value = champTel.value.replace(/[^0-9]/g, '').slice(0, 10);
                validerChamp(champTel, regexTel, msgTel, 'Entrer un numéro de téléphone valide.');
            });

            form.addEventListener('submit', function (e) {
                const nomOk = validerChamp(champNom, regexNom, msgNom, 'Entrer un nom valide.');
                const telOk = validerChamp(champTel, regexTel, msgTel, 'Entrer un numéro de téléphone valide.');
                if (!nomOk || !telOk) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
<footer class="bg-dark text-white text-center py-3 mt-5"><p class="mb-0">Auteur : Tanjona</p></footer>
</html>
