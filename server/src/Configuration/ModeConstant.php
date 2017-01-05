<?php

namespace ChauffeMarcel\Configuration;

abstract class ModeConstant
{
    const NOT_FORCED = 'not_forced';
    const FORCED_ON = 'forced_on';
    const FORCED_OFF = 'forced_off';

    const MODES = [
        self::NOT_FORCED,
        self::FORCED_ON,
        self::FORCED_OFF,
    ];
}
