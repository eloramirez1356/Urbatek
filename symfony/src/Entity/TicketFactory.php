<?php

namespace App\Entity;

class TicketFactory
{
    public function createFromRequest(CreateTicketRequest $request)
    {
        switch ($request->getType()) {
            case Ticket::TYPE_TRUCK_HOURS:
                return $this->createTruckHoursTicket($request);
                break;
            case Ticket::TYPE_TRUCK_SUPPLY:
                return $this->createTruckMaterialTicket($request);
                break;
            case Ticket::TYPE_TRUCK_PORT:
                return $this->createTruckPortTicket($request);
                break;
            case Ticket::TYPE_MACHINE:
                return $this->createMachineTicket($request);
                break;
        }
    }

    private function createTruckHoursTicket(CreateTicketRequest $request)
    {
        return new TruckHoursTicket(
            $request->getDate(),
            $request->getSite(),
            $request->getEmployee(),
            $request->getMachine(),
            $request->getHours()
        );
    }

    private function createTruckMaterialTicket(CreateTicketRequest $request)
    {
        return new TruckMaterialTicket(
            $request->getDate(),
            $request->getSite(),
            $request->getEmployee(),
            $request->getMachine(),
            $request->getNumTravels(),
            $request->getHours(),
            $request->getComments(),
            $request->getMaterial(),
            $request->getTons(),
            $request->getProvider()
        );
    }

    private function createTruckPortTicket(CreateTicketRequest $request)
    {
        return new TruckPortTicket(
            $request->getDate(),
            $request->getSite(),
            $request->getEmployee(),
            $request->getMachine(),
            $request->getPortages(),
            $request->getHours(),
            $request->getComments()
        );
    }

    private function createMachineTicket(CreateTicketRequest $request)
    {
        return new MachineTicket(
            $request->getDate(),
            $request->getSite(),
            $request->getEmployee(),
            $request->getMachine(),
            $request->getHours(),
            $request->getHammerHours(),
            $request->getComments()
        );
    }
}
