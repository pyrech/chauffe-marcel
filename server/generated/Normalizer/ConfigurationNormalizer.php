<?php

namespace ChauffeMarcel\Api\Normalizer;

use Joli\Jane\Runtime\Reference;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\SerializerAwareNormalizer;
class ConfigurationNormalizer extends SerializerAwareNormalizer implements DenormalizerInterface, NormalizerInterface
{
    public function supportsDenormalization($data, $type, $format = null)
    {
        if ($type !== 'ChauffeMarcel\\Api\\Model\\Configuration') {
            return false;
        }
        return true;
    }
    public function supportsNormalization($data, $format = null)
    {
        if ($data instanceof \ChauffeMarcel\Api\Model\Configuration) {
            return true;
        }
        return false;
    }
    public function denormalize($data, $class, $format = null, array $context = array())
    {
        if (isset($data->{'$ref'})) {
            return new Reference($data->{'$ref'}, $context['rootSchema'] ?: null);
        }
        $object = new \ChauffeMarcel\Api\Model\Configuration();
        if (!isset($context['rootSchema'])) {
            $context['rootSchema'] = $object;
        }
        if (property_exists($data, 'timeSlots')) {
            $values = array();
            foreach ($data->{'timeSlots'} as $value) {
                $values[] = $this->serializer->deserialize($value, 'ChauffeMarcel\\Api\\Model\\TimeSlot', 'raw', $context);
            }
            $object->setTimeSlots($values);
        }
        return $object;
    }
    public function normalize($object, $format = null, array $context = array())
    {
        $data = new \stdClass();
        if (null !== $object->getTimeSlots()) {
            $values = array();
            foreach ($object->getTimeSlots() as $value) {
                $values[] = $this->serializer->serialize($value, 'raw', $context);
            }
            $data->{'timeSlots'} = $values;
        }
        return $data;
    }
}