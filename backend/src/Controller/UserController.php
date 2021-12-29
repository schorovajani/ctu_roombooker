<?php

namespace App\Controller;

use App\Entity\Room;
use App\Entity\Team;
use App\Entity\User;
use App\Service\TeamService;
use App\Service\UserService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
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
	 * @Rest\Get("/users")
	 * @return Response
	 */
	public function routeGetUsers(): Response
	{
		$users = $this->userService->getAll();
		$view = $this->view($users, Response::HTTP_OK);
		return $this->handleView($view);
	}

	/**
	 * @Rest\Get("/users/{id}", requirements={"id": "\d+"})
	 * @param User $user
	 * @return Response
	 */
	public function routeGetUser(User $user): Response
	{
		return $this->handleView($this->view($user, Response::HTTP_OK));
	}

	/**
	 * @Rest\Get("/users/{id}/{attr}", requirements={"id": "\d+"})
	 * @param User $user
	 * @param string $attr
	 * @return Response
	 */
	public function routeGetUserAttr(User $user, string $attr): Response
	{
		switch ($attr) {
			case "requests":
				$data = $user->getRequests();
				break;

			case "rooms":
				// TODO - admin?
				$data = $this->userService->getUserRooms($user);
				break;

			/*
			case "accounts":
				break;
			*/

			default:
				throw $this->createNotFoundException();
		}
		return $this->handleView($this->view($data, Response::HTTP_OK));
	}
}
