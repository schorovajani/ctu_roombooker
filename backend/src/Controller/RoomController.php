<?php
namespace App\Controller;

use App\Entity\Room;
use App\Entity\RoomRole;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class  RoomController extends \FOS\RestBundle\Controller\AbstractFOSRestController
{
	//--------------------------------------------------------------------------------------------------------------------
	/**
	 * @Rest\Get("/rooms")
	 * @return Response
	 */
	public function ActionGetRooms(): Response
	{
		$rooms = $this->getDoctrine()->getRepository(Room::class)->findAll();
		return $this->handleView($this->view($rooms, Response::HTTP_OK));
	}
	//--------------------------------------------------------------------------------------------------------------------
	/**
	 * @Rest\Get("/rooms/{id}/{attr}", requirements={"id": "\d+"})
	 * @param Room $room
	 * @param string $attr
	 * @return Response
	 */
	public function ActionGetRoomAttr(Room $room, string $attr): Response
	{
		switch ($attr) {
			case "requests":
				return $this->handleView($this->view($room->getRequests(), Response::HTTP_OK));

			case "users":
				$users1 = [];
				$roles = $this->getDoctrine()->getRepository(RoomRole::class)->findBy(["room" => $room]);
				foreach ($roles as $role)
					$users1[] = $role->getUser();

				$users2 = [];
				$team = $room->getTeam();
				while ($team !== null) {
					$members = [];
					foreach ($team->getTeamRoles() as $role)
						$members[] = $role->getUser();
					$users2 = array_merge($users2, $members);
					$team = $team->getParent();
				}

				$users = array_merge($users1, $users2);
				return $this->handleView($this->view($users, Response::HTTP_OK));

			default:
				break;
		}
		throw $this->createNotFoundException();
	}
	//--------------------------------------------------------------------------------------------------------------------
}


?>
