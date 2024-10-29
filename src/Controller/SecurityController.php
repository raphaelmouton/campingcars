<?php

namespace App\Controller;

use App\Repository\AnnoncesRepository;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('account');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/mon-compte', name: 'app_account')]
    public function account(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/account.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/mes-annonces', name: 'my_announces')]
    public function myAnnounces(AnnoncesRepository $annonceRepository): Response
    {
        // Récupère l'utilisateur actuel
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour voir vos annonces.');
        }

        // Utilise le repository pour récupérer les annonces de l'utilisateur
        $annonces = $annonceRepository->findAnnoncesByUser($user);

        return $this->render('security/my_announces.html.twig', [
            'annonces' => $annonces,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
        
    }
}
