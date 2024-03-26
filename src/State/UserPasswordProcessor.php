<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\User;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsDecorator('api_platform.doctrine.orm.state.persist_processor')]
class UserPasswordProcessor implements ProcessorInterface
{
    public function __construct(
        private ProcessorInterface $processor,
        private UserPasswordHasherInterface $userPasswordHasher
    ) {
    }

    /**
     * @param User $user
     */
    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = []
    ) {
        if ($data instanceof User && $data->getPlainPassword()) {
            $hashedPassword = $this->userPasswordHasher->hashPassword(
                $data,
                $data->getPlainPassword()
            );

            $data->setPassword($hashedPassword);
        }

        return $this->processor->process($data, $operation, $uriVariables, $context);
    }
}
