<?php

namespace ChauffeMarcel\Remote;

use ChauffeMarcel\MarcelException;

class ParticleApiException extends \Exception implements MarcelException
{
    private $endpoint;

    public function __construct(string $endpoint)
    {
        $this->endpoint = $endpoint;

        parent::__construct(sprintf(
            'An error happened with the Particle\'s API when calling "%s"',
            $endpoint
        ));
    }

    public function getEndpoint(): string
    {
        return $this->endpoint;
    }
}
