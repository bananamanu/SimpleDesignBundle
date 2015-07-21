<?php
namespace Bananamanu\SimpleDesignBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use eZ\Bundle\EzPublishCoreBundle\Controller;

class MenuController extends Controller
{
    /**
     * Controller  for top menu
     * @param int $currentLocationId
     *
     * @return Response
     */
    public function topMenuAction( $currentLocationId )
    {
        $rootLocationId = $this->getConfigResolver()->getParameter( 'content.tree_root.location_id' );

        $response = new Response;
        $parameters = array();

        $helper = $this->get( 'bananamanu_simple_design.subelement_helper' );
        $menu = $helper->getSubElementLocation( $rootLocationId );

        // Get current master
        try
        {
            $currentLocation = $this->getRepository()->getLocationService()->loadLocation( $currentLocationId );
            $pathString = explode( '/', $currentLocation->pathString );
            if ( (int) $pathString[3] > 0 )
            {
                $currentMasterLocationId = $pathString[3];
            }
            else
            {
                $currentMasterLocationId = $rootLocationId;
            }
        }
        catch (\Exception $e)
        {
            $currentMasterLocationId = $rootLocationId;
        }

        $parameters['menu'] = $menu;
        $parameters['currentMasterLocationId'] = $currentMasterLocationId;
        return $this->render( 'BananamanuSimpleDesignBundle::page_topmenu.html.twig', $parameters, $response );
    }

}
