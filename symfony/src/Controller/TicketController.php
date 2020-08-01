<?php

namespace App\Controller;

use App\Entity\CreateTicketRequest;
use App\Entity\DailyReport;
use App\Entity\Document;
use App\Entity\TicketFactory;
use App\Entity\User;
use App\Form\DailyReportType;
use App\Form\TicketType;
use App\Library\ReflectionObjectSetter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
                    $this->submitTicket($user, $type, $form->getData());
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
        $uploaded_file = $data['file'];
        unset($data['file']);
        $data['employee'] = $data['employee'] ?? $user->getEmployee();
        $data['type'] = $type;

        $setter = new ReflectionObjectSetter();
        $request = new CreateTicketRequest();

        $setter->fillObject($request, $data);
        $ticket_factory = new TicketFactory();
        $ticket = $ticket_factory->createFromRequest($request);

        if ($uploaded_file) {
            $document_name = $uploaded_file->getClientOriginalName();
            $document_path = $this->getParameter('kernel.root_dir') . '/../uploads/documents/ticket/' . $user->getId();
            $document = new Document($document_name, $document_path);
            $document->setFile($uploaded_file);
            $document->upload($document_path);

            $ticket->setDocument($document);
            $this->get('doctrine')->getEntityManager()->persist($document);
        }

        $this->get('doctrine')->getEntityManager()->persist($ticket);
        $this->get('doctrine')->getEntityManager()->flush();
    }


    /**
     * @Route("/api/add_ticket", defaults={"page": "1", "_format"="html"}, methods={"POST", "GET"}, name="api_add_ticket")
     */
    public function apiAddTicketAction(Request $request)
    {
        $user = $this->getUser();
        return new Response($user->getUsername());
    }
}
