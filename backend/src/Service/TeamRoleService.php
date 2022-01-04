<?php

namespace App\Service;

use App\Entity\TeamRole;
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
	 * @param TeamRole $teamRole
	 * @return void
	 */
	public function delete(TeamRole $teamRole): void
	{
		$this->entityManager->remove($teamRole);
		$this->entityManager->flush();
	}

	/**
	 * Mirror repository's `find` method.
	 *
	 * @param array $ids
	 * @param $lockMode
	 * @param $lockVersion
	 * @return TeamRole|null
	 */
	public function get(array $ids, $lockMode = null, $lockVersion = null): ?TeamRole
	{
		return $this->teamRoleRepository->find($ids, $lockMode, $lockVersion);
	}

	/**
	 * @param TeamRole $teamRole
	 * @return void
	 */
	public function save(TeamRole $teamRole): void
	{
		$this->entityManager->persist($teamRole);
		$this->entityManager->flush();
	}
}
