<?php

namespace App;

use App\Api\Model\Configuration;
use App\Configuration\HeatingStrategy;
use App\Remote\Particle;

class Marcel
{
    private Particle $particle;
    private HeatingStrategy $heatingStrategy;

    public function __construct(Particle $particle, HeatingStrategy $heatingStrategy)
    {
        $this->particle = $particle;
        $this->heatingStrategy = $heatingStrategy;
    }

    public function chauffe(Configuration $configuration): void
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
