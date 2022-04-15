<?php

namespace App\Controller\Admin;

use App\Entity\Reservation;
use App\Entity\Suite;
use App\Entity\Utilisateur;
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
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Validator\Constraints\Date;

class ReservationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Reservation::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield DateTimeField::new('createdAt')
            ->setLabel('Date de reservation');
        yield DateField::new('checkIn')
            ->setLabel('Date d\'arrivée');
        yield DateField::new('checkOut')
            ->setLabel('Date de depart');
        yield AssociationField::new('suite')
            ->setLabel('Suite reservée');;
        yield AssociationField::new('client')
            ->setPermission('ROLE_GERANT')
            ->setLabel('Nom du client');
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->showEntityActionsInlined()
            ->setEntityLabelInPlural('Reservations')
            ->setEntityLabelInSingular('Reservation');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->setPermission(Action::NEW, 'ROLE_ADMIN')
            ->setPermission(Action::EDIT, 'ROLE_GERANT')
            ;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {

        if ($this->isGranted('ROLE_CLIENT')) {

            return parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters)
                ->where('entity.client = :client')
                ->setParameter('client', $this->getUser());

        } elseif ($this->isGranted('ROLE_GERANT')){

            return parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters)
                ->leftJoin('entity.suite', 's')
                ->leftJoin('s.etablissement', 'e')
                ->where('e.gerant = :gerant')
                ->setParameter('gerant', $this->getUser());

        }
        return parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);
    }

    public function deleteEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {

        $checkIn = date_timestamp_get($entityInstance->getCheckIn());
        $annulation = $checkIn - (259200);

        if (time() <= $annulation){
            parent::deleteEntity($entityManager, $entityInstance);
        } elseif($this->isGranted('ROLE_GERANT')){
            parent::deleteEntity($entityManager, $entityInstance);
        }

    }

}
