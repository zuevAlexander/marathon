<?php

namespace ApiBundle\Controller;

use CoreBundle\Entity\Participant;
use CoreBundle\Form\Participant\ParticipantDeleteType;
use CoreBundle\Form\Participant\ParticipantCreateType;
use CoreBundle\Form\Participant\ParticipantReadType;
use RestBundle\Controller\BaseController;
use RestBundle\Handler\ProcessorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\RouteResource;

/**
 * Class ParticipantController.
 *
 * @RouteResource("Participant")
 */
class ParticipantController extends BaseController
{
    /**
     * @ApiDoc(
     *  resource=true,
     *  section="Participant",
     *  description="Create a new Participant",
     *  input={
     *       "class" = "CoreBundle\Form\Participant\ParticipantCreateType",
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
        return $this->process($request, ParticipantCreateType::class, Response::HTTP_CREATED);
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  section="Participant",
     *  description="Get the Participant",
     *  input={
     *       "class" = "CoreBundle\Form\Participant\ParticipantReadType",
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
        return $this->process($request, ParticipantReadType::class);
    }

    /**
     * @ApiDoc (
     *   resource = true,
     *   section = "Participant",
     *   description = "Delete Participant",
     *   input = {
     *      "class" = "CoreBundle\Form\Participant\ParticipantDeleteType",
     *      "name" = "",
     *   },
     *   statusCodes = {
     *      "200" = "Ok",
     *      "204" = "Participant not found",
     *      "400" = "Bad format",
     *      "403" = "Forbidden",
     *   },
     * )
     * @param Request $request
     * @param Participant $participant
     * @return Response
     */
    public function deleteAction(Request $request, Participant $participant): Response
    {
        return $this->process($request, ParticipantDeleteType::class);
    }

    /**
     * @return ProcessorInterface
     */
    protected function getProcessor() : ProcessorInterface
    {
        return $this->get('core.handler.participant');
    }
}
