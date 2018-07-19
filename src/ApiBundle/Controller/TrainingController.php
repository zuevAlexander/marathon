<?php

namespace ApiBundle\Controller;

use CoreBundle\Entity\Training;
use CoreBundle\Form\Training\TrainingCreateType;
use CoreBundle\Form\Training\TrainingUpdateType;
use CoreBundle\Form\Training\TrainingDeleteType;
use RestBundle\Controller\BaseController;
use RestBundle\Handler\ProcessorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\RouteResource;

/**
 * Class TrainingController.
 *
 * @RouteResource("Training")
 */
class TrainingController extends BaseController
{
    /**
     * @ApiDoc(
     *  resource=true,
     *  section="Training",
     *  description="Create new training",
     *  input={
     *       "class" = "CoreBundle\Form\Training\TrainingCreateType",
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
        return $this->process($request, TrainingCreateType::class, Response::HTTP_CREATED);
    }


    /**
     * @ApiDoc (
     *   resource = true,
     *   section = "Training",
     *   description = "Update certain fields Training",
     *   input = {
     *      "class" = "CoreBundle\Form\Training\TrainingUpdateType",
     *      "name" = "",
     *   },
     *   statusCodes = {
     *      "200" = "Ok",
     *      "204" = "Training not found",
     *      "400" = "Bad format",
     *      "403" = "Forbidden",
     *   },
     * )
     * @param Request $request
     * @param Training $training
     * @return Response
     */
    public function patchAction(Request $request, Training $training): Response
    {
        return $this->process($request, TrainingUpdateType::class);
    }

    /**
     * @ApiDoc (
     *   resource = true,
     *   section = "Training",
     *   description = "Delete Training",
     *   input = {
     *      "class" = "CoreBundle\Form\Training\TrainingDeleteType",
     *      "name" = "",
     *   },
     *   statusCodes = {
     *      "200" = "Ok",
     *      "204" = "Training not found",
     *      "400" = "Bad format",
     *      "403" = "Forbidden",
     *   },
     * )
     * @param Request $request
     * @param Training $training
     * @return Response
     */
    public function deleteAction(Request $request, Training $training): Response
    {
        return $this->process($request, TrainingDeleteType::class);
    }

    /**
     * @return ProcessorInterface
     */
    protected function getProcessor() : ProcessorInterface
    {
        return $this->get('core.handler.training');
    }
}
