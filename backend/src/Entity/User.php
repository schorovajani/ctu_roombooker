<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User
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
	private $firstName;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $lastName;

	/**
	 * @ORM\Column(type="boolean")
	 */
	private $isAdmin;

	/**
	 * @ORM\OneToMany(targetEntity=Request::class, mappedBy="user")
	 */
	private $requests;

	/**
	 * @ORM\ManyToMany(targetEntity=Request::class, mappedBy="attendee")
	 */
	private $attendee;

	public function __construct()
	{
		$this->requests = new ArrayCollection();
		$this->attendee = new ArrayCollection();
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
	public function getAttendee(): Collection
	{
		return $this->attendee;
	}

	public function addAttendee(Request $attendee): self
	{
		if (!$this->attendee->contains($attendee)) {
			$this->attendee[] = $attendee;
			$attendee->addAttendee($this);
		}

		return $this;
	}

	public function removeAttendee(Request $attendee): self
	{
		if ($this->attendee->removeElement($attendee)) {
			$attendee->removeAttendee($this);
		}

		return $this;
	}
}
