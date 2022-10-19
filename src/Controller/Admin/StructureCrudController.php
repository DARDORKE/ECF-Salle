<?php

namespace App\Controller\Admin;

use App\Entity\Partner;
use App\Entity\Structure;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class StructureCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Structure::class;
    }

    public function configureCrud(Crud $crud): Crud
    {

        return $crud->setEntityLabelInPlural('Structures')
            ->setEntityLabelInSingular('Structure')
            ->setPageTitle('index', 'Administration des structures')
            ->setPaginatorPageSize(10)
            ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->update(Crud::PAGE_INDEX, Action::NEW,
                fn(Action $action) => $action->setLabel('Ajouter une structure'))
            ->update(Crud::PAGE_INDEX, Action::BATCH_DELETE,
                fn(Action$action) => $action->setLabel('Supprimer'))
            ->update(Crud::PAGE_NEW, Action::SAVE_AND_ADD_ANOTHER,
                fn(Action $action) => $action->setLabel('Créer et ajouter une nouvelle structure'));
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('address', 'Adresse postale de la structure'),
            AssociationField::new('partner', 'Partenaire de la structure')->setFormTypeOptions([
                'by_reference' => false,
            ]),
            AssociationField::new('user', 'Utilisateur de la structure')->setFormTypeOptions([
                'by_reference' => false,
            ]),
        ];
    }
}
