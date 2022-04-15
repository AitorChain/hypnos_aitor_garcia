<?php

namespace App\Controller\Admin;

use App\Entity\Etablissement;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class EtablissementCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Etablissement::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Hôtels')
            ->setEntityLabelInSingular('Hôtel')
            ->showEntityActionsInlined();
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->setPermission(Action::NEW, 'ROLE_ADMIN')
            ->setPermission(Action::DELETE, 'ROLE_ADMIN')
            ->setPermission(Action::EDIT, 'ROLE_ADMIN')
            ;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        //Forces the Gerant to only see in the Index the etablissements which he owns.
        if ($this->isGranted('ROLE_GERANT')){
            return parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters)
                ->where('entity.gerant = :gerant')
                ->setParameter(':gerant', $this->getUser());
        }

        return parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);

    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('nom');
        yield TextField::new('ville');
        yield TextField::new('addrese');
        yield AssociationField::new('gerant')
            //Fixes the search to only display gerant users
            ->setQueryBuilder(function (QueryBuilder $queryBuilder){
                $queryBuilder
                    ->where('entity.isGerant = true');
            })
            ->setHelp("Si vous n'avez pas encore crée votre gérant, vous devez le créer et puis
            retourner sur cette page pour l'asigner a cet établissement")
            ->setPermission('ROLE_ADMIN');
        yield ImageField::new('photoFilename')
            ->setBasePath('/uploads/images')
            ->setUploadDir('/public/uploads/images')
            ->setLabel('Photo')
        ;
        yield TextareaField::new('description')
        ->hideOnIndex();
        yield AssociationField::new('suites', 'Nombre de suites')
        ->hideOnForm();
    }

    public function deleteEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        //Makes impossible for the Gerant to delete an hotel entity
        if ($this->isGranted('ROLE_ADMIN')){
            parent::deleteEntity($entityManager, $entityInstance);
        }
    }
}
