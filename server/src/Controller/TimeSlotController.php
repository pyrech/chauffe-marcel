<?php

namespace App\Controller;

use App\Api\Model\Configuration;
use App\Api\Model\TimeSlot;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/time-slots")
 */
class TimeSlotController extends ApiController
{
    /**
     * @Route("/", name="time_slot_list", methods="GET")
     */
    public function listAction(): Response
    {
        $configuration = $this->getConfiguration();

        return $this->renderData($configuration->getTimeSlots());
    }

    /**
     * @Route("/", name="time_slot_create", methods="POST")
     */
    public function createAction(Request $request): Response
    {
        $timeSlot = $this->receiveData($request, TimeSlot::class);

        $this->assertTimeSlotIsValid($timeSlot);
        $timeSlot->setUuid(uuid_create(UUID_TYPE_RANDOM));

        $configuration = $this->getConfiguration();

        $timeSlots = $configuration->getTimeSlots();
        $timeSlots[] = $timeSlot;
        $configuration->setTimeSlots($timeSlots);

        $this->updateConfiguration($configuration);

        return $this->renderData($timeSlot->getUuid());
    }

    /**
     * @Route("/{uuid}", name="time_slot_get", methods="GET")
     */
    public function getAction(string $uuid): Response
    {
        $configuration = $this->getConfiguration();

        $timeSlot = $this->getTimeSlot($configuration, $uuid);

        return $this->renderData($timeSlot);
    }

    /**
     * @Route("/{uuid}", name="time_slot_update", methods="PUT")
     */
    public function updateAction(Request $request, string $uuid): Response
    {
        $timeSlot = $this->receiveData($request, TimeSlot::class);

        $this->assertTimeSlotIsValid($timeSlot);

        $configuration = $this->getConfiguration();

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
    public function removeAction(string $uuid): Response
    {
        $configuration = $this->getConfiguration();

        $timeSlots = $configuration->getTimeSlots();
        $newTimeSlots = array_filter($timeSlots, function (TimeSlot $timeSlot) use ($uuid) {
            return $timeSlot->getUuid() !== $uuid;
        });
        $configuration->setTimeSlots($newTimeSlots);

        $this->updateConfiguration($configuration);

        return $this->renderData(true);
    }

    private function getTimeSlot(Configuration $configuration, $uuid): TimeSlot
    {
        $timeSlots = $configuration->getTimeSlots();
        $timeSlots = array_filter($timeSlots, function (TimeSlot $timeSlot) use ($uuid) {
            return $timeSlot->getUuid() === $uuid;
        });

        if (empty($timeSlots)) {
            throw $this->createNotFoundException();
        }

        return array_shift($timeSlots);
    }

    private function assertTimeSlotIsValid(TimeSlot $timeSlot): void
    {
        if (!$this->validateTime($timeSlot->getStart())) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Start time should have format like "15:30"');
        }

        if (!$this->validateTime($timeSlot->getEnd())) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'End time should have format like "15:30"');
        }

        if (!is_int($timeSlot->getDayOfWeek()) || $timeSlot->getDayOfWeek() < 1 || $timeSlot->getDayOfWeek() > 7) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Day of week should be between 1 and 7');
        }
    }

    private function validateTime(string $time): bool
    {
        return preg_match('/^[0-2]\d:[0-5]\d$/', $time);
    }
}
