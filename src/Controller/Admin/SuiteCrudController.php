<?php

namespace App\Controller\Admin;

use App\Entity\Suite;
use App\Form\GallerieType;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
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
            ->setLabel('Hôtel')
            ->setQueryBuilder(function (QueryBuilder $queryBuilder){
               $queryBuilder
                    ->where('entity.gerant = :gerant')
                    ->setParameter('gerant', $this->getUser());
            });
        yield TextField::new('titre')
            ->setLabel('Nom de la suite');
        yield MoneyField::new('prix')
            ->setCurrency('EUR')
            ->setStoredAsCents();
        yield TextareaField::new('description')
            ->hideOnIndex();
        yield TextField::new('lien_booking')
            ->setLabel('Page en Booking.com');
        yield ImageField::new('photoFilename')
            ->setBasePath('/uploads/images')
            ->setUploadDir('/public/uploads/images')
            ->setLabel('Image de mise en avant')
        ;
        yield CollectionField::new('gallerieImages')
            ->setLabel('')
            ->setEntryType(GallerieType::class)
            ->setFormTypeOption('by_reference', false)
            ->onlyOnForms();
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->setPermission(Action::NEW, 'ROLE_GERANT')
            ->setPermission(Action::DELETE, 'ROLE_GERANT')
            ->setPermission(Action::EDIT, 'ROLE_GERANT')
            ;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->showEntityActionsInlined()
            ->setEntityLabelInPlural('Suites')
            ->setEntityLabelInSingular('Suite');
    }
}
