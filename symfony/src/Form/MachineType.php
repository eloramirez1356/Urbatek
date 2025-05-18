<?php

namespace App\Form;

use App\Entity\Machine;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Defines the form used to edit an user.
 *
 * @author Romain Monteil <monteil.romain@gmail.com>
 */
class MachineType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nombre'
            ])

            ->add('brand', TextType::class, [
                'label' => 'Marca',
            ])

            ->add('register', TextType::class, [
                'label' => 'Matrícula',
            ])

	    ->add('type', ChoiceType::class, [
                'label' => 'Tipo de máquina',
                'choices' => [
                    Machine::TYPE_MACHINE => Machine::TYPE_MACHINE,
                    Machine::TYPE_TRUCK => Machine::TYPE_TRUCK,
                ]
            ])

            ->add('submit', SubmitType::class, [
                'label' => 'Guardar'
            ])
        ;
    }
}
