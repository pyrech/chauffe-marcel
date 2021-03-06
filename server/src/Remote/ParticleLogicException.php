<?php

namespace App\Remote;

use App\MarcelException;

class ParticleLogicException extends \Exception implements MarcelException
{
    private string $endpoint;
    private array $result;

    public function __construct(string $endpoint, array $result)
    {
        $this->endpoint = $endpoint;
        $this->result = $result;

        parent::__construct(sprintf(
            'An error happened with the Particle\'s logic when calling "%s"',
            $endpoint
        ));
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
