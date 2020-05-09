<?php

namespace App\Controller;

use App\Entity\Document;
use App\Entity\Ticket;
use App\Entity\User;
use App\Form\TicketType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller used to manage blog contents in the public part of the site.
 *
 * @Route("/ticket")
 *
 * @author Ryan Weaver <weaverryan@gmail.com>
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 */
class TicketController extends AbstractController
{
    /**
     * @Route("/{type}", defaults={"page": "1", "_format"="html", "type": null }, methods={"GET", "POST"}, name="add_ticket")
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
                $data = $form->getData();

                $employee = $data['employee'] ?? $user->getEmployee();

                $ticket = new Ticket(
                    $type,
                    $data['date'],
                    $data['site'],
                    $employee,
                    $data['machine'],
                    $data['hours'] ?? null,
                    $data['num_travels'] ?? null,
                    $data['material'] ?? null,
                    $data['tons'] ?? null,
                    $data['portages'] ?? null,
                    $data['provider'] ?? null,
                    $data['hammer_hours'] ?? null
                );

                $document_name = $data['file']->getClientOriginalName();
                $document_path = $this->getParameter('kernel.root_dir') . '/../uploads/documents/ticket/' . $user->getId();
                $document = new Document($document_name, $document_path);
                $document->setFile($form['file']->getData());
                $document->upload($document_path);

                $ticket->setDocument($document);

                $this->get('doctrine')->getEntityManager()->persist($document);
                $this->get('doctrine')->getEntityManager()->persist($ticket);
                $this->get('doctrine')->getEntityManager()->flush();

                $this->addFlash('success', 'Albarán subido correctamente');
            }
        }

        return $this->render('ticket/add_ticket.html.twig', [
            'form' => $form ? $form->createView() : null,
        ]);
    }
}
