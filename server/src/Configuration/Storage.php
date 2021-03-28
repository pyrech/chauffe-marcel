<?php

namespace App\Configuration;

use App\Api\Model\Configuration;
use Symfony\Component\Serializer\SerializerInterface;

class Storage
{
    private string $path;
    private SerializerInterface $serializer;

    public function __construct(string $storagePath, SerializerInterface $serializer)
    {
        $this->path = $storagePath;
        $this->serializer = $serializer;
    }

    public function store(Configuration $configuration): void
    {
        $json = $this->serializer->serialize($configuration, 'json');

        $size = file_put_contents($this->path, $json);

        if (false === $size || $size < 1) {
            throw new StorageException('Could not store configuration');
        }
    }

    public function retrieve(): Configuration
    {
        if (!file_exists($this->path)) {
            $configuration = new Configuration();
            $configuration->setMode(ModeConstant::NOT_FORCED);
            $configuration->setTimeSlots([]);

            return $configuration;
        }

        $json = file_get_contents($this->path);

        if (false === $json) {
            throw new StorageException('Could not retrieve configuration');
        }

        /** @var Configuration $configuration */
        $configuration = $this->serializer->deserialize($json, Configuration::class, 'json');

        if (!($configuration instanceof Configuration)) {
            throw new StorageException('Invalid data was found in storage');
        }

        return $configuration;
    }
}
