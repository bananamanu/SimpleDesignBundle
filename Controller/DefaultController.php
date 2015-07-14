<?php

namespace Bananamanu\Bundle\SimpleDesignBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('BananamanuSimpleDesignBundle:Default:index.html.twig', array('name' => $name));
    }
}
