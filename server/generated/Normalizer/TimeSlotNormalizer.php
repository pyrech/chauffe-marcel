<?php

namespace ChauffeMarcel\Api\Normalizer;

use Joli\Jane\Runtime\Reference;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\SerializerAwareNormalizer;
class TimeSlotNormalizer extends SerializerAwareNormalizer implements DenormalizerInterface, NormalizerInterface
{
    public function supportsDenormalization($data, $type, $format = null)
    {
        if ($type !== 'ChauffeMarcel\\Api\\Model\\TimeSlot') {
            return false;
        }
        return true;
    }
    public function supportsNormalization($data, $format = null)
    {
        if ($data instanceof \ChauffeMarcel\Api\Model\TimeSlot) {
            return true;
        }
        return false;
    }
    public function denormalize($data, $class, $format = null, array $context = array())
    {
        if (isset($data->{'$ref'})) {
            return new Reference($data->{'$ref'}, $context['rootSchema'] ?: null);
        }
        $object = new \ChauffeMarcel\Api\Model\TimeSlot();
        if (!isset($context['rootSchema'])) {
            $context['rootSchema'] = $object;
        }
        if (property_exists($data, 'uuid')) {
            $object->setUuid($data->{'uuid'});
        }
        if (property_exists($data, 'start')) {
            $object->setStart($data->{'start'});
        }
        if (property_exists($data, 'end')) {
            $object->setEnd($data->{'end'});
        }
        if (property_exists($data, 'dayOfWeek')) {
            $object->setDayOfWeek($data->{'dayOfWeek'});
        }
        return $object;
    }
    public function normalize($object, $format = null, array $context = array())
    {
        $data = new \stdClass();
        if (null !== $object->getUuid()) {
            $data->{'uuid'} = $object->getUuid();
        }
        if (null !== $object->getStart()) {
            $data->{'start'} = $object->getStart();
        }
        if (null !== $object->getEnd()) {
            $data->{'end'} = $object->getEnd();
        }
        if (null !== $object->getDayOfWeek()) {
            $data->{'dayOfWeek'} = $object->getDayOfWeek();
        }
        return $data;
    }
}