<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\UserService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
	 * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
	 *
	 * @return Response
	 */
	public function routeGetUsers(): Response
	{
		$view = $this->view($this->userService->getAll(), Response::HTTP_OK);
		$view->getContext()->setGroups(['listUser']);
		return $this->handleView($view);
	}

	/**
	 * @Rest\Get("/users/{id}", requirements={"id": "\d+"})
	 * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
	 *
	 * @param User $user
	 * @return Response
	 */
	public function routeGetUser(User $user): Response
	{
		$view = $this->view($user, Response::HTTP_OK);
		$view->getContext()->setGroups(['listUser']);
		return $this->handleView($view);
	}

	/**
	 * @Rest\Get("/users/{id}/{attr}", requirements={"id": "\d+"})
	 * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
	 *
	 * @param User $user
	 * @param string $attr
	 * @return Response
	 */
	public function routeGetUserAttr(User $user, string $attr): Response
	{
		if (!$this->isGranted('ROLE_ADMIN') && $this->getUser()->getOwner() !== $user)
			throw $this->createAccessDeniedException();

		switch ($attr) {
			case "requests":
				$viewData = $this->userService->getUserRequests($user);
				break;

			case "rooms":
				$viewData = $this->userService->getUserRooms($user);
				break;

			/*
			case "accounts":
				break;
			*/

			default:
				throw $this->createNotFoundException();
		}

		$view = $this->view($viewData, Response::HTTP_OK);
		$view->getContext()->setGroups(['listBuilding', 'listRequest', 'listRoom', 'listStatus', 'listTeam', 'listUser']);
		return $this->handleView($view);
	}
}
