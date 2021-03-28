<?php

namespace App\Controller;

use App\Api\Model\Configuration;
use App\Configuration\Storage;
use App\Marcel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Symfony\Component\Serializer\SerializerInterface;

abstract class ApiController extends AbstractController
{
    private SerializerInterface $serializer;
    private Storage $storage;
    private Marcel $marcel;

    public function __construct(SerializerInterface $serializer, Storage $storage, Marcel $marcel)
    {
        $this->serializer = $serializer;
        $this->storage = $storage;
        $this->marcel = $marcel;
    }

    protected function receiveData(Request $request, $class)
    {
        try {
            $data = $this->serializer->deserialize($request->getContent(), $class, 'json');
        } catch (UnexpectedValueException $e) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Invalid data sent');
        }

        return $data;
    }

    protected function renderData($data): JsonResponse
    {
        return new JsonResponse($this->serializer->normalize($data), 200, [
            'Access-Control-Allow-Origin' => '*',
        ]);
    }

    protected function getConfiguration(): Configuration
    {
        return $this->storage->retrieve();
    }

    protected function updateConfiguration(Configuration $configuration): void
    {
        $this->storage->store($configuration);

        $this->marcel->chauffe($configuration);
    }
}
