<?php

namespace App\Controller;

use App\Entity\CreateTicketRequest;
use App\Entity\DailyReport;
use App\Entity\Document;
use App\Entity\Machine;
use App\Entity\Material;
use App\Entity\Site;
use App\Entity\Ticket;
use App\Entity\TicketFactory;
use App\Entity\User;
use App\Form\DailyReportType;
use App\Form\TicketType;
use App\Library\ReflectionObjectSetter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TicketController extends AbstractController
{
    /**
     * @Route("/ticket/{type}", defaults={"page": "1", "_format"="html", "type": null }, methods={"GET", "POST"}, name="add_ticket")
     *
     * NOTE: For standard formats, Symfony will also automatically choose the best
     * Content-Type header for the response.
     * See https://symfony.com/doc/current/quick_tour/the_controller.html#using-formats
     */
    public function index(Request $request, $type = null): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');

        }

        $form = null;
        if ($type !== null) {
            $options = ['user' => $user, 'type' => $type];
            $form = $this->createForm(TicketType::class, null, $options);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                try {
                    $data = $form->getData();
                    $data['file_name'] = $data['file'] ? $data['file']->getClientOriginalName() : null ;
                    $this->submitTicket($user, $type, $data);
                    $this->addFlash('success', 'Albarán subido correctamente');

                } catch (\Exception $e) {
                    $this->addFlash('warning', $e->getMessage());
                }
            }
        }

        return $this->render('ticket/add_ticket.html.twig', [
            'form' => $form ? $form->createView() : null,
        ]);
    }

    /**
     * @Route("/daily_report", defaults={"page": "1", "_format"="html"}, methods={"GET", "POST"}, name="add_daily_report")
     *
     * NOTE: For standard formats, Symfony will also automatically choose the best
     * Content-Type header for the response.
     * See https://symfony.com/doc/current/quick_tour/the_controller.html#using-formats
     */
    public function dailyReportAction(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $options = ['user' => $user];
        $form = $this->createForm(DailyReportType::class, null, $options);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $employee = $data['employee'] ?? $user->getEmployee();

            $report = new DailyReport(
                $data['date'],
                $employee,
                $data['hours']
            );

            $this->get('doctrine')->getEntityManager()->persist($report);
            $this->get('doctrine')->getEntityManager()->flush();

            $this->addFlash('success', 'Reporte diario guardado correctamente');
        }

        return $this->render('ticket/daily_report.html.twig', [
            'form' => $form ? $form->createView() : null,
        ]);
    }

    private function submitTicket($user, $type, $data)
    {
        $uploaded_file = $data['file'] ?? null;
        $file_name = $data['file_name'] ?? null;
        unset($data['file']);
        unset($data['file_name']);

        $data['employee'] = $data['employee'] ?? $user->getEmployee();
        $data['type'] = $type;

        $setter = new ReflectionObjectSetter();
        $request = new CreateTicketRequest();

        $setter->fillObject($request, $data);
        $ticket_factory = new TicketFactory();
        $ticket = $ticket_factory->createFromRequest($request);

        if ($uploaded_file) {
            $document_path = $this->getParameter('kernel.root_dir') . '/../uploads/documents/ticket/' . $user->getId();
            $document = new Document($file_name, $document_path);
            $document->setFile($uploaded_file);
            $document->upload($document_path);

            $ticket->setDocument($document);
            $this->get('doctrine')->getEntityManager()->persist($document);
        }

        $this->get('doctrine')->getEntityManager()->persist($ticket);
        $this->get('doctrine')->getEntityManager()->flush();

        return $ticket;
    }


    /**
     * @Route("/api/add_ticket", defaults={"page": "1", "_format"="html"}, methods={"POST", "GET"}, name="api_add_ticket")
     */
    public function apiAddTicketAction(Request $request)
    {
        $doctrine = $this->getDoctrine();

        $fields = [
            'date' => 'date',
            'site' => ['entity' => Site::class],
            'machine' => ['entity' => Machine::class],
            'material' => ['entity' => Material::class],
            'provider' => '',
            'num_travels' => '',
            'tons' => '',
            'portages' => '',
            'hours' => '',
            'hammer_hours' => '',
            'liters' => '',
            'comments' => '',
            'file' => 'file',
        ];

        $post_data = [];
        foreach ($fields as $field => $type) {
            if (is_null($request->get($field))) {
                continue;
            }

            if (is_array($type) && isset($type['entity'])) {
                $value = $doctrine->getRepository($type['entity'])->find($request->get($field));

            } elseif ($type == 'date') {
                $value = new \DateTime($request->get($field));

            } elseif ($type == 'file' && $request->get('file')) {
                $base64_string = $request->get($field);
                $file_path = '/tmp/' . rand() . '.jpg';
                $temp_jpg = fopen($file_path, 'wb');
                $data = explode( ',', $base64_string );
                fwrite($temp_jpg, base64_decode($data[1]));
                fclose($temp_jpg);

                $value = new File($file_path);
                $post_data['file_name'] = $value->getFilename();

            } else {
                $value = $request->get($field);
            }

            $post_data[$field] = $value;
        }


        $user = $this->getUser();
        $ticket = $this->submitTicket($user, $request->get('type'), $post_data);

        return new JsonResponse([
            'success' => true,
            'ticket_id' => $ticket->getId()
        ]);
    }

    /**
     * @Route("/u/tickets", defaults={"page": "1", "_format"="html", "type": null }, methods={"GET"}, name="my_tickets")
     *
     * NOTE: For standard formats, Symfony will also automatically choose the best
     * Content-Type header for the response.
     * See https://symfony.com/doc/current/quick_tour/the_controller.html#using-formats
     */
    public function myTicketsAction(Request $request, $type = null): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');

        }

        $ticket_repo = $this->getDoctrine()->getRepository(Ticket::class);

	$raw_tickets = $ticket_repo->findOfEmployee($user->getEmployee());
        
        $tickets = [];
        foreach ($raw_tickets as $raw_ticket) {
            if ($raw_ticket->getDate()->format('Y') == (new \DateTime())->format('Y')) {
                $tickets[] = $raw_ticket; 
            }
        }

        return $this->render('ticket/my_tickets.html.twig', [
            'tickets' => $tickets,

        ]);
    }
}
