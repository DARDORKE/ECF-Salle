<?php

namespace App\Controller\Admin;

use App\Entity\Module;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use function Sodium\add;

class ModuleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Module::class;
    }

    public function configureCrud(Crud $crud): Crud
    {

        return $crud->setEntityLabelInPlural('Modules')
            ->setEntityLabelInSingular('Module')
            ->setPageTitle('index','LISTE DES MODULES')
            ->setPageTitle('edit', 'MODIFICATION D\'UN MODULE')
            ->setPageTitle('new', 'CREATION D\'UN MODULE')
            ->setPaginatorPageSize(10)
            ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->update(Crud::PAGE_INDEX, Action::NEW,
            fn(Action $action) => $action
                ->setLabel('Ajouter un module'))
            ->update(Crud::PAGE_INDEX, Action::BATCH_DELETE,
            fn(Action$action) => $action
                ->setLabel('Supprimer'))
            ->update(Crud::PAGE_NEW, Action::SAVE_AND_ADD_ANOTHER,
            fn(Action $action) => $action
                ->setLabel('Créer et ajouter un nouveau module')
                );
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm()->hideOnDetail()->hideOnIndex(),
            TextField::new('name', 'Nom du module'),
            AssociationField::new('user', 'Utilisateurs autorisés'),
        ];
    }
}
