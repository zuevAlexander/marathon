<?php

namespace ApiBundle\Controller;

use CoreBundle\Entity\Voter;
use CoreBundle\Form\Voter\VoterCreateType;
use RestBundle\Controller\BaseController;
use RestBundle\Handler\ProcessorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\RouteResource;

/**
 * Class VoterController.
 *
 * @RouteResource("Voting")
 */
class VoterController extends BaseController
{
    /**
     * @ApiDoc(
     *  resource=true,
     *  section="Voting",
     *  description="Create new user",
     *  input={
     *       "class" = "CoreBundle\Form\Voter\VoterCreateType",
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
        return $this->process($request, VoterCreateType::class, Response::HTTP_CREATED);
    }

    /**
     * @return ProcessorInterface
     */
    protected function getProcessor() : ProcessorInterface
    {
        return $this->get('core.handler.voter');
    }
}
