<?php

namespace App\Controller;

use App\Entity\Room;
use App\Entity\Team;
use App\Entity\User;
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

	//--------------------------------------------------------------------------------------------------------------------
	/**
	 * @param UserService $userService
	 */
	public function __construct(UserService $userService)
	{
		$this->userService = $userService;
	}
	//--------------------------------------------------------------------------------------------------------------------
	/**
	 * @Rest\Get("/users")
	 * @return Response
	 */
	public function ActionGetUsers(): Response
	{
		$users = $this->userService->getAll();
		$view = $this->view($users, Response::HTTP_OK);
		return $this->handleView($view);
	}
	//--------------------------------------------------------------------------------------------------------------------
	/**
	 * @Rest\Get("/users/{id}", requirements={"id": "\d+"})
	 * @param User $user
	 * @return Response
	 */
	public function ActionGetUser(User $user): Response
	{
		return $this->handleView($this->view($user, Response::HTTP_OK));
	}
	//--------------------------------------------------------------------------------------------------------------------
	/**
	 * @Rest\Get("/users/{id}/{attr}", requirements={"id": "\d+"})
	 * @param User $user
	 * @param string $attr
	 * @return Response
	 */
	public function ActionGetUserAttr(User $user, string $attr): Response
	{
		switch ($attr) {
			case "requests":
				$data = $user->getRequests();
				break;

			case "rooms":
				if ($user->getIsAdmin()) {
					$data = $this->getDoctrine()->getRepository(Room::class)->findAll();
					break;
				}
				$rooms1 = [];
				$rooms2 = [];
				foreach ($user->getRoomRoles() as $roomRole)
					$rooms1[] = $roomRole->getRoom();
				foreach ($user->getTeamRoles() as $teamRole)
					$rooms2 = array_merge($rooms2, $this->findRooms($teamRole->getTeam()));
				$data = array_unique(array_merge($rooms1, $rooms2), SORT_REGULAR);
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
	//--------------------------------------------------------------------------------------------------------------------
	private function findRooms(Team $team): array
	{
		$children = $this->getChildrenRecursive($team);
		$rooms = [];
		foreach ($children as $child)
			$rooms = array_merge($rooms, $child->getRooms()->toArray());
		return $rooms;
	}
	//--------------------------------------------------------------------------------------------------------------------
	private function getChildrenRecursive(Team $team): array
	{
		// result includes the $team itself
		return array_reduce($team->getChildren()->toArray(),
			function (array $carry, Team $item) {
				return array_merge($carry, $this->getChildrenRecursive($item));
			},
			[$team]);
	}
	//--------------------------------------------------------------------------------------------------------------------
}
