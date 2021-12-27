<?php

namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class DummyController extends AbstractFOSRestController
{
	/**
	 * @Rest\Get("/canBeAccessed")
	 */
	public function canBeAccessed(): Response
	{
		$view = $this->view('Some data', Response::HTTP_OK);
		return $this->handleView($view);
	}

	/**
	 * @Rest\Get("/cannotBeAccessed")
	 * @IsGranted("ROLE_USER")
	 */
	public function cannotBeAccessed(): Response
	{
		$view = $this->view('Secured data', Response::HTTP_OK);
		return $this->handleView($view);
	}
}
