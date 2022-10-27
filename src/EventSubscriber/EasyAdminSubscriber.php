<?php

namespace App\EventSubscriber;

use App\Entity\Module;
use App\Entity\Partner;
use App\Entity\Structure;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
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
                ->htmlTemplate('email/newAccountEmail.html.twig')
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
                ->htmlTemplate('email/createPartnerEmail.html.twig')
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
            // Mail d'association de la structure à un compte
            if (!(is_null($entity->getUser()))) {
                $userEmail = $entity->getUser()->getEmail();
                $userModules = $entity->getUser()->getModules();
                $email = (new TemplatedEmail())
                    ->from('swapaccess.contact@gmail.com')
                    ->to($userEmail)
                    ->subject('Modification de votre compte SwapAccess.')
                    ->htmlTemplate('email/createStructureEmail.html.twig')
                    ->context([
                        'userEmail' => $userEmail,
                        'structure' => $entity,
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
                    ->htmlTemplate('email/createdStructureConfirmationEmail.html.twig')
                    ->context([
                        'userEmail' => $partnerEmail,
                        'structure' => $entity
                    ]);
                $this->mailer->send($email);
            }
        }

        // Mail de modification d'accès à un module (lors de la création du module)
        if ($entity instanceof Module) {
            if (is_null($entity->getUsers())) {
                return;
            }
            $users = $entity->getUsers();
            foreach ($users as $user) {
                $email = (new TemplatedEmail())
                    ->from('swapaccess.contact@gmail.com')
                    ->to($user->getEmail())
                    ->subject('Vos accès ont étés modifiés.')
                    ->htmlTemplate('email/createdModuleConfirmationEmail.html.twig')
                    ->context([
                        'userEmail' => $user->getEmail(),
                        'userModules' => $user->getModules(),
                        'userRole' => $user->getRoles(),
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
                $partnerName = $partner->getName();
                $userEmail = $entity->getEmail();
                $email = (new TemplatedEmail())
                    ->from('swapaccess.contact@gmail.com')
                    ->to($userEmail)
                    ->subject('Modification de compte SwapAccess.')
                    ->htmlTemplate('email/createPartnerEmail.html.twig')
                    ->context([
                        'partnerName' => $partnerName,
                    ]);
                $this->mailer->send($email);
            }

            // Mail d'association d'un compte à une structure (on update)
            if (!(is_null($entity->getStructure()))) {
                $structure = $entity->getStructure();
                $userEmail = $entity->getEmail();
                $partnerEmail = $structure->getPartner()->getUser()->getEmail();
                $emailStructure = (new TemplatedEmail())
                    ->from('swapaccess.contact@gmail.com')
                    ->to($userEmail)
                    ->subject('Modification de votre compte SwapAccess.')
                    ->htmlTemplate('email/createStructureEmail.html.twig')
                    ->context([
                        'structure' => $structure
                    ]);
                $emailPartner = (new TemplatedEmail())
                    ->from('swapaccess.contact@gmail.com')
                    ->to($partnerEmail)
                    ->subject('Modification de l\'une de vos structure SwapAccess.')
                    ->htmlTemplate('email/createdStructureConfirmationEmail.html.twig')
                    ->context([
                        'structure' => $structure
                    ]);
                $this->mailer->send($emailStructure);
                $this->mailer->send($emailPartner);
            }
        }

        // Mail de modification des structures associé à un partenaire (on update)
        if ($entity instanceof Partner) {
            if (is_null($entity->getUser())) {
                return;
            }

            $userEmail = $entity->getUser()->getEmail();
            $partnerName = $entity->getName();
            $email = (new TemplatedEmail())
                ->from('swapaccess.contact@gmail.com')
                ->to($userEmail)
                ->subject('Modification de compte SwapAccess.')
                ->htmlTemplate('email/createPartnerEmail.html.twig')
                ->context([
                    'partnerName' => $partnerName,
                ]);
            $this->mailer->send($email);
        }

        // Mail de modification d'une structure (on update)
        if ($entity instanceof Structure) {
            if ((is_null($entity->getUser())) && (is_null($entity->getPartner()->getUser()))) {
                return;
            }
                // Mail de modification de la structure à l'utilisateur (on update)
                if(!(is_null($entity->getUser()))){
                    $userEmail = $entity->getUser()->getEmail();
                    $structurePartner = $entity->getPartner();
                    $partnerEmail = $structurePartner->getUser()->getEmail();
                    $emailStructure = (new TemplatedEmail())
                        ->from('swapaccess.contact@gmail.com')
                        ->to($userEmail)
                        ->subject('Modification de compte SwapAccess.')
                        ->htmlTemplate('email/createStructureEmail.html.twig')
                        ->context([
                            'structure' => $entity,
                        ]);
                    $this->mailer->send($emailStructure);
                }
                 // Mail de confirmation au partenaire associé (on update)
                if(!(is_null($entity->getPartner()->getUser()))){
                    $structurePartner = $entity->getPartner();
                    $partnerEmail = $structurePartner->getUser()->getEmail();
                    $emailPartner = (new TemplatedEmail())
                        ->from('swapaccess.contact@gmail.com')
                        ->to($partnerEmail)
                        ->addTo($structurePartner->getUser()->getEmail())
                        ->subject('Modification de votre compte SwapAccess.')
                        ->htmlTemplate('email/createdStructureConfirmationEmail.html.twig')
                        ->context([
                            'structure' => $entity,
                        ]);
                    $this->mailer->send($emailPartner);
                }
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