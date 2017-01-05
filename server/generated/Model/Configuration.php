<?php

namespace ChauffeMarcel\Api\Model;

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
    /**
     * @return string
     */
    public function getMode()
    {
        return $this->mode;
    }
    /**
     * @param string $mode
     *
     * @return self
     */
    public function setMode($mode = null)
    {
        $this->mode = $mode;
        return $this;
    }
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