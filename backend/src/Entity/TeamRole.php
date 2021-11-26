<?php

namespace App\Entity;

use App\Repository\TeamRoleRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TeamRoleRepository::class)
 */
class TeamRole
{
	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity=User::class, inversedBy="teamRoles")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $user;

	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity=Team::class, inversedBy="teamRoles")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $team;

	/**
	 * @ORM\ManyToOne(targetEntity=RoleType::class)
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $roleType;

	public function getUser(): ?User
	{
		return $this->user;
	}

	public function setUser(?User $user): self
	{
		$this->user = $user;

		return $this;
	}

	public function getTeam(): ?Team
	{
		return $this->team;
	}

	public function setTeam(?Team $team): self
	{
		$this->team = $team;

		return $this;
	}

	public function getRoleType(): ?RoleType
	{
		return $this->roleType;
	}

	public function setRoleType(?RoleType $roleType): self
	{
		$this->roleType = $roleType;

		return $this;
	}
}
