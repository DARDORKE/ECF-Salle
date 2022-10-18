<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

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
                ->setPageTitle('index','Administration des utilisateurs')
                ->setPaginatorPageSize(10)
            ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            EmailField::new('email', 'Adresse e-mail'),
            TextField::new('password','Mot de passe')->setFormType(PasswordType::class),
            ArrayField::new('roles','Droits de l\'utilisateur'),
            BooleanField::new('enabled', 'Actif'),
            AssociationField::new('partners', 'Partenaire associé à l\'utilisateur')->setFormTypeOptions([
                'by_reference' => false,
            ]),
            AssociationField::new('structures', 'Structure associé à l\'utilisateur')->setFormTypeOptions([
                'by_reference' => false,
            ]),
            AssociationField::new('modules', 'Modules accessibles par l\'utilisateur')->setFormTypeOptions([
                'by_reference' => false,
            ]),
        ];
    }

}
