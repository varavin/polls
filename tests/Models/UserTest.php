<?php
declare(strict_types=1);

use Polls\Models\User;
use PHPUnit\Framework\TestCase;

final class UserTest extends TestCase
{
    public function testFill(): void
    {
        $data = ['id' => '1', 'uid' => 'f17bceaa02d816c1d4ea14e5b36c2202'];
        $user = new User(null, $data);
        $this->assertEquals($user->getId(), $data['id']);
        $this->assertEquals($user->getUid(), $data['uid']);
    }

    public function testValidate(): void
    {
        $data = ['id' => '1', 'uid' => 'f17bceaa02d816c1d4ea14e5b36c2202'];
        $user = new User(null, $data);
        $this->assertTrue($user->validate());

        $data = ['id' => '-1', 'uid' => 'f17bceaa02d816c1d4ea14e5b36c2202'];
        $user = new User(null, $data);
        $this->assertFalse($user->validate());

        $data = ['id' => '1', 'uid' => '17bceaa02d816c1d4ea14e5b36c2202'];
        $user = new User(null, $data);
        $this->assertFalse($user->validate());
    }

    public function testJsonSerialize(): void
    {
        $data = ['id' => '1', 'uid' => 'f17bceaa02d816c1d4ea14e5b36c2202'];
        $user = new User(null, $data);
        $this->assertEquals($user->jsonSerialize(), $data);
    }
}