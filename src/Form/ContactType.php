<?php

namespace App\Form;

use App\Entity\Demande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('email')
            ->add('sujet', ChoiceType::class, [
                'placeholder' => 'Chosissez une sujet',
                'choices' => $this->getSujetChoices()
            ])
            ->add('message')
        ;
    }

    public function getSujetChoices(){
        $choices = [
            'Je souhaite poser une réclamation',
            'Je souhaite commander un service supplémentaire',
            'Je souhaite en savoir plus sur une suite',
            'J’ai un souci avec cette application'
        ];

        return array_combine($choices, $choices);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Demande::class,
        ]);
    }
}
