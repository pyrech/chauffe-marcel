<?php

namespace ChauffeMarcel\Api\Normalizer;

class NormalizerFactory
{
    public static function create()
    {
        $normalizers = array();
        $normalizers[] = new \Joli\Jane\Runtime\Normalizer\ReferenceNormalizer();
        $normalizers[] = new \Joli\Jane\Runtime\Normalizer\ArrayDenormalizer();
        $normalizers[] = new ConfigurationNormalizer();
        $normalizers[] = new TimeSlotNormalizer();
        return $normalizers;
    }
}