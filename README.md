# Gestion de Réservation de Voitures

Application web développée avec **Laravel** permettant de gérer des voitures, des clients et leurs réservations de voyage, avec suivi automatique des places disponibles.

## Sommaire

- [Stack technique](#stack-technique)
- [Prérequis](#prérequis)
- [Installation](#installation)
- [Structure du projet](#structure-du-projet)
- [Fonctionnalités](#fonctionnalités)
- [Règles de validation](#règles-de-validation)
- [Routes principales](#routes-principales)
- [Modèle de données](#modèle-de-données)
- [Notes techniques](#notes-techniques)

## Stack technique

- PHP 8.3
- Laravel 13.8
- Base de données SQLite
- Bootstrap 5.3 (interface)
- Vite (compilation des assets)

## Prérequis

- PHP >= 8.3 avec les extensions habituelles de Laravel
- Composer
- Node.js et npm (pour compiler les assets)

## Installation

```bash
composer install
npm install

cp .env.example .env
php artisan key:generate

touch database/database.sqlite

php artisan migrate

npm run build
php artisan serve
```

L'application est ensuite accessible sur `http://localhost:8000`.

## Structure du projet

```
app/
  Http/Controllers/
    clientcontroller.php        Gestion des clients
    voiturecontroller.php       Gestion des voitures
    reservationcontroller.php   Gestion des réservations et des places
  Models/
    client.php
    voiture.php
    reservation.php
    place.php
database/migrations/            Schéma des tables (voitures, client, reservations, places)
resources/views/
  client.blade.php              Liste et gestion des clients
  voiture.blade.php             Liste et gestion des voitures
  reservation.blade.php         Liste et gestion des réservations
  recu.blade.php                Reçu imprimable d'une réservation
  home.blade.php / welcome.blade.php
routes/web.php                  Déclaration des routes
```

## Fonctionnalités

### Voitures
- Ajout d'une voiture (désignation, type simple/premium/VIP, nombre de places, frais)
- Modification d'une voiture
- Suppression d'une voiture (supprime automatiquement ses places et ses réservations liées)
- Création automatique des places disponibles à partir du nombre de places défini

### Clients
- Ajout d'un client (nom, numéro de téléphone)
- Modification d'un client
- Suppression d'un client (libère automatiquement les places de ses réservations avant suppression)
- Recherche par nom ou numéro de téléphone

### Réservations
- Création d'une réservation (client, voiture, place, date de voyage, mode de paiement)
- Sélection dynamique des places libres selon la voiture choisie
- Gestion du paiement : sans avance / avec avance / tout payé, avec calcul automatique du reste à payer
- Modification d'une réservation (avec réajustement automatique de l'occupation des places si la place ou la voiture change)
- Suppression d'une réservation (libère immédiatement la place associée)
- Indicateur de statut **Terminé** / **À venir** selon que la date de voyage est passée ou non
- **Libération automatique des places** : dès qu'une réservation atteint sa date de voyage, sa place repasse automatiquement à l'état "libre" (sans avoir besoin de supprimer la réservation), afin qu'elle puisse être réutilisée pour une nouvelle réservation
- Génération d'un reçu imprimable par réservation
- Statistiques de paiement et recette totale par voiture

## Règles de validation

### Client
| Champ | Règle |
|---|---|
| Nom | Obligatoire, lettres uniquement (accents et espaces autorisés) |
| Numéro de téléphone | Obligatoire, doit commencer par `03` et contenir exactement 10 chiffres |

Ces règles sont vérifiées côté serveur (Laravel `validate`) et côté client en temps réel (JavaScript), avec messages d'erreur affichés sous chaque champ.

## Routes principales

| Méthode | URI | Action | Nom |
|---|---|---|---|
| GET | /voiture | Liste des voitures | voiture.index |
| POST | /voiture | Créer une voiture | voiture.store |
| PUT | /voiture/{id} | Modifier une voiture | voiture.update |
| DELETE | /voiture/{id} | Supprimer une voiture | voiture.destroy |
| GET | /client | Liste des clients | client.index |
| POST | /client | Créer un client | client.store |
| PUT | /client/{id} | Modifier un client | client.update |
| DELETE | /client/{id} | Supprimer un client | client.destroy |
| GET | /reservation | Liste des réservations | reservation.index |
| POST | /reservation | Créer une réservation | reservation.store |
| PUT | /reservation/{id} | Modifier une réservation | reservation.update |
| DELETE | /reservation/{id} | Supprimer une réservation | reservation.destroy |
| GET | /reservation/places-libres/{idvoit} | Places libres d'une voiture (JSON) | reservation.placesLibres |
| GET | /reservation/stats/{idvoit} | Statistiques de paiement d'une voiture (JSON) | reservation.stats |
| GET | /reservation/recette | Recette totale (JSON) | reservation.recette |
| GET | /reservation/recu/{id} | Reçu d'une réservation | reservation.recu |

## Modèle de données

- **voitures** (`idvoit`, `design`, `type`, `nbrplace`, `frais`)
- **client** (`idcli`, `nom`, `numtel`)
- **places** (`idvoit`, `place`, `occupation`) — une ligne par place physique d'une voiture
- **reservations** (`idreserv`, `idvoit`, `idcli`, `place`, `date_reserv`, `date_voyage`, `payement`, `montant_avance`)

Relations :
- Une voiture a plusieurs places et plusieurs réservations
- Une réservation appartient à une voiture et à un client

## Notes techniques

- La libération automatique des places terminées est effectuée par `reservationcontroller::libererPlacesTerminees()`, appelée à chaque affichage de la liste des réservations et à chaque consultation des places libres d'une voiture.
- La suppression d'une voiture ou d'un client entraîne la suppression en cascade de leurs réservations associées, avec libération préalable des places concernées.
- Aucune tâche planifiée (cron) n'est nécessaire : la mise à jour des places se fait à la volée lors de la navigation dans l'application.
