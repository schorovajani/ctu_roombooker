<?php

namespace App\Entity;

use App\Repository\RoomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RoomRepository::class)
 */
class Room
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
	 * @ORM\Column(type="boolean")
	 */
	private $isPublic;

	/**
	 * @ORM\Column(type="boolean")
	 */
	private $isLocked;

	/**
	 * @ORM\ManyToOne(targetEntity=Building::class, inversedBy="rooms")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $building;

	/**
	 * @ORM\ManyToOne(targetEntity=Group::class, inversedBy="rooms")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $owner;

	/**
	 * @ORM\OneToMany(targetEntity=Request::class, mappedBy="room")
	 */
	private $requests;

	public function __construct()
	{
		$this->requests = new ArrayCollection();
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

	public function getOwner(): ?Group
	{
		return $this->owner;
	}

	public function setOwner(?Group $owner): self
	{
		$this->owner = $owner;

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
}
