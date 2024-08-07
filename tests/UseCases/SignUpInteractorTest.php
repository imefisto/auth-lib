<?php
namespace Imefisto\AuthLib\Testing\UseCases;

use Imefisto\AuthLib\Domain\BasicRoles;
use Imefisto\AuthLib\Domain\Role;
use Imefisto\AuthLib\Domain\RoleList;
use Imefisto\AuthLib\Domain\User;
use Imefisto\AuthLib\Domain\UserId;
use Imefisto\AuthLib\Domain\UserRepository;
use Imefisto\AuthLib\Domain\ValidationResult;
use Imefisto\AuthLib\UseCases\SignUp\SignUpInteractor;
use Imefisto\AuthLib\UseCases\SignUp\SignUpInputPort;
use Imefisto\AuthLib\UseCases\SignUp\SignUpOutputPort;
use Imefisto\AuthLib\UseCases\SignUp\SignUpRequest;
use Imefisto\AuthLib\UseCases\SignUp\SignUpResponse;
use Imefisto\AuthLib\UseCases\SignUp\SignUpUserFactory;
use Imefisto\AuthLib\UseCases\SignUp\SignUpValidator;
use Imefisto\AuthLib\UseCases\SignUp\Validators\EmailValidator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

#[CoversClass(SignUpInteractor::class)]
class SignUpInteractorTest extends TestCase
{
    private MockObject $userRepository;
    private MockObject $output;
    private ?SignUpValidator $validator = null;
    private ?SignUpUserFactory $userFactory = null;

    protected function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->userFactory = new SignUpUserFactory();
        $this->output = $this->createMock(SignUpOutputPort::class);
    }

    public function testSignUpWithValidData(): void
    {
        $username = 'user@example.com';
        $password = 'some-password';
        $id = 'some-user-id';

        $user = (new User($username))
            ->hashPassword($password);

        $request = new SignUpRequest($username, $password);

        $this->userRepository->expects($this->once())
                             ->method('existsByUsername')
                             ->with($username)
                             ->willReturn(false);

        $this->userRepository->expects($this->once())
                             ->method('createUser')
                             ->with($this->callback(
                                 function (User $user) use ($username, $password) {
                                     return $user->username === $username
                                         && $user->passwordMatches($password);
                                 })
                             )
                             ->willReturn(new UserId($id));

        $this->output->expects($this->once())
                     ->method('userSignedUp')
                     ->with($this->callback(
                         function (SignUpResponse $response) use ($id) {
                             return (string) $response->userId === $id;
                         })
                     );

        $this->interactor()->signUp($request);
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

        $this->interactor()->signUp($request);
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
                     ->method('invalidData')
                     ->with($this->callback(
                         function (ValidationResult $validation) use ($username) {
                             return $validation->getErrors() === ['username' => ["{$username} is not a valid email"]];
                         }));

        $this->interactor()->signUp($request);
    }

    public function testSignUpWithDefaultRole(): void
    {
        $username = 'user@example.com';
        $password = 'some-password';

        $request = new SignUpRequest($username, $password);

        $this->userRepository->method('existsByUsername')
                             ->willReturn(false);

        $this->userRepository->expects($this->once())
                             ->method('createUser')
                             ->with($this->callback(
                                 function (User $user) use ($username, $password) {
                                     return $user->username === $username
                                         && $user->passwordMatches($password)
                                         && $user->getRole() === BasicRoles::User;
                                 })
                             );

        $this->interactor()->signUp($request);
    }

    public function testSignUpWithRolePassedInRequest(): void
    {
        $username = 'user@example.com';
        $password = 'some-password';

        $request = (new SignUpRequest(
            $username,
            $password
        ))->withRole(BasicRoles::Admin->value);

        $this->userRepository->method('existsByUsername')
                             ->willReturn(false);

        $this->userRepository->expects($this->once())
                             ->method('createUser')
                             ->with($this->callback(
                                 function (User $user) use ($username, $password) {
                                     return $user->username === $username
                                         && $user->passwordMatches($password)
                                         && $user->getRole() === BasicRoles::Admin;
                                 })
                             );

        $roleList = (new RoleList())
            ->addRole(BasicRoles::Admin);

        $this->userFactory = new SignUpUserFactory(
            BasicRoles::User,
            $roleList
        );

        $this->interactor()->signUp($request);
    }

    public function testSignUpChoosingAdmittedRoles(): void
    {
        $username = 'user@example.com';
        $password = 'some-password';

        $request = (new SignUpRequest(
            $username,
            $password
        ))->withRole(BasicRoles::Admin->value);

        $this->userRepository->method('existsByUsername')
                             ->willReturn(false);

        $this->userRepository->expects($this->never())
                             ->method('createUser');

        $this->output->expects($this->once())
                     ->method('roleNotAdmitted')
                     ->with('admin');

        $this->interactor()->signUp($request);
    }

    protected function interactor(): SignUpInputPort
    {
        return new SignUpInteractor(
            userRepository: $this->userRepository,
            output: $this->output,
            userFactory: $this->userFactory
        );
    }
}
