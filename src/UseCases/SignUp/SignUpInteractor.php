<?php
namespace Imefisto\AuthLib\UseCases\SignUp;

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

        $userId = $this->userRepository->createUser(
            $request->username,
            $request->password
        );

        $this->output->userSignedUp(new SignUpResponse($userId));
    }
}
