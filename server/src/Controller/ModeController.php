<?php

namespace ChauffeMarcel\Controller;

use ChauffeMarcel\Configuration\ModeConstant;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * @Route("/api/mode")
 */
class ModeController extends ApiController
{
    /**
     * @Route("/", name="mode_get", methods="GET")
     */
    public function getAction()
    {
        $configuration = $this->getConfiguration();

        return $this->renderData($configuration->getMode());
    }

    /**
     * @Route("/", name="mode_update", methods="POST")
     */
    public function createAction(Request $request)
    {
        $mode = $request->getContent();

        if (!in_array($mode, ModeConstant::MODES)) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Invalid mode sent');
        }

        $configuration = $this->getConfiguration();

        $configuration->setMode($mode);

        $this->updateConfiguration($configuration);

        return $this->renderData(true);
    }
}
