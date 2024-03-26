<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final class UserLoginDto
{
    #[Assert\Email()]
    #[Assert\NotBlank()]
    public readonly string $email;

    #[Assert\NotBlank()]
    public readonly string $password;

    public function __construct(string $email, string $password)
    {
        $this->email = $email;
        $this->password = $password;
    }
}