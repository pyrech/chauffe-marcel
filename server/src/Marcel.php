<?php

namespace ChauffeMarcel;

use ChauffeMarcel\Api\Model\Configuration;
use ChauffeMarcel\Configuration\HeatingStrategy;
use ChauffeMarcel\Remote\Particle;

class Marcel
{
    private $particle;
    private $heatingStrategy;

    public function __construct(Particle $particle, HeatingStrategy $heatingStrategy)
    {
        $this->particle = $particle;
        $this->heatingStrategy = $heatingStrategy;
    }

    public function chauffe(Configuration $configuration)
    {
        $isHeating = $this->particle->isHeating();
        $shouldHeat = $this->heatingStrategy->shouldHeat($configuration);

        if ($isHeating === $shouldHeat) {
            return;
        }

        if ($shouldHeat) {
            $this->particle->enable();
        } else {
            $this->particle->disable();
        }
    }
}
