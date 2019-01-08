<?php
declare(strict_types=1);

use Polls\Models\Answer;
use PHPUnit\Framework\TestCase;

final class AnswerTest extends TestCase
{
    public function testFill(): void
    {
        $data = ['id' => '1', 'text' => 'Answer text', 'pollId' => '22'];
        $answer = new Answer(null, $data);
        $this->assertEquals($answer->getId(), $data['id']);
        $this->assertEquals($answer->getText(), $data['text']);
        $this->assertEquals($answer->getPollId(), $data['pollId']);
    }

    public function testValidate(): void
    {
        $data = ['id' => '1', 'text' => 'Answer text', 'pollId' => '22'];
        $answer = new Answer(null, $data);
        $this->assertTrue($answer->validate());

        $data = ['id' => '1', 'text' => '', 'pollId' => '22'];
        $answer = new Answer(null, $data);
        $this->assertFalse($answer->validate());

        $data = ['id' => '1', 'text' => 'Answer text', 'pollId' => ''];
        $answer = new Answer(null, $data);
        $this->assertFalse($answer->validate());
    }

    public function testJsonSerialize(): void
    {
        $data = ['id' => '1', 'text' => 'Answer text', 'pollId' => '22'];
        $answer = new Answer(null, $data);
        $this->assertEquals($answer->jsonSerialize(), $data);
    }
}