<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de réservation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="d-flex flex-column min-vh-100">
    <style>
        button{
            width: 140px;
            height: 60px;
        }
        a{margin-left: 18px;}
        body{cursor: default;}
    </style>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand">Gestion de réservation</a>
        </nav>
        
    </header>
    <main class="container text-center flex-grow-1 d-flex flex-column justify-content-center">
    
        <h1 class="mb-4">Bienvenue</h1>
        <p class="lead">Que souhaitez-vous faire aujourd'hui ?</p>
        <div class="d-flex justify-content-center gap-4">
            <button class="btn btn-primary" onclick="window.location.href='/client'"> Gérer les clients</button>
            <button class="btn btn-primary" onclick="window.location.href='/voiture'">Gérer les voitures</button>
            <button class="btn btn-primary" onclick="window.location.href='/reservation'">Gérer les réservation</button>
        </div>
    
    </main>
    <footer class="bg-dark text-white text-center py-3 mt-5"><p class="mb-0">Auteur : Tanjona</p></footer>
    
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.js"></script> 
</body>
 
</html>