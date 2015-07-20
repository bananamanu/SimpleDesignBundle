<?php
namespace Bananamanu\SimpleDesignBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use eZ\Bundle\EzPublishCoreBundle\Controller;

class MenuController extends Controller
{
    /**
     *
     * @return Response
     */
    public function topMenuAction()
    {
        $rootLocationId = $this->getConfigResolver()->getParameter( 'content.tree_root.location_id' );

        $response = new Response;
        $parameters = array();

        $helper = $this->get( 'bananamanu_simple_design.subelement_helper' );
        $menu = $helper->getSubElementLocation( $rootLocationId );

        $parameters['menu'] = $menu;
        return $this->render( 'BananamanuSimpleDesignBundle::page_topmenu.html.twig', $parameters, $response );
    }

}
