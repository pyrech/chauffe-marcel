<?php

namespace App\Configuration;

use App\Api\Model\Configuration;

class HeatingStrategy
{
    public function shouldHeat(Configuration $configuration): bool
    {
        if (ModeConstant::NOT_FORCED === $configuration->getMode()) {
            return $this->makeDecisionForTimeSlots($configuration->getTimeSlots());
        }

        return $this->makeDecisionForForcedMode($configuration->getMode());
    }

    private function makeDecisionForTimeSlots(array $timeSlots): bool
    {
        $now = new \DateTime();

        $time = $now->format('H:i');
        $dayOfTheWeek = (int) $now->format('w');

        if (0 === $dayOfTheWeek) {
            $dayOfTheWeek = 7;
        }

        foreach ($timeSlots as $timeSlot) {
            if ($timeSlot->getDayOfWeek() !== $dayOfTheWeek) {
                continue;
            }

            if ($time >= $timeSlot->getStart() && $time <= $timeSlot->getEnd()) {
                return true;
            }
        }

        return false;
    }

    private function makeDecisionForForcedMode(string $mode): bool
    {
        if (ModeConstant::FORCED_ON === $mode) {
            return true;
        }

        return false;
    }
}
