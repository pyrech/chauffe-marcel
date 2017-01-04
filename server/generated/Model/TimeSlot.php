<?php

namespace ChauffeMarcel\Api\Model;

class TimeSlot
{
    /**
     * @var string
     */
    protected $uuid;
    /**
     * @var string
     */
    protected $start;
    /**
     * @var string
     */
    protected $end;
    /**
     * @var int
     */
    protected $dayOfWeek;
    /**
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }
    /**
     * @param string $uuid
     *
     * @return self
     */
    public function setUuid($uuid = null)
    {
        $this->uuid = $uuid;
        return $this;
    }
    /**
     * @return string
     */
    public function getStart()
    {
        return $this->start;
    }
    /**
     * @param string $start
     *
     * @return self
     */
    public function setStart($start = null)
    {
        $this->start = $start;
        return $this;
    }
    /**
     * @return string
     */
    public function getEnd()
    {
        return $this->end;
    }
    /**
     * @param string $end
     *
     * @return self
     */
    public function setEnd($end = null)
    {
        $this->end = $end;
        return $this;
    }
    /**
     * @return int
     */
    public function getDayOfWeek()
    {
        return $this->dayOfWeek;
    }
    /**
     * @param int $dayOfWeek
     *
     * @return self
     */
    public function setDayOfWeek($dayOfWeek = null)
    {
        $this->dayOfWeek = $dayOfWeek;
        return $this;
    }
}