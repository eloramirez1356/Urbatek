<?php

namespace App\Form;

use App\Entity\Employee;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
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
class DailyReportType extends AbstractType
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
        ;

        $builder->add('hours', NumberType::class, [
            'label' => 'Horas',
        ]);

        if ($user->isAdmin()) {
            $builder->add('employee', EntityType::class, [
                'class' => Employee::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('e');
                },
                'label' => 'Empleado',
                'choice_value' => 'getId',
                'choice_label' => function (Employee $employee) {
                    return $employee->getFullName();
                }
            ]);
        }

        $builder->add('submit', SubmitType::class, [
            'label' => 'Guardar'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('user', null);
        $resolver->setDefault('type', null);
        $resolver->setDefault('all_employees', []);
    }
}
