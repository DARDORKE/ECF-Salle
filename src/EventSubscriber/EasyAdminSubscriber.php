<?php

namespace App\EventSubscriber;

use App\Entity\Module;
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
            AfterEntityPersistedEvent::class => ['onCreateEmail'],
            AfterEntityUpdatedEvent::class => ['onUpdateEmail'],
        ];
    }

    public function onCreateEmail(AfterEntityPersistedEvent $event)
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

            $userEmail = $entity->getUser()->getEmail();
            $partnerName = $entity->getName();
            $partnerStructures = $entity->getStructures()->toArray();
            $userModules = $entity->getUser()->getModules()->toArray();
            $email = (new TemplatedEmail())
                ->from('swapaccess.contact@gmail.com')
                ->to($userEmail)
                ->subject('Modification de votre compte SwapAccess.')
                ->htmlTemplate('editAccountEmail.html.Twig')
                ->context([
                    'userEmail' => $userEmail,
                    'partnerName' => $partnerName,
                    'partnerStructures' => $partnerStructures,
                    'userModules' => $userModules,
                ]);
            $this->mailer->send($email);
        }

        // Mail d'association d'une structure à un compte + mail partenaire associé (lors de la création de la structure)
        if ($entity instanceof Structure) {
            if (is_null($entity->getUser()) && is_null($entity->getPartner())) {
                return;
            }

            $structureAddress = $entity->getAddress();
            $structureZipCode = $entity->getZipCode();
            $structureCity = $entity->getCity();

            // Mail d'association de la structure à un compte
            if (!(is_null($entity->getUser()))) {
                $userEmail = $entity->getUser()->getEmail();
                $userModules = $entity->getUser()->getModules();
                $email = (new TemplatedEmail())
                    ->from('swapaccess.contact@gmail.com')
                    ->to($userEmail)
                    ->subject('Modification de votre compte SwapAccess.')
                    ->htmlTemplate('editAccountEmail.html.Twig')
                    ->context([
                        'userEmail' => $userEmail,
                        'structureAddress' => $structureAddress,
                        'structureZipCode' => $structureZipCode,
                        'structureCity' => $structureCity,
                        'userModules' => $userModules
                    ]);
                $this->mailer->send($email);
            }

            // Mail de confirmation au partenaire associé
            if ((!(is_null($entity->getPartner()))) && (!(is_null($entity->getPartner()->getUser())))) {
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
        }

        // Mail de modification d'accès à un module (lors de la création du module)
        if ($entity instanceof Module) {
            if (is_null($entity->getUsers())) {
                return;
            }
            $users = $entity->getUsers()->toArray();
            foreach ($users as $user) {
                $email = (new TemplatedEmail())
                    ->from('swapaccess.contact@gmail.com')
                    ->to($user->getEmail())
                    ->subject('Vos accès ont étés modifiés.')
                    ->htmlTemplate(template: 'moduleInformationsEmail.html.twig')
                    ->context([
                        'userEmail' => $user->getEmail(),
                        'userModules' => $user->getModules()->toArray(),
                    ]);
                $this->mailer->send($email);
            }
        }
    }

    public function onUpdateEmail(AfterEntityUpdatedEvent $event)
    {
        $entity = $event->getEntityInstance();

        // Mail d'association d'un compte à un partenaire/structure (on update)
        if ($entity instanceof User) {
            if (is_null($entity->getPartner()) && (is_null($entity->getStructure()))) {
                return;
            }

            //Mail d'association d'un compte à un partenaire (on update)
            if (!(is_null($entity->getPartner()))) {
                $partner = $entity->getPartner();
                $partnerStructures = $partner->getStructures()->toArray();
                $partnerName = $partner->getName();
                $userEmail = $entity->getEmail();
                $userModules = $entity->getModules()->toArray();
                $email = (new TemplatedEmail())
                    ->from('swapaccess.contact@gmail.com')
                    ->to($userEmail)
                    ->subject('Modification de compte SwapAccess.')
                    ->htmlTemplate('editAccountEmail.html.Twig')
                    ->context([
                        'userEmail' => $userEmail,
                        'partnerName' => $partnerName,
                        'partnerStructures' => $partnerStructures,
                        'userModules' => $userModules,
                    ]);
                $this->mailer->send($email);
            }

            // Mail d'association d'un compte à une structure (on update)
            if (!(is_null($entity->getStructure()))) {
                $structure = $entity->getStructure();
                $structurePartnerName = $structure->getPartner()->getName();
                $structureAddress = $structure->getAddress();
                $structureZipCode = $structure->getZipCode();
                $structureCity = $structure->getCity();
                $userEmail = $entity->getEmail();
                $userModules = $entity->getModules()->toArray();
                $email = (new TemplatedEmail())
                    ->from('swapaccess.contact@gmail.com')
                    ->to($userEmail)
                    ->addTo($structure->getPartner()->getUser()->getEmail())
                    ->subject('Modification de compte SwapAccess.')
                    ->htmlTemplate('editStructureAccountEmail.html.Twig')
                    ->context([
                        'userEmail' => $userEmail,
                        'userModules' => $userModules,
                        'structurePartnerName' => $structurePartnerName,
                        'structureAddress' => $structureAddress,
                        'structureZipCode' => $structureZipCode,
                        'structureCity' => $structureCity,
                    ]);
                $this->mailer->send($email);
            }
        }

        // Mail de modification des structures associé à un partenaire (on update)
        if ($entity instanceof Partner) {
            if (is_null($entity->getUser())) {
                return;
            }

            $userEmail = $entity->getUser()->getEmail();
            $userModules = $entity->getUser()->getModules();
            $partnerName = $entity->getName();
            $partnerStructures = $entity->getStructures()->toArray();
            $email = (new TemplatedEmail())
                ->from('swapaccess.contact@gmail.com')
                ->to($userEmail)
                ->subject('Modification de compte SwapAccess.')
                ->htmlTemplate('editPartnerAccountEmail.html.Twig')
                ->context([
                    'userEmail' => $userEmail,
                    'userModules' => $userModules,
                    'partnerName' => $partnerName,
                    'partnerStructures' => $partnerStructures,
                ]);
            $this->mailer->send($email);
        }

        // Mail de modification d'une structure (on update)
        if ($entity instanceof Structure) {
            if ((is_null($entity->getUser())) && (is_null($entity->getPartner()->getUser()))) {
                return;
            }

                // Mail de modification de la structure aux utilisateurs concernés (on update)
                $userEmail = $entity->getUser()->getEmail();
                $userModules = $entity->getUser()->getModules()->toArray();
                $structureAddress = $entity->getAddress();
                $structureZipCode = $entity->getZipCode();
                $structureCity = $entity->getCity();
                $structurePartner = $entity->getPartner();
                $email = (new TemplatedEmail())
                    ->from('swapaccess.contact@gmail.com')
                    ->to($userEmail)
                    ->addTo($structurePartner->getUser()->getEmail())
                    ->subject('Modification de compte SwapAccess.')
                    ->htmlTemplate('editStructureAccountEmail.html.Twig')
                    ->context([
                        'userEmail' => $userEmail,
                        'userModules' => $userModules,
                        'structurePartnerName' => $structurePartner->getName(),
                        'structureAddress' => $structureAddress,
                        'structureZipCode' => $structureZipCode,
                        'structureCity' => $structureCity,
                    ]);
                $this->mailer->send($email);
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