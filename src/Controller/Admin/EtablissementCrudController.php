<?php

namespace App\Controller\Admin;

use App\Entity\Etablissement;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class EtablissementCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Etablissement::class;
    }

    public function configureFields(string $pageName): iterable
    {

        yield TextField::new('nom');
        yield TextField::new('ville');
        yield TextField::new('addrese');
        yield TextEditorField::new('description');
        yield AssociationField::new('gerant')
            ->setQueryBuilder(function (QueryBuilder $queryBuilder){
                $queryBuilder
                    ->where('entity.isGerant = true');
            });
    }
}
