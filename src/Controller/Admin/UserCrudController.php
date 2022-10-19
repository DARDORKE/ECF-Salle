<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\RoleType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {

        return $crud->setEntityLabelInPlural('Utilisateurs')
                ->setEntityLabelInSingular('Utilisateur')
                ->setPageTitle('index','LISTE DES UTILISATEURS')
                ->setPageTitle('edit', 'MODIFICATION D\'UN UTILISATEUR')
                ->setPageTitle('new', 'CREATION D\'UN UTILISATEUR')
                ->setPaginatorPageSize(10)
            ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->update(Crud::PAGE_INDEX, Action::NEW,
                fn(Action $action) => $action->setLabel('Ajouter un utilisateur'))
            ->update(Crud::PAGE_INDEX, Action::BATCH_DELETE,
                fn(Action$action) => $action->setLabel('Supprimer'))
            ->update(Crud::PAGE_NEW, Action::SAVE_AND_ADD_ANOTHER,
                fn(Action $action) => $action->setLabel('Créer et ajouter un nouvel utilisateur'))
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addPanel( 'Informations de l\'utilisateur' )->setIcon( 'fa fa-user' ),
            IdField::new('id')->setDisabled()->hideOnForm()->hideOnDetail()->hideOnIndex(),
            EmailField::new('email', 'Adresse e-mail')->onlyWhenUpdating()->setDisabled(),
            EmailField::new( 'email' )->onlyWhenCreating(),
            TextField::new( 'email' )->onlyOnIndex(),
            FormField::addPanel( 'Création du mot de passe' )->setIcon( 'fa fa-key' ),
            Field::new( 'password', 'Mot de passe' )->onlyWhenCreating()->setRequired( true )
                ->setFormType( RepeatedType::class )
                ->setFormTypeOptions( [
                    'type'            => PasswordType::class,
                    'first_options'   => [ 'label' => 'Mot de passe' ],
                    'second_options'  => [ 'label' => 'Vérification du mot de passe' ],
                    'error_bubbling'  => true,
                    'invalid_message' => 'Les mots de passe ne correspondent pas.',
                ]),
            Field::new( 'password', 'Nouveau mot de passe' )->onlyWhenUpdating()->setRequired( false )
                ->setFormType( RepeatedType::class )
                ->setFormTypeOptions( [
                    'type'            => PasswordType::class,
                    'first_options'   => [ 'label' => 'Nouveau mot de passe' ],
                    'second_options'  => [ 'label' => 'Vérification du mot de passe' ],
                    'error_bubbling'  => true,
                    'invalid_message' => 'Les mots de passe ne correspondent pas.',
                ]),
            FormField::addPanel( 'Ajout des droits' )->setIcon('fa-light fa-scale-balanced'),
            ChoiceField::new( 'roles', 'Role')
                        ->setChoices([
                            'ADMINISTRATEUR' => 'ROLE_ADMIN',
                            'PARTENAIRE' => 'ROLE_PARTNER',
                            'STRUCTURE' => 'ROLE_STRUCTURE'
                        ])
                        ->allowMultipleChoices(false)
                        ->renderAsNativeWidget()
                        ->setFormType(RoleType::class)
                        ,
            BooleanField::new('enabled', 'Actif'),
            AssociationField::new('partner', 'Partenaire associé à l\'utilisateur')->setFormTypeOptions([
                'by_reference' => false,
            ]),
            AssociationField::new('structure', 'Structure associé à l\'utilisateur')->setFormTypeOptions([
                'by_reference' => false,
            ]),
            AssociationField::new('modules', 'Modules accessibles par l\'utilisateur')->setFormTypeOptions([
                'by_reference' => false,
            ]),
        ];
    }
}
