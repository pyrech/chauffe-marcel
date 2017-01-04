<?php

namespace ChauffeMarcel\Api\Model;

class Configuration
{
    /**
     * @var TimeSlot[]
     */
    protected $timeSlots;
    /**
     * @return TimeSlot[]
     */
    public function getTimeSlots()
    {
        return $this->timeSlots;
    }
    /**
     * @param TimeSlot[] $timeSlots
     *
     * @return self
     */
    public function setTimeSlots(array $timeSlots = null)
    {
        $this->timeSlots = $timeSlots;
        return $this;
    }
}