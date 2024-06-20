<?php
namespace Imefisto\AuthLib\UseCases\SignUp;

use Imefisto\AuthLib\Domain\UserRepository;

class SignUpInteractor implements SignUpInputPort
{
    public function __construct(
        private UserRepository $userRepository,
        private SignUpOutputPort $output
    ) {
    }

    public function signUp(SignUpRequest $request): void
    {
        if (!filter_var($request->username, FILTER_VALIDATE_EMAIL)) {
            $this->output->invalidUsername($request->username);
            return;
        }

        if ($this->userRepository->existsByUsername($request->username)) {
            $this->output->userAlreadyExists($request->username);
            return;
        }

        $userId = $this->userRepository->createUser(
            $request->username,
            $request->password
        );

        $this->output->userSignedUp(new SignUpResponse($userId));
    }
}
