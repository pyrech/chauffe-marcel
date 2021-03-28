<?php

namespace App\Api\Normalizer;

use App\Api\Runtime\Normalizer\CheckArray;
use Jane\JsonSchemaRuntime\Reference;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ConfigurationNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;

    public function supportsDenormalization($data, $type, $format = null)
    {
        return 'App\\Api\\Model\\Configuration' === $type;
    }

    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof \App\Api\Model\Configuration;
    }

    public function denormalize($data, $class, $format = null, array $context = [])
    {
        if (isset($data['$ref'])) {
            return new Reference($data['$ref'], $context['document-origin']);
        }
        if (isset($data['$recursiveRef'])) {
            return new Reference($data['$recursiveRef'], $context['document-origin']);
        }
        $object = new \App\Api\Model\Configuration();
        if (null === $data || false === \is_array($data)) {
            return $object;
        }
        if (\array_key_exists('mode', $data)) {
            $object->setMode($data['mode']);
        }
        if (\array_key_exists('timeSlots', $data)) {
            $values = [];
            foreach ($data['timeSlots'] as $value) {
                $values[] = $this->denormalizer->denormalize($value, 'App\\Api\\Model\\TimeSlot', 'json', $context);
            }
            $object->setTimeSlots($values);
        }

        return $object;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $data = [];
        if (null !== $object->getMode()) {
            $data['mode'] = $object->getMode();
        }
        if (null !== $object->getTimeSlots()) {
            $values = [];
            foreach ($object->getTimeSlots() as $value) {
                $values[] = $this->normalizer->normalize($value, 'json', $context);
            }
            $data['timeSlots'] = $values;
        }

        return $data;
    }
}
