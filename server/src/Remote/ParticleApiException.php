<?php

namespace ChauffeMarcel\Remote;

class ParticleApiException extends \Exception
{
    private $url;

    public function __construct(string $url)
    {
        $this->url = $url;

        parent::__construct('An error happened when calling Particle\'s API');
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}
