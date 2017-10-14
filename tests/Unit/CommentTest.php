<?php

namespace Webgraphe\Phlip\Tests\Unit;

use Webgraphe\Phlip\Comment;
use Webgraphe\Phlip\Tests\TestCase;

class CommentTest extends TestCase
{
    public function testGetValue()
    {
        $comment = 'This is a comment';
        $this->assertEquals($comment, (new Comment($comment))->getValue());
    }

    public function testStringConvertible()
    {
        $comment = 'This is a comment';
        $this->assertEquals("; $comment\n", (string)(new Comment($comment)));
    }
}
