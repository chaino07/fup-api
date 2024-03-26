<?php

namespace App\Entity;

use ApiPlatform\Metadata as API;
use App\Dto\UserLoginDto;
use App\Repository\UserTokenRepository;
use App\State\UserLoginProcessor;
use Doctrine\ORM\Mapping as ORM;

#[API\Post(input: UserLoginDto::class, processor: UserLoginProcessor::class)]
#[ORM\Entity(repositoryClass: UserTokenRepository::class)]
class UserToken
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $token = null;

    #[ORM\ManyToOne(inversedBy: 'tokens')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): static
    {
        $this->token = $token;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }
}
