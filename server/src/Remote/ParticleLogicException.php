<?php

namespace ChauffeMarcel\Remote;

class ParticleLogicException extends \Exception
{
    private $endpoint;
    private $result;

    public function __construct(string $endpoint, array $result)
    {
        $this->endpoint = $endpoint;
        $this->result = $result;

        parent::__construct('An error happened with the particle\'s logic');
    }

    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    public function getResult(): array
    {
        return $this->result;
    }
}
