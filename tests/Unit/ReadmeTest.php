<?php

namespace Webgraphe\Phlip\Tests\Unit;

use Webgraphe\Phlip\PhlipException;
use Webgraphe\Phlip\Phlipy;
use Webgraphe\Phlip\Program;
use Webgraphe\Phlip\Tests\TestCase;

class ReadmeTest extends TestCase
{
    /**
     * @throws PhlipException
     */
    public function testExample()
    {
        $program = Program::parse('(lambda (x) (* x x))');
        $scope = Phlipy::basic()->getScope();
        $square = $program->execute($scope);
        $this->assertEquals(M_PI ** 2, $square(M_PI));
    }
}
