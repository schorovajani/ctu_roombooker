<?php

namespace App\Entity;

use App\Repository\RoomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serialize;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=RoomRepository::class)
 *
 * @Serialize\ExclusionPolicy("all")
 * @Serialize\Exclude(if="!is_granted('IS_AUTHENTICATED_REMEMBERED') && !object.getIsPublic()")
 */
class Room
{
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 *
	 * @Serialize\Expose
	 * @Serialize\Groups({"listRoom"})
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=255)
	 *
	 * @Assert\NotBlank
	 * @Assert\Length(min=3, max=255)
	 *
	 * @Serialize\Expose
	 * @Serialize\Groups({"listRoom"})
	 */
	private $name;

	/**
	 * @ORM\Column(type="boolean")
	 *
	 * @Assert\NotNull
	 * @Assert\Type("bool")
	 *
	 * @Serialize\Expose
	 * @Serialize\Groups({"listRoom"})
	 */
	private $isPublic;

	/**
	 * @ORM\Column(type="boolean")
	 *
	 * @Assert\NotNull
	 * @Assert\Type("bool")
	 */
	private $isLocked;

	/**
	 * @ORM\ManyToOne(targetEntity=Building::class, inversedBy="rooms")
	 * @ORM\JoinColumn(nullable=false)
	 *
	 * @Serialize\Expose
	 * @Serialize\Groups({"listRoom"})
	 * @Serialize\MaxDepth(1)
	 */
	private $building;

	/**
	 * @ORM\ManyToOne(targetEntity=Team::class, inversedBy="rooms")
	 * @ORM\JoinColumn(nullable=false)
	 *
	 * @Serialize\Expose
	 * @Serialize\Groups({"listTeam"})
	 * @Serialize\MaxDepth(1)
	 */
	private $team;

	/**
	 * @ORM\OneToMany(targetEntity=Request::class, mappedBy="room")
	 */
	private $requests;

	/**
	 * @ORM\OneToMany(targetEntity=RoomRole::class, mappedBy="room")
	 */
	private $roomRoles;

	public function __construct()
	{
		$this->requests = new ArrayCollection();
		$this->roomRoles = new ArrayCollection();
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

	public function getIsPublic(): ?bool
	{
		return $this->isPublic;
	}

	public function setIsPublic(bool $isPublic): self
	{
		$this->isPublic = $isPublic;

		return $this;
	}

	public function getIsLocked(): ?bool
	{
		return $this->isLocked;
	}

	public function setIsLocked(bool $isLocked): self
	{
		$this->isLocked = $isLocked;

		return $this;
	}

	public function getBuilding(): ?Building
	{
		return $this->building;
	}

	public function setBuilding(?Building $building): self
	{
		$this->building = $building;

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

	/**
	 * @return Collection|Request[]
	 */
	public function getRequests(): Collection
	{
		return $this->requests;
	}

	public function addRequest(Request $request): self
	{
		if (!$this->requests->contains($request)) {
			$this->requests[] = $request;
			$request->setRoom($this);
		}

		return $this;
	}

	public function removeRequest(Request $request): self
	{
		if ($this->requests->removeElement($request)) {
			// set the owning side to null (unless already changed)
			if ($request->getRoom() === $this) {
				$request->setRoom(null);
			}
		}

		return $this;
	}

	/**
	 * @return Collection|RoomRole[]
	 */
	public function getRoomRoles(): Collection
	{
		return $this->roomRoles;
	}

	public function addRoomRole(RoomRole $roomRole): self
	{
		if (!$this->roomRoles->contains($roomRole)) {
			$this->roomRoles[] = $roomRole;
			$roomRole->setRoom($this);
		}

		return $this;
	}

	public function removeRoomRole(RoomRole $roomRole): self
	{
		if ($this->roomRoles->removeElement($roomRole)) {
			// set the owning side to null (unless already changed)
			if ($roomRole->getRoom() === $this) {
				$roomRole->setRoom(null);
			}
		}

		return $this;
	}
}
