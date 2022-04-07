<?php

namespace App\Form;

use App\Entity\Etablissement;
use App\Entity\Reservation;
use App\Entity\Suite;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('etablissement', EntityType::class, [
                'class' => Etablissement::class,
                'placeholder' => 'Choisissez un etablissement',
                'mapped' => false
            ])
            ->add('checkIn', DateType::class, [
                'widget' => 'choice',
                'format' => 'd-M-y',
                'by_reference' => true,
                'mapped' => true,
                'required' => true,
            ])
            ->add('checkOut', DateType::class, [
                'widget' => 'choice',
                'format' => 'd-M-y',
                'by_reference' => true,
                'mapped' => true,
                'required' => true,
            ])

        ;

        $builder->get('etablissement')->addEventListener(
            FormEvents::POST_SUBMIT,
            function(FormEvent $event)
            {
                $form = $event->getForm();

                if ($form->getData() !== null) {
                    $form->getParent()->add('suite', EntityType::class, [
                        'class' => Suite::class,
                        'placeholder' => 'Choisissez une suite',
                        'choices' => $form->getData()->getSuites(),
                        'mapped' => true,
                    ]);
                }
            }
        );

        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function(FormEvent $event)
            {
                $form = $event->getForm();
                $data = $event->getData();
                $suite = $data->getSuite();

                if ($suite !== null) {

                    $form->get('etablissement')->setData($suite->getEtablissement());

                    $form->add('suite', EntityType::class, [
                        'class' => Suite::class,
                        'placeholder' => 'Choisissez une suite',
                        'choices' => $form->getData()->getSuites(),
                        'mapped' => true,
                    ]);
                } else {
                    $form->add('suite', EntityType::class, [
                        'class' => Suite::class,
                        'placeholder' => 'Choisissez une suite',
                        'choices' => []
                    ]);
                }

            }
        );

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
            'allow_extra_fields' => true
        ]);
    }
}
