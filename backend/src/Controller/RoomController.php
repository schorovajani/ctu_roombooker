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
	public function routeGetRooms(): Response
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
	public function routeGetRoomAttr(Room $room, string $attr): Response
	{
		switch ($attr) {
			case "requests":
				return $this->handleView($this->view($room->getRequests(), Response::HTTP_OK));

			case "users":
				$users1 = [];
				$roles = $room->getRoomRoles();
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
				$users = array_unique($users, SORT_REGULAR);
				// array_unique retains keys even when the origin array didn't have them, e.g. [1, 1, 2] becomes ["0" => 1, "2" => 2].
			  // Possible explanation: https://www.php.net/manual/en/function.array-unique.php
				// > [...] If multiple elements compare equal under the given flags, then the key and value of the first equal
				// > element will be retained.
			  // It is possible that other places where array_unique is used are affected
				$users = array_values($users);
				return $this->handleView($this->view($users, Response::HTTP_OK));

			default:
				break;
		}
		throw $this->createNotFoundException();
	}
	//--------------------------------------------------------------------------------------------------------------------
}


?>
