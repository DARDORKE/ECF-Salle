<?php

namespace App\Controller;

use App\Entity\User;
use App\Interfaces\DisplayUserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StructureController extends AbstractController implements DisplayUserInterface
{
    #[Route('/structure', name: 'app_structure', methods: ['GET'])]
    public function displayUserInformations (): Response
    {
        $this->denyAccessUnlessGranted('ROLE_STRUCTURE');

        //User Partner Informations
        /** @var User $user */
        $user = $this->getUser();
        $userModules = $user->getModules();
        $userEmail = $user->getEmail();

        //Structure Informations
        $structure = $user->getStructure();
        $structurePartner = $structure->getPartner();
        $structureAddress = $structure->getAddress();
        $structureZipCode = $structure->getZipCode();
        $structureCity = $structure->getCity();

        return $this->render('structure/displayUserInformations.html.twig' , [
            'userModules' => $userModules,
            'userEmail' => $userEmail,
            'structurePartner' => $structurePartner,
            'structureAddress' => $structureAddress,
            'structureZipCode' => $structureZipCode,
            'structureCity' => $structureCity,
        ]);
    }
}
