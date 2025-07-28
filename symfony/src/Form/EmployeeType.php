<?php

namespace App\Form;

use Doctrine\DBAL\Types\FloatType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Defines the form used to edit an user.
 *
 * @author Romain Monteil <monteil.romain@gmail.com>
 */
class EmployeeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nombre',
                'required' => true
            ])
            ->add('surname', TextType::class, [
                'label' => 'Apellidos',
                'required' => true
            ])
            ->add('username', TextType::class, [
                'label' => 'Usuario',
                'required' => true,
                'mapped' => false
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => true,
                'mapped' => false
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Contraseña',
                'required' => true,
                'mapped' => false
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Guardar'
            ])
        ;
    }
}
