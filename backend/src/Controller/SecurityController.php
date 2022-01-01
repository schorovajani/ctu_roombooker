<?php

namespace App\Controller;

use App\Entity\Account;
use App\Service\UserService;
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
	private UserService $userService;

	public function __construct(UserService $userService)
	{
		$this->userService = $userService;
	}

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
			'scope' => $this->getUserRoles(),
		], Response::HTTP_OK);
	}

	/**
	 * @return array
	 */
	private function getUserRoles(): array
	{
		/** @var Account $loggedInAccount */
		$loggedInAccount = $this->getUser();
		$roles = [];

		if ($this->isGranted('ROLE_ADMIN'))
			$roles[] = 'admin';

		foreach ($loggedInAccount->getOwner()->getRoomRoles() as $role)
			if (!in_array('room' . $role->getRoleType()->getName(), $roles))
				$roles[] = 'room' . $role->getRoleType()->getName();

		foreach ($loggedInAccount->getOwner()->getTeamRoles() as $role)
			if (!in_array('team' . $role->getRoleType()->getName(), $roles))
				$roles[] = 'team' . $role->getRoleType()->getName();

		return $roles;
	}
}
