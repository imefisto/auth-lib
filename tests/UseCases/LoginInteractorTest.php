<?php
namespace Imefisto\AuthLib\Testing\UseCases;

use Imefisto\AuthLib\UseCases\Login\LoginInputPort;
use Imefisto\AuthLib\UseCases\Login\LoginInteractor;
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
        $this->interactor = new LoginInteractor();
    }

    public function testImplementLoginInputPort(): void
    {
        $this->assertInstanceOf(LoginInputPort::class, $this->interactor);
    }
}
