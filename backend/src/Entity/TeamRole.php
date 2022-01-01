<?php

namespace App\Entity;

use App\Repository\TeamRoleRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serialize;

/**
 * @ORM\Entity(repositoryClass=TeamRoleRepository::class)
 *
 * @Serialize\ExclusionPolicy("all")
 */
class TeamRole
{
	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity=User::class, inversedBy="teamRoles")
	 * @ORM\JoinColumn(nullable=false)
	 *
	 * @Serialize\Expose
	 */
	private $user;

	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity=Team::class, inversedBy="teamRoles")
	 * @ORM\JoinColumn(nullable=false)
	 *
	 * @Serialize\Expose
	 * @Serialize\Groups({"listTeamRole"})
	 */
	private $team;

	/**
	 * @ORM\ManyToOne(targetEntity=RoleType::class)
	 * @ORM\JoinColumn(nullable=false)
	 *
	 * @Serialize\Expose
	 * @Serialize\Groups({"listTeamRole"})
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
