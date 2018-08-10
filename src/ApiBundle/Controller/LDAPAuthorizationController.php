<?php

namespace ApiBundle\Controller;

use CoreBundle\Form\LDAPAuthorization\LDAPAuthorizationPostType;
use FOS\RestBundle\Controller\Annotations;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use RestBundle\Controller\BaseController;
use RestBundle\Handler\ProcessorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LDAPAuthorizationController extends BaseController
{

    /**
     * @ApiDoc (
     *   resource = true,
     *   section = "User",
     *   description = "Login",
     *   input = {
     *      "class" = "CoreBundle\Form\LDAPAuthorization\LDAPAuthorizationPostType",
     *      "name" = "",
     *   },
     *   statusCodes = {
     *      "200" = "Ok",
     *      "204" = "User not found",
     *      "400" = "Bad format",
     *      "403" = "Forbidden",
     *   },
     * )
     *
     * @Annotations\Post("/login")
     *
     * @param Request $request
     * @return Response
     */
    public function loginAction(Request $request): Response
    {
        return $this->process($request, LDAPAuthorizationPostType::class);
    }

    /**
     * @return ProcessorInterface
     */
    protected function getProcessor(): ProcessorInterface
    {
        return $this->get('core.handler.ldap_authorization');
    }
}