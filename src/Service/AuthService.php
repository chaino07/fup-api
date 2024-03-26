<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\UserToken;

class AuthService
{
    private const TOKEN_HASH_ALGO = 'sha256';

    public function __construct(
        private string $appSecret
    ) {
    }

    public function generateUserToken(User $user): UserToken
    {
        $token = new UserToken;

        $token->setOwner($user);
        $token->setToken(hash(
            self::TOKEN_HASH_ALGO,
            join('', [
                microtime(true),
                $this->appSecret,
                random_bytes(32),
                $user->getUserIdentifier(),
            ])
        ));

        return $token;
    }
}
