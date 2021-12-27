<?php

namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class SecurityController extends AbstractFOSRestController
{
	/**
	 * @Rest\Post("/login", name="app_login")
	 */
	public function login(): Response
	{
		if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
			return $this->json([
				'error' => 'Invalid login request: check that the Content-Type header is "application/json".',
			], Response::HTTP_BAD_REQUEST);
		}

		return $this->json([
			'status' => 'Logged in successfully (info for testing purposes)',
			'username' => $this->getUser()->getUserIdentifier(),
		], Response::HTTP_OK);
	}

	/**
	 * @Rest\Get("/logout", name="app_logout")
	 */
	public function logout()
	{
		throw new \Exception('This exception should be never thrown');
	}

	/**
	 * @Rest\Get("/logout_response", name="app_logout_response")
	 */
	public function logoutResponse(): Response
	{
		return $this->json([
			'status' => 'Logged out successfully (info for testing purposes)',
		], Response::HTTP_OK); // TODO: after testing change to 204?
	}
}
