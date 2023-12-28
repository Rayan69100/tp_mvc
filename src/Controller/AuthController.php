<?php

namespace App\Controller;

use App\Model\User;
use App\Service\SessionManager;
use Twig\Environment;
use App\Routing\Attribute\Route;

class AuthController
{
    private $userModel;
    private $sessionManager;
    private $twig;

    public function __construct(User $userModel, SessionManager $sessionManager, Environment $twig)
    {
        $this->userModel = $userModel;
        $this->sessionManager = $sessionManager;
        $this->twig = $twig;
    }

    #[Route('/login', methods: ['GET'])]
    public function showLoginForm(): string
    {
        return $this->twig->render('login.html.twig');
    }

    #[Route('/login', methods: ['POST'])]
    public function handleLogin(): void
    {
        $username = $_POST['username'];
        $password = $_POST['password'];

        if ($this->userModel->validatePassword($username, $password)) {
            $this->sessionManager->setUser($username);
            header('Location: /dashboard'); // Redirigez vers une page appropriée
            exit;
        } else {
            echo $this->twig->render('login.html.twig', ['error' => 'Identifiants incorrects']);
        }
    }
}
