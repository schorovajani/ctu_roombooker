<?php

namespace App\Entity;

use App\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serialize;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TeamRepository::class)
 *
 * @Serialize\ExclusionPolicy("none")
 */
class Team
{
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 *
	 * @Serialize\Groups({"listTeam", "listTeamDetailed"})
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=255)
	 *
	 * @Assert\NotBlank
	 * @Assert\Length(min=2, max=255)
	 *
	 * @Serialize\Groups({"listTeam", "listTeamDetailed"})
	 */
	private $name;

	/**
	 * @ORM\OneToMany(targetEntity=Room::class, mappedBy="team")
	 *
	 * @Serialize\Groups({"listTeamDetailed"})
	 */
	private $rooms;

	/**
	 * @ORM\OneToMany(targetEntity=TeamRole::class, mappedBy="team")
	 *
	 * @Serialize\MaxDepth(2)
	 */
	private $teamRoles;

	/**
	 * @ORM\ManyToOne(targetEntity=Team::class, inversedBy="children")
	 *
	 * @Serialize\Groups({"listTeamDetailed"})
	 * @Serialize\MaxDepth(1)
	 */
	private $parent;

	/**
	 * @ORM\OneToMany(targetEntity=Team::class, mappedBy="parent")
	 *
	 * @Serialize\Groups({"listTeamDetailed"})
	 */
	private $children;

	public function __construct()
	{
		$this->rooms = new ArrayCollection();
		$this->teamRoles = new ArrayCollection();
		$this->children = new ArrayCollection();
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

	public function getParent(): ?self
	{
		return $this->parent;
	}

	public function setParent(?self $parent): self
	{
		$this->parent = $parent;

		return $this;
	}

	/**
	 * @return Collection|self[]
	 */
	public function getChildren(): Collection
	{
		return $this->children;
	}

	public function addChild(self $child): self
	{
		if (!$this->children->contains($child)) {
			$this->children[] = $child;
			$child->setParent($this);
		}

		return $this;
	}

	public function removeChild(self $child): self
	{
		if ($this->children->removeElement($child)) {
			// set the owning side to null (unless already changed)
			if ($child->getParent() === $this) {
				$child->setParent(null);
			}
		}

		return $this;
	}
}
