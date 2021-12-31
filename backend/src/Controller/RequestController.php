<?php

namespace App\Controller;

use App\Entity\Request;
use App\Service\RequestService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class RequestController extends AbstractFOSRestController
{
	private RequestService $requestService;

	/**
	 * @param RequestService $requestService
	 */
	public function __construct(RequestService $requestService)
	{
		$this->requestService = $requestService;
	}

	/**
	 * @Rest\Get("/requests")
	 * @IsGranted("ROLE_ADMIN")
	 *
	 * @return Response
	 */
	public function routeGetRequests(): Response
	{
		$view = $this->view($this->requestService->getAll(), Response::HTTP_OK);
		$view->getContext()->setGroups(['listBuilding', 'listRequest', 'listRoom', 'listStatus', 'listUser']);
		return $this->handleView($view);
	}

	/**
	 * @Rest\Get("/requests/{id}", requirements={"id":"\d+"})
	 * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function routeGetRequest(Request $request): Response
	{
		$view = $this->view($request, Response::HTTP_OK);
		$view->getContext()->setGroups(['listBuilding', 'listRequest', 'listRoom', 'listStatus', 'listUser']);
		return $this->handleView($view);
	}
}
