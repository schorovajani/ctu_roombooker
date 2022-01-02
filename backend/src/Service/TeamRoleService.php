<?php

namespace App\Service;

use App\Entity\Team;
use App\Entity\User;
use App\Repository\TeamRoleRepository;
use Doctrine\ORM\EntityManagerInterface;

class TeamRoleService
{
	private TeamRoleRepository $teamRoleRepository;
	private EntityManagerInterface $entityManager;

	/**
	 * @param EntityManagerInterface $entityManager
	 * @param TeamRoleRepository $teamRoleRepository
	 */
	public function __construct(EntityManagerInterface $entityManager, TeamRoleRepository $teamRoleRepository)
	{
		$this->teamRoleRepository = $teamRoleRepository;
		$this->entityManager = $entityManager;
	}

	/**
	 * @param Team $team
	 * @param User $user
	 * @return void
	 */
	public function removeTeamRole(Team $team, User $user): void
	{
		$teamRole = $this->teamRoleRepository->find(['user' => $user, 'team' => $team]);
		$this->entityManager->remove($teamRole);
		$this->entityManager->flush();
	}
}
