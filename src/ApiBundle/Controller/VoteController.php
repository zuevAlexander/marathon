<?php

namespace ApiBundle\Controller;

use CoreBundle\Form\Vote\VoteCreateType;
use CoreBundle\Form\Vote\VoteListType;
use RestBundle\Controller\BaseController;
use RestBundle\Handler\ProcessorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\RouteResource;

/**
 * Class VoteController.
 *
 * @RouteResource("Vote")
 */
class VoteController extends BaseController
{
    /**
     * @ApiDoc(
     *  resource=true,
     *  section="Voting",
     *  description="Create a new vote",
     *  input={
     *       "class" = "CoreBundle\Form\Vote\VoteCreateType",
     *       "name" = ""
     *  },
     *  statusCodes={
     *      200 = "Ok",
     *      204 = "Entity not found",
     *      400 = "Bad format",
     *      403 = "Forbidden"
     *  }
     *)
     *
     * @param Request $request
     *
     * @return Response
     */
    public function postAction(Request $request) : Response
    {
        return $this->process($request, VoteCreateType::class, Response::HTTP_CREATED);
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  section="Voting",
     *  description="Get a list of votes for challenge",
     *  input={
     *       "class" = "CoreBundle\Form\Vote\VoteListType",
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
        return $this->process($request, VoteListType::class);
    }

    /**
     * @return ProcessorInterface
     */
    protected function getProcessor() : ProcessorInterface
    {
        return $this->get('core.handler.vote');
    }
}
