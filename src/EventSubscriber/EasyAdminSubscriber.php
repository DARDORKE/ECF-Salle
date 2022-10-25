<?php

namespace App\EventSubscriber;

use App\Entity\Partner;
use App\Entity\Structure;
use App\Entity\User;
use App\Repository\UserRepository;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterCrudActionEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityBuiltEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeCrudActionEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityUpdatedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\StoppableEventTrait;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;


class EasyAdminSubscriber implements EventSubscriberInterface
{
    private UserPasswordHasherInterface $passwordEncoder;

    private MailerInterface $mailer;

    public function __construct(UserPasswordHasherInterface $passwordEncoder, MailerInterface $mailer)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityPersistedEvent::class => ['encodePassword'],
            AfterEntityPersistedEvent::class => ['sendNewAccountEmail'],

        ];
    }

    public function sendNewAccountEmail(AfterEntityPersistedEvent $event)
    {
       $entity = $event->getEntityInstance();

       // Mail de création d'un utilisateur
      if ($entity instanceof User) {
          $newUserEmail = $entity->getEmail();
          $email = (new TemplatedEmail())
              ->from('swapaccess.contact@gmail.com')
              ->to($newUserEmail)
              ->subject('Création de votre compte SwapAccess.')
              ->htmlTemplate('newAccountEmail.html.Twig')
              ->context([
                  'userEmail' => $newUserEmail
              ]);
          $this->mailer->send($email);
      }

      // Mail d'association d'un partenaire à un compte (lors de la création d'un partenaire)
      if ($entity instanceof Partner) {
          if (is_null($entity->getUser())) {
              return;
          }

          $partnerEmail = $entity->getUser()->getEmail();
          $partnerName = $entity->getName();
          $partnerStructures = $entity->getStructures();
          $partnerModules = $entity->getUser()->getModules();
          $email = (new TemplatedEmail())
              ->from('swapaccess.contact@gmail.com')
              ->to($partnerEmail)
              ->subject('Modification de votre compte SwapAccess.')
              ->htmlTemplate('editAccountEmail.html.Twig')
              ->context([
                  'userEmail' => $partnerEmail,
                  'partnerName' => $partnerName,
                  'partnerStructures' => $partnerStructures,
                  'partnerModules' => $partnerModules,
              ]);
          $this->mailer->send($email);
      }

      // Mail d'association d'une structure à un compte + mail partenaire associé (lors de la création de la structure)
      if ($entity instanceof Structure) {
          if (is_null($entity->getUser()) && is_null($entity->getPartner())){
              return;
          }

          $structureAddress = $entity->getAddress();
          $structureZipCode = $entity->getZipCode();
          $structureCity = $entity->getCity();

          // Mail d'association de la structure à un compte
          if (!(is_null($entity->getUser()))) {
              $structureEmail = $entity->getUser()->getEmail();
              $structureModules = $entity->getUser()->getModules();
              $email = (new TemplatedEmail())
                  ->from('swapaccess.contact@gmail.com')
                  ->to($structureEmail)
                  ->subject('Modification de votre compte SwapAccess.')
                  ->htmlTemplate('editAccountEmail.html.Twig')
                  ->context([
                      'userEmail' => $structureEmail,
                      'structureAddress' => $structureAddress,
                      'structureZipCode' => $structureZipCode,
                      'structureCity' => $structureCity,
                      'structureModules' => $structureModules
                  ]);
              $this->mailer->send($email);
          }

          // Mail de confirmation au partenaire associé
          if (!(is_null($entity->getPartner()))) {
          $partnerEmail = $entity->getPartner()->getUser()->getEmail();
          $email = (new TemplatedEmail())
              ->from('swapaccess.contact@gmail.com')
              ->to($partnerEmail)
              ->subject('Création d\'une nouvelle structure.')
              ->htmlTemplate('structureConfirmationEmail.html.Twig')
              ->context([
                  'userEmail' => $partnerEmail,
                  'structureAddress' => $structureAddress,
                  'structureZipCode' => $structureZipCode,
                  'structureCity' => $structureCity,
              ]);
          $this->mailer->send($email);
      }

      // Mail d'édition d'un partenaire
//      if ($currentAction === 'edit' && $currentEntityFqcn === 'App\Entity\Partner') {
//          if (is_null($event->getAdminContext()->getEntity()->getInstance()->getUser())) {
//              return;
//            }
//
//          $partnerStructures = $event->getAdminContext()->getEntity()->getInstance()->getStructures()->getValues();
//          $structureAddresses = [];
//          foreach ($partnerStructures as $structure){
//              $structureAddress = $structure->getAddress();
//              $structureAddresses[] = $structureAddress;
//            }
//
//            $partnerName = $event->getAdminContext()->getEntity()->getInstance()->getName();
//            $partnerEmail = $event->getAdminContext()->getEntity()->getInstance()->getUser()->getEmail();
//            $newUserEmail = $event->getAdminContext()->getRequest()->get('User')['email'];
//            $email = (new TemplatedEmail())
//                ->from('swapaccess.contact@gmail.com')
//                ->to($newUserEmail)
//                ->subject('Modification de votre compte SwapAccess.')
//                ->htmlTemplate('editPartnerEmail.html.Twig')
//                ->context([
//                    'partnerName' => $partnerName,
//                    'partnerEmail' => $partnerEmail,
//                    'structureAddresses' => $structureAddresses,
//                ]);
//            $this->mailer->send($email);
//        }
//
//      // Mail d'édition d'un compte utilisateur
//      if ($currentAction === 'edit' && $currentEntityFqcn === 'App\Entity\User') {
//          $userRole = $event->getAdminContext()->getEntity()->getInstance()->getRoles();
//
//          //Mail d'édition d'un compte partenaire
//          if (in_array('ROLE_PARTNER', $userRole)) {
//              $userEmail = $event->getAdminContext()->getEntity()->getInstance()->getEmail();
//              $userModules = $event->getAdminContext()->getEntity()->getInstance()->getModules();
//          }
      }
    }


    public function encodePassword(BeforeEntityPersistedEvent $event): void
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof User)) {
            return;
        }

        $entity->setPassword($this->passwordEncoder->hashPassword($entity, $entity->getPassword()));
    }
}