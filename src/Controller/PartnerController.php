<?php

namespace App\Controller;

use App\Entity\User;
use App\Interfaces\DisplayUserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PartnerController extends AbstractController implements DisplayUserInterface
{
    #[Route('/partner', name: 'app_partner')]
    public function displayUserInformations (): Response
    {
        $this->denyAccessUnlessGranted('ROLE_PARTNER');

        //User Partner Informations
        /** @var User $user */
        $user = $this->getUser();
        $userModules = $user->getModules();
        $userEmail = $user->getEmail();

        //Partner Information
        $partner = $user->getPartner();
        $partnerName = $partner->getName();
        $partnerStructures = $partner->getStructures();

        return $this->render('partner/displayUserInformations.html.twig' , [
            'userModules' => $userModules,
            'userEmail' => $userEmail,
            'partnerName' => $partnerName,
            'partnerStructures' => $partnerStructures,
        ]);
    }
}
