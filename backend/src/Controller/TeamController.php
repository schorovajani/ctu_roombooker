<?php

namespace App\Controller;

use App\Entity\RoleType;
use App\Entity\Team;
use App\Entity\User;
use App\Service\TeamRoleService;
use App\Service\TeamService;
use App\Service\UserService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * @Route("/api")
 */
class TeamController extends AbstractFOSRestController
{
	private TeamRoleService $teamRoleService;
	private TeamService $teamService;
	private UserService $userService;

	/**
	 * @param TeamService $teamService
	 * @param TeamRoleService $teamRoleService
	 * @param UserService $userService
	 */
	public function __construct(TeamService $teamService, TeamRoleService $teamRoleService, UserService $userService)
	{
		$this->teamRoleService = $teamRoleService;
		$this->teamService = $teamService;
		$this->userService = $userService;
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
		$view->getContext()->setGroups(['listBuilding', 'listRoom', 'listTeam', 'listTeamDetails']);
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
				$this->denyAccessUnlessGranted('GET_TEAM_USERS', $team);
				$users = $this->teamService->getTeamMembers($team);
				foreach ($users as $user)
					$this->userService->filterUserRolesByTeam($user, $team);
				$viewData = $users;
				break;

			default:
				throw $this->createNotFoundException();
		}
		$view = $this->view($viewData, Response::HTTP_OK);
		$view->getContext()->setGroups(['listBuilding', 'listRoom', 'listTeam', 'listTeamRole', 'listUser']);
		return $this->handleView($view);
	}

	/**
	 * @Rest\Delete("/teams/{id}", requirements={"id": "\d+"})
	 * @IsGranted("ROLE_ADMIN")
	 *
	 * @param Team $team
	 * @return Response
	 */
	public function routeDeleteTeam(Team $team): Response
	{
		if (!$team->getRooms()->isEmpty())
			return $this->handleView($this->view(['error' => 'Delete or reassign rooms to a different team first'], Response::HTTP_BAD_REQUEST));
		if (!$team->getChildren()->isEmpty())
			return $this->handleView($this->view(['error' => 'Delete or reassign children to a different team first'], Response::HTTP_BAD_REQUEST));

		$this->teamService->delete($team);
		return $this->handleView($this->view(null, Response::HTTP_NO_CONTENT));
	}

	/**
	 * @Rest\Delete("/teams/{id}/users/{user_id}", requirements={"id": "\d+", "user_id": "\d+"})
	 * @Entity("user", expr="repository.find(user_id)")
	 * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
	 *
	 * @param Team $team
	 * @param User $user
	 * @return Response
	 */
	public function routeDeleteUserTeamRole(Team $team, User $user): Response
	{
		$teamRole = $this->teamRoleService->get(['user' => $user, 'team' => $team]);
		if (!$this->isGranted('ROLE_ADMIN'))
			if ($teamRole->getRoleType()->getName() === RoleType::ROLE_MANAGER
				|| !in_array($this->getUser()->getOwner(), $this->teamService->getTeamManagers($team)))
				throw $this->createAccessDeniedException();

		$this->teamRoleService->delete($teamRole);
		return $this->handleView($this->view(null, Response::HTTP_NO_CONTENT));
	}

	/**
	 * @Rest\Post("/teams")
	 * @IsGranted("ROLE_ADMIN")
	 * @ParamConverter("team", converter="fos_rest.request_body")
	 *
	 * @param Team $team
	 * @param ConstraintViolationListInterface $validationErrors
	 * @return Response
	 */
	public function routePostTeam(Team $team, ConstraintViolationListInterface $validationErrors): Response
	{
		if (count($validationErrors) > 0)
			return $this->handleView($this->view(["error" => $validationErrors], Response::HTTP_BAD_REQUEST));
		if (in_array(null, $team->getRooms()))
			return $this->handleView($this->view(["error" => "Some of the room were invalid"], Response::HTTP_BAD_REQUEST));

		$this->teamService->save($team);
		$view = $this->view($team, Response::HTTP_CREATED);
		$view->getContext()->setGroups(['listBuilding', 'listRoom', 'listTeam', 'listTeamDetails']);
		return $this->handleView($view);
	}

	/**
	 * @Rest\Put("/teams/{id}", requirements={"id": "\d+"})
	 * @ParamConverter("team")
	 * @ParamConverter("newTeam", converter="fos_rest.request_body")
	 * @IsGranted("ROLE_ADMIN")
	 *
	 * @param Team $team
	 * @param Team $newTeam
	 * @param ConstraintViolationListInterface $validationErrors
	 * @return Response
	 */
	public function routePutTeam(Team $team, Team $newTeam, ConstraintViolationListInterface $validationErrors): Response
	{
		if (count($validationErrors) > 0)
			return $this->handleView($this->view(['error' => $validationErrors], Response::HTTP_BAD_REQUEST));

		if ($newTeam->getParent() === null || in_array(null, $newTeam->getRooms()->toArray()))
			return $this->handleView($this->view(null, Response::HTTP_BAD_REQUEST));

		$this->teamService->update($team, $newTeam);

		$view = $this->view($team, Response::HTTP_OK);
		$view->getContext()->setGroups(['listBuilding', 'listRoom', 'listTeam', 'listTeamDetails']);
		return $this->handleView($view);
	}
}
