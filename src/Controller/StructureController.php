<?php

namespace App\Controller;

use App\Entity\User;
use App\Interfaces\DisplayUserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StructureController extends AbstractController implements DisplayUserInterface
{
    #[Route('/structure', name: 'app_structure', methods: ['GET'])]
    public function displayUserInformations (): Response
    {
        $this->denyAccessUnlessGranted('ROLE_STRUCTURE');

        /** @var User $user */
        $user = $this->getUser();

        $userModules = $user->getModules();
        $userEmail = $user->getEmail();
        $userPartner = $user->getPartners();

        $response = new JsonResponse();
        $response->setData([
            ['email' => $userEmail],
            ['modules' => $userModules],
            ['partner' => $userPartner]
            ]);

        return $response;
    }
}
