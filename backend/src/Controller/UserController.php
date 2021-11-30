<?php

namespace App\Controller;

use App\Service\UserService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class UserController extends AbstractFOSRestController
{
	private UserService $userService;

	/**
	 * @param UserService $userService
	 */
	public function __construct(UserService $userService)
	{
		$this->userService = $userService;
	}

	/**
	 * @Route("/users", methods={"GET"})
	 *
	 * @return Response
	 */
	public function getAllUsers(): Response
	{
		$employees = $this->userService->getAll();
		$view = $this->view($employees, 200);

		return $this->handleView($view);
	}
}
