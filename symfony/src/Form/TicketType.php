<?php

namespace App\Form;

use App\Entity\Employee;
use App\Entity\Machine;
use App\Entity\Material;
use App\Entity\Site;
use App\Entity\Ticket;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Config\Definition\Exception\Exception;
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
        /** @var User $user */
        $user = $options['user'];

        $type = $options['type'];

        $builder
            ->add('date', DateType::class, [
                'label' => 'Fecha',
                'data' => new \DateTime(),
            ])

            ->add('site', EntityType::class, $this->buildSiteOptions($user));

        if (in_array($type, [Ticket::TYPE_TRUCK_SUPPLY, Ticket::TYPE_TRUCK_WITHDRAWAL])) {
            $builder->add('machine', EntityType::class, $this->buildMachineOptions($user, $type));
        }

            $builder->add('material', EntityType::class, [
                'class' => Material::class,
                'choice_label' => 'name',
                'choice_attr' => function (Material $material) {
                    return ['js-type' => $material->getType()];
                },
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('m');
                },
            ]);

        if ($type == Ticket::TYPE_TRUCK_WITHDRAWAL) {
            $builder->add('num_travels', NumberType::class, [
                'label' => 'Nº de viajes',
                'required' => false
            ]);
        }

        if ($type == Ticket::TYPE_TRUCK_SUPPLY) {
            $builder->add('tons', NumberType::class, [
                'label' => 'Toneladas',
                'required' => false
            ]);
        }

        if ($type == Ticket::TYPE_TRUCK_PORT) {
            $builder->add('portages', ChoiceType::class, [
                'choices' => [
                    1 => 1,
                    2 => 2
                ],
                'label' => 'Portes'
            ]);
        }

        if ($type == Ticket::TYPE_TRUCK_HOURS) {
            $builder->add('hours', NumberType::class, [
                'label' => 'Horas',
            ]);
        }

        if ($type == Ticket::TYPE_MACHINE) {
            $builder->add('hours', NumberType::class, [
                'label' => 'Horas cazo',
            ]);

            $builder->add('hammer_hours', NumberType::class, [
                'label' => 'Horas martillo',
            ]);
        }

        $builder->add('file', FileType::class);

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

    private function buildSiteOptions(User $user)
    {
        return [
            'class' => Site::class,
            'choices' => $user->getEmployee()->getSites(),
            'choice_value' => 'getId',
            'label' => 'Obra',
            'choice_label' => function (Site $site) {
                return $site->getName();
            }
        ];
    }

    private function buildMachineOptions(User $user, $type)
    {
        $machine_type = $this->mapMachineTypeFromTicket($type);

        return [
            'class' => Machine::class,
            'query_builder' => function (EntityRepository $er) use($machine_type) {
                return $er->createQueryBuilder('m')->andWhere('m.type = :type')->setParameter('type', $machine_type);
            },
//            'choices' => $user->getEmployee()->getMachines(),     Show only employee machines
            'label' => 'Máquina',
            'choice_value' => 'getId',
            'choice_label' => function (Machine $machine) {
                return $machine->getName();
            }
        ];
    }

    private function mapMachineTypeFromTicket($type)
    {
        switch ($type) {
            case Ticket::TYPE_MACHINE:
                $type = Machine::TYPE_MACHINE;
                break;
            case Ticket::TYPE_TRUCK_HOURS:
            case Ticket::TYPE_TRUCK_PORT:
            case Ticket::TYPE_TRUCK_SUPPLY:
            case Ticket::TYPE_TRUCK_WITHDRAWAL:
                $type = Machine::TYPE_TRUCK;
        }

        return $type;
    }
}
