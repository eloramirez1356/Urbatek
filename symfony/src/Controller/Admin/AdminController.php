<?php

namespace App\Controller\Admin;

use App\Entity\DailyReport;
use App\Entity\Document;
use App\Entity\Employee;
use App\Entity\Machine;
use App\Entity\Material;
use App\Entity\Site;
use App\Entity\Ticket;
use App\Entity\User;
use App\Form\EmployeeType;
use App\Form\MachineType;
use App\Form\MaterialType;
use App\Form\SiteType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller used to manage blog contents in the backend.
 *
 * Please note that the application backend is developed manually for learning
 * purposes. However, in your real Symfony application you should use any of the
 * existing bundles that let you generate ready-to-use backends without effort.
 *
 * See http://knpbundles.com/keyword/admin
 *
 * @Route("/admin")
 *
 * @author Ryan Weaver <weaverryan@gmail.com>
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 */
class AdminController extends AbstractController
{
    /**
     * Lists all Post entities.
     *
     * This controller responds to two different routes with the same URL:
     *   * 'admin_post_index' is the route with a name that follows the same
     *     structure as the rest of the controllers of this class.
     *   * 'admin_index' is a nice shortcut to the backend homepage. This allows
     *     to create simpler links in the templates. Moreover, in the future we
     *     could move this annotation to any other controller while maintaining
     *     the route name and therefore, without breaking any existing link.
     *
     * @Route("/", methods={"GET"}, name="admin_index")
     * @Route("/", methods={"GET"}, name="admin_post_index")
     */
    public function index(): Response
    {
        return $this->redirectToRoute('admin_view_tickets');
    }

    /**
     * Add a machine
     *
     * @Route("/machines", methods={"GET", "POST"}, name="admin_add_machine")
     *
     */
    public function editMachinesAction(Request $request): Response
    {
        $form = $this->createForm(MachineType::class);

        $machine_repo = $this->getDoctrine()->getRepository(Machine::class);

        if ($request->isMethod('POST') && $form->submit($request->request->get('machine'))->isValid()) {
            $machine = new Machine();
            $machine->setName($form->getData()['name']);
            $machine->setBrand($form->getData()['brand']);
            $machine->setRegister($form->getData()['register']);
	    $machine->setType($form->getData()['type']);
            $this->get('doctrine')->getEntityManager()->persist($machine);
            $this->get('doctrine')->getEntityManager()->flush();
        }

        $all_machines = $machine_repo->findAll();

        return $this->render('admin/blog/add_machine.html.twig', [
            'machines' => $all_machines,
            'machine_form' => $form->createView()
        ]);
    }

    /**
     * Add a material
     *
     * @Route("/materials", methods={"GET", "POST"}, name="admin_add_material")
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */

    public function editMaterialAction(Request $request): Response
    {
        $form = $this->createForm(MaterialType::class);

        $material_repo = $this->getDoctrine()->getRepository(Material::class);

        $all_materials = $material_repo->findAll();

        if ($request->isMethod('POST') && $form->submit($request->request->get('material'))->isValid()) {
            $data = $request->request->get('material');
            $material = new Material($data['name'], $data['price'], $data['type']);
            $this->get('doctrine')->getEntityManager()->persist($material);
            $this->get('doctrine')->getEntityManager()->flush();
        }

        return $this->render('admin/blog/add_material.html.twig', [
            'materials' => $all_materials,
            'material_form' => $form->createView()
        ]);
    }

    /**
     * View employees
     *
     * @Route("/employees", methods={"GET", "POST"}, name="admin_add_employee")
     *
     */
    public function viewEmployeesAction(Request $request): Response
    {
        $form = $this->createForm(EmployeeType::class);
        $employee_repo = $this->getDoctrine()->getRepository(Employee::class);
        $user_repo = $this->getDoctrine()->getRepository(User::class);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            
            if ($form->isSubmitted() && $form->isValid()) {
                $data = $form->getData();
                $formData = $request->request->get('employee');
                
                // Check if username already exists
                $existingUser = $user_repo->findOneBy(['username' => $formData['username']]);
                if ($existingUser) {
                    $this->addFlash('error', 'El nombre de usuario ya existe.');
                    return $this->render('admin/blog/add_employee.html.twig', [
                        'employee_form' => $form->createView(),
                        'employees' => $employee_repo->findAll()
                    ]);
                }
                
                // Create Employee
                $employee = new Employee();
                $employee->setName($data['name']);
                $employee->setSurname($data['surname']);
                
                // Create User
                $user = new User();
                $user->setUsername($formData['username']);
                $user->setEmail($formData['email']);
                $user->setPassword(password_hash($formData['password'], PASSWORD_DEFAULT));
                $user->setRoles(['ROLE_USER']);
                
                // Link Employee and User
                $employee->setUser($user);
                $user->setEmployee($employee);
                
                // Persist both entities
                $em = $this->getDoctrine()->getManager();
                $em->persist($employee);
                $em->persist($user);
                $em->flush();
                
                $this->addFlash('success', 'Empleado y usuario creados correctamente.');
                return $this->redirectToRoute('admin_add_employee');
            }
        }

        $all_employees = $employee_repo->findAll();

        return $this->render('admin/blog/add_employee.html.twig', [
            'employee_form' => $form->createView(),
            'employees' => $all_employees
        ]);
    }

    /**
     * Edit a employee
     *
     * @Route("/employees/{employee_id}", methods={"GET"}, name="admin_edit_employee")
     *
     */
    public function editEmployeeAction($employee_id)
    {

    }

    /**
     * Get available and assigned machines for an employee
     *
     * @Route("/employees/{employee_id}/machines", methods={"GET"}, name="admin_employee_machines")
     */
    public function getEmployeeMachinesAction($employee_id): JsonResponse
    {
        try {
            $employee_repo = $this->getDoctrine()->getRepository(Employee::class);
            $machine_repo = $this->getDoctrine()->getRepository(Machine::class);
            
            $employee = $employee_repo->find($employee_id);
            if (!$employee) {
                return new JsonResponse(['error' => 'Empleado no encontrado'], 404);
            }
            
            // Get all machines
            $allMachines = $machine_repo->findAll();
            $assignedMachines = $employee->getMachines();
            
            // Convert to arrays
            $availableMachines = [];
            $assignedMachinesArray = [];
            
            foreach ($allMachines as $machine) {
                $machineData = [
                    'id' => $machine->getId(),
                    'name' => $machine->getName(),
                    'brand' => $machine->getBrand(),
                    'register' => $machine->getRegister(),
                    'type' => $machine->isTruck() ? 'truck' : 'machine'
                ];
                
                if (in_array($machine, $assignedMachines)) {
                    $assignedMachinesArray[] = $machineData;
                } else {
                    $availableMachines[] = $machineData;
                }
            }
            
            return new JsonResponse([
                'available' => $availableMachines,
                'assigned' => $assignedMachinesArray
            ]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Error interno: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Assign machine to employee
     *
     * @Route("/employees/{employee_id}/machines/{machine_id}/assign", methods={"POST"}, name="admin_assign_machine")
     */
    public function assignMachineAction($employee_id, $machine_id): JsonResponse
    {
        $employee_repo = $this->getDoctrine()->getRepository(Employee::class);
        $machine_repo = $this->getDoctrine()->getRepository(Machine::class);
        
        $employee = $employee_repo->find($employee_id);
        $machine = $machine_repo->find($machine_id);
        
        if (!$employee || !$machine) {
            return new JsonResponse(['success' => false, 'error' => 'Empleado o máquina no encontrado'], 404);
        }
        
        // Check if already assigned
        $machines = $employee->getMachines();
        if (in_array($machine, $machines)) {
            return new JsonResponse(['success' => false, 'error' => 'La máquina ya está asignada'], 400);
        }
        
        // Add machine to employee
        $machines[] = $machine;
        $employee->setMachines($machines);
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($employee);
        $em->flush();
        
        return new JsonResponse(['success' => true]);
    }

    /**
     * Unassign machine from employee
     *
     * @Route("/employees/{employee_id}/machines/{machine_id}/unassign", methods={"POST"}, name="admin_unassign_machine")
     */
    public function unassignMachineAction($employee_id, $machine_id): JsonResponse
    {
        $employee_repo = $this->getDoctrine()->getRepository(Employee::class);
        $machine_repo = $this->getDoctrine()->getRepository(Machine::class);
        
        $employee = $employee_repo->find($employee_id);
        $machine = $machine_repo->find($machine_id);
        
        if (!$employee || !$machine) {
            return new JsonResponse(['success' => false, 'error' => 'Empleado o máquina no encontrado'], 404);
        }
        
        // Remove machine from employee
        $machines = $employee->getMachines() ?: [];
        if (is_object($machines)) {
            $machines = $machines->toArray();
        }
        $machines = array_filter($machines, function($m) use ($machine) {
            return $m->getId() !== $machine->getId();
        });
        $employee->setMachines($machines);
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($employee);
        $em->flush();
        
        return new JsonResponse(['success' => true]);
    }

    /**
     * Add a ticket
     *
     * @Route("/tickets", methods={"GET"}, name="admin_view_tickets")
     *
     */

    public function editTicketAction(Request $request): Response
    {
        $ticket_repo = $this->getDoctrine()->getRepository(Ticket::class);
        $site_repo = $this->getDoctrine()->getRepository(Site::class);
        $employee_repo = $this->getDoctrine()->getRepository(Employee::class);

        // Get filter parameters
        $year = $request->query->get('year');
        $month = $request->query->get('month');
        $siteId = $request->query->get('site');
        $employeeId = $request->query->get('employee');
        $page = $request->query->getInt('page', 1);
        $limit = 500; // Items per page

        // Create query builder with filters
        $qb = $ticket_repo->createQueryBuilder('t')
            ->join('t.site', 's')
            ->where('s.is_active = 1');

        if ($year) {
            $qb->andWhere('SUBSTRING(t.date, 1, 4) = :year')
               ->setParameter('year', $year);
        }

        if ($month) {
            $qb->andWhere('SUBSTRING(t.date, 6, 2) = :month')
               ->setParameter('month', $month);
        }

        if ($siteId) {
            $qb->andWhere('t.site = :site')
               ->setParameter('site', $siteId);
        }

        if ($employeeId) {
            $qb->andWhere('t.employee = :employee')
               ->setParameter('employee', $employeeId);
        }

        $qb->orderBy('t.id', 'DESC');

        // Get total count for pagination
        $totalItems = count($qb->getQuery()->getResult());
        $totalPages = ceil($totalItems / $limit);

        // Add pagination
        $qb->setFirstResult(($page - 1) * $limit)
           ->setMaxResults($limit);

        $tickets = $qb->getQuery()->getResult();

        // Get available years for filter
        $years = $ticket_repo->createQueryBuilder('t')
            ->select('DISTINCT SUBSTRING(t.date, 1, 4) as year')
            ->orderBy('year', 'DESC')
            ->getQuery()
            ->getResult();

        $months = [
            ['value' => '', 'label' => 'Todos los meses'],
            ['value' => '01', 'label' => 'Enero'],
            ['value' => '02', 'label' => 'Febrero'],
            ['value' => '03', 'label' => 'Marzo'],
            ['value' => '04', 'label' => 'Abril'],
            ['value' => '05', 'label' => 'Mayo'],
            ['value' => '06', 'label' => 'Junio'],
            ['value' => '07', 'label' => 'Julio'],
            ['value' => '08', 'label' => 'Agosto'],
            ['value' => '09', 'label' => 'Septiembre'],
            ['value' => '10', 'label' => 'Octubre'],
            ['value' => '11', 'label' => 'Noviembre'],
            ['value' => '12', 'label' => 'Diciembre']
        ];

        // Get active sites and employees for filters
        $sites = $site_repo->findBy(['is_active' => true], ['name' => 'ASC']);
        $employees = $employee_repo->findAll();

        return $this->render('admin/blog/add_ticket.html.twig', [
            'tickets' => $tickets,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems,
            'years' => array_column($years, 'year'),
            'months' => $months,
            'sites' => $sites,
            'employees' => $employees,
            'selectedYear' => $year,
            'selectedMonth' => $month,
            'selectedSite' => $siteId,
            'selectedEmployee' => $employeeId
        ]);
    }

    /**
     * View site tickets
     *
     * @Route("/site-tickets/{site_id}", methods={"GET"}, name="admin_site_tickets")
     * @param $site_id
     * @return Response
     */
    public function siteTicketsAction($site_id): Response
    {
        $ticket_repo = $this->getDoctrine()->getRepository(Ticket::class);
        $site_repo = $this->getDoctrine()->getRepository(Site::class);

        $site = $site_repo->find($site_id);
        $site_tickets = $ticket_repo->findOfSite($site);

        return $this->render('admin/blog/add_ticket.html.twig', [
            'tickets' => $site_tickets,
            'site' => $site
        ]);
    }

    /**
     * Add a site
     *
     * @Route("/view_document/{document_id}", methods={"GET"}, name="admin_view_document")
     * @param int $document_id
     * @return Response
     */
    public function viewDocumentAction(int $document_id)
    {
        /** @var Document $document */
        $document = $this->getDoctrine()->getRepository(Document::class)->find($document_id);

        if (!$document) {
            throw $this->createNotFoundException('flash.not_authorized_document');
        }

        $response = new Response();


        $content = file_get_contents($document->getWebPath());

        if ($mime_type = $this->getCleanMimeType($document->getName())) {
            $response->headers->set('Content-Type', $mime_type);
        }

        $response->headers->set('Content-Disposition', 'inline;filename="albaran"');
        $response->setContent($content);
        return $response;
    }

    /**
     * Add a site
     *
     * @Route("/sites", methods={"GET", "POST"}, name="admin_add_site")
     *
     */
    public function sitesAction(Request $request): Response
    {
        $employee_repo = $this->getDoctrine()->getRepository(Employee::class);
        $all_employees = $employee_repo->findAll();

        $employee_choices = [];
        foreach ($all_employees as $employee) {
            $employee_choices[] = $employee;
        }

        $form = $this->createForm(SiteType::class, null, ['employees' => $employee_choices]);

        if ($request->isMethod('POST') && $form->submit($request->request->get('site'))->isValid()) {
            $data = $request->request->get('site');
            $site = new Site($data['name']);
            if(isset($data['employees'])) {
                foreach ($data['employees'] as $employee_id) {
                    $site->addEmployee($employee_repo->find($employee_id));
                }
            }


            $this->get('doctrine')->getEntityManager()->persist($site);
            $this->get('doctrine')->getEntityManager()->flush();
        }

        $site_repo = $this->getDoctrine()->getRepository(Site::class);

        $all_sites = $site_repo->findAll();
        return $this->render('admin/blog/add_site.html.twig', [
            'site_form' => $form->createView(),
            'sites'=> $all_sites
        ]);
    }

    /**
     * Edit a site
     *
     * @Route("/sites/{site_id}", methods={"GET", "POST"}, name="admin_edit_site")
     * @param $site_id
     * @param Request $request
     * @return Response
     */
    public function editSiteAction($site_id ,Request $request): Response
    {
        $site_repo = $this->getDoctrine()->getRepository(Site::class);
        $site = $site_repo->find($site_id);

        $employees = $this->getDoctrine()->getRepository(Employee::class)->findAll();

        $form = $this->createForm(SiteType::class, $site, ['employees' => $employees]);

        if ($request->isMethod('POST') && $form->submit($request->request->get('site'))->isValid()) {
            $this->get('doctrine')->getEntityManager()->persist($site);
            $this->get('doctrine')->getEntityManager()->flush();

            return $this->redirectToRoute('admin_add_site');
        }

        return $this->render('admin/blog/edit_site.html.twig', [
            'site'=> $site,
            'form' => $form->createView()
        ]);
    }

    /**
     * Add a site
     *
     * @Route("/daily_report", methods={"GET"}, name="admin_daily_report")
     *
     */
    public function dailyReportAction(Request $request): Response
    {
        $daily_report_repo = $this->getDoctrine()->getRepository(DailyReport::class);
        $reports = $daily_report_repo->findAll();


        return $this->render('admin/blog/daily_report.html.twig', [
            'reports'=> $reports
        ]);
    }

    /**
     * Get filtered tickets for export
     *
     * @Route("/tickets/export", methods={"GET"}, name="admin_export_tickets")
     */
    public function exportTicketsAction(Request $request): Response
    {
        $ticket_repo = $this->getDoctrine()->getRepository(Ticket::class);

        // Get filter parameters
        $year = $request->query->get('year');
        $month = $request->query->get('month');
        $siteId = $request->query->get('site');
        $employeeId = $request->query->get('employee');

        // Create query builder with filters
        $qb = $ticket_repo->createQueryBuilder('t')
            ->join('t.site', 's')
            ->where('s.is_active = 1');

        if ($year) {
            $qb->andWhere('SUBSTRING(t.date, 1, 4) = :year')
               ->setParameter('year', $year);
        }

        if ($month) {
            $qb->andWhere('SUBSTRING(t.date, 6, 2) = :month')
               ->setParameter('month', $month);
        }

        if ($siteId) {
            $qb->andWhere('t.site = :site')
               ->setParameter('site', $siteId);
        }

        if ($employeeId) {
            $qb->andWhere('t.employee = :employee')
               ->setParameter('employee', $employeeId);
        }

        $qb->orderBy('t.id', 'DESC');

        $tickets = $qb->getQuery()->getResult();

        // Create CSV content
        $csvContent = "ID,Obra,Empleado,Fecha,Máquina,Material,Nº Viajes,Toneladas,Portes,Horas,Horas Martillo,Horas Cazo,Proveedor,Litros,Comentarios,Firmado\n";
        
        foreach ($tickets as $ticket) {
            $material = '';
            $numTravels = '';
            $tons = '';
            $portages = '';
            $hours = '';
            $hammerHours = '';
            $spoonHours = '';
            $provider = '';
            $liters = '';
            $comments = '';
            $signed = 'No';

            // Get common fields
            $id = $ticket->getId();
            $site = $ticket->getSite()->getName();
            $employee = $ticket->getEmployee()->getName();
            $date = $ticket->getDate()->format('Y-m-d');
            $machine = $ticket->getMachine()->getName();

            // Get specific fields based on ticket type
            if ($ticket instanceof \App\Entity\Ticket) {
                $material = $ticket->getMaterial() ? $ticket->getMaterial()->getName() : '';
                $numTravels = $ticket->getNumTravels();
                $tons = $ticket->getTons();
                $portages = $ticket->getPortages();
                $hours = $ticket->getHours();
                $hammerHours = $ticket->getHammerHours();
                $spoonHours = $ticket->getSpoonHours();
                $provider = $ticket->getProvider();
                $liters = $ticket->getLiters();
                $comments = $ticket->getComments();
                $signed = $ticket->isProviderSigned() ? 'Sí' : 'No';
            } elseif ($ticket instanceof \App\Entity\MachineTicket) {
                $hours = $ticket->getHours();
                $hammerHours = $ticket->getHammerHours();
                $spoonHours = $ticket->getSpoonHours();
                $comments = $ticket->getComments();
            } elseif ($ticket instanceof \App\Entity\TruckPortTicket) {
                $numTravels = $ticket->getNumTravels();
                $tons = $ticket->getTons();
                $portages = $ticket->getPortages();
                $provider = $ticket->getProvider();
                $liters = $ticket->getLiters();
                $comments = $ticket->getComments();
                $signed = $ticket->isProviderSigned() ? 'Sí' : 'No';
            }

            $row = [
                $id,
                $site,
                $employee,
                $date,
                $machine,
                $material,
                $numTravels,
                $tons,
                $portages,
                $hours,
                $hammerHours,
                $spoonHours,
                $provider,
                $liters,
                $comments,
                $signed
            ];

            // Escape fields that contain commas or quotes
            $row = array_map(function($field) {
                if (is_string($field) && (strpos($field, ',') !== false || strpos($field, '"') !== false)) {
                    return '"' . str_replace('"', '""', $field) . '"';
                }
                return $field;
            }, $row);

            $csvContent .= implode(',', $row) . "\n";
        }

        // Create response
        $response = new Response($csvContent);
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="tickets.csv"');

        return $response;
    }

    /**
     * Simple ticket form for quick entry
     *
     * @Route("/simple-ticket", methods={"GET", "POST"}, name="admin_simple_ticket")
     */
    public function simpleTicketAction(Request $request): Response
    {
        $site_repo = $this->getDoctrine()->getRepository(Site::class);
        $machine_repo = $this->getDoctrine()->getRepository(Machine::class);
        $employee_repo = $this->getDoctrine()->getRepository(Employee::class);

        if ($request->isMethod('POST')) {
            $data = $request->request->get('simple_ticket');
            
            if ($data) {
                $employee = $employee_repo->find($data['employee']);
                $machine = $machine_repo->find($data['machine']);
                
                if (!$employee || !$machine) {
                    $this->addFlash('error', 'Empleado o máquina no encontrado.');
                    return $this->redirectToRoute('admin_simple_ticket');
                }
                
                // Process multiple works
                $works = $data['works'] ?? [];
                $successCount = 0;
                
                foreach ($works as $work) {
                    if (empty($work['site']) || empty($work['hours'])) {
                        continue; // Skip empty entries
                    }
                    
                    $site = $site_repo->find($work['site']);
                    if (!$site) {
                        continue;
                    }
                    
                    // Create ticket based on machine type
                    if ($machine->isTruck()) {
                        $ticket = new \App\Entity\TruckPortTicket();
                        $ticket->setNumTravels($work['hours']);
                        $ticket->setTons($work['tons'] ?? 0);
                        $ticket->setPortages($work['portages'] ?? 0);
                        $ticket->setProvider($work['provider'] ?? '');
                        $ticket->setLiters($work['liters'] ?? 0);
                    } else {
                        $ticket = new \App\Entity\MachineTicket();
                        $ticket->setHours($work['hours']);
                        $ticket->setHammerHours($work['hammer_hours'] ?? 0);
                        $ticket->setSpoonHours($work['spoon_hours'] ?? 0);
                    }
                    
                    $ticket->setEmployee($employee);
                    $ticket->setMachine($machine);
                    $ticket->setSite($site);
                    $ticket->setDate(new \DateTime($data['date']));
                    $ticket->setComments($work['comments'] ?? '');
                    
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($ticket);
                    $successCount++;
                }
                
                if ($successCount > 0) {
                    $em->flush();
                    $this->addFlash('success', "Se crearon {$successCount} tickets correctamente.");
                } else {
                    $this->addFlash('error', 'No se pudo crear ningún ticket.');
                }
                
                return $this->redirectToRoute('admin_simple_ticket');
            }
        }

        $sites = $site_repo->findBy(['is_active' => true]);
        $machines = $machine_repo->findAll();
        $employees = $employee_repo->findAll();

        return $this->render('admin/blog/simple_ticket.html.twig', [
            'sites' => $sites,
            'machines' => $machines,
            'employees' => $employees
        ]);
    }

    protected function getCleanMimeType($filename)
    {
        $name = explode('.', $filename);
        $ext = mb_strtolower(end($name));
        switch ($ext) {
            case 'pdf':
                $mime_type = 'application/pdf';
                break;
            case 'jpg':
            case 'jpeg':
                $mime_type = 'image/jpeg';
                break;
            case 'png':
                $mime_type = 'image/png';
                break;
            case 'bmp':
                $mime_type = 'image/bmp';
                break;
            case 'doc':
                $mime_type = 'application/msword';
                break;
            case 'docx':
                $mime_type = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
                break;
            default:
                $mime_type = null;
        }

        return $mime_type;
    }
}
