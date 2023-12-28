<?php

namespace App\Service;

class SessionManager
{
    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start(); // Démarrer la session si elle n'est pas déjà démarrée
        }
    }

    public function setUser($userId)
    {
        $_SESSION['user_id'] = $userId; // Stocker l'ID de l'utilisateur dans la session
    }

    public function getUser()
    {
        return $_SESSION['user_id'] ?? null; // Récupérer l'ID de l'utilisateur de la session, retourner null si non défini
    }

    public function isAuthenticated()
    {
        return isset($_SESSION['user_id']); // Vérifier si l'utilisateur est connecté
    }

    public function logout()
    {
        session_unset(); // Effacer toutes les données de session
        session_destroy(); // Détruire la session
    }

    // Autres méthodes utiles pour la gestion de session...
}
