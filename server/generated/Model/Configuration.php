<?php

namespace App\Api\Model;

class Configuration
{
    /**
     * @var string
     */
    protected $mode;
    /**
     * @var TimeSlot[]
     */
    protected $timeSlots;

    public function getMode(): string
    {
        return $this->mode;
    }

    public function setMode(string $mode): self
    {
        $this->mode = $mode;

        return $this;
    }

    /**
     * @return TimeSlot[]
     */
    public function getTimeSlots(): array
    {
        return $this->timeSlots;
    }

    /**
     * @param TimeSlot[] $timeSlots
     */
    public function setTimeSlots(array $timeSlots): self
    {
        $this->timeSlots = $timeSlots;

        return $this;
    }
}
