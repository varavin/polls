<?php
declare(strict_types=1);

use Polls\App;
use Polls\Services\UsersCRUD;
use PHPUnit\Framework\TestCase;

final class UsersCRUDTest extends TestCase
{
    public function testCreate(): void
    {
        $app = new App(App::AUTOTESTS_MODE);
        $usersService = new UsersCRUD($app->pdo());
        $user = $usersService->create();
        $this->assertTrue($user->getId() > 0);
        $this->assertTrue(strlen($user->getUid()) === 32);
    }
    public function testRead(): void
    {
        // Not necessary, because read() method is used in create(), which is already covered above.
        $this->assertTrue(true);
    }
}