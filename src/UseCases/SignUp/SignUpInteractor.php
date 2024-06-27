<?php
namespace Imefisto\AuthLib\UseCases\SignUp;

use Imefisto\AuthLib\Domain\UserRepository;
use Imefisto\AuthLib\UseCases\SignUp\Validators\EmailValidator;

class SignUpInteractor implements SignUpInputPort
{
    public function __construct(
        private UserRepository $userRepository,
        private SignUpOutputPort $output,
        private ?SignUpValidator $validator = new EmailValidator(),
        private ?SignUpUserFactory $userFactory = new SignUpUserFactory()
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

        $user = $this->userFactory->createUserFromRequest($request);
        $userId = $this->userRepository->createUser($user);

        $this->output->userSignedUp(new SignUpResponse($userId));
    }
}
