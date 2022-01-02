<?php

namespace App\Controller;

use App\Entity\Room;
use App\Service\RoomService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
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

	/**
	 * @param RoomService $roomService
	 */
	public function __construct(RoomService $roomService)
	{
		$this->roomService = $roomService;
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
				$view = $this->view($this->roomService->getRoomUsers($room), Response::HTTP_OK);
				$view->getContext()->setGroups(['listRoom', 'listRoomRole', 'listUser']);
				return $this->handleView($view);

			default:
				throw $this->createNotFoundException();
		}
	}

	/**
	 * @Route("/rooms/{id}", requirements={"id": "\d+"}, methods={"DELETE"})
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
}
