<?php

namespace App\Controller\Admin;

use App\Entity\Suite;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SuiteCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Suite::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield AssociationField::new('etablissement')
            ->setQueryBuilder(function (QueryBuilder $queryBuilder){
               $queryBuilder
                    ->where('entity.gerant = :gerant')
                    ->setParameter('gerant', $this->getUser());
            });
        yield TextField::new('titre');
        yield TextareaField::new('description');
        yield MoneyField::new('prix')->setCurrency('EUR');
        yield TextField::new('lien_booking');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            // ...
            ->setPermission(Action::NEW, 'ROLE_GERANT')
            ->setPermission(Action::DELETE, 'ROLE_GERANT')
            ->setPermission(Action::EDIT, 'ROLE_GERANT')
            ->setPermission(Action::DETAIL, 'ROLE_GERANT')
            ;
    }
}
