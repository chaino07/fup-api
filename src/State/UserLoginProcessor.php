<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\UserLoginDto;
use App\Entity\UserToken;
use App\Repository\UserRepository;
use App\Service\AuthService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserLoginProcessor implements ProcessorInterface
{
    public function __construct(
        private AuthService $authService,
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $userPasswordHasher,
        private EntityManagerInterface $entityManager
    ) {
    }

    /**
     * @param UserLoginDto $data
     */
    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = []
    ): UserToken {
        $user = $this->userRepository->findOneBy(['email' => $data->email]);

        if (!$user) {
            throw new HttpException(
                Response::HTTP_NOT_FOUND,
                sprintf("The user '%s' does not exist", $data->email)
            );
        }

        if (!$this->userPasswordHasher->isPasswordValid($user, $data->password)) {
            throw new HttpException(
                Response::HTTP_UNAUTHORIZED,
                sprintf("The password could not be validated")
            );
        }

        $token = $this->authService->generateUserToken($user);

        $this->entityManager->persist($token);
        $this->entityManager->flush();

        return $token;
    }
}
