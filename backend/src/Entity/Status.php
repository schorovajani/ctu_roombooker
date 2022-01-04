<?php

namespace App\Entity;

use App\Repository\StatusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serialize;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=StatusRepository::class)
 *
 * @Serialize\ExclusionPolicy("all")
 */
class Status
{
	const PENDING_ID = 1;

	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 *
	 * @Serialize\Expose
	 * @Serialize\Groups({"listStatus"})
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=255)
	 *
	 * @Assert\NotBlank
	 * @Assert\Length(min=3, max=255)
	 *
	 * @Serialize\Expose
	 * @Serialize\Groups({"listStatus"})
	 */
	private $name;

	/**
	 * @ORM\OneToMany(targetEntity=Request::class, mappedBy="status")
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
			$request->setStatus($this);
		}

		return $this;
	}

	public function removeRequest(Request $request): self
	{
		if ($this->requests->removeElement($request)) {
			// set the owning side to null (unless already changed)
			if ($request->getStatus() === $this) {
				$request->setStatus(null);
			}
		}

		return $this;
	}
}
