<?php
declare(strict_types=1);

use Polls\Models\Vote;
use PHPUnit\Framework\TestCase;

final class VoteTest extends TestCase
{
    public function testFill(): void
    {
        $data = ['id' => 1, 'userId' => 2, 'answerId' => 3, 'visitorName' => 'John Smith'];
        $vote = new Vote(null, $data);
        $this->assertEquals($vote->getVisitorName(), $data['visitorName']);
        $this->assertEquals($vote->getId(), $data['id']);
        $this->assertEquals($vote->getAnswerId(), $data['answerId']);
        $this->assertEquals($vote->getUserId(), $data['userId']);
    }

    public function testValidate(): void
    {
        $data = ['id' => 1, 'userId' => 2, 'answerId' => 3, 'visitorName' => 'John Smith'];
        $vote = new Vote(null, $data);
        $this->assertTrue($vote->validate());

        $data = ['id' => 1, 'userId' => null, 'answerId' => 3, 'visitorName' => 'John Smith'];
        $vote = new Vote(null, $data);
        $this->assertFalse($vote->validate());

        $data = ['id' => 1, 'userId' => 2, 'answerId' => null, 'visitorName' => 'John Smith'];
        $vote = new Vote(null, $data);
        $this->assertFalse($vote->validate());

        $data = ['id' => 1, 'userId' => 2, 'answerId' => 3, 'visitorName' => null];
        $vote = new Vote(null, $data);
        $this->assertFalse($vote->validate());
    }

    public function testJsonSerialize(): void
    {
        $data = ['id' => 1, 'userId' => 2, 'answerId' => 3, 'visitorName' => 'John Smith'];
        $vote = new Vote(null, $data);
        $this->assertArraySubset($vote->jsonSerialize(), $data);
    }

}