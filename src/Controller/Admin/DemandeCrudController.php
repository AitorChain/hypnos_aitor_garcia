<?php

namespace App\Controller\Admin;

use App\Entity\Demande;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class DemandeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Demande::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->showEntityActionsInlined()
            ->setEntityLabelInPlural('Demandes')
            ->setEntityLabelInSingular('Demande');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->setPermission(Action::NEW, 'ROLE_SUPER_ADMIN')
            ->setPermission(Action::DELETE, 'ROLE_ADMIN')
            ->setPermission(Action::EDIT, 'ROLE_SUPER_ADMIN')
            ->setPermission(Action::DETAIL, 'ROLE_ADMIN')
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield DateTimeField::new('createdAt')
            ->setLabel('Date de la demande');
        yield TextField::new('prenom')
            ->setLabel('Prenom');
        yield TextField::new('nom')
            ->setLabel('Nom');
        yield EmailField::new('email')
            ->setLabel('Email du client');
        yield TextField::new('sujet')
        ->setLabel('Sujet');
        yield TextareaField::new('message')
        ->hideOnIndex();

    }
}
