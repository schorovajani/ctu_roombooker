<?php

namespace App\Entity;

use App\Repository\RoomRoleRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serialize;

/**
 * @ORM\Entity(repositoryClass=RoomRoleRepository::class)
 *
 * @Serialize\ExclusionPolicy("none")
 */
class RoomRole
{
	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity=User::class, inversedBy="roomRoles")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $user;

	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity=Room::class, inversedBy="roomRoles")
	 * @ORM\JoinColumn(nullable=false)
	 *
	 * @Serialize\Groups({"listRoomRole"})
	 */
	private $room;

	/**
	 * @ORM\ManyToOne(targetEntity=RoleType::class)
	 * @ORM\JoinColumn(nullable=false)
	 *
	 * @Serialize\Groups({"listRoomRole"})
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

	public function getRoom(): ?Room
	{
		return $this->room;
	}

	public function setRoom(?Room $room): self
	{
		$this->room = $room;

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
