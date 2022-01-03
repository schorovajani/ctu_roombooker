<?php

namespace App\Controller;

use App\Entity\RoleType;
use App\Entity\Room;
use App\Entity\User;
use App\Service\RoomRoleService;
use App\Service\RoomService;
use App\Service\UserService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class RoomController extends AbstractFOSRestController
{
	private RoomService $roomService;
	private RoomRoleService $roomRoleService;
	private UserService $userService;

	/**
	 * @param RoomRoleService $roomRoleService
	 * @param RoomService $roomService
	 * @param UserService $userService
	 */
	public function __construct(RoomRoleService $roomRoleService, RoomService $roomService, UserService $userService)
	{
		$this->roomService = $roomService;
		$this->roomRoleService = $roomRoleService;
		$this->userService = $userService;
	}

	/**
	 * @Rest\Get("/rooms")
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function routeGetRooms(Request $request): Response
	{
		if ($request->query->get("type") === "public" || !$this->isGranted('IS_AUTHENTICATED_REMEMBERED'))
			$rooms = $this->roomService->getBy(["isPublic" => true]);
		else
			$rooms = $this->roomService->getAll();

		$view = $this->view($rooms, Response::HTTP_OK);
		$view->getContext()->setGroups(['listBuilding', 'listRoom', 'listTeam']);
		return $this->handleView($view);
	}

	/**
	 * @Rest\Get("/rooms/{id}/{attr}", requirements={"id": "\d+"})
	 *
	 * @param Room $room
	 * @param string $attr
	 * @return Response
	 */
	public function routeGetRoomAttr(Room $room, string $attr): Response
	{
		switch ($attr) {
			case "requests":
				if (!$room->getIsPublic() && !$this->isGranted('IS_AUTHENTICATED_REMEMBERED'))
					throw $this->createAccessDeniedException();

				$view = $this->view($room->getRequests(), Response::HTTP_OK);
				if ($this->isGranted('IS_AUTHENTICATED_REMEMBERED'))
					$view->getContext()->setGroups(['listBuilding', 'listRequest', 'listRoom', 'listStatus', 'listUser']);
				else
					$view->getContext()->setGroups(['listBuilding', 'listRequestMinimal', 'listRoom', 'listStatus']);
				return $this->handleView($view);

			case "users":
				$this->denyAccessUnlessGranted('GET_ROOM_USERS', $room);
				$users = $this->roomService->getRoomUsers($room);
				foreach ($users as $user)
					$this->userService->filterUserRolesByRoom($user, $room);
				$view = $this->view($users, Response::HTTP_OK);
				$view->getContext()->setGroups(['listRoom', 'listRoomRole', 'listTeam', 'listTeamRole', 'listUser']);
				return $this->handleView($view);

			default:
				throw $this->createNotFoundException();
		}
	}

	/**
	 * @Rest\Delete("/rooms/{id}", requirements={"id": "\d+"})
	 * @IsGranted("ROLE_ADMIN")
	 *
	 * @param Room $room
	 * @return Response
	 */
	public function routeDeleteRoom(Room $room): Response
	{
		$this->roomService->delete($room);
		return $this->handleView($this->view(null, Response::HTTP_NO_CONTENT));
	}

	/**
	 * @Rest\Delete("/rooms/{id}/users/{user_id}", requirements={"id": "\d+", "user_id": "\d+"})
	 * @Entity("user", expr="repository.find(user_id)")
	 * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
	 *
	 * @param Room $room
	 * @param User $user
	 * @return Response
	 */
	public function routeDeleteUserRoomRole(Room $room, User $user): Response
	{
		$roomRole = $this->roomRoleService->get(['user' => $user, 'room' => $room]);
		if (!$this->isGranted('ROLE_ADMIN'))
			if ($roomRole->getRoleType()->getName() === RoleType::ROLE_MANAGER
				|| !in_array($this->getUser()->getOwner(), $this->roomService->getRoomUsers($room, true)))
				throw $this->createAccessDeniedException();

		$this->roomRoleService->delete($roomRole);
		return $this->handleView($this->view(null, Response::HTTP_NO_CONTENT));
	}
}
