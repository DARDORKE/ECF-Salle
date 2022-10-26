<?php

namespace App\Controller\Admin;

use App\Entity\Partner;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Form\Filter\Type\ArrayFilterType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class PartnerCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Partner::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->update(Crud::PAGE_INDEX, Action::NEW,
                fn(Action $action) => $action->setLabel('Ajouter un partenaire'))
            ->update(Crud::PAGE_INDEX, Action::BATCH_DELETE,
                fn(Action$action) => $action->setLabel('Supprimer'))
            ->update(Crud::PAGE_NEW, Action::SAVE_AND_ADD_ANOTHER,
                fn(Action $action) => $action->setLabel('Créer et ajouter un nouveau partenaire'));
    }

    public function configureCrud(Crud $crud): Crud
    {

        return $crud->setEntityLabelInPlural('Partenaires')
            ->setEntityLabelInSingular('Partenaire')
            ->setPageTitle('index','LISTE DES PARTENAIRES')
            ->setPageTitle('edit', 'MODIFICATION D\'UN PARTENAIRE')
            ->setPageTitle('new', 'CREATION D\'UN PARTENAIRE')
            ->setPaginatorPageSize(10)
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm()->hideOnIndex(),
            TextField::new('name', 'Nom de partenaire'),
            AssociationField::new('structures', 'Structures du partenaire')
                ->setFormTypeOptions([
                'by_reference' => false,
            ]),
            AssociationField::new('user','Utilisateur associé au partenaire')
                ->hideWhenUpdating()
                ->hideOnIndex(),
        ];
    }

}
