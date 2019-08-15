<?php

namespace App\Form;

use App\Entity\Site;
use App\Repository\SiteRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
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
        $builder
            ->add('date', DateType::class, [
                'label' => 'Fecha',
                'data' => new \DateTime(),
            ])

            ->add('obra', EntityType::class, $this->buildSiteOptions())
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

    private function buildSiteOptions()
    {
        return [
            'class' => Site::class,
            'query_builder' => function (EntityRepository $repository) {

                return $repository->createQueryBuilder('s')->setMaxResults(10);
            },
            'choice_label' => function (Site $site) {
                return $site->getName();
            },
            'choice_value' => 'getId'
        ];
    }
}
