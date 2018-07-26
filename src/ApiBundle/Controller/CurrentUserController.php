<?php

namespace ApiBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use RestBundle\Controller\BaseController;
use RestBundle\Handler\ProcessorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use CoreBundle\Form\CurrentUser\CurrentUserType;


/**
 * Class CurrentUserController
 *
 * @RouteResource ("CurrentUser")
 */
class CurrentUserController extends BaseController
{

    /**
     * @ApiDoc (
     *   resource = true,
     *   section = "CurrentUser",
     *   description = "Get CurrentUser",
     *   input = {
     *      "class" = "CoreBundle\Form\CurrentUser\CurrentUserType",
     *      "name" = "",
     *   },
     *   statusCodes = {
     *      "200" = "Ok",
     *      "204" = "CurrentUser not found",
     *      "400" = "Bad format",
     *      "403" = "Forbidden",
     *   },
     * )
     * @param Request $request
     * @return Response
     */
    public function getAction(Request $request): Response
    {
         return $this->process($request, CurrentUserType::class);
    }

    /**
     * @return ProcessorInterface
     */
    public function getProcessor(): ProcessorInterface
    {
        return $this->get('core.handler.current_user');
    }

}
