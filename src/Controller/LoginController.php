<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route(path: '/', name: 'app-home')]
    public function home()
    {
        return $this->redirectToRoute('app_login');
    }

    #[Route(path: '/login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        $user = $this->getUser();

        if (!(is_null($user))) {
            if (in_array('ROLE_ADMIN' , $user->getRoles())) {
                return $this->redirectToRoute('admin');
            }

            if (in_array('ROLE_PARTNER' , $user->getRoles())) {
                return $this->redirectToRoute('app_partner');
            }

            if (in_array('ROLE_STRUCTURE' , $user->getRoles())) {
                return $this->redirectToRoute('app_structure');
            }
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login/index.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout', methods: ['GET'])]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
