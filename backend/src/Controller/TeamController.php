<?php
namespace App\Controller;

use App\Entity\Team;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class  TeamController extends \FOS\RestBundle\Controller\AbstractFOSRestController
{
	//--------------------------------------------------------------------------------------------------------------------
	/**
	 * @Rest\Get("/teams")
	 * @return Response
	 */
	public function routeGetTeams(): Response
	{
		$teams = $this->getDoctrine()->getRepository(Team::class)->findAll();
		return $this->handleView($this->view($teams, Response::HTTP_OK));
	}
	//--------------------------------------------------------------------------------------------------------------------
	/**
	 * @Rest\Get("/teams/{id}/{attr}", requirements={"id": "\d+"})
	 * @param Team $team
	 * @param string $attr
	 * @return Response
	 */
	public function routeGetTeamAttr(Team $team, string $attr): Response
	{
		switch ($attr) {
			case "rooms":
				$rooms = $this->findRooms($team);
				return $this->handleView($this->view($rooms, Response::HTTP_OK));

			case "users":
				$teams = $this->getChildrenRecursive($team);
				$users = [];
				foreach ($teams as $team)
					foreach ($team->getTeamRoles() as $role)
						$users[] = $role->getUser();
				$users = array_unique($users, SORT_REGULAR);
				return $this->handleView($this->view($users, Response::HTTP_OK));

			default:  // fallthrough to exception
				break;
		}
		throw $this->createNotFoundException();
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


?>
