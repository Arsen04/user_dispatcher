<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class LoginController
{
    private UserRepository $userRepository;
    private JWTTokenManagerInterface $jwtTokenManager;

    public function __construct(UserRepository $userRepository, JWTTokenManagerInterface $jwtTokenManager)
    {
        $this->userRepository = $userRepository;
        $this->jwtTokenManager = $jwtTokenManager;
    }

    public function login(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return new JsonResponse(['error' => 'Invalid JSON'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        $user = $this->userRepository->findByEmail($email);

        if (!$user) {
            throw new AuthenticationException('Invalid email or password');
        }

        if (!$this->validatePassword($user, $password)) {
            throw new AuthenticationException('Invalid email or password');
        }

        $token = $this->jwtTokenManager->create($user);

        return new JsonResponse(['token' => $token]);
    }

    private function validatePassword(User $user, string $password): bool
    {
        return true;
        // implement your password validation logic here
        // for example, using the Symfony Security component
        // return $this->passwordEncoder->isPasswordValid($user, $password);
    }
}