<?php

namespace App\Controller\Admin;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UtilisateurCrudController extends AbstractCrudController
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public static function getEntityFqcn(): string
    {
        return Utilisateur::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Gerants')
            ->setEntityLabelInSingular('Gerant')
            ->setHelp('new','Après la creation d\'un gérant, vous devez ajouter l\'etablissement a sa charge en éditant la page de l\'etablissement');
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('prenom')->setLabel('Prénom');
        yield TextField::new('nom')->setLabel('Nom');
        yield EmailField::new('email')->setLabel('Email');
        yield TextField::new('password')->setLabel('Mot de Passe')
            ->hideOnIndex();
        yield AssociationField::new('etablissements')
            //Changes the display in the index page from numbers to lists
            ->formatValue(function ($value, $entity){
                return implode(",",$entity->getEtablissements()->toArray());
            })
            ->setDisabled()
            ->hideWhenCreating()
            ->setHelp("Vous devez ajouter le gérant a l'etablissement depuis la page de l'etablissement");
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        return parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters)
            ->where('entity.isGerant = true');
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $gerant = $entityInstance;
        $plainPassword = $gerant->getPassword();
        $hashedPassword = $this->passwordHasher->hashPassword($gerant, $plainPassword);
        $gerant->setPassword($hashedPassword);
        $gerant->setRoles(['ROLE_GERANT']);
        $gerant->setIsGerant(true);
        parent::persistEntity($entityManager, $gerant);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $gerant = $entityInstance;
        $plainPassword = $gerant->getPassword();
        $hashedPassword = $this->passwordHasher->hashPassword($gerant, $plainPassword);
        $gerant->setPassword($hashedPassword);
        parent::persistEntity($entityManager, $gerant);
    }

}
