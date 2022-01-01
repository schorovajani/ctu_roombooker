<?php

namespace App\Entity;

use App\Repository\RoleTypeRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serialize;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=RoleTypeRepository::class)
 *
 * @Serialize\ExclusionPolicy("none")
 */
class RoleType
{
	const ROLE_MANAGER = 'Manager';

	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 *
	 * @Serialize\Groups({"listRoomRole", "listTeamRole"})
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=255)
	 *
	 * @Assert\NotBlank
	 * @Assert\Length(min=5, max=255)
	 *
	 * @Serialize\Groups({"listRoomRole", "listTeamRole"})
	 */
	private $name;

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
}
