<?php
namespace App\Controller;

use App\Entity\Room;
use App\Service\RoomService;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class  RoomController extends \FOS\RestBundle\Controller\AbstractFOSRestController
{
	private RoomService $service;


	/**
	 * @param RoomService $service
	 */
	public function __construct(RoomService $service)
	{
		$this->service = $service;
	}

	/**
	 * @Rest\Get("/rooms")
	 * @return Response
	 */
	public function routeGetRooms(): Response
	{
		$rooms = $this->service->getAll();
		return $this->handleView($this->view($rooms, Response::HTTP_OK));
	}

	/**
	 * @Rest\Get("/rooms/{id}/{attr}", requirements={"id": "\d+"})
	 * @param Room $room
	 * @param string $attr
	 * @return Response
	 */
	public function routeGetRoomAttr(Room $room, string $attr): Response
	{
		switch ($attr) {
			case "requests":
				$viewData = $room->getRequests();
				break;

			case "users":
				$viewData = $this->service->getRoomUsers($room);
				break;

			default:
				throw $this->createNotFoundException();
		}
		return $this->handleView($this->view($viewData, Response::HTTP_OK));
	}
}


?>
