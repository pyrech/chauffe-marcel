<?php

namespace ChauffeMarcel\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $this->get('chauffe_marcel.particle');
        // todo

        return new \Symfony\Component\HttpFoundation\Response('ok');
    }
}
