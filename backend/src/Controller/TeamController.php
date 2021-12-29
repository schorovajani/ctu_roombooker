<?php
namespace App\Controller;

use App\Entity\Team;
use App\Service\TeamService;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class  TeamController extends \FOS\RestBundle\Controller\AbstractFOSRestController
{
	private TeamService $service;


	/**
	 * @param TeamService $service
	 */
	public function __construct(TeamService $service)
	{
		$this->service = $service;
	}

	/**
	 * @Rest\Get("/teams")
	 * @return Response
	 */
	public function routeGetTeams(): Response
	{
		$teams = $this->service->getAll();
		return $this->handleView($this->view($teams, Response::HTTP_OK));
	}

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
				$rooms = $this->service->getTeamRooms($team);
				return $this->handleView($this->view($rooms, Response::HTTP_OK));

			case "users":
				$users = $this->service->getTeamMembers($team);
				return $this->handleView($this->view($users, Response::HTTP_OK));

			default:  // fallthrough to exception
				break;
		}
		throw $this->createNotFoundException();
	}
}


?>
