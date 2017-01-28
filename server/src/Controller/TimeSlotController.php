<?php

namespace ChauffeMarcel\Controller;

use ChauffeMarcel\Api\Model\Configuration;
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

        return $this->renderData($timeSlot->getUuid());
    }

    /**
     * @Route("/{uuid}", name="time_slot_update", methods="PUT")
     */
    public function updateAction(Request $request, string $uuid)
    {
        $configuration = $this->getConfiguration();

        $timeSlot = $this->receiveData($request, TimeSlot::class);

        $timeSlotToUpdate = $this->getTimeSlot($configuration, $uuid);
        $timeSlotToUpdate->setStart($timeSlot->getStart());
        $timeSlotToUpdate->setEnd($timeSlot->getEnd());
        $timeSlotToUpdate->setDayOfWeek($timeSlot->getDayOfWeek());

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

    private function getTimeSlot(Configuration $configuration, $uuid): TimeSlot
    {
        $timeSlots = $configuration->getTimeSlots();
        $timeSlots = array_filter($timeSlots, function(TimeSlot $timeSlot) use ($uuid) {
            return $timeSlot->getUuid() === $uuid;
        });

        if (empty($timeSlots)) {
            throw $this->createNotFoundException();
        }

        return array_shift($timeSlots);
    }
}
