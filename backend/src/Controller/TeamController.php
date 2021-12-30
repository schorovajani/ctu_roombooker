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
	private TeamService $teamService;


	/**
	 * @param TeamService $teamService
	 */
	public function __construct(TeamService $teamService)
	{
		$this->teamService = $teamService;
	}

	/**
	 * @Rest\Get("/teams")
	 * @return Response
	 */
	public function routeGetTeams(): Response
	{
		$teams = $this->teamService->getAll();
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
				$viewData = $this->teamService->getTeamRooms($team);
				break;

			case "users":
				$viewData = $this->teamService->getTeamMembers($team);
				break;

			default:
				throw $this->createNotFoundException();
		}
		return $this->handleView($this->view($viewData, Response::HTTP_OK));
	}
}


?>
