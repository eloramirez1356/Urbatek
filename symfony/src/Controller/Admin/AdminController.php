<?php

namespace App\Controller\Admin;

use App\Entity\Document;
use App\Entity\Employee;
use App\Entity\Machine;
use App\Entity\Material;
use App\Entity\Site;
use App\Entity\Ticket;
use App\Form\EmployeeType;
use App\Form\MachineType;
use App\Form\MaterialType;
use App\Form\SiteType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
            $machine->setKms($form->getData()['kms']);
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
            $material = new Material();
            $material->setName($data['name']);
            $material->setPrice($data['price']);
            $this->get('doctrine')->getEntityManager()->persist($material);
            $this->get('doctrine')->getEntityManager()->flush();
        }

        return $this->render('admin/blog/add_material.html.twig', [
            'materials' => $all_materials,
            'material_form' => $form->createView()
        ]);
    }

    /**
     * Add a employee
     *
     * @Route("/employees", methods={"GET"}, name="admin_add_employee")
     *
     */
    public function editEmployeeAction(Request $request): Response
    {
        $form = $this->createForm(EmployeeType::class);
        $employee_repo = $this->getDoctrine()->getRepository(Employee::class);

        $all_employees= $employee_repo->findAll();

        return $this->render('admin/blog/add_employee.html.twig', [
            'employee_form' => $form->createView(),
            'employees' => $all_employees
        ]);
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

        $all_tickets= $ticket_repo->findAll();

        $tickets_by_site = [];
        foreach ($all_tickets as $ticket) {
            $tickets_by_site[$ticket->getSite()->getName()][] = $ticket;
        }


        return $this->render('admin/blog/add_ticket.html.twig', [
            'tickets' => $tickets_by_site,
            'sites' => array_keys($tickets_by_site)
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
     * Add a site
     *
     * @Route("/site/{site_id}", methods={"GET", "POST"}, name="edit_site")
     * @param $site_id
     * @param Request $request
     * @return Response
     */
    public function editSiteAction($site_id ,Request $request): Response
    {
        $site_repo = $this->getDoctrine()->getRepository(Site::class);
        $site = $site_repo->find($site_id);

        return $this->render('admin/blog/add_site.html.twig', [
            'site'=> $site
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
