<?php
namespace Imefisto\AuthLib\Testing\UseCases;

use Imefisto\AuthLib\Domain\UserId;
use Imefisto\AuthLib\Domain\UserRepository;
use Imefisto\AuthLib\UseCases\SignUp\SignUpInteractor;
use Imefisto\AuthLib\UseCases\SignUp\SignUpInputPort;
use Imefisto\AuthLib\UseCases\SignUp\SignUpOutputPort;
use Imefisto\AuthLib\UseCases\SignUp\SignUpRequest;
use Imefisto\AuthLib\UseCases\SignUp\SignUpResponse;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

#[CoversClass(SignUpInteractor::class)]
class SignUpInteractorTest extends TestCase
{
    private MockObject $userRepository;
    private MockObject $output;
    private MockObject $validator;
    private SignUpInteractor $interactor;

    protected function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->output = $this->createMock(SignUpOutputPort::class);
        $this->interactor = new SignUpInteractor(
            $this->userRepository,
            $this->output
        );
    }

    public function testSignUpWithValidData(): void
    {
        $username = 'user@example.com';
        $password = 'some-password';
        $id = 'some-user-id';

        $request = new SignUpRequest($username, $password);

        $this->userRepository->expects($this->once())
                             ->method('existsByUsername')
                             ->with($username)
                             ->willReturn(false);

        $this->userRepository->expects($this->once())
                             ->method('createUser')
                             ->with($username, $password)
                             ->willReturn(new UserId($id));

        $this->output->expects($this->once())
                     ->method('userSignedUp')
                     ->with($this->callback(
                         function (SignUpResponse $response) use ($id) {
                             return (string) $response->userId === $id;
                         })
                     );

        $this->interactor->signUp($request);
    }

    public function testSignUpWithExistingUser(): void
    {
        $username = 'user@example.com';
        $password = 'some-password';

        $request = new SignUpRequest($username, $password);

        $this->userRepository->expects($this->once())
                             ->method('existsByUsername')
                             ->with($username)
                             ->willReturn(true);

        $this->userRepository->expects($this->never())
                             ->method('createUser');

        $this->output->expects($this->once())
                     ->method('userAlreadyExists')
                     ->with($username);

        $this->interactor->signUp($request);
    }

    public function testSignUpWithInvalidUsername(): void
    {
        $username = 'invalid-email';
        $request = new SignUpRequest($username, 'securepassword');

        $this->userRepository->expects($this->never())
                             ->method('existsByUsername');

        $this->userRepository->expects($this->never())
                             ->method('createUser');

        $this->output->expects($this->once())
                     ->method('invalidUsername')
                     ->with($username);

        $this->interactor->signUp($request);
    }
}
