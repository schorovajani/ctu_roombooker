<?php

namespace App\Entity;

use App\Repository\BuildingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serialize;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=BuildingRepository::class)
 *
 * @Serialize\ExclusionPolicy("all")
 */
class Building
{
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 *
	 * @Serialize\Expose
	 * @Serialize\Groups({"listBuilding"})
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=255)
	 *
	 * @Assert\NotBlank
	 * @Assert\Length(min=2, max=255)
	 *
	 * @Serialize\Expose
	 * @Serialize\Groups({"listBuilding"})
	 */
	private $name;

	/**
	 * @ORM\OneToMany(targetEntity=Room::class, mappedBy="building")
	 *
	 * @Serialize\Expose
	 * @Serialize\MaxDepth(1)
	 */
	private $rooms;

	public function __construct()
	{
		$this->rooms = new ArrayCollection();
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function setId(int $id): self
	{
		$this->id = $id;

		return $this;
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
			$room->setBuilding($this);
		}

		return $this;
	}

	public function removeRoom(Room $room): self
	{
		if ($this->rooms->removeElement($room)) {
			// set the owning side to null (unless already changed)
			if ($room->getBuilding() === $this) {
				$room->setBuilding(null);
			}
		}

		return $this;
	}
}
