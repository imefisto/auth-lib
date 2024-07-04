<?php
use Imefisto\AuthLib\Domain\User;
use Imefisto\AuthLib\Domain\UserId;
use Imefisto\AuthLib\Infrastructure\Persistence\InMemoryUserRepository;
use Imefisto\AuthLib\UseCases\Login\LoginInteractor;
use Imefisto\AuthLib\UseCases\Login\LoginRequest;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/common-include.php';

$user = (new User('some@example.com'))
        ->hashPassword('some-password')
        ->setId(new UserId('some-user-id'));

$userRepository = new InMemoryUserRepository();
$userRepository->createUser($user);

$presenter = buildLoginPresenterImplementation();
$loginInteractor = new LoginInteractor($userRepository, $presenter);

$request = new LoginRequest('some@not-found.com', 'some-password');
$loginInteractor->login($request);

$request = new LoginRequest('some@example.com', 'some-wrong-password');
$loginInteractor->login($request);

$request = new LoginRequest('some@example.com', 'some-password');
$loginInteractor->login($request);
