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
        if ($user->isEnabled() === false) {
            throw $this->createAccessDeniedException('Votre compte est désactivé, veuillez contacter un administrateur afin qu\'il active votre compte.');
        }
        $userModules = $user->getModules();

        //Structure Informations
        $structure = $user->getStructure();
        if (is_null($structure)) {
            throw $this->createNotFoundException('Votre compte n\'est lié à aucune structure, veuillez contacter un administrateur.');
        }

        $structurePartner = $structure->getPartner();
        if (is_null($structurePartner)) {
            throw $this->createNotFoundException('Votre structure n\'est liée à aucun partenaire, veuillez contacter un administrateur');
        }

        $partnerEmail = $structurePartner->getUser()->getEmail();

        return $this->render('structure/displayUserInformations.html.twig' , [
            'user' => $user,
            'userModules' => $userModules,
            'structurePartner' => $structurePartner,
            'structure' => $structure,
            'partnerEmail' => $partnerEmail,
        ]);
    }
}
