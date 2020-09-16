<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class EmployeeController extends AbstractController
{
    /**
     * @Route("/api/employee/tickets", methods={"GET"}, name="api_employee_tickets")
     *
     */
    public function myTicketsAction(): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        $user->getEmployee();

        $ticket_repo = $this->getDoctrine()->getRepository(Ticket::class);

        $employee_tickets = $ticket_repo->findOfEmployee($user->getEmployee());

        $my_tickets = [];
        foreach ($employee_tickets as $ticket) {
            $ticket_raw = $ticket->toArray();
            $ticket_raw['document'] = $ticket->getDocument()
                ? $this->generateUrl('admin_view_document', ['document_id' => $ticket->getDocument()->getId()]) : null;

            $my_tickets[] = $ticket_raw;
        }

        return new JsonResponse($my_tickets);
    }
}
