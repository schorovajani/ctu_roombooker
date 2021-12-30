<?php

namespace App\Controller;

use App\Entity\Request;
use App\Service\RequestService;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class  RequestController extends \FOS\RestBundle\Controller\AbstractFOSRestController
{
	private RequestService $requestService;

	/**
	 * @param RequestService $service
	 */
	public function __construct(RequestService $service)
	{
		$this->requestService = $service;
	}

	/**
	 * @Rest\Get("/requests")
	 * @return Response
	 */
	public function routeGetRequests(): Response
	{
		$requests = $this->requestService->getAll();
		return $this->handleView($this->view($requests, Response::HTTP_OK));
	}

	/**
	 * @Rest\Get("/requests/{id}", requirements={"id":"\d+"})
	 * @param Request $request
	 * @return Response
	 */
	public function routeGetRequest(Request $request): Response
	{
		return $this->handleView($this->view($request, Response::HTTP_OK));
	}
}


?>