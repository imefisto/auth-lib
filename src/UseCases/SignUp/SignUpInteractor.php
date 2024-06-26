<?php
namespace Imefisto\AuthLib\UseCases\SignUp;

use Imefisto\AuthLib\Domain\User;
use Imefisto\AuthLib\Domain\UserRepository;
use Imefisto\AuthLib\UseCases\SignUp\Validators\EmailValidator;

class SignUpInteractor implements SignUpInputPort
{
    public function __construct(
        private UserRepository $userRepository,
        private SignUpOutputPort $output,
        private ?SignUpValidator $validator = new EmailValidator()
    ) {
    }

    public function signUp(SignUpRequest $request): void
    {
        $validation = $this->validator->validate($request);
        if ($validation->hasErrors()) {
            $this->output->invalidData($validation);
            return;
        }

        if ($this->userRepository->existsByUsername($request->username)) {
            $this->output->userAlreadyExists($request->username);
            return;
        }

        $user = new User($request->username, $request->password);
        $userId = $this->userRepository->createUser($user);

        $this->output->userSignedUp(new SignUpResponse($userId));
    }
}
