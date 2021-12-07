<?php

namespace App\Entity;

use App\Repository\RequestRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinTable;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=RequestRepository::class)
 */
class Request
{
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=500)
	 *
	 * @Assert\Length(min=5, max=500)
	 */
	private $description;

	/**
	 * @ORM\Column(type="datetime")
	 *
	 * @Assert\NotBlank
	 * @Assert\DateTime
	 * @var string A "Y-m-d H:i:s" formatted value
	 */
	private $eventStart;

	/**
	 * @ORM\Column(type="datetime")
	 *
	 * @Assert\NotBlank
	 * @Assert\DateTime
	 * @var string A "Y-m-d H:i:s" formatted value
	 */
	private $eventEnd;

	/**
	 * @ORM\ManyToOne(targetEntity=Room::class, inversedBy="requests")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $room;

	/**
	 * @ORM\ManyToOne(targetEntity=User::class, inversedBy="requests")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $user;

	/**
	 * @ORM\ManyToMany(targetEntity=User::class, inversedBy="attendees")
	 * @JoinTable(name="attendee")
	 */
	private $attendees;

	/**
	 * @ORM\ManyToOne(targetEntity=Status::class, inversedBy="requests")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $status;

	public function __construct()
	{
		$this->attendees = new ArrayCollection();
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getDescription(): ?string
	{
		return $this->description;
	}

	public function setDescription(string $description): self
	{
		$this->description = $description;

		return $this;
	}

	public function getEventStart(): ?\DateTimeInterface
	{
		return $this->eventStart;
	}

	public function setEventStart(\DateTimeInterface $eventStart): self
	{
		$this->eventStart = $eventStart;

		return $this;
	}

	public function getEventEnd(): ?\DateTimeInterface
	{
		return $this->eventEnd;
	}

	public function setEventEnd(\DateTimeInterface $eventEnd): self
	{
		$this->eventEnd = $eventEnd;

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

	public function getUser(): ?User
	{
		return $this->user;
	}

	public function setUser(?User $user): self
	{
		$this->user = $user;

		return $this;
	}

	/**
	 * @return Collection|User[]
	 */
	public function getAttendees(): Collection
	{
		return $this->attendees;
	}

	public function addAttendee(User $attendee): self
	{
		if (!$this->attendees->contains($attendee)) {
			$this->attendees[] = $attendee;
		}

		return $this;
	}

	public function removeAttendee(User $attendee): self
	{
		$this->attendees->removeElement($attendee);

		return $this;
	}

	public function getStatus(): ?Status
	{
		return $this->status;
	}

	public function setStatus(?Status $status): self
	{
		$this->status = $status;

		return $this;
	}
}
