<?php

namespace ChauffeMarcel\Controller;

use ChauffeMarcel\Api\Model\Configuration;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;

abstract class ApiController extends Controller
{
    protected function receiveData(Request $request, $class)
    {
        $serializer = $this->get('chauffe_marcel.api.serializer');

        try {
            $data = $serializer->deserialize($request->getContent(), $class, 'json');
        } catch (UnexpectedValueException $e) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Invalid data sent');
        }

        return $data;
    }

    protected function renderData($data): JsonResponse
    {
        $serializer = $this->get('chauffe_marcel.api.serializer');

        return new JsonResponse($serializer->normalize($data), 200, [
            'Access-Control-Allow-Origin' => '*',
        ]);
    }

    protected function getConfiguration(): Configuration
    {
        return $this->get('chauffe_marcel.storage')->retrieve();
    }

    protected function updateConfiguration(Configuration $configuration)
    {
        $this->get('chauffe_marcel.storage')->store($configuration);

        $this->get('chauffe_marcel.marcel')->chauffe($configuration);
    }
}
