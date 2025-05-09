# Hôtel California PHP

## Description du Projet

Hôtel California PHP est une application web de gestion de réservations d'hôtel. Elle permet aux employés de l'hôtel de :

* **Gérer les chambres :** Afficher la liste des chambres, leur statut (disponible ou non), et potentiellement les modifier ou en ajouter (selon les rôles).
* **Gérer les clients :** Afficher la liste des clients, leurs informations de contact, et potentiellement les modifier ou en ajouter.
* **Gérer les réservations :** Créer, afficher, modifier et supprimer les réservations. Le système gère les dates d'arrivée et de départ, l'attribution des chambres aux clients, et potentiellement le statut des réservations.
* **Authentification et Autorisation :** Un système de connexion et de gestion des rôles permet de contrôler l'accès aux différentes fonctionnalités de l'application en fonction du rôle de l'utilisateur (administrateur, directeur, manager, standard, etc.).

## Techniques et Technologies Utilisées

Ce projet est développé en utilisant les technologies suivantes :

* **PHP :** Le langage de script côté serveur principal pour la logique de l'application et l'interaction avec la base de données.
* **MySQL :** Le système de gestion de base de données relationnelle (SGBDR) utilisé pour stocker les informations de l'hôtel (chambres, clients, réservations, employés, rôles).
* **PDO (PHP Data Objects) :** L'extension PHP utilisée pour accéder à la base de données de manière sécurisée et portable. Les requêtes préparées sont utilisées pour prévenir les injections SQL.
* **HTML :** Le langage de balisage standard pour la structure et le contenu des pages web.
* **CSS :** Les feuilles de style en cascade utilisées pour la présentation et la mise en page des pages web.
* **Bootstrap :** Un framework CSS populaire qui facilite la création d'interfaces utilisateur responsives et esthétiques avec des composants pré-stylisés (navigation, formulaires, tableaux, alertes, etc.).
* **Font Awesome :** Une librairie d'icônes vectorielles utilisée pour améliorer l'interface utilisateur avec des icônes significatives.
* **Sessions PHP :** Utilisées pour gérer l'état de connexion des utilisateurs et stocker temporairement des informations telles que l'ID de l'utilisateur et son rôle.
* **Gestion des erreurs :** Des mécanismes de gestion des erreurs (blocs `try...catch` pour les exceptions PDO) sont mis en place pour rendre l'application plus robuste.
* **Redirections HTTP :** Utilisées pour naviguer entre les pages et pour gérer les soumissions de formulaires (POST/REDIRECT/GET pattern).
* **Encodage URL (`urlencode()`, `urldecode()`):** Utilisé pour passer des messages (succès/erreur) via les paramètres d'URL de manière sécurisée.
* **Sécurité :**
    * **Hachage des mots de passe :** Les mots de passe des employés sont stockés dans la base de données sous forme de hachages sécurisés (SHA-256).
    * **Requêtes préparées (PDO) :** Utilisées pour prévenir les injections SQL lors des interactions avec la base de données.
    * **Validation des entrées utilisateur :** Les données soumises via les formulaires sont validées côté serveur pour s'assurer de leur format et de leur validité.
    * **Gestion des rôles et des permissions :** Un système de rôles (`admin`, `directeur`, `manager`, `standard`, etc.) est implémenté pour contrôler l'accès aux différentes fonctionnalités de l'application. La fonction `requireRole()` est utilisée pour protéger les pages en fonction du rôle requis.
    * **Protection contre le XSS (`htmlspecialchars()`):** Utilisé pour afficher les données dynamiques dans les pages HTML de manière sécurisée, en échappant les caractères spéciaux.
    * **Déconnexion sécurisée :** La suppression des cookies de session est gérée lors de la déconnexion.

## Structure du Projet
hotel_california_php/
├── assets/
│   └── style.css
│   └── gestionMessage.php (peut-être ?)
├── auth/
│   ├── authFunctions.php
│   ├── login.php
│   ├── logout.php
├── config/
│   └── db_connect.php
├── chambres/
│   ├── listChambres.php
│   ├── ...
├── clients/
│   ├── listClients.php
│   ├── ...
├── reservations/
│   ├── createReservation.php
│   ├── deleteReservation.php
│   ├── editReservation.php
│   ├── listReservations.php
├── index.php
└── README.md

## Auteurs

[DA SILVA PEIXOTO GABRIEL]