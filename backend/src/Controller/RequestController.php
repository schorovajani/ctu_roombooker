<?php

namespace App\Controller;

use App\Entity\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class  RequestController extends \FOS\RestBundle\Controller\AbstractFOSRestController
{
	/**
	 * @Rest\Get("/requests")
	 * @return Response
	 */
	public function routeGetRequests(): Response
	{
		$requests = $this->getDoctrine()->getRepository(Request::class)->findAll();
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