<?php

namespace App\Form;

use App\Entity\Machine;
use App\Entity\Site;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Defines the form used to edit an user.
 *
 * @author Romain Monteil <monteil.romain@gmail.com>
 */
class TicketType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var User $user */
        $user = $options['user'];

        $builder
            ->add('date', DateType::class, [
                'label' => 'Fecha',
                'data' => new \DateTime(),
            ])

            ->add('site', EntityType::class, $this->buildSiteOptions($user))
            ->add('machine', EntityType::class, $this->buildMachineOptions($user))
            ->add('num_travels', NumberType::class, [
                'label' => 'Nº de viajes',
            ])

            ->add('hours', NumberType::class, [
                'label' => 'Horas',
            ])

            ->add('material', ChoiceType::class, [
                'label' => 'Material',
                'choices' => ['Zahorra' => 'zahorra', 'Grava' => 'grava']
            ])

//            ->add('file', FileType::class, ['label' => 'Subir albarán'])

            ->add('submit', SubmitType::class, [
                'label' => 'Guardar'
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('user', null);
    }

    private function buildSiteOptions(User $user)
    {
        return [
            'class' => Site::class,
            'choices' => $user->getEmployee()->getSites(),
            'choice_value' => 'getId',
            'choice_label' => function (Site $site) {
                return $site->getName();
            }
        ];
    }

    private function buildMachineOptions(User $user)
    {
        return [
            'class' => Machine::class,
            'choices' => $user->getEmployee()->getMachines(),
            'choice_value' => 'getId',
            'choice_label' => function (Machine $machine) {
                return $machine->getName();
            }
        ];
    }
}
