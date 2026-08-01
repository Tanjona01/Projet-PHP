<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des clients</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
     <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css','resources/js/app.js']); ?>
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
        <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <b>Ajouter un client</b>
            </div>
            <div class="card-body">
                <form method="post" action="<?php echo e(route('client.store')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label>Nom</label>
                        <input type="text" name="nom" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Numéro de téléphone</label>
                        <input type="text" name="numtel" class="form-control" required>
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
                        <form method="get" action="<?php echo e(route('client.index')); ?>">
                            <div class="row">
                                <div class="col-md-10">
                                    <input type="text"
                                    name="search"
                                     class="form-control"
                                    placeholder="Rechercher par nom ou numéro de téléphone"
                                    value="<?php echo e($search); ?>">
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
                        <?php $__currentLoopData = $client; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $clients): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($clients->idcli); ?></td>
                            <td><?php echo e($clients->nom); ?></td>
                            <td><?php echo e($clients->numtel); ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                data-bs-target="#editModal<?php echo e($clients->idcli); ?>">Modifier</button>
                            </td>
                        </tr>
                        <div class="modal fade" id="editModal<?php echo e($clients->idcli); ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Modifier client</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="post" action="<?php echo e(route('client.update',$clients->idcli)); ?>">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('put'); ?>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label>Nom</label>
                                                <input type="text" name="nom"
                                                class="form-control"
                                                value="<?php echo e($clients->nom); ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>Numéro de téléphone</label>
                                                <input type="text"
                                                name="numtel"
                                                class="form-control"
                                                value="<?php echo e($clients->numtel); ?>" required>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                        <button type="submit" class="btn btn-success">Mettre à jour</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.js"></script>
</body>
<footer class="bg-dark text-white text-center py-3 mt-5"><p class="mb-0">Auteur : Tanjona</p></footer>
</html><?php /**PATH C:\TANJONA\projet_php\resources\views/client.blade.php ENDPATH**/ ?>