<?php

namespace App\Controller;

use App\Entity\Account;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class SecurityController extends AbstractFOSRestController
{
	/**
	 * @Rest\Post("/login", name="app_login")
	 *
	 * @return Response
	 */
	public function login(): Response
	{
		if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
			return $this->json([
				'error' => 'Invalid login request: check that the Content-Type header is "application/json".',
			], Response::HTTP_BAD_REQUEST);
		}

		return $this->loggedInAccountDetails();
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
	 *
	 * @return Response
	 */
	public function logoutResponse(): Response
	{
		return $this->json(null, Response::HTTP_OK);
	}

	/**
	 * @Rest\Get("/me")
	 * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
	 *
	 * @return Response
	 */
	public function loggedInAccountDetails(): Response
	{
		/* @var Account $loggedInAccount */
		$loggedInAccount = $this->getUser();

		return $this->json([
			'firstName' => $loggedInAccount->getOwner()->getFirstName(),
			'lastName' => $loggedInAccount->getOwner()->getLastName(),
			'username' => $loggedInAccount->getUserIdentifier(),
		], Response::HTTP_OK);
	}
}
