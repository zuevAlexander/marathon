<?php

namespace RestBundle\Controller;

use Doctrine\ORM\EntityNotFoundException;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use RestBundle\Event\ProcessorEvent;
use RestBundle\Event\ProcessorEvents;
use RestBundle\Exception\ParametrizedExceptionInterface;
use RestBundle\Exception\Request\BadRequestException;
use RestBundle\Handler\ErrorInterface;
use RestBundle\Handler\ProcessorInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PropertyAccess\Exception\InvalidArgumentException;
use Symfony\Component\Validator\ConstraintViolation;

/**
 * Class BaseController.
 */
abstract class BaseController extends FOSRestController
{
    /**
     * @return ProcessorInterface
     */
    abstract protected function getProcessor() : ProcessorInterface;

    /**
     * @return ErrorInterface
     */
    protected function getErrorHandler() : ErrorInterface
    {
        return $this->getProcessor();
    }

    /**
     * @param Request $request
     * @param string  $formType
     * @param int     $successStatusCode
     *
     * @return Response
     */
    protected function process(Request $request, string $formType, int $successStatusCode = Response::HTTP_OK) : Response
    {
        $data = [];
        $translator = $this->container->get('translator');
        try {
            $requestMethod = strtolower($request->getMethod());
            $actionType = str_replace([$requestMethod, 'Action'], '', debug_backtrace()[1]['function']);
            $processActionName = 'process'.ucfirst($requestMethod).ucfirst($actionType);
            $errorActionName = 'error'.ucfirst($requestMethod).ucfirst($actionType);

            $this->container->get('event_dispatcher')->dispatch(
                ProcessorEvents::PRE_LOAD,
                (new ProcessorEvent($request, $this->getProcessor(), $processActionName))
            );

            $form = $this->container->get('form.factory')->createNamed('', $formType);

            if ($request->isMethod(Request::METHOD_GET)) {
                $params = $request->query->all();
            } else {
                $params = $request->request->all();
            }

            $params = array_merge($params, $request->files->all());

            foreach ($request->attributes->get('_route_params') as $name => $value) { // add url parameters such as {id}
                if (substr($name, 0, 1) != '_') {
                    $params[$name] = $value;
                }
            }

            unset($params['_format']);

            $form->submit($params, false);

            $this->handleFormErrors($form, $errorActionName);

            $data = $this->getProcessor()->$processActionName($form->getData());

            $statusCode = $successStatusCode;
        } catch (EntityNotFoundException $exception) {
            $statusCode = Response::HTTP_NO_CONTENT;
        } catch (ParametrizedExceptionInterface $exception) {
            $statusCode = Response::HTTP_BAD_REQUEST;
            $data['errorMessage'] = $translator->trans($exception->getMessage(), $exception->getParameters());
        } catch (BadRequestException $exception) {
            $statusCode = Response::HTTP_BAD_REQUEST;
            $data['errorMessage'] = $translator->trans($exception->getMessage());
            $data['errorInfo'] = $exception->getErrorInfo();
            $data['errorInfo'] = $exception->getErrorInfo();

            foreach ($data['errorInfo'] as $key => $value) {
                $data['errorInfo'][$key] = $translator->trans($value);
            }
        } catch (InvalidArgumentException $exception) {
            $data['errorMessage'] = $translator->trans($exception->getMessage());
            if ($this->container->get('kernel')->getEnvironment() != 'prod') {
                $data['debug']['errorFile'] = $exception->getFile();
                $data['debug']['errorLine'] = $exception->getLine();
                $data['debug']['errorType'] = get_class($exception);
                $data['debug']['trace'] = $data['debug']['trace'] = $exception->getTrace()[0]['args'][0];
            }
            $statusCode = Response::HTTP_BAD_REQUEST;
        } catch (\Throwable $exception) {
            $data['errorMessage'] = $translator->trans($exception->getMessage());
            if ($this->container->get('kernel')->getEnvironment() != 'prod') {
                $data['debug']['errorFile'] = $exception->getFile();
                $data['debug']['errorLine'] = $exception->getLine();
                $data['debug']['errorType'] = get_class($exception);
                $data['debug']['trace'] = $exception->getTraceAsString();
            }
            $statusCode = $exception->getCode() && round($exception->getCode() / 100) == 4 ? $exception->getCode() : Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        $view = $this->view($data, $statusCode);

        $this->addJmsGroupsIntoView($request->get('_route'), $view);

        return $this->handleView($view);
    }

    /**
     * @param Form   $form
     * @param string $errorActionName
     *
     * @throws \RuntimeException
     */
    private function handleFormErrors(Form $form, string $errorActionName)
    {
        $this->validateForm($form);

        if ($form->isValid()) {
            return;
        }

        if (!method_exists($this->getErrorHandler(), $errorActionName)) {
            $invalidFields = [];

            if ($form->getErrors()->count() == 0) {
                $chunkErrors = explode("\n", $form->getErrors(true, false));
                for ($i = 0; $i < count($chunkErrors) - 1; $i += 2) {
                    if (!empty($chunkErrors[$i]) && !empty($chunkErrors[$i + 1])) {
                        $invalidFields[$chunkErrors[$i]] = preg_replace('/^\s+ERROR:\s+/', '', $chunkErrors[$i + 1]);
                    }
                }
            } else {

                $errorMessages = [];

                foreach ($form->getErrors() as $error) {
                    preg_match('/(\w+): (.+)/', $error->getMessage(), $matches);
                    if (isset($matches[1]) && isset($matches[2])) {
                        /* @var FormError $error */
                        $invalidFields[$matches[1]] = $matches[2];

                        $errorMessages[] = $matches[1] . ': ' . $matches[2];
                    }

                    if (!empty($error->getMessageParameters())) {
                        $invalidFields = $error->getMessageParameters();
                    }
                }
            }

            throw new BadRequestException(implode("\n", $errorMessages), Response::HTTP_BAD_REQUEST, new \RuntimeException(), $invalidFields);
        }

        $this->getErrorHandler()->$errorActionName($form);
    }

    /**
     * @param string $route
     * @param View   $view
     */
    private function addJmsGroupsIntoView(string $route, View $view)
    {
        $view->getContext()->setGroups(array_merge(['Default', $route]));
    }

    /**
     * @param array $data
     * @param int   $statusCode
     *
     * @return Response
     */
    protected function processMock(array $data, int $statusCode = Response::HTTP_OK): Response
    {
        return $this->handleView(
            $this->view($data, $statusCode)
        );
    }

    /**
     * @param Form $form
     */
    private function validateForm(Form $form)
    {
        foreach ($this->container->get('validator')->validate($form->getData()) as $error) {
            /* @var ConstraintViolation $error */
            $form->addError(
                new FormError(
                    strtolower(preg_replace('/([A-Z])/', '_$1', $error->getPropertyPath()).': '.$error->getMessage())
                )
            );
        }
    }
}
