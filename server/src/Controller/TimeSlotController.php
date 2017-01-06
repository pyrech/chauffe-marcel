<?php

namespace ChauffeMarcel\Controller;

use ChauffeMarcel\Api\Model\TimeSlot;
use Ramsey\Uuid\Uuid;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/api/time-slots")
 */
class TimeSlotController extends ApiController
{
    /**
     * @Route("/", name="time_slot_list", methods="GET")
     */
    public function listAction()
    {
        $configuration = $this->getConfiguration();

        return $this->renderData($configuration->getTimeSlots());
    }

    /**
     * @Route("/", name="time_slot_create", methods="POST")
     */
    public function createAction(Request $request)
    {
        $timeSlot = $this->receiveData($request, TimeSlot::class);
        $timeSlot->setUuid(Uuid::uuid4());

        $configuration = $this->getConfiguration();

        $timeSlots = $configuration->getTimeSlots();
        $timeSlots[] = $timeSlot;
        $configuration->setTimeSlots($timeSlots);

        $this->updateConfiguration($configuration);

        return $this->renderData(true);
    }

    /**
     * @Route("/{uuid}", name="time_slot_update", methods="PUT")
     */
    public function updateAction(Request $request, string $uuid)
    {
        $timeSlot = $this->receiveData($request, TimeSlot::class);

        $configuration = $this->getConfiguration();

        $timeSlots = $configuration->getTimeSlots();
        $timeSlotsToUpdate = array_filter($timeSlots, function(TimeSlot $timeSlot) use ($uuid) {
            return $timeSlot->getUuid() === $uuid;
        });

        $timeSlotsToUpdate[0]->setStart($timeSlot->getStart());
        $timeSlotsToUpdate[0]->setEnd($timeSlot->getEnd());
        $timeSlotsToUpdate[0]->setDayOfWeek($timeSlot->getDayOfWeek());

        $this->updateConfiguration($configuration);

        return $this->renderData(true);
    }

    /**
     * @Route("/{uuid}", name="time_slot_delete", methods="DELETE")
     */
    public function removeAction(string $uuid)
    {
        $configuration = $this->getConfiguration();

        $timeSlots = $configuration->getTimeSlots();
        $newTimeSlots = array_filter($timeSlots, function(TimeSlot $timeSlot) use ($uuid) {
            return $timeSlot->getUuid() !== $uuid;
        });
        $configuration->setTimeSlots($newTimeSlots);

        $this->updateConfiguration($configuration);

        return $this->renderData(true);
    }
}
