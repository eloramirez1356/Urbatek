<?php

namespace App\Form;


use App\Entity\Employee;
use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Defines the form used to edit an user.
 *
 * @author Romain Monteil <monteil.romain@gmail.com>
 */
class SiteType extends AbstractType
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

            ->add('employees', ChoiceType::class, [
                'label' => 'Empleados',
                'choices' => $options['employees'],
                'expanded'  => true,
                'multiple'  => true,
                'choice_label' => function(Employee $employee) {
                    return $employee ? strtoupper($employee->getName()) : '';
                },
                'choice_value' => function(Employee $employee) {
                    return $employee ? strtoupper($employee->getId()) : '';
                },
            ])

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
        $resolver->setDefault('employees', null);
    }
}
