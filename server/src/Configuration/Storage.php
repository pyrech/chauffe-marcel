<?php

namespace ChauffeMarcel\Configuration;

use ChauffeMarcel\Api\Model\Configuration;
use Symfony\Component\Serializer\Serializer;

class Storage
{
    private $path;
    private $serializer;

    public function __construct(string $path, Serializer $serializer)
    {
        $this->path = $path;
        $this->serializer = $serializer;
    }

    public function store(Configuration $configuration)
    {
        $json = $this->serializer->serialize($configuration, 'json');

        $size = file_put_contents($this->path, $json);

        if ($size === false || $size < 1) {
            throw new StorageException('Could not store configuration');
        }
    }

    public function retrieve(): Configuration
    {
        if (!file_exists($this->path)) {
            return new Configuration();
        }

        $json = file_get_contents($this->path);

        if ($json === false) {
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
