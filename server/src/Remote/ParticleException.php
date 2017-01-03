<?php

namespace ChauffeMarcel\Remote;

class ParticleException extends \Exception
{
    private $action;
    private $result;

    public function __construct(string $action, \stdClass $result)
    {
        $this->action = $action;
        $this->result = $result;

        parent::__construct('An error happened when calling particle\'s API');
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function getResult(): \stdClass
    {
        return $this->result;
    }
}
