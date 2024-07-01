<?php
namespace Imefisto\AuthLib\Testing\UseCases;

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
    private LoginInteractor $interactor;

    protected function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->output = $this->createMock(LoginOutputPort::class);
        $this->interactor = new LoginInteractor(
            $this->userRepository,
            $this->output
        );
    }

    public function testImplementLoginInputPort(): void
    {
        $this->assertInstanceOf(LoginInputPort::class, $this->interactor);
    }

    public function testLoginWithValidData(): void
    {
        $username = 'user@example.com';
        $password = 'some-password';
        $id = 'some-user-id';

        $user = (new User($username, $password))
            ->setId(new UserId($id));

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

        $this->interactor->login($request);
    }
}
