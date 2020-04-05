<?php

namespace App\Form;

use App\Entity\Material;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Defines the form used to edit an user.
 *
 * @author Romain Monteil <monteil.romain@gmail.com>
 */
class MaterialType extends AbstractType
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


            ->add('price', NumberType::class, [
                'label' => 'Precio',
            ])

            ->add('type', ChoiceType::class, [
                'label' => 'Tipo material',
                'choices' => [
                     'Suministro' => Material::TYPE_SUPPLY,
                    'Retirada' => Material::TYPE_WITHDRAWAL,
                ]
            ])

            ->add('submit', SubmitType::class, [
                'label' => 'Guardar'
            ])
        ;
    }
}
