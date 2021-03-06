<?php

namespace ApiBundle\Controller;

use CoreBundle\Entity\User;
use CoreBundle\Form\User\UserUpdateType;
use CoreBundle\Form\User\UserDeleteType;
use CoreBundle\Form\User\UserListType;
use CoreBundle\Form\User\UserReadType;
use RestBundle\Controller\BaseController;
use RestBundle\Handler\ProcessorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\RouteResource;

/**
 * Class UserController.
 *
 * @RouteResource("User")
 */
class UserController extends BaseController
{
    /**
     * @ApiDoc(
     *  resource=true,
     *  section="User",
     *  description="Get user",
     *  input={
     *       "class" = "CoreBundle\Form\User\UserReadType",
     *       "name" = ""
     *  },
     *  statusCodes={
     *      200 = "Ok",
     *      400 = "Bad format",
     *      403 = "Access denied"
     *  }
     *)
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function getAction(Request $request) : Response
    {
        return $this->process($request, UserReadType::class);
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  section="User",
     *  description="Get a list of Users",
     *  input={
     *       "class" = "CoreBundle\Form\User\UserListType",
     *       "name" = ""
     *  },
     *  statusCodes={
     *      200 = "Ok",
     *      204 = "Entity not found",
     *      400 = "Bad format",
     *      403 = "Forbidden"
     *  }
     * )
     *
     * @param Request $request
     *
     * @return Response
     */
    public function cgetAction(Request $request) : Response
    {
        return $this->process($request, UserListType::class);
    }

    /**
     * @ApiDoc (
     *   resource = true,
     *   section = "User",
     *   description = "Update certain fields User",
     *   input = {
     *      "class" = "CoreBundle\Form\User\UserUpdateType",
     *      "name" = "",
     *   },
     *   statusCodes = {
     *      "200" = "Ok",
     *      "204" = "User not found",
     *      "400" = "Bad format",
     *      "403" = "Forbidden",
     *   },
     * )
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function patchAction(Request $request, User $user): Response
    {
        return $this->process($request, UserUpdateType::class);
    }

    /**
     * @ApiDoc (
     *   resource = true,
     *   section = "User",
     *   description = "Delete User",
     *   input = {
     *      "class" = "CoreBundle\Form\User\UserDeleteType",
     *      "name" = "",
     *   },
     *   statusCodes = {
     *      "200" = "Ok",
     *      "204" = "User not found",
     *      "400" = "Bad format",
     *      "403" = "Forbidden",
     *   },
     * )
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function deleteAction(Request $request, User $user): Response
    {
        return $this->process($request, UserDeleteType::class);
    }

    /**
     * @return ProcessorInterface
     */
    protected function getProcessor() : ProcessorInterface
    {
        return $this->get('core.handler.user');
    }
}
