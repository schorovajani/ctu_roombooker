<?php

namespace App\Entity;

use App\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TeamRepository::class)
 */
class Team
{
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $name;

	/**
	 * @ORM\OneToMany(targetEntity=Room::class, mappedBy="team")
	 */
	private $rooms;

	/**
	 * @ORM\OneToMany(targetEntity=TeamRole::class, mappedBy="team")
	 */
	private $teamRoles;

	public function __construct()
	{
		$this->rooms = new ArrayCollection();
		$this->teamRoles = new ArrayCollection();
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getName(): ?string
	{
		return $this->name;
	}

	public function setName(string $name): self
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * @return Collection|Room[]
	 */
	public function getRooms(): Collection
	{
		return $this->rooms;
	}

	public function addRoom(Room $room): self
	{
		if (!$this->rooms->contains($room)) {
			$this->rooms[] = $room;
			$room->setTeam($this);
		}

		return $this;
	}

	public function removeRoom(Room $room): self
	{
		if ($this->rooms->removeElement($room)) {
			// set the owning side to null (unless already changed)
			if ($room->getTeam() === $this) {
				$room->setTeam(null);
			}
		}

		return $this;
	}

	/**
	 * @return Collection|TeamRole[]
	 */
	public function getTeamRoles(): Collection
	{
		return $this->teamRoles;
	}

	public function addTeamRole(TeamRole $teamRole): self
	{
		if (!$this->teamRoles->contains($teamRole)) {
			$this->teamRoles[] = $teamRole;
			$teamRole->setTeam($this);
		}

		return $this;
	}

	public function removeTeamRole(TeamRole $teamRole): self
	{
		if ($this->teamRoles->removeElement($teamRole)) {
			// set the owning side to null (unless already changed)
			if ($teamRole->getTeam() === $this) {
				$teamRole->setTeam(null);
			}
		}

		return $this;
	}
}
