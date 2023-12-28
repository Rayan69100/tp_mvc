<?php

namespace App\Model;

class User
{
    private $db; // Instance de PDO ou de connexion à la base de données

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function validatePassword($email, $password)
    {
        // Récupérer l'utilisateur par email
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            return $user['id'];
        }

        return false; // Retourner false si l'utilisateur n'est pas trouvé ou si le mot de passe est incorrect
    }

}
