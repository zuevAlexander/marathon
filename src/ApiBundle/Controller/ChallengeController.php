<?php

namespace ApiBundle\Controller;

use CoreBundle\Entity\Challenge;
use CoreBundle\Form\Challenge\ChallengeUpdateType;
use CoreBundle\Form\Challenge\ChallengeDeleteType;
use CoreBundle\Form\Challenge\ChallengeListType;
use CoreBundle\Form\Challenge\ChallengeCreateType;
use CoreBundle\Form\Challenge\ChallengeReadType;
use RestBundle\Controller\BaseController;
use RestBundle\Handler\ProcessorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\RouteResource;

/**
 * Class ChallengeController.
 *
 * @RouteResource("Challenge")
 */
class ChallengeController extends BaseController
{
    /**
     * @ApiDoc(
     *  resource=true,
     *  section="Challenge",
     *  description="Create a new Challenge",
     *  input={
     *       "class" = "CoreBundle\Form\Challenge\ChallengeCreateType",
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
        return $this->process($request, ChallengeCreateType::class, Response::HTTP_CREATED);
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  section="Challenge",
     *  description="Get the Challenge",
     *  input={
     *       "class" = "CoreBundle\Form\Challenge\ChallengeReadType",
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
        return $this->process($request, ChallengeReadType::class);
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  section="Challenge",
     *  description="Get a list of Challenges",
     *  input={
     *       "class" = "CoreBundle\Form\Challenge\ChallengeListType",
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
        return $this->process($request, ChallengeListType::class);
    }

    /**
     * @ApiDoc (
     *   resource = true,
     *   section = "Challenge",
     *   description = "Update certain fields Challenge",
     *   input = {
     *      "class" = "CoreBundle\Form\Challenge\ChallengeUpdateType",
     *      "name" = "",
     *   },
     *   statusCodes = {
     *      "200" = "Ok",
     *      "204" = "Challenge not found",
     *      "400" = "Bad format",
     *      "403" = "Forbidden",
     *   },
     * )
     * @param Request $request
     * @param Challenge $challenge
     * @return Response
     */
    public function putAction(Request $request, Challenge $challenge): Response
    {
        return $this->process($request, ChallengeUpdateType::class);
    }

    /**
     * @ApiDoc (
     *   resource = true,
     *   section = "Challenge",
     *   description = "Delete Challenge",
     *   input = {
     *      "class" = "CoreBundle\Form\Challenge\ChallengeDeleteType",
     *      "name" = "",
     *   },
     *   statusCodes = {
     *      "200" = "Ok",
     *      "204" = "Challenge not found",
     *      "400" = "Bad format",
     *      "403" = "Forbidden",
     *   },
     * )
     * @param Request $request
     * @param Challenge $challenge
     * @return Response
     */
    public function deleteAction(Request $request, Challenge $challenge): Response
    {
        return $this->process($request, ChallengeDeleteType::class);
    }

    /**
     * @return ProcessorInterface
     */
    protected function getProcessor() : ProcessorInterface
    {
        return $this->get('core.handler.challenge');
    }
}
