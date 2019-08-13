<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
        $builder
            ->add('date', DateType::class, [
                'label' => 'Fecha',
                'data' => new \DateTime(),
            ])

            ->add('obra', ChoiceType::class, [
                'label' => 'Obra',
                'choices' => ['Moratalaz' => 1, 'Valdemoro' => 2]
            ])
            ->add('machine', ChoiceType::class, [
                'label' => 'Máquina',
                'choices' => ['Mixta' => 'mixta', 'Retro' => 'retro']

            ])
            ->add('travels', NumberType::class, [
                'label' => 'Nº de viajes',
            ])

            ->add('hours', NumberType::class, [
                'label' => 'Horas',
            ])

            ->add('material', ChoiceType::class, [
                'label' => 'Material',
                'choices' => ['Zahorra' => 'zahorra', 'Grava' => 'grava']
            ])

            ->add('file', FileType::class, ['label' => 'Subir albarán'])

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
    }
}
