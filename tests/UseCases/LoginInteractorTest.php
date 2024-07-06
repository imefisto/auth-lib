<?php
namespace Imefisto\AuthLib\Testing\UseCases;

use Imefisto\AuthLib\Domain\BasicRoles;
use Imefisto\AuthLib\Domain\RoleList;
use Imefisto\AuthLib\Domain\User;
use Imefisto\AuthLib\Domain\UserId;
use Imefisto\AuthLib\Domain\UserRepository;
use Imefisto\AuthLib\UseCases\Login\LoginInputPort;
use Imefisto\AuthLib\UseCases\Login\LoginInteractor;
use Imefisto\AuthLib\UseCases\Login\LoginOutputPort;
use Imefisto\AuthLib\UseCases\Login\LoginRequest;
use Imefisto\AuthLib\UseCases\Login\LoginResponse;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

#[CoversClass(LoginInteractor::class)]
class LoginInteractorTest extends TestCase
{
    private MockObject $output;

    protected function setUp(): void
    {
        $this->admittedRoles = (new RoleList())->addRole(BasicRoles::User);
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->output = $this->createMock(LoginOutputPort::class);
    }

    public function testImplementLoginInputPort(): void
    {
        $this->assertInstanceOf(LoginInputPort::class, $this->interactor());
    }

    public function testLoginWithValidData(): void
    {
        $username = 'user@example.com';
        $password = 'some-password';
        $id = 'some-user-id';

        $user = (new User($username))
            ->setId(new UserId($id))
            ->hashPassword($password);

        $request = new LoginRequest($username, $password);

        $this->userRepository->expects($this->once())
                             ->method('findByUsername')
                             ->with($username)
                             ->willReturn($user);

        $this->output->expects($this->once())
                     ->method('userLoggedIn')
                     ->with($this->callback(
                         function (LoginResponse $response) use ($id) {
                             return (string) $response->userId === $id;
                         })
                     );

        $this->interactor()->login($request);
    }

    public function testLoginWithUserNotFound(): void
    {
        $username = 'user@example.com2';
        $password = 'some-password';
        $request = new LoginRequest($username, $password);

        $this->userRepository->method('findByUsername')
                             ->willReturn(null);

        $this->output->expects($this->once())
                     ->method('userNotFound');

        $this->interactor()->login($request);
    }

    public function testLoginWithInvalidPassword(): void
    {
        $username = 'user@example.com';
        $password = 'some-password';
        $id = 'some-user-id';

        $user = (new User($username))
            ->setId(new UserId($id))
            ->hashPassword($password);

        $request = new LoginRequest($username, 'a-wrong-password');

        $this->userRepository->expects($this->once())
                             ->method('findByUsername')
                             ->with($username)
                             ->willReturn($user);

        $this->output->expects($this->once())
                     ->method('passwordNotMatch');

        $this->interactor()->login($request);
    }

    public function testLoginWithAdmittedRole(): void
    {
        $username = 'user@example.com';
        $password = 'some-password';
        $id = 'some-user-id';

        $user = (new User($username))
            ->setId(new UserId($id))
            ->hashPassword($password)
            ->setRole(BasicRoles::Admin);

        $request = new LoginRequest($username, $password);

        $this->userRepository->expects($this->once())
                             ->method('findByUsername')
                             ->with($username)
                             ->willReturn($user);

        $this->output->expects($this->once())
                     ->method('userLoggedIn');

        $this->admittedRoles = (new RoleList())->addRole(BasicRoles::Admin);
        $this->interactor()->login($request);
    }

    public function testLoginWithNonAdmittedRole(): void
    {
        $username = 'user@example.com';
        $password = 'some-password';
        $id = 'some-user-id';

        $user = (new User($username))
            ->setId(new UserId($id))
            ->hashPassword($password)
            ->setRole(BasicRoles::User);

        $request = new LoginRequest($username, $password);

        $this->userRepository->expects($this->once())
                             ->method('findByUsername')
                             ->with($username)
                             ->willReturn($user);

        $this->output->expects($this->once())
                     ->method('roleNotAdmitted')
                     ->with(BasicRoles::User->value);

        $this->admittedRoles = (new RoleList())->addRole(BasicRoles::Admin);
        $this->interactor()->login($request);
    }

    protected function interactor(): LoginInputPort
    {
        return new LoginInteractor(
            $this->userRepository,
            $this->output,
            $this->admittedRoles
        );
    }
}
