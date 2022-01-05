<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\RoleType;
use App\Entity\Room;
use App\Entity\RoomRole;
use App\Entity\User;
use App\Service\RoomRoleService;
use App\Service\RoomService;
use App\Service\UserService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;

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
		$criteria = [];
		if ($request->query->get("type") === "public" || !$this->isGranted('IS_AUTHENTICATED_REMEMBERED'))
			$criteria["isPublic"] = true;
		if ($request->query->get("team") === "null")
			$criteria["team"] = null;

		$view = $this->view($this->roomService->getBy($criteria), Response::HTTP_OK);
		$view->getContext()->setGroups(['listBuilding', 'listRoom', 'listTeam']);
		return $this->handleView($view);
	}

	/**
	 * @Rest\Get("/rooms/{id}", requirements={"id": "\d+"})
	 *
	 * @param Room $room
	 * @return Response
	 */
	public function routeGetRoom(Room $room): Response
	{
		if (!$room->getIsPublic() && !$this->isGranted('IS_AUTHENTICATED_REMEMBERED'))
			throw $this->createAccessDeniedException();

		$view = $this->view($room, Response::HTTP_OK);
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

	/**
	 * @Rest\Post("/rooms")
	 * @ParamConverter("room", converter="fos_rest.request_body")
	 * @IsGranted("ROLE_ADMIN")
	 *
	 * @param Room $room
	 * @param ConstraintViolationListInterface $validationErrors
	 * @return Response
	 */
	public function routePostRoom(Room $room, ConstraintViolationListInterface $validationErrors): Response
	{
		if (count($validationErrors) > 0)
			return $this->handleView($this->view(["error" => $validationErrors], Response::HTTP_BAD_REQUEST));

		$this->roomService->save($room);

		$view = $this->view($room, Response::HTTP_CREATED);
		$view->getContext()->setGroups(['listBuilding', 'listRoom', 'listTeam']);
		return $this->handleView($view);
	}

	/**
	 * @Rest\Put("/rooms/{id}", requirements={"id": "\d+"})
	 * @ParamConverter("rooms")
	 * @ParamConverter("newRoom", converter="fos_rest.request_body")
	 * @IsGranted("ROLE_ADMIN")
	 *
	 * @param Room $room
	 * @param Room $newRoom
	 * @param ConstraintViolationListInterface $validationErrors
	 * @return Response
	 */
	public function routePutRoom(Room $room, Room $newRoom, ConstraintViolationListInterface $validationErrors): Response
	{
		if (count($validationErrors) > 0)
			return $this->handleView($this->view(['error' => $validationErrors], Response::HTTP_BAD_REQUEST));

		if ($newRoom->getBuilding() === null)
			return $this->handleView($this->view(['error' => 'Invalid data'], Response::HTTP_BAD_REQUEST));

		$this->roomService->update($room, $newRoom);

		$view = $this->view($room, Response::HTTP_OK);
		$view->getContext()->setGroups(['listBuilding', 'listRoom', 'listTeam']);
		return $this->handleView($view);
	}

	/**
	 * @Rest\Patch("/rooms/{id}", requirements={"id": "\d+"})
	 * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
	 *
	 * @param Room $room
	 * @param Request $request
	 * @return Response
	 */
	public function routePatchRoom(Room $room, Request $request): Response
	{
		if ($this->getUser()->getUserIdentifier() !== 'CardReader')
			throw $this->createAccessDeniedException();

		$isLocked = $request->request->get('isLocked');
		if (!is_bool($isLocked))
			return $this->handleView($this->view(['error' => 'Invalid data'], Response::HTTP_BAD_REQUEST));

		$room->setIsLocked($isLocked);
		$this->roomService->save($room);

		return $this->handleView($this->view(null, Response::HTTP_NO_CONTENT));
	}

	/**
	 * @Rest\Put("/rooms/{id}/users", requirements={"id": "\d+"})
	 * @ParamConverter("room")
	 * @ParamConverter("newRoom", converter="fos_rest.request_body")
	 * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
	 *
	 * @param Room $room
	 * @param Room $newRoom
	 * @return Response
	 */
	public function routePutUserRoomRole(Room $room, Room $newRoom): Response
	{
		/** @var Account $loggedInUser */
		$loggedInUser = $this->getUser();
		if (!($this->isGranted('ROLE_ADMIN')
			|| in_array($loggedInUser->getOwner(), $this->roomService->getRoomUsers($room, true))))
			throw $this->createAccessDeniedException();
		if (in_array(null, $newRoom->getRoomRoles()->toArray())
			|| $this->hasDuplicates($newRoom->getRoomRoles()->toArray()))
			return $this->handleView($this->view(['error' => 'Invalid data'], Response::HTTP_BAD_REQUEST));

		$this->roomService->updateRoles($room, $newRoom);
		return $this->routeGetRoomAttr($room, "users");
	}

	/**
	 * A dirty way to find duplicates among incoming room roles.
	 * @param RoomRole[] $roles
	 * @return bool
	 */
	private function hasDuplicates(array $roles): bool
	{
		for ($i = 0; $i < count($roles); $i++)
			for ($j = $i + 1; $j < count($roles); $j++)
				if ($roles[$i]->getUser() === $roles[$j]->getUser()
					|| ($roles[$i]->getRoleType() === $roles[$j]->getRoleType()
						&& $roles[$i]->getRoleType()->getName() === RoleType::ROLE_MANAGER))
					return true;
		return false;
	}
}
