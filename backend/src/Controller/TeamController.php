<?php

namespace App\Controller;

use App\Entity\Team;
use App\Service\TeamService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class TeamController extends AbstractFOSRestController
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
	 * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
	 *
	 * @return Response
	 */
	public function routeGetTeams(): Response
	{
		$view = $this->view($this->teamService->getAll(), Response::HTTP_OK);
		$view->getContext()->setGroups(['listBuilding', 'listRoom', 'listTeamDetailed']);
		return $this->handleView($view);
	}

	/**
	 * @Rest\Get("/teams/{id}/{attr}", requirements={"id": "\d+"})
	 * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
	 *
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
		$view = $this->view($viewData, Response::HTTP_OK);
		$view->getContext()->setGroups(['listBuilding', 'listRoom', 'listTeam', 'listTeamRole', 'listUser']);
		return $this->handleView($view);
	}
}
