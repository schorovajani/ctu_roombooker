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
	//--------------------------------------------------------------------------------------------------------------------
	/**
	 * @Rest\Get("/rooms")
	 * @return Response
	 */
	public function routeGetRooms(): Response
	{
		$rooms = $this->service->getAll();
		return $this->handleView($this->view($rooms, Response::HTTP_OK));
	}
	//--------------------------------------------------------------------------------------------------------------------
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
				return $this->handleView($this->view($room->getRequests(), Response::HTTP_OK));

			case "users":
				$users = $this->service->getRoomUsers($room);
				return $this->handleView($this->view($users, Response::HTTP_OK));

			default:
				break;
		}
		throw $this->createNotFoundException();
	}
	//--------------------------------------------------------------------------------------------------------------------
}


?>
