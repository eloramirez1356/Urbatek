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
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
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
        /** @var User $user */
        $user = $options['user'];

        $type = $options['type'];

        $builder
            ->add('date', DateType::class, [
                'label' => 'Fecha',
                'data' => new \DateTime(),
            ])

            ->add('site', EntityType::class, $this->buildSiteOptions($user))
            ->add('machine', EntityType::class, $this->buildMachineOptions($user, $type))
        ;

        if (in_array($type, [Ticket::TYPE_TRUCK_SUPPLY, Ticket::TYPE_TRUCK_WITHDRAWAL])) {
            $material_type = $this->mapMaterialType($type);
            $builder->add('material', EntityType::class, [
                'class' => Material::class,
                'choice_label' => 'name',
                'choice_attr' => function (Material $material) {
                    return ['js-type' => $material->getType()];
                },
                'query_builder' => function (EntityRepository $er) use($material_type) {
                    return $er->createQueryBuilder('m')->andWhere('m.type = :type')->setParameter('type', $material_type);
                }
            ]);
        }

        if ($type == Ticket::TYPE_TRUCK_WITHDRAWAL) {
            $builder->add('provider', ChoiceType::class, [
		'choices' => [
                    'ALDEHUELA AMAEXCO' => 'ALDEHUELA AMAEXCO',
                    'TRUSAN' => 'TRUSAN',
                    'RECICAM' => 'RECICAM', 
                    'SALMEDINA VALDEMINGOMEZ' => 'SALMEDINA VALDEMINGOMEZ', 
                    'LAREDO CEMEX' => 'LAREDO CEMEX',
                    'LAREDO SODIRA M506' => 'LAREDO SODIRA M506',
                    'LAREDO EIFFAGE' => 'LAREDO EIFFAGE',
                    'LAREDO SALITRAL' => 'LAREDO SALITRAL',
                    'ALAMEDA DE LA SAGRA ONEGESCOR ' => 'ALAMEDA DE LA SAGRA ONEGESCOR ',
                    'LOS CANTILLOS FGM' => 'LOS CANTILLOS FGM',
                    'ARIDOS LA CABEZA (EL ALAMO)' => 'ARIDOS LA CABEZA (EL ALAMO)',
                    'GADARAI' => 'GADARAI',
                    'TRAMSA' => 'TRAMSA',
                    'SODIRA LA POLA' => 'SODIRA LA POLA',
                    'CCR LAS MULAS' => 'CCR LAS MULAS',
                    'TEC REC' => 'TEC REC',
                    'ARIDENCA (MSC ARIDOS 2002)' => 'ARIDENCA (MSC ARIDOS 2002)',
                    'OTRO' => 'OTRO',
                ],
                'label' => 'Destino'
            ]);

            $builder->add('num_travels', NumberType::class, [
                'label' => 'Nº de viajes',
                'required' => true
            ]);
        }

        if ($type == Ticket::TYPE_TRUCK_SUPPLY) {
            $builder->add('provider', ChoiceType::class, [
                'choices' => [
		    'Tec Rec' => 'Tec Rec',
                    'Sodira La pola' => 'Sodira La pola',
                    'Sodira El puente' => 'Sodira El puente',
                    'Sodira Gravera Roman M506' => 'Sodira Gravera Roman M506',
                    'Eiffage' => 'Eiffage',
                    'Recicam' => 'Recicam',
                    'Salmedina' => 'Salmedina',
                    'Alameda de la Sagra Onegescor' => 'Alameda de la Sagra Onegescor',
                    'Msc aridos'  => 'Msc aridos',
                    'Hanson'  => 'Hanson',
                    'Tramsa'  => 'Tramsa',
                    'Mahorsa'  => 'Mahorsa',
                    'Otro' => 'Otro',
                ],
                'label' => 'Origen'
            ]);

            $builder->add('tons', NumberType::class, [
                'label' => 'Toneladas',
                'required' => false
            ]);

            $builder->add('num_travels', NumberType::class, [
                'label' => 'Nº de viajes',
                'required' => true
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

        if ($type == Ticket::TYPE_MACHINE) {
            $builder->add('hours', NumberType::class, [
                'label' => 'Horas totales (Antonio Aguado)',
            ]);

            $builder->add('hammer_hours', NumberType::class, [
                'label' => 'Horas martillo (Firmadas)',
                'required' => false
            ]);

            $builder->add('spoon_hours', NumberType::class, [
                'label' => 'Horas cazo/máquina (Firmadas)',
                'required' => false
            ]);
        }

        $builder->add('provider_signed', ChoiceType::class, [
            'choices' => [
                'No' => 0,
                'Si' => 1
            ],
            'label' => '¿Firmado por el cliente?'
        ]);

        if ($type != Ticket::TYPE_MACHINE) {
            $builder->add('hours', NumberType::class, [
                'label' => 'Horas (Antonio Aguado)',
            ]);
        }

        $builder->add('liters', NumberType::class, [
            'required' => false,
            'label' => 'Litros de gasóleo repostado'
        ]);

        $builder->add('comments', TextType::class, [
            'required' => false,
            'label' => 'Comentarios'
        ]);

        $builder->add('file', FileType::class, [
            'required' => false,
            'label' => 'Foto albarán'
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
        $resolver->setDefault('csrf_protection', false);
        $resolver->setDefault('allow_extra_fields', true);
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
//            'query_builder' => function (EntityRepository $er) use($machine_type) {
//                return $er->createQueryBuilder('m')->andWhere('m.type = :type')->setParameter('type', $machine_type);
//            },
            'choices' => $user->getEmployee()->getMachines(),//      Show only employee machines
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
                break;
        }

        return $type;
    }

    private function mapMaterialType($type)
    {
        switch ($type) {
            case Ticket::TYPE_TRUCK_SUPPLY:
                $type = Material::TYPE_SUPPLY;
                break;
            case Ticket::TYPE_TRUCK_WITHDRAWAL:
                $type = Material::TYPE_WITHDRAWAL;
                break;
        }

        return $type;
    }
}
