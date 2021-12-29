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
	public function ActionGetTeams(): Response
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
	public function ActionGetTeamAttr(Team $team, string $attr): Response
	{
		switch ($attr) {
			case "rooms":
				$rooms = $this->findRooms($team);
				return $this->handleView($this->view($rooms, Response::HTTP_OK));

			case "users":
				$teams = $this->getChildrenRecursive($team);
				$users = [];
				foreach ($teams as $team)
					foreach ($team->getTeamRoles() as $role) {
						$user = $role->getUser();
						// array_unique would've been a better choice, but it uses string comparison
						// internally, i.e. (string)a == (string)b, hence isn't applicable.
						if (in_array($user, $users))
							continue;
						else
							$users[] = $role->getUser();
					}
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
		/*
		$rooms = $team -> getRooms () -> toArray ();
		// Terminating condition "hidden" inside foreach. If there are no children to this $team, $children will be empty
		// and foreach becomes a no-op.
		$children = $team -> getChildren (); // $this -> getDoctrine () -> getRepository ( Team::class ) -> findBy ( array ( "parent_id" => $team -> getId () ) );
		foreach ( $children as $child )
			$rooms = array_merge ( $rooms, $this -> findRooms ( $child ) );
		return $rooms;
		*/
	}
	//--------------------------------------------------------------------------------------------------------------------
	private function getChildrenRecursive(Team $team): array
	{
		// result includes the $team itself
		return array_reduce($team->getChildren()->toArray(),
			function (array $carry, Team $item) {
				return array_merge($carry, $this->getChildrenRecursive($item));
			},
			array($team));
	}
	//--------------------------------------------------------------------------------------------------------------------
}


?>
