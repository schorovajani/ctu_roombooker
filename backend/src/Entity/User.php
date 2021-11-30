<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 *
 * @ExclusionPolicy("all")
 */
class User
{
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 *
	 * @Expose
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=255)
	 *
	 * @Assert\NotBlank
	 * @Assert\Length(min=2, max=255)
	 * @Expose
	 */
	private $firstName;

	/**
	 * @ORM\Column(type="string", length=255)
	 *
	 * @Assert\NotBlank
	 * @Assert\Length(min=2, max=255)
	 * @Expose
	 */
	private $lastName;

	/**
	 * @ORM\Column(type="boolean")
	 *
	 * @Assert\NotNull
	 * @Assert\Type("bool")
	 */
	private $isAdmin;

	/**
	 * @ORM\OneToMany(targetEntity=Request::class, mappedBy="user")
	 */
	private $requests;

	/**
	 * @ORM\ManyToMany(targetEntity=Request::class, mappedBy="attendees")
	 */
	private $attendees;

	/**
	 * @ORM\OneToMany(targetEntity=RoomRole::class, mappedBy="user")
	 */
	private $roomRoles;

	/**
	 * @ORM\OneToMany(targetEntity=TeamRole::class, mappedBy="user")
	 */
	private $teamRoles;

	public function __construct()
	{
		$this->requests = new ArrayCollection();
		$this->attendees = new ArrayCollection();
		$this->roomRoles = new ArrayCollection();
		$this->teamRoles = new ArrayCollection();
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getFirstName(): ?string
	{
		return $this->firstName;
	}

	public function setFirstName(string $firstName): self
	{
		$this->firstName = $firstName;

		return $this;
	}

	public function getLastName(): ?string
	{
		return $this->lastName;
	}

	public function setLastName(string $lastName): self
	{
		$this->lastName = $lastName;

		return $this;
	}

	public function getIsAdmin(): ?bool
	{
		return $this->isAdmin;
	}

	public function setIsAdmin(bool $isAdmin): self
	{
		$this->isAdmin = $isAdmin;

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
			$request->setUser($this);
		}

		return $this;
	}

	public function removeRequest(Request $request): self
	{
		if ($this->requests->removeElement($request)) {
			// set the owning side to null (unless already changed)
			if ($request->getUser() === $this) {
				$request->setUser(null);
			}
		}

		return $this;
	}

	/**
	 * @return Collection|Request[]
	 */
	public function getAttendees(): Collection
	{
		return $this->attendees;
	}

	public function addAttendee(Request $attendee): self
	{
		if (!$this->attendees->contains($attendee)) {
			$this->attendees[] = $attendee;
			$attendee->addAttendee($this);
		}

		return $this;
	}

	public function removeAttendee(Request $attendee): self
	{
		if ($this->attendees->removeElement($attendee)) {
			$attendee->removeAttendee($this);
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
			$roomRole->setUser($this);
		}

		return $this;
	}

	public function removeRoomRole(RoomRole $roomRole): self
	{
		if ($this->roomRoles->removeElement($roomRole)) {
			// set the owning side to null (unless already changed)
			if ($roomRole->getUser() === $this) {
				$roomRole->setUser(null);
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
			$teamRole->setUser($this);
		}

		return $this;
	}

	public function removeTeamRole(TeamRole $teamRole): self
	{
		if ($this->teamRoles->removeElement($teamRole)) {
			// set the owning side to null (unless already changed)
			if ($teamRole->getUser() === $this) {
				$teamRole->setUser(null);
			}
		}

		return $this;
	}
}
