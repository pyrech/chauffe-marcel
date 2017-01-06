<?php

namespace ChauffeMarcel\Configuration;

use ChauffeMarcel\Api\Model\Configuration;

class HeatingStrategy
{
    public function shouldHeat(Configuration $configuration): bool
    {
        if ($configuration->getMode() === ModeConstant::NOT_FORCED) {
            return $this->makeDecisionForTimeSlots($configuration->getTimeSlots());
        }

        return $this->makeDecisionForForcedMode($configuration->getMode());
    }

    private function makeDecisionForTimeSlots(array $timeSlots): bool
    {
        $now = new \DateTime();

        $time = $now->format('H:i');
        $dayOfTheWeek = (int) $now->format('w');

        if ($dayOfTheWeek === 0) {
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
        if ($mode === ModeConstant::FORCED_ON) {
            return true;
        }

        return false;
    }
}
