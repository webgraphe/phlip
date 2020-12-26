<?php

namespace Webgraphe\Phlip\Tests;

class Dummy
{
    public const PUBLIC_CONSTANT = 'public constant';
    protected const PROTECTED_CONSTANT = 'protected constant';
    private const PRIVATE_CONSTANT = 'private constant';
    public const sameStaticName = 'same static name constant';

    public static $publicStaticField = 'public static field';
    protected static $protectedStaticField = 'protected static field';
    private static $privateStaticField = 'private static field';

    /** @var string|null */
    public $string;
    /** @var int|null */
    public $int;
    /** @var float|null */
    public $float;
    /** @var bool|null */
    public $bool;

    public $sameInstanceName = 'same instance name field';

    protected $protectedInstanceField = 'protected instance field';
    private $privateInstanceField = 'private instance field';

    private $cloneIndex = 0;

    public function __construct(string $string = null, int $int = null, float $float = null, bool $bool = null)
    {
        $this->string = $string;
        $this->int = $int;
        $this->float = $float;
        $this->bool = $bool;
    }

    public static function fromScalars(string $string, int $int, float $float, bool $bool): self
    {
        return new static($string, $int, $float, $bool);
    }

    public function __clone()
    {
        ++$this->cloneIndex;
    }

    public static function publicStaticMethod(): string
    {
        return 'public static method';
    }

    protected static function protectedStaticMethod(): string
    {
        return 'protected static method';
    }

    private static function privateStaticMethod(): string
    {
        return 'private static method';
    }

    public static function sameStaticName(): string
    {
        return 'same static name method';
    }

    public function publicInstanceMethod(): string
    {
        return 'public instance method';
    }

    public function hasCloneIndex(int $index): bool
    {
        return $index === $this->cloneIndex;
    }

    protected function protectedInstanceMethod(): string
    {
        return 'protected instance method';
    }

    private function privateInstanceMethod(): string
    {
        return 'private instance method';
    }

    public function sameInstanceName(): string
    {
        return 'same instance name method';
    }
}
